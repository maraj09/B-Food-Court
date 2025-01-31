<div class="modal fade" id="loginSignupModal" tabindex="-1" aria-labelledby="loginSignupLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered {{ $settings->sign_in_method == 'otpless' ? 'modal-sm' : '' }}">
    <div class="modal-content">
      <div class="modal-body">
        <!-- Your form or content goes here -->
        @if ($settings->sign_in_method == 'firebase')
          <h2 class="mb-10">Place Order</h2>
          <form id="customer_login_signup_form">
            <div class="fv-row mb-5">
              <!--begin::Phone-->
              <label for="phoneLoginSignup" class="form-label">Enter Phone Number</label>
              <input id="phoneLoginSignup" type="text" name="phone" autocomplete="off"
                class="form-control bg-transparent @error('phone') is-invalid @enderror" value="{{ old('phone') }}" />
              <!--end::Phone-->
              @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <div id="recaptcha-container-custom" class="mb-5"></div>
            <div class="text-center mt-10">
              <button type="reset" data-bs-dismiss="modal" class="btn btn-light me-3">
                Cancel
              </button>

              <button type="submit" id="customer_login_signup_submit" class="btn btn-primary">
                <span class="indicator-label">
                  Continue
                </span>
                <span class="indicator-progress">
                  Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
              </button>
            </div>
          </form>
          <form id="otp_verification_form" style="display: none">
            <!--begin::Label-->
            <h2 class="text-gray-900 mb-3">Otp Verification</h2>
            <!--end::Title-->
            <!--begin::Sub-title-->
            <div class="text-muted fw-semibold fs-5 mb-5">Enter the verification code we sent to</div>
            <div class="fw-bold text-gray-900 fs-3" id="masked-number"></div>
            <!--end::Label-->
            <!--begin::Input group-->
            <div class="d-flex flex-wrap flex-stack">
              <input type="text" name="code_1" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1"
                class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" value="" />
              <input type="text" name="code_2" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1"
                class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" value="" />
              <input type="text" name="code_3" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1"
                class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" value="" />
              <input type="text" name="code_4" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1"
                class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" value="" />
              <input type="text" name="code_5" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1"
                class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" value="" />
              <input type="text" name="code_6" data-inputmask="'mask': '9', 'placeholder': ''" maxlength="1"
                class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" value="" />
            </div>
            <!--begin::Input group-->
            <div class="d-flex flex-center mt-5">
              <button type="button" id="otp_verification_form_back" class="btn btn-light me-3">
                Back
              </button>
              <button type="button" id="otp_verification_form_submit" class="btn btn-lg btn-primary fw-bold">
                <span class="indicator-label">Submit</span>
                <span class="indicator-progress">Please wait...
                  <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
              </button>
            </div>
          </form>
        @elseif ($settings->sign_in_method == 'otpless')
          <div id="otpless-login-page"></div>
        @endif
      </div>
    </div>
  </div>
</div>
