<div class="modal modalCentered fade form-sign-in modal-part-content" id="address">
    <div class="modal-dialog modal-dialog-centered tcul-auth-modal">
        <div class="modal-content">
            <div class="header">
                <div class="demo-title">Address</div>
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            </div>
            <div class="tf-login-form">
                <form action="{{ route('frontend.addresses.store') }}" accept-charset="utf-8" id="addressForm">
                    @csrf
                    <input type="hidden" name="default" value="YES">
                    <div class="row">
                        <div class="col-6">
                            <div class="tf-field style-1">
                                <input class="tf-field-input tf-input" placeholder=" " type="text"
                                    name="recipient_first_name" id="recipient_first_name_input">
                                <label class="tf-field-label" for="recipient_first_name_input">First name</label>
                                <span id="recipient_first_name_error" style="color:red"></span>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="tf-field style-1">
                                <input class="tf-field-input tf-input" placeholder=" " type="text"
                                    name="recipient_last_name" id="recipient_last_name_input">
                                <label class="tf-field-label" for="recipient_last_name_input">Last name</label>
                                <span id="recipient_last_name_error" style="color:red"></span>
                            </div>
                        </div>
                    </div>
                    <div class="tf-field style-1">
                        <div class="select-custom">
                            <select class="tf-select w-100" id="type" name="type">
                                <option value="">Address Type</option>
                                <option value="Home">Home</option>
                                <option value="Office">Office</option>
                                <option value="Other">Other</option>
                            </select>
                            <span id="type_error" style="color:red;"></span>
                        </div>
                    </div>
                    <div class="tf-field style-1">
                        <input class="tf-field-input tf-input" placeholder=" " type="text" name="address_line_1"
                            id="address_line_1">
                        <label class="tf-field-label" for="">Address line 1</label>
                        <span id="address_line_1_error" style="color:red"></span>
                    </div>
                    <div class="tf-field style-1">
                        <input class="tf-field-input tf-input" placeholder=" " type="text" name="address_line_2"
                            id="address_line_2">
                        <label class="tf-field-label" for="">Address line 2</label>
                        <span id="address_line_2_error" style="color:red"></span>
                    </div>
                    <div class="tf-field style-1">
                        <input class="tf-field-input tf-input" placeholder=" " type="text" name="city"
                            id="city">
                        <label class="tf-field-label" for="">City</label>
                        <span id="city_error" style="color:red"></span>
                    </div>
                    <div class="tf-field style-1">
                        <input class="tf-field-input tf-input" placeholder=" " type="text" name="pincode"
                            id="pincode">
                        <label class="tf-field-label" for="">Postal/ZIP Code</label>
                        <span id="pincode_error" style="color:red"></span>
                    </div>
                    <div class="bottom">
                        <div class="w-100">
                            <button type="submit"
                                class="tf-btn btn-fill animate-hover-btn radius-3 w-100 justify-content-center"
                                id="addressBtn">
                                <span id="addressBtnText">Save Address</span>
                                <span id="addressBtnLoader" class="d-none">
                                    <span class="spinner-border spinner-border-sm" role="status"
                                        aria-hidden="true"></span>
                                </span>
                            </button>

                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
