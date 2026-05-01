<?php

namespace App\Http\Controllers\Admin;

use App\Models\Product;
use App\Models\CouponCode;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;

class CouponCodeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('Admin.CouponCodes.index');
    }

    public function data(Request $request)
    {
        $query = CouponCode::where('id', '!=', 0)->orderByDesc('id');

        return DataTables::eloquent($query)

            ->editColumn('start_date', function ($couponcode) {
                return toIndianDate($couponcode->start_date);
            })
            ->editColumn('end_date', function ($couponcode) {
                return toIndianDate($couponcode->end_date);
            })
            ->editColumn('coupon_code', function ($couponcode) {
                return $couponcode->coupon_code;
            })
            ->editColumn('percentage', function ($couponcode) {
                return $couponcode->percentage . '%';
            })
            ->editColumn('minimum_order_amount', function ($couponcode) {
                return formatCurrency($couponcode->minimum_order_amount, $couponcode->currency_code);
            })
            ->editColumn('status', function ($couponcode) {
                if ($couponcode->status == 'ACTIVE') {
                    return '<div class="form-check form-switch"><input class="form-check-input couponcode-status-switch" type="checkbox" checked data-routekey="' . $couponcode->route_key . '"/></div>';
                } else {
                    return '<div class="form-check form-switch"><input class="form-check-input couponcode-status-switch" type="checkbox" data-routekey="' . $couponcode->route_key . '"/></div>';
                }
            })
            ->addColumn('action', function ($couponcode) {
                $edit = '<a href="' . route('admin.coupon-codes.edit', ['coupon_code' => $couponcode->id]) . '" class="badge bg-warning fs-1"><i class="fa fa-edit"></i></a>';
                return $edit;
            })
            ->addIndexColumn()
            ->rawColumns(['coupon_code', 'action', 'status', 'start_date', 'end_date', 'percentage', 'minimum_order_amount'])
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
        $products = Product::where('status', 'ACTIVE')->get();
        return view('Admin.CouponCodes.form', compact('products'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate($this->rules, $this->customMessages);

        $couponcode = new CouponCode;
        $couponcode->fill($request->all());

        $couponcode->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Coupon Code created successfully.'
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

    public function edit(CouponCode $couponcode)
    {
        return view('Admin.CouponCodes.form', compact('couponcode'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, CouponCode $couponcode)
    {
        $request->validate($this->rules, $this->customMessages);
        $couponcode->fill($request->all());

        $couponcode->save();

        return response()->json([
            'status' => 'success',
            'message' => 'Couponcode Updated Successfully',
            'couponcode' => $couponcode,
        ], 201);
    }


    public function changeStatus(Request $request)
    {
        $couponcode = CouponCode::findByKey($request->route_key);
        $couponcode->status = $request->status;
        $couponcode->save();

        return response()->json([
            'status' => 'success',
            'message' => $couponcode->coupon_code . ' has been marked ' . $couponcode->status . ' successfully',
            'couponcode' => $couponcode,
        ], 201);
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


    private $rules = [
        'start_date' => 'required|date',
        'end_date' => 'required|date',
        'coupon_code' => 'required|string',
        'percentage' => 'required|numeric',
        'minimum_order_amount' => 'required|numeric',
        'currency_code' => 'required|string|exists:currencies,code',
    ];

    private $customMessages = [
        'start_date.required' => 'Start Date is required',
        'start_date.date' => 'Start Date must be a valid date',
        'end_date.required' => 'End Date is required',
        'end_date.date' => 'End Date must be a valid date',
        'coupon_code.required' => 'Coupon Code is required',
        'coupon_code.string' => 'Coupon Code must be a string',
        'percentage.required' => 'Percentage is required',
        'percentage.numeric' => 'Percentage must be a number',
        'minimum_order_amount.required' => 'Max Amount is required',
        'minimum_order_amount.numeric' => 'Max Amount must be a number',
        'currency_code.required' => 'Currency is required',
    ];
}
