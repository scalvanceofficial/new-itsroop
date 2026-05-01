<?php

namespace App\Http\Controllers\Admin;

use App\Models\Blog;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class BlogController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $max_index = Blog::max('index');
        return view('Admin.Blogs.index', compact('max_index'));
    }


    public function data(Request $request, Blog $blog)
    {
        $query = Blog::where('id', '!=', 0)->orderByDesc('id');

        return DataTables::eloquent($query)

            ->editColumn('name', function ($blog) {
                return $blog->name;
            })

            ->editColumn('index', function ($blog) {
                return '<a href="#" class="badge bg-success btn-sm blogIndexBtn"  data-title="Change Indexing" data-id="' . $blog->id . '" data-index="' . $blog->index . '" style="padding: 0px 7px 1px !important;">
                <span class="badge badge-light" style="font-size:10px; margin:0px -10px 0px -10px !important;">' . $blog->index . '</span>
            </a>';
            })

            ->editColumn('title', function ($blog) {
                return $blog->title;
            })

            ->editColumn('description', function ($blog) {
                return $blog->description;
            })

            ->editColumn('status', function ($blog) {
                if ($blog->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input blog-status-switch" type="checkbox" checked data-blog_id="' . $blog->id . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input blog-status-switch" type="checkbox" data-blog_id="' . $blog->id . '"/></div>';
                }
            })
            ->addColumn('action', function ($blog) {
                $edit = '<a href="' . route('admin.blogs.edit', ['blog' => $blog->id]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                return $edit;
            })
            ->addIndexColumn()
            ->rawColumns(['name', 'status', 'action', 'index', 'title', 'description'])->setRowId('id')->make(true);
    }

    public function create()
    {
        return view('Admin.Blogs.form');
    }


    public function store(Request $request)
    {
        $request->validate($this->rules, $this->messages);
        $max_index = Blog::max('index');
        $data['slug'] = Str::slug($request->slug);
        $data = $request->all();
        $data['index'] = $max_index + 1;

        if ($request->hasFile('thumbnail_image')) {
            $data['thumbnail_image'] = Storage::disk('public')->put('blog', $request->file('thumbnail_image'));
        }
        if ($request->hasFile('main_image')) {
            $data['main_image'] = Storage::disk('public')->put('blog', $request->file('main_image'));
        }

        Blog::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Blog created successfully.'
        ], 201);
    }


    public function changeStatus(Request $request)
    {
        $blog = Blog::find($request->blog_id);
        $blog->status = $request->status;
        $blog->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Blog  has been marked ' . $blog->status . ' successfully',
        ], 201);
    }

    public function edit(blog $blog)
    {
        return view('Admin.Blogs.form', compact('blog'));
    }

    public function update(Request $request, blog $blog)
    {
        $this->rules['thumbnail_image'] = 'sometimes|nullable';
        $this->rules['main_image'] = 'sometimes|nullable';

        $request->validate($this->rules, $this->messages);
        $blog->fill($request->all());
        $blog->slug = Str::slug($request->slug);

        if ($request->hasFile('thumbnail_image')) {
            if ($blog->thumbnail_image) {
                Storage::disk('public')->delete($blog->thumbnail_image);
            }
            $blog->thumbnail_image = Storage::disk('public')->put('blog', $request->file('thumbnail_image'));
        }
        if ($request->hasFile('main_image')) {
            if ($blog->main_image) {
                Storage::disk('public')->delete($blog->main_image);
            }
            $blog->main_image = Storage::disk('public')->put('blog', $request->file('main_image'));
        }
        $blog->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Blog Updated Successfully',
            'blog' => $blog
        ], 201);
    }

    public function updateIndex(Request $request)
    {
        $blog = blog::find($request->blog_id);

        $swap_index_blog = blog::where('index', $request->index)->first();

        $swap_index_blog->update(['index' => $blog->index]);

        $blog->update(['index' => $request->index]);

        return response()->json([
            'status' => 'success',
            'message' => 'Index updated successfully.',
        ], 200);
    }

    private $rules = [
        'title' => 'required',
        'slug' => 'required',
        // 'description_1' => 'required',
        // 'description_2' => 'required',
        'thumbnail_image' => 'required',
        'main_image' => 'required',
    ];

    private $messages = [
        'title.required' => 'Title is required.',
        'slug.required' => 'Slug is required',
        // 'description_1.required' => 'Description is required.',
        // 'description_2.required' => 'Description is required.',
        'thumbnail_image.required' => 'Thumbnail Img is required.',
        'main_image.required' => 'Image is required.',
    ];
}
