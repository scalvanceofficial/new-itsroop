<?php

namespace App\Http\Controllers\Admin;

use App\Models\Slider;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class SliderController extends Controller
{

    public function index()
    {
        $max_index = Slider::max('index');
        return view('Admin.Sliders.index', compact('max_index'));
    }

    public function data(Request $request)
    {
        $query = Slider::orderByDesc('id');

        return DataTables::eloquent($query)

            ->editColumn('index', function ($slider) {
                return '<a href="#" class="badge bg-success btn-sm sliderIndexBtn"
                    data-id="' . $slider->id . '"
                    data-index="' . $slider->index . '">
                    ' . $slider->index . '
                </a>';
            })

            ->editColumn('category', function ($slider) {
                return optional($slider->category)->name;
            })

            ->editColumn('title', function ($slider) {
                return strlen($slider->title) > 50
                    ? substr($slider->title, 0, 50) . '...'
                    : $slider->title;
            })

            ->editColumn('subtitle', function ($slider) {
                return $slider->subtitle ?? '';
            })
            ->editColumn('image', function ($slider) {

                if (!$slider->image)
                    return '';

                return '<img src="' . Storage::url($slider->image) . '"
                        style="width:100px;height:100px;border-radius:8px;">';
            })

            

            ->editColumn('status', function ($slider) {

                $checked = $slider->status == 'ACTIVE' ? 'checked' : '';

                return '<div class="form-check form-switch">
                        <input class="form-check-input slider-status-switch"
                        type="checkbox"
                        ' . $checked . '
                        data-routekey="' . $slider->route_key . '">
                        </div>';
            })

            ->addColumn('action', function ($slider) {

                return '<a href="' . route('admin.sliders.edit', $slider->route_key) . '"
                        class="badge bg-warning">
                        <i class="fa fa-edit"></i>
                        </a>';
            })

            ->filterColumn('subtitle', function ($query, $keyword) {
                $query->where('subtitle', 'like', "%$keyword%");
            })

            ->addIndexColumn()

            ->rawColumns([
                'index',
                'subtitle',
                'image',
                'status',
                'action'
            ])

            ->make(true);
    }


    public function create()
    {
        $categories = Category::where('status', 'ACTIVE')->get();
        return view('Admin.Sliders.form', compact('categories'));
    }


    public function store(Request $request)
    {
        $request->validate($this->rules);

        $max_index = Slider::max('index');

        $data = $request->all();
        $data['index'] = $max_index + 1;

        if ($request->hasFile('image')) {
            $data['image'] = Storage::disk('public')
                ->put('sliders', $request->image);
        }


        Slider::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Slider created successfully.'
        ]);
    }


    public function edit(Slider $slider)
    {
        $categories = Category::where('status', 'ACTIVE')->get();
        return view('Admin.Sliders.form', compact('slider', 'categories'));
    }


    public function update(Request $request, Slider $slider)
    {

        $rules = [
            'image' => 'nullable|image',
            'subtitle' => 'nullable|string'
        ];

        $request->validate($rules);

        $data = $request->all();

        if ($request->hasFile('image')) {

            if ($slider->image) {
                Storage::disk('public')->delete($slider->image);
            }

            $data['image'] = Storage::disk('public')
                ->put('sliders', $request->image);
        }


        $slider->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Slider updated successfully.'
        ]);
    }


    public function changeStatus(Request $request)
    {
        $slider = Slider::findByKey($request->route_key);

        $slider->update([
            'status' => $request->status
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Slider status updated successfully.'
        ]);
    }


    public function updateIndex(Request $request)
    {

        $slider = Slider::find($request->slider_id);

        $swap = Slider::where('index', $request->index)->first();

        if ($swap) {
            $swap->update([
                'index' => $slider->index
            ]);
        }

        $slider->update([
            'index' => $request->index
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Index updated successfully.'
        ]);
    }


    private $rules = [
        'image' => 'required|image',
        'subtitle' => 'nullable|string'
    ];
}