<?php

namespace App\Http\Controllers\Admin;

use App\Models\News;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\Facades\DataTables;

class NewsController extends Controller
{
    public function index()
    {
        $max_index = News::max('index');
        return view('Admin.News.index', compact('max_index'));
    }

    public function data(Request $request, News $news)
    {
        $query = News::where('id', '!=', 0)->orderByDesc('id');

        return DataTables::eloquent($query)

            ->editColumn('name', function ($news) {
                return $news->name;
            })

            ->editColumn('index', function ($news) {
                return '<a href="#" class="badge bg-success btn-sm newsIndexBtn"  data-title="Change Indexing" data-id="' . $news->id . '" data-index="' . $news->index . '" style="padding: 0px 7px 1px !important;">
                <span class="badge badge-light" style="font-size:10px; margin:0px -10px 0px -10px !important;">' . $news->index . '</span>
            </a>';
            })

            ->editColumn('title', function ($news) {
                return $news->title;
            })

            ->editColumn('status', function ($news) {
                if ($news->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input news-status-switch" type="checkbox" checked data-news_id="' . $news->id . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input news-status-switch" type="checkbox" data-news_id="' . $news->id . '"/></div>';
                }
            })
            ->addColumn('action', function ($news) {
                $edit = '<a href="' . route('admin.news.edit', ['news' => $news->id]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                return $edit;
            })
            ->addIndexColumn()
            ->rawColumns(['name', 'status', 'action', 'index', 'title'])->setRowId('id')->make(true);
    }

    public function create()
    {
        return view('Admin.News.form');
    }

    public function store(Request $request)
    {
        $request->validate($this->rules, $this->messages);
        $max_index = News::max('index');
        $data['slug'] = Str::slug($request->slug);
        $data = $request->all();
        $data['index'] = $max_index + 1;

        if ($request->hasFile('thumbnail_image')) {
            $data['thumbnail_image'] = Storage::disk('public')->put('news', $request->file('thumbnail_image'));
        }
        if ($request->hasFile('main_image')) {
            $data['main_image'] = Storage::disk('public')->put('news', $request->file('main_image'));
        }

        News::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'News created successfully.'
        ], 201);
    }


    public function changeStatus(Request $request)
    {
        $news = News::find($request->news_id);
        $news->status = $request->status;
        $news->save();

        return response()->json([
            'status' => 'success',
            'message' => 'News  has been marked ' . $news->status . ' successfully',
        ], 201);
    }

    public function edit(News $news)
    {
        return view('Admin.News.form', compact('news'));
    }

    public function update(Request $request, News $news)
    {
        $this->rules['thumbnail_image'] = 'sometimes|nullable';
        $this->rules['main_image'] = 'sometimes|nullable';

        $request->validate($this->rules, $this->messages);
        $news->fill($request->all());
        $news->slug = Str::slug($request->slug);

        if ($request->hasFile('thumbnail_image')) {
            if ($news->thumbnail_image) {
                Storage::disk('public')->delete($news->thumbnail_image);
            }
            $news->thumbnail_image = Storage::disk('public')->put('news', $request->file('thumbnail_image'));
        }
        if ($request->hasFile('main_image')) {
            if ($news->main_image) {
                Storage::disk('public')->delete($news->main_image);
            }
            $news->main_image = Storage::disk('public')->put('news', $request->file('main_image'));
        }
        $news->save();

        return response()->json([
            'status' => 'success',
            'message' => 'News Updated Successfully',
            'news' => $news
        ], 201);
    }

    public function updateIndex(Request $request)
    {
        $news = News::find($request->news_id);

        $swap_index_news = News::where('index', $request->index)->first();

        $swap_index_news->update(['index' => $news->index]);

        $news->update(['index' => $request->index]);

        return response()->json([
            'status' => 'success',
            'message' => 'Index updated successfully.',
        ], 200);
    }

    private $rules = [
        'title' => 'required',
        'slug' => 'required',
        // 'description_1' => 'required',
        'thumbnail_image' => 'required',
        'main_image' => 'required',
    ];

    private $messages = [
        'title.required' => 'Title is required.',
        'slug.required' => 'Slug is required',
        // 'description_1.required' => 'Description is required.',
        'thumbnail_image.required' => 'Thumbnail Img is required.',
        'main_image.required' => 'Image is required.',
    ];
}
