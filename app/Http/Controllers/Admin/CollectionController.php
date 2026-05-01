<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\Collection;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class CollectionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $max_index =  Collection::max('index');

        return view('Admin.Collections.index', compact('max_index'));
    }

    /**
     * Get a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
        $query = Collection::where('id', '!=', 0)->orderBy('id', 'desc');

        return DataTables::eloquent($query)
            ->editColumn('name', function ($collection) {
                return $collection->name;
            })
            ->editColumn('index', function ($collection) {
                return '<a href="#" class="badge bg-success btn-sm collectionIndexBtn"  data-title="Change Indexing" data-id="' . $collection->id . '" data-index="' . $collection->index . '" style="padding: 0px 7px 1px !important;">
                    <span class="badge badge-light" style="font-size:10px; margin:0px -10px 0px -10px !important;">' . $collection->index . '</span>
                </a>';
            })
            ->editColumn('status', function ($collection) {
                if ($collection->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input collections-status-switch" type="checkbox" checked data-routekey="' . $collection->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input collections-status-switch" type="checkbox" data-routekey="' . $collection->route_key . '"/></div>';
                }
            })
            ->addColumn('action', function ($collection) {
                $edit  = '<a href="' . route('admin.collections.edit', ['collection' => $collection->route_key]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';

                return $edit;
            })
            ->filterColumn('name', function ($query, $keyword) {
                $query->where('name', 'like', "%{$keyword}%")
                    ->orWhere('status', 'like', "%{$keyword}%")
                    ->orWhere('index', 'like', "%{$keyword}%")
                    ->orWhere('slug', 'like', "%{$keyword}%");
            })
            ->addIndexColumn()
            ->rawColumns(['name', 'index', 'status', 'action'])
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
        $products = Product::where('status', 'ACTIVE')->get();

        return view('Admin.Collections.form', compact('products'));
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

        $max_index = Collection::max('index');

        $data = $request->all();
        $data['index'] = $max_index + 1;

        Collection::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Collection created successfully',
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
    public function edit(Collection $collection)
    {
        $products = Product::where('status', 'ACTIVE')->get();

        return view('Admin.Collections.form', compact('collection', 'products'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Collection $collection)
    {
        $this->rules['image'] = 'sometimes|nullable|image';
        $this->rules['slug'] = 'required|unique:collections,slug,' . $collection->id;

        $request->validate($this->rules, $this->messages);

        $data = $request->all();

        $collection->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Collection updated successfully',
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

    /**
     * Update the status of the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $collection = Collection::findByKey($request->route_key);

        $collection->update(['status' => $request->status]);

        return response()->json([
            'status' => 'success',
            'message' => 'Collection status updated successfully',
        ], 201);
    }

    public function updateIndex(Request $request)
    {
        $collection = Collection::find($request->collection_id);

        $swap_index_collection = Collection::where('index', $request->index)->first();

        $swap_index_collection->update(['index' => $collection->index]);

        $collection->update(['index' => $request->index]);

        return response()->json([
            'status' => 'success',
            'message' => 'Index updated successfully.',
        ], 200);
    }

    private $rules = [
        'product_ids' => 'required|array',
        'name' => 'required|string|max:255',
        'slug' => 'required|unique:categories,slug',
    ];

    private $messages = [
        'product_ids' => 'The products field is required.'
    ];
}
