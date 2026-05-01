<div class="modal modalCentered fade form-sign-in modal-part-content" id="register">
    <div class="modal-dialog modal-dialog-centered tcul-auth-modal">
        <div class="modal-content">
            <div class="header">
                <div class="demo-title">Register</div>
                <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
            </div>
            <div class="tf-login-form">
                <form action="{{ route('frontend.signup') }}" accept-charset="utf-8" id="registerForm">
                    @csrf
                    <input type="hidden" name="user_id" id="user_id">
                    <div class="tf-field style-1">
                        <input class="tf-field-input tf-input" placeholder=" " type="text" name="first_name"
                            id="first_name">
                        <label class="tf-field-label" for="">First Name</label>
                        <span id="first_name_error" style="color:red"></span>
                    </div>
                    <div class="tf-field style-1">
                        <input class="tf-field-input tf-input" placeholder=" " type="text" name="last_name"
                            id="last_name">
                        <label class="tf-field-label" for="">Last Name</label>
                        <span id="last_name_error" style="color:red"></span>
                    </div>
                    <div class="tf-field style-1">
                        <input class="tf-field-input tf-input" placeholder=" " type="text" name="email"
                            id="email">
                        <label class="tf-field-label" for="">Email</label>
                        <span id="email_error" style="color:red"></span>
                    </div>
                    <div class="bottom">
                        <div class="w-100">
                            <button type="submit"
                                class="tf-btn btn-fill animate-hover-btn radius-3 w-100 justify-content-center"
                                id="registerBtn">
                                <span id="registerBtnText">Register</span>
                                <span id="registerBtnLoader" class="d-none">
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
