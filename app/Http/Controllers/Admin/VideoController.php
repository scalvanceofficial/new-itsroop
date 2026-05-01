<?php

namespace App\Http\Controllers\Admin;

use Storage;
use App\Models\Video;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class VideoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $max_index = Video::max('index');

        return view('Admin.Videos.index', compact('max_index'));
    }

    /**
     * Get a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function data(Request $request)
    {
        $query = Video::where('id', '!=', 0)->orderByDesc('id');

        return DataTables::eloquent($query)
            ->editColumn('index', function ($video) {
                return '<a href="#" class="badge bg-success btn-sm videoIndexBtn"  data-title="Change Indexing" data-id="' . $video->id . '" data-index="' . $video->index . '" style="padding: 0px 7px 1px !important;">
            <span class="badge badge-light" style="font-size:10px; margin:0px -10px 0px -10px !important;">' . $video->index . '</span>
        </a>';
            })
            ->editColumn('url', function ($video) {
                return !empty($video->url) ? '<a href="' . $video->url . '" target="_blank">' . $video->url . '</a>' : '';
            })
            ->editColumn('video', function ($video) {
                if ($video->video) {
                    $file_extension = pathinfo($video->video, PATHINFO_EXTENSION);

                    // $mime_type = $file_extension === 'mp4' ? 'video/mp4' : ($file_extension === 'webm' ? 'video/webm' : '');

                    if ($video) {
                        return '<video width="220" height="100" controls>
                                    <source src="' . asset(Storage::url($video->video)) . '" type="' . $video . '">
                                    Your browser does not support the video tag.
                                </video>';
                    }
                }
                return 'No video available';
            })
            ->editColumn('status', function ($video) {
                if ($video->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input video-status-switch" type="checkbox" checked data-routekey="' . $video->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input video-status-switch" type="checkbox" data-routekey="' . $video->route_key . '"/></div>';
                }
            })
            ->addColumn('action', function ($video) {
                $edit  = '<a href="' . route('admin.videos.edit', ['video' => $video->route_key]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                return $edit;
            })
            ->addIndexColumn()
            ->rawColumns(['action', 'index', 'url', 'video', 'status'])
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
        return view('Admin.Videos.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->rules);

        $max_index = Video::max('index');

        $data = $request->all();
        $data['index'] = $max_index + 1;

        if ($request->hasFile('video')) {
            $data['video'] = Storage::disk('public')->put('videos', $request->file('video'));
        }

        Video::create($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Video created successfully.'
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
    public function edit(Video $video)
    {
        return view('Admin.Videos.form', compact('video'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Video $video)
    {
        $rules = [
            'video' => 'nullable|max:30000',
        ];

        $customMessages = [];

        $request->validate($rules, $customMessages);

        $data = $request->all();

        if ($request->hasFile('video')) {
            $data['video'] = Storage::disk('public')->put('videos', $request->file('video'));

            if ($video->video) {
                Storage::disk('public')->delete($video->video);
            }
        }

        $video->update($data);

        return response()->json([
            'status' => 'success',
            'message' => 'Video updated successfully.'
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

    public function changeStatus(Request $request)
    {
        $video = Video::findByKey($request->route_key);
        $video->update(['status' => $request->status]);

        return response()->json([
            'status' => 'success',
            'message' => 'Video status updated successfully.',
        ], 200);
    }

    public function updateIndex(Request $request)
    {
        $video = Video::find($request->video_id);

        $swap_index_video = Video::where('index', $request->index)->first();

        $swap_index_video->update(['index' => $video->index]);

        $video->update(['index' => $request->index]);

        return response()->json([
            'status' => 'success',
            'message' => 'Index updated successfully.',
        ], 200);
    }

    private $rules = [
        'url' => 'nullable|url',
        'video' => 'required|max:30000',
    ];
}
