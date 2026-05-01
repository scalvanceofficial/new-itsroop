<?php

namespace App\Http\Controllers\Admin;

use App\Models\Review;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class ReviewController extends Controller
{
    public function index()
    {
        return view('Admin.Reviews.index');
    }

    public function create()
    {
        $products = Product::where('status', 'ACTIVE')->get();
        return view('Admin.Reviews.form', compact('products'));
    }

    public function data(Request $request, Review $review)
    {
        $query = Review::where('id', '!=', 0)->orderByDesc('id');

        return DataTables::eloquent($query)

            ->editColumn('customer_name', function ($review) {
                return ($review->user->first_name ?? '') . ' ' . ($review->user->last_name ?? '');
            })

            ->editColumn('product_name', function ($review) {
                return $review->product->name;
            })

            ->editColumn('title', function ($review) {
                return $review->title;
            })

            ->editColumn('description', function ($review) {
                return $review->description;
            })

            ->editColumn('status', function ($review) {
                if ($review->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input review-status-switch" type="checkbox" checked data-review_id="' . $review->id . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input review-status-switch" type="checkbox" data-review_id="' . $review->id . '"/></div>';
                }
            })
            ->addColumn('action', function ($review) {
                $edit = '<a href="' . route('admin.reviews.edit', ['review' => $review->id]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                return $edit;
            })

            ->filterColumn('product_name', function ($query, $keyword) {
                $query->whereHas('product', function ($q) use ($keyword) {
                    $q->where('name', 'like', "%{$keyword}%");
                });
            })

            ->filterColumn('customer_name', function ($query, $keyword) {
                $query->whereHas('user', function ($q) use ($keyword) {
                    $q->where(DB::raw("CONCAT(first_name, ' ', last_name)"), 'like', "%{$keyword}%");
                });
            })
            ->addIndexColumn()
            ->rawColumns(['customer_name', 'status', 'action', 'title', 'description', 'product_name'])->setRowId('id')->make(true);
    }

    public function store(Request $request)
    {
        $request->validate($this->rules, $this->customMessages);

        $review = new Review;
        $review->fill($request->all());

        if ($request->hasFile('photos')) {
            $photos = [];
            foreach ($request->file('photos') as $file) {
                $photos[] = $file->store('reviews/photo', 'public');
            }
            $review->photos = $photos;
        }
        $review->save();

        $averageRating = Review::where('product_id', $request->product_id)->avg('rating');

        $product = Product::find($request->product_id);
        $product->average_rating = $averageRating;
        $product->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Review created successfully.'
        ], 201);
    }

    public function changeStatus(Request $request)
    {
        $review = Review::find($request->review_id);
        $review->status = $request->status;
        $review->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Review has been marked ' . $review->status . ' successfully',
        ], 201);
    }

    public function edit(review $review)
    {
        $products = Product::where('status', 'ACTIVE')->get();
        return view('Admin.Reviews.form', compact('products', 'review'));
    }

    public function update(Request $request, review $review)
    {
        $this->rules['photos'] = 'sometimes|nullable';

        $request->validate($this->rules, $this->customMessages);

        $review->fill($request->except(['photos']));

        $oldPhotos = $review->photos ?? [];

        if ($request->filled('delete_photos')) {
            $deleteImages = explode(',', $request->delete_photos);
            foreach ($deleteImages as $delImage) {
                if (($key = array_search($delImage, $oldPhotos)) !== false) {
                    unset($oldPhotos[$key]);
                    Storage::disk('public')->delete($delImage);
                }
            }
            $oldPhotos = array_values($oldPhotos);
        }

        if ($request->hasFile('photos')) {
            foreach ($request->file('photos') as $imageFile) {
                $oldPhotos[] = Storage::disk('public')->put('reviews/photo', $imageFile);
            }
        }

        $review->photos = array_values($oldPhotos);
        $review->save();

        $averageRating = Review::where('product_id', $request->product_id)->avg('rating');

        $product = Product::find($request->product_id);
        $product->average_rating = $averageRating;
        $product->save();


        return response()->json([
            'status' => 'success',
            'message' => 'Review Updated Successfully',
            'review' => $review,
        ], 200);
    }

    private $rules = [
        'product_id' => 'required',
        'title' => 'required',
        'rating' => 'required',
        // 'description' => 'required',
        // 'photos' => 'required',
    ];

    private $customMessages = [
        'product_id.required' => 'Product is required.',
        'title.required' => 'Title is required.',
        'rating.required' => 'Rating is required.',
        // 'description.required' => 'Description is required.',
        // 'photos.required' => 'Photo is required',
    ];
}
