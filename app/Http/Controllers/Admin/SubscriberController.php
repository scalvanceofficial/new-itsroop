<?php

namespace App\Http\Controllers\Admin;

use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Subscriber;
use Illuminate\Support\Carbon;

class SubscriberController extends Controller
{

    public function index()
    {
        return view('Admin.Subscribers.index');
    }

    public function data(Request $request)
    {
        $query = Subscriber::where('id', '!=', 0);

        return DataTables::eloquent($query)
            ->editColumn('datetime', function ($subscriber) {
                return Carbon::parse($subscriber->created_at)
                    ->setTimezone('Asia/Kolkata')
                    ->format('d-m-Y') . ' || ' . Carbon::parse($subscriber->created_at)
                    ->setTimezone('Asia/Kolkata')
                    ->format('h:i A');
            })
            ->editColumn('email', function ($subscriber) {
                return $subscriber->email;
            })
            ->editColumn('product_details', function ($subscriber) {
                return $subscriber->product_details ?? '<span class="text-muted">N/A</span>';
            })
            ->addColumn('product_image', function ($subscriber) {
                if ($subscriber->product_image) {
                    return '<a href="' . \Illuminate\Support\Facades\Storage::url($subscriber->product_image) . '" target="_blank">
                                <img src="' . \Illuminate\Support\Facades\Storage::url($subscriber->product_image) . '" width="50" class="rounded">
                            </a>';
                }
                return '<span class="text-muted">No Image</span>';
            })
            ->addColumn('action', function ($subscriber) {
                return '<span class="badge bg-success">Subscribed</span>'; // You can customize this
            })
            ->addIndexColumn()
            ->rawColumns(['datetime', 'email', 'product_details', 'product_image', 'action']) // make sure 'action' is included
            ->setRowId('id')
            ->make(true);
    }

    public function list()
    {
        $subscriber = Subscriber::all();
        return response()->json([
            'status' => 'success',
            'list' => $subscriber
        ], 200);
    }

    public function create()
    {
        //
    }

    public function store(Request $request)
    {
        //
    }
    public function show(Subscriber $subscriber)
    {
        //
    }

    public function edit(Subscriber $subscriber)
    {
        //
    }

    public function update(Request $request, $subscriber)
    {
        //
    }

    public function destroy(Subscriber $subscriber)
    {
        //
    }
}
