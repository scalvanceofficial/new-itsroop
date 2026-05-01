<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Category;
use App\Models\Property;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $max_index =  Category::max('index');

        return view('Admin.Categories.index', compact('max_index'));
    }

    /**
     * Get a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
        $query = Category::where('id', '!=', 0)->orderBy('id', 'desc');

        return DataTables::eloquent($query)
            ->editColumn('name', function ($category) {
                return $category->name;
            })
            ->editColumn('index', function ($category) {
                return '<a href="#" class="badge bg-success btn-sm categoryIndexBtn"  data-title="Change Indexing" data-id="' . $category->id . '" data-index="' . $category->index . '" style="padding: 0px 7px 1px !important;">
                    <span class="badge badge-light" style="font-size:10px; margin:0px -10px 0px -10px !important;">' . $category->index . '</span>
                </a>';
            })
            ->editColumn('image', function ($category) {
                return '<div style="width: 100%;">
                            <img src="' . Storage::url($category->image) . '" alt="Category Image" style="width:100px; height: 100px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);"/>
                            <br>
                        </div>';
            })

            ->editColumn('home_featured', function ($category) {
                if ($category->home_featured == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input categories-home_featured-switch" type="checkbox" data-column="home_featured"  checked data-routekey="' . $category->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input categories-home_featured-switch" type="checkbox" data-column="home_featured"  data-routekey="' . $category->route_key . '"/></div>';
                }
            })
            ->editColumn('show_in_navbar', function ($category) {
                if ($category->show_in_navbar) {
                    return '<div class="form-check form-switch"><input class="form-check-input categories-show_in_navbar-switch" type="checkbox" data-column="show_in_navbar"  checked data-routekey="' . $category->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input categories-show_in_navbar-switch" type="checkbox" data-column="show_in_navbar"  data-routekey="' . $category->route_key . '"/></div>';
                }
            })
            ->editColumn('status', function ($category) {
                if ($category->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input categories-status-switch" data-column="status" type="checkbox" checked data-routekey="' . $category->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input categories-status-switch" data-column="status" type="checkbox" data-routekey="' . $category->route_key . '"/></div>';
                }
            })

            ->addColumn('action', function ($category) {

                $edit  = '<a href="' . route('admin.categories.edit', ['category' => $category->route_key]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';

                $categoryIdJson = (string) $category->id;

                $exists = Product::whereJsonContains('category_ids', $categoryIdJson)->exists();

                $delete = '';

                if (!$exists) {
                    $delete = '<a href="#" class="btn btn-danger btn-sm fs-1 category-delete-btn"
                            data-entity="categories" 
                            data-title="Category"
                            data-id="' . $category->id . '">
                            <i class="fa fa-trash"></i></a>';
                } else {
                    $delete = '<span class="btn btn-secondary btn-sm fs-1" style="text-decoration: line-through;">
                            <i class="fa fa-trash-alt"></i>
                         </span>';
                }
                return $edit . ' ' . $delete;
            })

            ->addColumn('sub_categories', function ($category) {
                $sub_categories = '<a href="' . route('admin.categories.sub_categories.index', ['category' => $category->route_key]) . '" style="background-color: #214332 !important; color: white;" class="badge fs-1">
    <i class="fs-1"></i> Sub Categories
                        </a>';
                return $sub_categories;
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('home_featured', 'like', "%{$keyword}%")
                    ->orWhere('status', 'like', "%{$keyword}%")
                    ->orWhere('index', 'like', "%{$keyword}%")
                    ->orWhere('slug', 'like', "%{$keyword}%");
            })
            ->addIndexColumn()
            ->rawColumns(['name', 'sub_categories', 'index', 'image', 'home_featured', 'show_in_navbar', 'status', 'action'])
            ->setRowId('id')
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $properties = Property::where('status', 'ACTIVE')->get();

        return view('Admin.Categories.form', compact('properties'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->rules, $this->messages);

        $max_index = Category::max('index');

        $data = $request->all();
        $data['index'] = $max_index + 1;

        if ($request->hasFile('image')) {
            $data['image'] = Storage::disk('public')->put('category_images', $request->file('image'));
        }

        Category::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Category created successfully',
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $properties = Property::where('status', 'ACTIVE')->get();

        return view('Admin.Categories.form', compact('category', 'properties'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category)
    {
        $this->rules['image'] = 'sometimes|nullable|image';
        $this->rules['slug'] = 'required|unique:categories,slug,' . $category->id;

        $request->validate($this->rules, $this->messages);

        $data = $request->all();

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($category->image);
            $data['image'] = Storage::disk('public')->put('category_images', $request->file('image'));
        }

        $category->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Category updated successfully',
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Category $category)
    {
        $category->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Category deleted successfully.',
            'category' => $category
        ]);
    }

    /**
     * Update the status of the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $category = Category::findByKey($request->route_key);

        if ($request->column == 'home_featured') {
            $category->update(['home_featured' => $request->status]);
        }

        if ($request->column == 'show_in_navbar') {
            $category->update(['show_in_navbar' => $request->status == 'ACTIVE' ? 1 : 0]);
        }

        if ($request->column == 'status') {
            $category->update(['status' => $request->status]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Category status updated successfully',
        ], 201);
    }

    public function updateIndex(Request $request)
    {
        $category = Category::find($request->category_id);

        $swap_index_category = Category::where('index', $request->index)->first();

        $swap_index_category->update(['index' => $category->index]);

        $category->update(['index' => $request->index]);

        return response()->json([
            'status' => 'success',
            'message' => 'Index updated successfully.',
        ], 200);
    }

    private $rules = [
        'property_ids' => 'required|array',
        'name' => 'required|string|max:255',
        'slug' => 'required|unique:categories,slug',
        'image' => 'required|image',
    ];

    private $messages = [
        'property_ids' => 'The properties field is required.'
    ];
}
