<?php

namespace App\Http\Controllers\Frontend;

use App\Mail\Thankyou;
use App\Models\Enquiry;
use App\Mail\ContactMail;
use Illuminate\Http\Request;
use App\Services\EmailService;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Mail;

class EnquiryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function storeEnquiry(Request $request)
    {
        $rules = [
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email',
            'mobile' => 'required|digits:10',
            'message' => 'required',
        ];

        $customMessages = [
            'first_name.required' => 'First name is required',
            'last_name.required' => 'Last name is required',
            'email.required' => 'Email is required',
            'email.email' => 'Please provide a valid email address',
            'mobile.required' => 'Mobile number is required',
            'mobile.digits' => 'Mobile no. should be 10 digit',
            'message.required' => 'Message is reuqired',
        ];

        $request->validate($rules, $customMessages);

        $enquiry = new Enquiry();
        $enquiry->first_name = $request->first_name;
        $enquiry->last_name = $request->last_name;
        $enquiry->email = $request->email;
        $enquiry->mobile = $request->mobile;
        $enquiry->message = $request->message;
        $enquiry->save();

        // Send email to admin
        EmailService::sendEmail(env('ADMIN_EMAIL'), 'emails.enquiry', [
            'subject' => 'New Enquiry Received',
            'name' => $request->first_name . ' ' . $request->last_name,
            'email' => $request->email,
            'mobile' => $request->mobile,
            'message' => $request->message,
        ]);

        // Send thank you email to user
        EmailService::sendEmail($request->email, 'emails.thankyou', [
            'subject' => 'Thank you for your enquiry',
            'name' => $request->first_name . ' ' . $request->last_name,
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Your enquiry has been submitted successfully.',
            'enquiry' => $enquiry
        ], 201);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
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
}
