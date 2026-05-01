<?php

namespace App\Http\Controllers\Admin;

use App\Models\Category;
use App\Models\SubCategory;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class SubCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        return view('Admin.Categories.SubCategories.index', compact('category'));
    }


    public function data(Request $request, Category $category)
    {
        $query = SubCategory::where('id', '!=', 0)->where('category_id', $category->id)->orderByDesc('id');

        if ($request->status != '') {
            $query->where('status', $request->status);
        }

        return DataTables::eloquent($query)

            ->editColumn('name', function ($sub_category) {
                return $sub_category->name;
            })


            ->editColumn('show_in_navbar', function ($sub_category) {
                if ($sub_category->show_in_navbar) {
                    return '<div class="form-check form-switch"><input class="form-check-input sub_category-show_in_navbar-switch" type="checkbox" data-column="show_in_navbar" checked data-id="' . $sub_category->id . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input sub_category-show_in_navbar-switch" type="checkbox" data-column="show_in_navbar" data-id="' . $sub_category->id . '"/></div>';
                }
            })
            ->editColumn('status', function ($sub_category) {
                if ($sub_category->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input sub_category-status-switch" type="checkbox" data-column="status" checked data-id="' . $sub_category->id . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input sub_category-status-switch" type="checkbox" data-column="status" data-id="' . $sub_category->id . '"/></div>';
                }
            })

            ->addColumn('action', function ($sub_category) use ($category) {
                $edit = '<a href="' . route('admin.categories.sub_categories.edit', ['category' => $category->route_key, 'sub_category' => $sub_category->route_key]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                return $edit;
            })
            ->addIndexColumn()
            ->rawColumns(['name', 'show_in_navbar', 'status', 'action'])
            ->setRowId('id')
            ->make(true);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Category $category)
    {
        return view('Admin.Categories.SubCategories.form', compact('category'));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Category $category)
    {
        $request->validate($this->rules, $this->messages);

        $sub_category = new SubCategory();
        $sub_category->fill($request->all());
        $sub_category->category_id = $category->id;
        $sub_category->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Sub Category Created Successfully',
            'sub_category' => $sub_category
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
    public function edit(Category $category, SubCategory $sub_category)
    {
        return view('Admin.Categories.SubCategories.form', compact('sub_category', 'category'));
    }



    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category, SubCategory $sub_category,)
    {
        $request->validate($this->rules, $this->messages);
        $sub_category->fill($request->all());
        $sub_category->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Sub Category Updated Successfully',
            'sub_category' => $sub_category
        ], 201);
    }

    public function changeStatus(Request $request)
    {
        $sub_category = SubCategory::find($request->id);
        
        if ($request->has('column') && $request->column == 'show_in_navbar') {
            $sub_category->update(['show_in_navbar' => $request->status == 'ACTIVE' ? 1 : 0]);
        } else {
            $sub_category->update(['status' => $request->status]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Category status updated successfully.',
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    private $rules = [
        'name' => 'required',
        'slug' => 'required',
    ];

    private $messages = [
        'name.required' => 'Name is required',
        'slug.required' => 'Slug is required',

    ];
}
