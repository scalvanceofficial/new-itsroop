<?php

namespace App\Http\Controllers\Admin;

use App\Models\Gallery;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class GalleryController extends Controller
{
    public function index()
    {
        return view('Admin.Galleries.index');
    }

    public function data(Request $request)
    {
        $query = Gallery::orderByDesc('id');

        return DataTables::eloquent($query)
            ->editColumn('created_at', function ($gallery) {
                return $gallery->created_at->format('d M Y, h:i A');
            })
            ->addColumn('datetime', function ($gallery) {
                return $gallery->created_at->format('d M Y, h:i A');
            })
            ->addColumn('action', function ($gallery) {
                $edit   = '<a href="' . route('admin.galleries.edit', ['gallery' => $gallery->id]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                $delete = '<a href="#" class="btn btn-danger btn-sm fs-1 gallery-delete-btn"
                            data-id="' . $gallery->id . '">
                            <i class="fa fa-trash"></i></a>';
                return $edit . ' ' . $delete;
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'datetime'])
            ->make(true);
    }

    public function create()
    {
        return view('Admin.Galleries.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'links' => 'nullable|array',
            'links.*' => 'nullable|url',
        ]);

        $images = [];
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $images[] = Storage::disk('public')->put('galleries', $image);
            }
        }

        $links = array_filter($request->links ?? [], fn($l) => !empty($l));

        Gallery::create([
            'images' => $images,
            'links'  => array_values($links),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Gallery created successfully.',
        ], 201);
    }

    public function edit(Gallery $gallery)
    {
        return view('Admin.Galleries.form', compact('gallery'));
    }

    public function update(Request $request, Gallery $gallery)
    {
        $request->validate([
            'images' => 'nullable|array',
            'images.*' => 'image|mimes:jpeg,png,jpg,gif,webp|max:4096',
            'links' => 'nullable|array',
            'links.*' => 'nullable|url',
        ]);

        $oldImages = $gallery->images ?? [];

        // Delete images flagged for removal
        if ($request->filled('delete_images_source')) {
            $deleteImages = explode(',', $request->delete_images_source);
            foreach ($deleteImages as $delImage) {
                if (($key = array_search($delImage, $oldImages)) !== false) {
                    unset($oldImages[$key]);
                    Storage::disk('public')->delete($delImage);
                }
            }
            $oldImages = array_values($oldImages);
        }

        // Upload new images
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $oldImages[] = Storage::disk('public')->put('galleries', $image);
            }
        }

        $links = array_filter($request->links ?? [], fn($l) => !empty($l));

        $gallery->update([
            'images' => array_values($oldImages),
            'links'  => array_values($links),
        ]);

        return response()->json([
            'status'  => 'success',
            'message' => 'Gallery updated successfully.',
        ], 200);
    }

    public function destroy(Gallery $gallery)
    {
        // Delete all associated images from storage
        if ($gallery->images) {
            foreach ($gallery->images as $image) {
                Storage::disk('public')->delete($image);
            }
        }
        $gallery->delete();

        return response()->json([
            'status'  => 'success',
            'message' => 'Gallery deleted successfully.',
        ], 200);
    }
}
