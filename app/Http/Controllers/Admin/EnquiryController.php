<?php

namespace App\Http\Controllers\Admin;

use App\Models\Enquiry;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class EnquiryController extends Controller
{
    public function index()
    {
        return view('Admin.Enquiries.index');
    }

    public function data(Request $request)
    {
        $query = Enquiry::where('id', '!=', 0)->orderBy('id', 'desc');

        return DataTables::eloquent($query)
            ->editColumn('datetime', function ($enquiry) {
                return toIndianDateTime($enquiry->created_at);
            })
            ->editColumn('first_name', function ($enquiry) {
                return $enquiry->first_name;
            })
            ->editColumn('last_name', function ($enquiry) {
                return $enquiry->last_name;
            })
            ->editColumn('email', function ($enquiry) {
                return $enquiry->email;
            })
            ->editColumn('mobile', function ($enquiry) {
                return $enquiry->mobile;
            })
            ->editColumn('message', function ($enquiry) {
                return $enquiry->message;
            })

            ->addIndexColumn()
            ->rawColumns(['datetime', 'first_name', 'last_name', 'email', 'mobile', 'status'])->setRowId('id')->make(true);
    }

    public function list()
    {
        $enquiries = Enquiry::all();
        return response()->json([
            'status' => 'success',
            'list' => $enquiries
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
    public function show(Enquiry $enquiry) {}

    public function edit(Enquiry $enquiry)
    {
        //
    }

    public function update(Request $request, $enquiry)
    {
        //
    }

    public function destroy(Enquiry $enquiry)
    {
        //
    }
}
