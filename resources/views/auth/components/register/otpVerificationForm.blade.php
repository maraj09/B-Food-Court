<form class="form w-100 mb-13" novalidate="novalidate" id="otp_verification_form" data-kt-redirect-url="/"
  style="display: none">
  <!--begin::Icon-->
  <div class="text-center mb-10">
    <img alt="Logo" class="mh-125px" src="assets/media/svg/misc/smartphone-2.svg" />
  </div>
  <!--end::Icon-->
  <!--begin::Heading-->
  <div class="text-center mb-10">
    <!--begin::Title-->
    <h1 class="text-gray-900 mb-3">OTP Verification</h1>
    <!--end::Title-->
    <!--begin::Sub-title-->
    <div class="text-muted fw-semibold fs-5 mb-5">Enter the verification code we sent to</div>
    <!--end::Sub-title-->
    <!--begin::Mobile no-->
    <div class="fw-bold text-gray-900 fs-3" id="masked-number"></div>
    <!--end::Mobile no-->
  </div>
  <!--end::Heading-->
  <!--begin::Section-->
  <div class="mb-10">
    <!--begin::Label-->
    <div class="fw-bold text-start text-gray-900 fs-6 mb-1 ms-1">Type your 6-digit security code</div>
    <!--end::Label-->
    <!--begin::Input group-->
    <div class="d-flex flex-wrap flex-stack">
      <input type="text" name="code_1" maxlength="1"
        class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" />
      <input type="text" name="code_2" maxlength="1"
        class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" />
      <input type="text" name="code_3" maxlength="1"
        class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" />
      <input type="text" name="code_4" maxlength="1"
        class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" />
      <input type="text" name="code_5" maxlength="1"
        class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" />
      <input type="text" name="code_6" maxlength="1"
        class="form-control bg-transparent h-60px w-60px fs-2qx text-center mx-1 my-2" />
    </div>
    <!--end::Input group-->
  </div>
  <!--end::Section-->
  <!--begin::Submit-->
  <div class="d-flex flex-center">
    <button type="button" id="otp_verification_form_submit" class="btn btn-lg btn-primary fw-bold">
      <span class="indicator-label">Submit</span>
      <span class="indicator-progress">Please wait...
        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
    </button>
  </div>
  <!--end::Submit-->
</form>
