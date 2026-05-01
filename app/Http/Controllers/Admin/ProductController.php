<?php

namespace App\Http\Controllers\Admin;

use PDO;
use App\Models\Cart;
use App\Models\Product;
use App\Models\Category;
use App\Models\Property;
use App\Models\SubCategory;
use App\Models\OrderProduct;
use App\Models\ProductImage;
use App\Models\ProductPrice;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use App\Models\ProductPropertyValue;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ProductController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $categories = Category::where('status', 'ACTIVE')->get();
        $sub_categories = SubCategory::where('status', 'ACTIVE')->get();
        $max_index = Product::max('index');

        return view('Admin.Products.index', compact('categories', 'max_index', 'sub_categories'));
    }

    /**
     * Get a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
        $query = Product::where('id', '!=', 0)->orderBy('id', 'desc');

        $categoryId = $request->category_id;

        if ($categoryId) {
            $query->whereJsonContains('category_ids', $categoryId);
        }

        $subCategoryId = $request->sub_category_id;

        if ($subCategoryId) {
            $query->whereJsonContains('sub_category_ids', $subCategoryId);
        }

        return DataTables::eloquent($query)
            ->editColumn('name', function ($product) {
                return $product->name;
            })
            ->editColumn('category_ids', function ($product) {
                $categories = Category::whereIn('id', $product->category_ids)
                    ->pluck('name')
                    ->toArray();

                return implode(', ', $categories);
            })
            ->editColumn('sub_category_ids', function ($product) {
                $sub_category_ids = is_array($product->sub_category_ids) ? $product->sub_category_ids : [];

                $sub_categories = SubCategory::whereIn('id', $sub_category_ids)
                    ->pluck('name')
                    ->toArray();

                return implode(', ', $sub_categories);
            })

            ->editColumn('sku', function ($product) {
                return $product->sku;
            })
            ->editColumn('hsn', function ($product) {
                return $product->hsn;
            })
            ->editColumn('index', function ($product) {
                return '<a href="#" class="badge bg-success btn-sm productIndexBtn"  data-title="Change Indexing" data-id="' . $product->id . '" data-index="' . $product->index . '" style="padding: 0px 7px 1px !important;">
                <span class="badge badge-light" style="font-size:10px; margin:0px -10px 0px -10px !important;">' . $product->index . '</span>
            </a>';
            })
            ->editColumn('status', function ($product) {
                if ($product->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input product-status-switch" type="checkbox" checked data-column="status" data-routekey="' . $product->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input product-status-switch" type="checkbox" data-column="status" data-routekey="' . $product->route_key . '"/></div>';
                }
            })
            ->editColumn('featured', function ($product) {
                if ($product->featured == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input product-featured-switch" type="checkbox" checked data-column="featured" data-routekey="' . $product->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input product-featured-switch" type="checkbox" data-column="featured" data-routekey="' . $product->route_key . '"/></div>';
                }
            })
            ->addColumn('action', function ($product) {
                $edit = '<a href="' . route('admin.products.edit', ['product' => $product->id]) . '" class="badge bg-warning fs-1" title="edit"><i class="fa fa-edit"></i></a>';
                $images = '<a href="' . route('admin.products.images.create', ['product' => $product->id]) . '" class="badge bg-success fs-1" title="images"><i class="fas fa-images"></i></a>';
                $prices = '<a href="' . route('admin.products.prices.create', ['product' => $product->id]) . '" class="badge bg-secondary fs-1" title="price"><i class="fas fa-rupee-sign"></i></a>';

                $delete = '';

                $usedInCart = Cart::where('product_id', $product->id)->exists();
                $usedInOrder = OrderProduct::where('product_id', $product->id)->exists();

                if (!$usedInCart && !$usedInOrder) {
                    $delete = '<a href="#" class="btn btn-danger btn-sm fs-1 product-delete-btn"
                    data-entity="products" 
                    data-title="Product"
                    data-id="' . $product->id . '">
                    <i class="fa fa-trash"></i></a>';
                } else {
                    $delete = '<span class="btn btn-secondary btn-sm fs-1" style="text-decoration: line-through;" title="Product in use — cannot delete">
                    <i class="fa fa-trash-alt"></i>
                  </span>';
                }
                return $edit . ' ' . $images . ' ' . $prices . ' ' . $delete;
            })

            ->filterColumn('name', function ($query, $keyword) {
                $query->where('index', 'like', "%{$keyword}%")
                    ->orWhere('name', 'like', "%{$keyword}%")
                    ->orWhere('sku', 'like', "%{$keyword}%")
                    ->orWhere('hsn', 'like', "%{$keyword}%");
            })
            ->addIndexColumn()
            ->rawColumns(['name', 'type', 'category_ids', 'sub_category_ids', 'sku', 'hsn', 'index', 'status', 'featured', 'action',])
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
        $categories = Category::where('status', 'ACTIVE')->get();
        $sub_categories = SubCategory::where('status', 'ACTIVE')->get();

        return view('Admin.Products.form', compact('categories', 'sub_categories'));
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
        $data = [];

        if ($request->video_type === 'video') {
            if ($request->hasFile('video')) {
                $data['video'] = Storage::disk('public')->put('product_videos', $request->video);
            }
        }

        if ($request->video_type === 'youtube_link') {
            $data['youtube_link'] = $request->youtube_link;
        }

        $data = array_merge($data, $request->except('video'));
        $data['index'] = Product::max('index') + 1;

        $product = Product::create($data);

        foreach ($request->property_values as $property_id => $property_value_ids) {
            foreach ($property_value_ids as $property_value_id) {
                $color_name = isset($request->color_names[$property_id][$property_value_id]) ? $request->color_names[$property_id][$property_value_id] : null;

                $color_code = isset($request->color_codes[$property_id][$property_value_id]) ? $request->color_codes[$property_id][$property_value_id] : null;

                ProductPropertyValue::create(
                    [
                        'product_id' => $product->id,
                        'property_id' => $property_id,
                        'property_value_id' => $property_value_id,
                        'is_primary' => in_array($property_value_id, $request->is_primary) ? 'YES' : 'NO',
                        'color_name' => $color_name,
                        'color_code' => $color_code,
                    ]
                );
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product created successfully',
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
    public function edit(Product $product)
    {
        $categories = Category::where('status', 'ACTIVE')->get();
        $sub_categories = SubCategory::where('status', 'ACTIVE')->get();

        return view('Admin.Products.form', compact('product', 'categories', 'sub_categories'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product)
    {
        $this->rules['slug'] = 'required|string|max:255|unique:products,slug,' . $product->id;
        $this->rules['name'] = 'required|string|max:255|unique:products,name,' . $product->id;
        $this->rules['sku'] = 'required|string|max:100|unique:products,sku,' . $product->id;
        $this->rules['video'] = 'sometimes|nullable|required_if:video_type,video|file|mimes:mp4';

        $request->validate($this->rules, $this->messages);
        $data = $request->except('video');
        $data['sub_category_ids'] = $request->sub_category_ids ?? [];
        // Set type
        $data['video_type'] = $request->video_type;

        // Handle media type
        if ($request->video_type === 'video') {
            if ($request->hasFile('video')) {
                $data['video'] = Storage::disk('public')->put('product_videos', $request->video);
            }

            $data['youtube_link'] = null; // clear YouTube link if type is video
        } elseif ($request->video_type === 'youtube_link') {
            if ($product->video) {
                Storage::disk('public')->delete($product->video);
            }
            $data['youtube_link'] = $request->youtube_link;
            $data['video'] = null;
        } elseif ($request->video_type === 'remove_type') {
            if ($product->video) {
                Storage::disk('public')->delete($product->video);
            }
            $data['youtube_link'] = null;
            $data['video'] = null;
        }

        $product->update($data); // Only update with sanitized $data

        // Handle property values
        $property_value_id_array = [];

        foreach ($request->property_values as $property_id => $property_value_ids) {
            foreach ($property_value_ids as $property_value_id) {
                $property_value_id_array[] = $property_value_id;

                $color_name = $request->color_names[$property_id][$property_value_id] ?? null;
                $color_code = $request->color_codes[$property_id][$property_value_id] ?? null;

                ProductPropertyValue::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'property_id' => $property_id,
                        'property_value_id' => $property_value_id,
                    ],
                    [
                        'is_primary' => in_array($property_value_id, $request->is_primary ?? []) ? 'YES' : 'NO',
                        'color_name' => $color_name,
                        'color_code' => $color_code,
                    ]
                );
            }
        }

        // Delete any old property values that were not in the updated list
        ProductPropertyValue::where('product_id', $product->id)
            ->whereNotIn('property_value_id', $property_value_id_array)
            ->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product updated successfully',
        ], 200);
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product)
    {
        $product->delete();

        return response()->json([
            'status' => 'success',
            'message' => 'Product deleted successfully.',
            'product' => $product
        ]);
    }

    /**
     * Update the status of the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function changeStatus(Request $request)
    {
        $product = Product::findByKey($request->route_key);

        if ($request->column == 'featured') {
            $product->update(['featured' => $request->status]);
        }

        if ($request->column == 'status') {
            $product->update(['status' => $request->status]);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product status updated successfully',
        ], 201);
    }

    public function updateIndex(Request $request)
    {
        $product = Product::find($request->product_id);

        $swap_index_product = Product::where('index', $request->index)->first();

        $swap_index_product->update(['index' => $product->index]);

        $product->update(['index' => $request->index]);

        return response()->json([
            'status' => 'success',
            'message' => 'Index updated successfully.',
        ], 200);
    }


    public function getSubCategories(Request $request)
    {
        $categoryIds = $request->category_ids ?? [];
        $subCategories = SubCategory::whereIn('category_id', $categoryIds)->where('status', 'ACTIVE')->get();

        return response()->json($subCategories);
    }

    public function getPropertyValues(Request $request)
    {
        $property_ids = Category::whereIn('id', $request->category_ids)
            ->pluck('property_ids')
            ->flatten()
            ->unique()
            ->toArray();

        $product = Product::find($request->product_id);

        $properties = Property::whereIn('id', $property_ids)->get();


        return view('Admin.Products.property-values', compact('properties', 'product'));
    }


    public function createImages(Product $product)
    {
        $property_ids = ProductPropertyValue::where('product_id', $product->id)
            ->distinct('property_id')
            ->pluck('property_id');


        $properties = Property::whereIn('id', $property_ids)->get();

        $product_images = ProductImage::where('product_id', $product->id)->get();

        return view('Admin.Products.images', compact('properties', 'product', 'product_images'));
    }

    public function imageForm(Request $request, Product $product)
    {
        $property = Property::find($request->property_id);

        $product_property_values = ProductPropertyValue::where('product_id', $product->id)
            ->where('property_id', $request->property_id)
            ->get();

        return view('Admin.Products.images-form', compact('property', 'product_property_values', 'product'));
    }

    public function storeImages(Request $request, Product $product)
    {
        $product->update(['primary_property_id' => $request->property_id]);

        if ($request->has('images')) {
            foreach ($request->images as $property_value_id => $product_images) {
                foreach ($product_images as $image) {
                    if ($image->isValid()) {
                        ProductImage::create([
                            'product_id' => $product->id,
                            'property_value_id' => $property_value_id,
                            'image' => Storage::disk('public')->put('product_images', $image),
                        ]);
                    }
                }
            }
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product images stored successfully.',
        ], 201);
    }

    public function destroyImage(Request $request, Product $product)
    {
        ProductImage::find($request->product_image_id)->delete();
    }

    public function createPrices(Product $product)
    {
        $property_values = [];

        foreach ($product->productPropertyValues as $product_property_value) {
            $property_id = $product_property_value->propertyValue->property_id;
            $property_value_id = $product_property_value->property_value_id;
            $value = $product_property_value->propertyValue->name;
            $property_values[$property_id][$property_value_id] = $value;
        }

        $combinations = $this->generateCombinations($property_values);

        return view('Admin.Products.prices', compact('product', 'combinations'));
    }

    public function storePrices(Request $request, Product $product)
    {
        foreach ($request->prices as $product_price) {
            $product_price['product_id'] = $product->id;

            $id = ProductPrice::where([
                ['product_id', $product->id],
                ['label', $product_price['label']],
            ])->value('id');

            validator(
                ['model' => $product_price['model']],
                ['model' => ['required', Rule::unique('product_prices')->ignore($id)]],
                [
                    'model.required' => 'Model is required for each variant',
                    'model.unique' => 'Model must be unique for each variant',
                ]
            )->validate();

            ProductPrice::updateOrCreate(
                [
                    'product_id' => $product_price['product_id'],
                    'label' => $product_price['label'],
                ],
                [
                    'property_values' => json_decode($product_price['property_values']),
                    'label' => $product_price['label'],
                    'actual_price' => $product_price['actual_price'] ?? 0,
                    'discount_percentage' => $product_price['discount_percentage'] ?? 0,
                    'discount_price' => $product_price['discount_price'] ?? 0,
                    'selling_price' => $product_price['selling_price'] ?? 0,
                    'stock' => $product_price['stock'] ?? 0,
                    'model' => $product_price['model'],
                ]
            );
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Product prices stored successfully.',
        ], 201);
    }

    private function generateCombinations($property_values)
    {
        $combinations = [[]];

        foreach ($property_values as $values) {
            $new_combinations = [];

            foreach ($combinations as $existing_combination) {
                foreach ($values as $property_value_id => $property_value) {
                    $combination_key = implode('-', array_values($existing_combination)) . '-' . $property_value;

                    $new_combinations[$combination_key] = $existing_combination + [$property_value_id => $property_value];
                }
            }

            $combinations = $new_combinations;
        }

        return $combinations;
    }

    private $rules = [
        'category_ids' => 'required|array',
        // 'sub_category_ids' => 'required|array',
        'property_values' => 'required|array',
        'name' => 'required|string|max:255|unique:products,name',
        'tag_line' => 'required|string|max:255',
        'slug' => 'required|string|max:255|unique:products,slug',
        'sku' => 'required|string|max:100|unique:products,sku',
        // 'hsn' => 'required|string|max:50',
        'highlights' => 'required|string',
        'description' => 'required|string',
        'gender' => 'nullable|in:Men,Women,Unisex',
        'video_type' => 'nullable|in:video,youtube_link,remove_type',
        'video' => 'required_if:video_type,video|file|mimes:mp4',
        'youtube_link' => 'required_if:video_type,youtube_link',
    ];

    private $messages = [
        'category_ids.required' => 'The categories field is required.',
        // 'sub_category_ids.required' => 'The sub categories field is required.',
        'property_values' => 'The property values field is required.',
        'video.required_if' => 'The video field is required.',
        'youtube_link.required_if' => 'The YouTube link field is required.',
    ];
}
