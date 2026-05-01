<?php

namespace App\Http\Controllers\Admin;

use App\Models\VisitorLog;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;
use App\Http\Controllers\Controller;

class VisitorLogController extends Controller
{
    public function index()
    {
        return view('Admin.VisitorLogs.index');
    }

    public function data()
    {
        $query = VisitorLog::orderByDesc('id');

        return DataTables::eloquent($query)
            ->addIndexColumn()
            ->make(true);
    }


    public function create() {}

    public function store(Request $request) {}

    public function show($id) {}

    public function edit($id) {}

    public function update(Request $request, $id) {}

    public function destroy($id) {}
}
