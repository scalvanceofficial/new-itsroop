<?php

namespace App\Http\Controllers\Admin;

use App\Models\Property;
use Illuminate\Http\Request;
use App\Models\PropertyValue;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\Facades\DataTables;

class PropertyController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $max_index = Property::max('index');
        return view('Admin.Properties.index', compact('max_index'));
    }

    /**
     * Data for datatable
     */
    public function data(Request $request)
    {
        $query = Property::where('id', '!=', 0)
            ->orderBy('id', 'desc');

        return DataTables::eloquent($query)
            ->editColumn('name', function ($property) {
                return $property->name ?? '';
            })

            ->editColumn('property_values', function ($property) {
                $property_values = isset($property->propertyValues)
                    ? $property->propertyValues->sortBy('index')
                    : null;

                return $property_values ? $property_values->pluck('name')->implode(', ') : '';
            })

            // ✅ SHOW SEX (NEW)
            ->editColumn('sex', function ($property) {
                return $property->sex ?? '-';
            })

            ->editColumn('index', function ($property) {
                return '<a href="#" class="badge bg-success btn-sm propertyIndexBtn"
                        data-title="Change Indexing"
                        data-id="' . $property->id . '"
                        data-index="' . $property->index . '">
                        <span class="badge badge-light">' . $property->index . '</span>
                    </a>';
            })

            ->editColumn('status', function ($property) {
                if ($property->status == 'ACTIVE') {
                    return '<div class="form-check form-switch">
                        <input class="form-check-input properties-status-switch"
                        type="checkbox" checked data-routekey="' . $property->route_key . '"/>
                    </div>';
                } else {
                    return '<div class="form-check form-switch">
                        <input class="form-check-input properties-status-switch"
                        type="checkbox" data-routekey="' . $property->route_key . '"/>
                    </div>';
                }
            })

            ->addColumn('action', function ($property) {
                return '<a href="' . route('admin.properties.edit', ['property' => $property->route_key]) . '" class="badge bg-warning fs-1">
                            <i class="fa fa-edit"></i>
                        </a>';
            })

            ->filterColumn('index', function ($query, $keyword) {
                $query->where('index', 'like', "%{$keyword}%")
                    ->orWhere('name', 'like', "%{$keyword}%")
                    ->orWhere('status', 'like', "%{$keyword}%")
                    ->orWhere('sex', 'like', "%{$keyword}%"); // ✅ FILTER SEX
            })

            ->addIndexColumn()
            ->rawColumns(['name', 'property_values', 'index', 'status', 'action'])
            ->setRowId('id')
            ->make(true);
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('Admin.Properties.form');
    }

    /**
     * Store property
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'label' => 'nullable|string|max:255',
            'is_color' => 'required|in:YES,NO',
            'type' => 'required|string',
            'sex' => 'nullable|in:Male,Female,Unisex', // ✅ NEW
        ]);

        if (!$request->names) {
            return response()->json([
                'status' => 'error',
                'message' => 'Please add at least one property value.',
            ], 400);
        }

        $user = Auth::user();

        $data = $request->all();
        $data['index'] = Property::max('index') + 1;

        $property = Property::create($data);

        $property_values = [];

        foreach ($request->names as $key => $name) {
            $property_values[] = [
                'property_id' => $property->id,
                'name' => $name,
                'color' => $request->is_color == 'YES' ? ($request->colors[$key] ?? null) : null,
                'index' => $request->indexes[$key] ?? 0,
                'status' => isset($request->statuses[$key]) ? 'ACTIVE' : 'INACTIVE',
                'created_by' => $user->id,
                'updated_by' => $user->id,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        PropertyValue::insert($property_values);

        return response()->json([
            'status' => 'success',
            'message' => 'Property created successfully',
        ], 201);
    }

    /**
     * Show edit form
     */
    public function edit(Property $property)
    {
        return view('Admin.Properties.form', compact('property'));
    }

    /**
     * Update property
     */
    public function update(Request $request, Property $property)
    {
        $this->validate($request, [
            'name' => 'required|string|max:255',
            'label' => 'nullable|string|max:255',
            'is_color' => 'required|in:YES,NO',
            'type' => 'required|string',
            'sex' => 'nullable|in:Male,Female,Unisex', // ✅ NEW
        ]);

        $user = Auth::user();

        $property->update($request->all());

        foreach ($request->names as $key => $name) {
            PropertyValue::updateOrCreate(
                [
                    'property_id' => $property->id,
                    'name' => $name,
                ],
                [
                    'color' => $request->is_color == 'YES' ? ($request->colors[$key] ?? null) : null,
                    'index' => $request->indexes[$key] ?? 0,
                    'status' => isset($request->statuses[$key]) ? 'ACTIVE' : 'INACTIVE',
                    'updated_by' => $user->id,
                    'updated_at' => now(),
                    'created_by' => $user->id,
                    'created_at' => now(),
                ]
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Property updated successfully',
        ], 200);
    }

    /**
     * Change status
     */
    public function changeStatus(Request $request)
    {
        $property = Property::findByKey($request->route_key);
        $property->update(['status' => $request->status]);

        return response()->json([
            'status' => 'success',
            'message' => ucfirst(strtolower($property->name)) . ' marked ' . ucfirst(strtolower($property->status)),
        ], 200);
    }

    /**
     * Create dynamic value row
     */
    public function propertyValueCreate(Request $request)
    {
        if (!$request->is_color) {
            return response()->json([
                'status' => 'error',
                'message' => 'Is color field is required.',
            ], 400);
        }

        $random_number = rand(1000, 9999);

        return view('Admin.Properties.values', [
            'random_number' => $random_number,
            'is_color' => $request->is_color,
            'type' => $request->type
        ]);
    }

    /**
     * Update index
     */
    public function updateIndex(Request $request)
    {
        $property = Property::find($request->property_id);

        $swap = Property::where('index', $request->index)->first();

        if ($swap) {
            $swap->update(['index' => $property->index]);
        }

        $property->update(['index' => $request->index]);

        return response()->json([
            'status' => 'success',
            'message' => 'Index updated successfully.',
        ], 200);
    }

    /**
     * Validation rules
     */
    private $rules = [
        'name' => 'required|string|max:255',
        'label' => 'nullable|string|max:255',
        'slug' => 'nullable|string|max:255',
        'is_color' => 'required|in:YES,NO',
        'type' => 'required|string',
        'sex' => 'nullable|in:Male,Female,Unisex', // ✅ NEW
    ];
}