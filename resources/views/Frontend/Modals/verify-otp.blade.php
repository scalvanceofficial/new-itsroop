<div class="modal modalCentered fade form-sign-in modal-part-content" id="verifyOtp">
  <div class="modal-dialog modal-dialog-centered tcul-auth-modal">
    <div class="modal-content">
      <div class="header">
        <div class="demo-title">Verify OTP</div>
        <span class="icon-close icon-close-popup" data-bs-dismiss="modal"></span>
      </div>
      <div class="tf-login-form">
        <form action="{{ route('frontend.verify-otp') }}" accept-charset="utf-8" id="verifyOtpForm">
          @csrf
          <div class="tf-field style-1">
            <input type="hidden" name="email" id="verifyOtpEmail">
            <input class="tf-field-input tf-input" placeholder=" " type="text" name="otp" id="otp" maxlength="6">
            <label class="tf-field-label" for="">Enter 6-digit OTP*</label>
            <span id="otpError" style="color:red"></span>
          </div>
          <div class="bottom">
            <div class="w-100">
              <button type="submit" class="tf-btn btn-fill animate-hover-btn radius-3 w-100 justify-content-center">
                <span>Verify OTP</span>
              </button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>
</div>
