<?php

namespace App\Http\Controllers\Admin;

use App\Models\Currency;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CurrencyController extends Controller
{
    public function index()
    {
        return view('Admin.Currencies.index');
    }

    public function data(Request $request)
    {
        $query = Currency::query();

        return DataTables::eloquent($query)
            ->editColumn('is_active', function ($currency) {
                $checked = $currency->is_active ? 'checked' : '';
                return '<div class="form-check form-switch">
                            <input class="form-check-input change-status" type="checkbox" data-id="' . $currency->id . '" ' . $checked . '>
                        </div>';
            })
            ->addColumn('action', function ($currency) {
                return '<div class="d-flex gap-2">
                            <a href="' . route('admin.currencies.edit', $currency->id) . '" class="btn btn-sm btn-primary"><i class="fa fa-edit"></i></a>
                            <form action="' . route('admin.currencies.destroy', $currency->id) . '" method="POST" onsubmit="return confirm(\'Are you sure?\')">
                                ' . csrf_field() . method_field('DELETE') . '
                                <button type="submit" class="btn btn-sm btn-danger"><i class="fa fa-trash"></i></button>
                            </form>
                        </div>';
            })
            ->rawColumns(['is_active', 'action'])
            ->make(true);
    }

    public function create()
    {
        return view('Admin.Currencies.form');
    }

    public function store(Request $request)
    {
        $request->validate([
            'code' => 'required|unique:currencies,code',
            'name' => 'required',
            'symbol' => 'required',
            'exchange_rate' => 'required|numeric',
        ]);

        Currency::create($request->all());

        return redirect()->route('admin.currencies.index')->with('success', 'Currency created successfully.');
    }

    public function edit(Currency $currency)
    {
        return view('Admin.Currencies.form', compact('currency'));
    }

    public function update(Request $request, Currency $currency)
    {
        $request->validate([
            'code' => 'required|unique:currencies,code,' . $currency->id,
            'name' => 'required',
            'symbol' => 'required',
            'exchange_rate' => 'required|numeric',
        ]);

        $currency->update($request->all());

        return redirect()->route('admin.currencies.index')->with('success', 'Currency updated successfully.');
    }

    public function destroy(Currency $currency)
    {
        $currency->delete();
        return redirect()->route('admin.currencies.index')->with('success', 'Currency deleted successfully.');
    }

    public function changeStatus(Request $request)
    {
        $currency = Currency::findOrFail($request->id);
        $currency->is_active = !$currency->is_active;
        $currency->save();

        return response()->json(['status' => 'success', 'message' => 'Status changed successfully.']);
    }
}
