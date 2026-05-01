<?php

namespace App\Http\Controllers\Admin;

use App\Models\Testimonial;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class TestimonialController extends Controller
{
    public function index()
    {
        $max_index = Testimonial::max('index');
        return view('Admin.Testimonials.index', compact('max_index'));
    }

    public function create()
    {
        return view('Admin.Testimonials.form');
    }

    public function data(Request $request, Testimonial $testimonial)
    {
        $query = Testimonial::where('id', '!=', 0)->orderByDesc('id');

        return DataTables::eloquent($query)

            ->editColumn('name', function ($testimonial) {
                return $testimonial->name;
            })

            ->editColumn('index', function ($testimonial) {
                return '<a href="#" class="badge bg-success btn-sm testimonialIndexBtn"  data-title="Change Indexing" data-id="' . $testimonial->id . '" data-index="' . $testimonial->index . '" style="padding: 0px 7px 1px !important;">
                <span class="badge badge-light" style="font-size:10px; margin:0px -10px 0px -10px !important;">' . $testimonial->index . '</span>
            </a>';
            })
            ->editColumn('purchase_item', function ($testimonial) {
                return $testimonial->purchase_item;
            })
            ->editColumn('title', function ($testimonial) {
                return $testimonial->title;
            })
            ->editColumn('image', function ($testimonial) {
                return $testimonial->image;
            })
            ->editColumn('description', function ($testimonial) {
                return $testimonial->description;
            })

            ->editColumn('status', function ($testimonial) {
                if ($testimonial->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input testimonial-status-switch" type="checkbox" checked data-testimonial_id="' . $testimonial->id . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input testimonial-status-switch" type="checkbox" data-testimonial_id="' . $testimonial->id . '"/></div>';
                }
            })
            ->addColumn('action', function ($testimonial) {
                $edit = '<a href="' . route('admin.testimonials.edit', ['testimonial' => $testimonial->id]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                return $edit;
            })
            ->addIndexColumn()
            ->rawColumns(['name', 'status', 'action', 'index', 'title', 'description', 'image', 'purchase_item'])->setRowId('id')->make(true);
    }

    public function store(Request $request)
    {
        $request->validate($this->rules, $this->messages);
        $max_index = Testimonial::max('index');

        $data = $request->all();
        $data['index'] = $max_index + 1;

        if ($request->hasFile('image')) {
            $data['image'] = Storage::disk('public')->put('testimonial', $request->file('image'));
        }

        Testimonial::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Testimonial created successfully.'
        ], 201);
    }

    public function changeStatus(Request $request)
    {
        $testimonial = Testimonial::find($request->testimonial_id);
        $testimonial->status = $request->status;
        $testimonial->save();

        return response()->json([
            'status' => 'success',
            'message' => $testimonial->name . ' has been marked ' . $testimonial->status . ' successfully',
        ], 201);
    }

    public function edit(Testimonial $testimonial)
    {
        return view('Admin.Testimonials.form', compact('testimonial'));
    }

    public function update(Request $request, Testimonial $testimonial)
    {
        $this->rules['image'] = 'sometimes|nullable|image';

        $request->validate($this->rules, $this->messages);
        $testimonial->fill($request->all());

        if ($request->hasFile('image')) {
            if ($testimonial->image) {
                Storage::disk('public')->delete($testimonial->image);
            }
            $testimonial->image = Storage::disk('public')->put('testimonial', $request->file('image'));
        }
        $testimonial->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Testimonial Updated Successfully',
            'testimonial' => $testimonial
        ], 201);
    }

    public function updateIndex(Request $request)
    {
        $testimonial = Testimonial::find($request->testimonial_id);

        $swap_index_testimonial = Testimonial::where('index', $request->index)->first();

        $swap_index_testimonial->update(['index' => $testimonial->index]);

        $testimonial->update(['index' => $request->index]);

        return response()->json([
            'status' => 'success',
            'message' => 'Index updated successfully.',
        ], 200);
    }

    private $rules = [
        'name' => 'required|string|max:255',
        'title' => 'required|string|max:255',
        'description' => 'required',
        'purchase_item' => 'required',
        'image' => 'nullable|image|max:2048',
    ];

    private $messages = [
        'name.required' => 'Name is required.',
        'name.string' => ' Name must be a valid string.',
        'title.required' => 'Title is required.',
        'description.required' => 'Description is required.',
        // 'image.required' => 'An image is required.',
        'image.image' => ' uploaded file must be an image.',
        'image.max' => ' image size should not exceed 2MB (2048 KB).',
    ];
}
