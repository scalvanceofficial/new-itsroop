@extends('layouts.admin')
@section('title')
    Credential
@endsection
@section('content')
    <section>
        <div class="row">
            <div class="col-12">
                <div class="card w-100 position-relative overflow-hidden">
                    <div class="card-header px-4 py-3 border-bottom">
                        <div class="row">
                            <div class="col-6 d-flex justify-content-start">
                                <h5 class="card-title fw-semibold mb-0 lh-sm">Credentials</h5>
                            </div>
                        </div>
                    </div>
                    <div class="card-body p-4">
                        <div class="table-responsive rounded-2 mb-4">
                            <table class="table border table-bordered table-sm w-100 text-nowrap mb-0 align-middle">
                                <thead class="text-dark fs-3">
                                    <tr>
                                        <th width="3%">
                                            <h6 class="fs-3 fw-semibold mb-0">#</h6>
                                        </th>
                                        <th width="15%">
                                            <h6 class="fs-3 fw-semibold mb-0">Portal</h6>
                                        </th>
                                        <th width="30%">
                                            <h6 class="fs-3 fw-semibold mb-0">Credentials</h6>
                                        </th>
                                        <th width="30%">
                                            <h6 class="fs-3 fw-semibold mb-0">URL</h6>
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>1</td>
                                        <td>Admin Login</td>
                                        <td>Email: super@technicul.com<br>
                                            Password: super@123@technicul</td>
                                        <td><a href="https://bamboostreet.technicul.com/login"
                                                target="_blank">https://bamboostreet.technicul.com/login</a></td>
                                    </tr>
                                    <tr>
                                        <td>2</td>
                                        <td>Shiprocket</td>
                                        <td>
                                            Password: Bamboo@2025</td>
                                        <td>
                                            <a href="mailto:updates@bamboostreet.in" target="_blank">
                                                updates@bamboostreet.in
                                            </a>
                                        </td>

                                    </tr>
                                    <tr>
                                        <td>3</td>
                                        <td>Razorpay</td>
                                        <td>
                                            OTP: 9307028238 </td>
                                        <td>
                                            <a href="https://razorpay.com" target="_blank">
                                                https://razorpay.com
                                            </a>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td>4</td>
                                        <td>fast2sms </td>
                                        <td>
                                            OTP: 9307028238 </td>
                                        <td>
                                            <a href="https://www.fast2sms.com" target="_blank">
                                                https://www.fast2sms.com
                                            </a>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
