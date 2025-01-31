<style>
  .iti {
    width: 100%;
    display: block;
  }

  .iti__country-name {
    color: #000;
  }

  .iti__search-input {
    background: white;
    color: #000;

  }
</style>
<form class="form w-100" id="customer_signup_form" novalidate="novalidate" method="POST">
  @csrf
  <!--begin::Heading-->
  <div class="text-center mb-11">
    <!--begin::Title-->
    <h1 class="text-gray-900 fw-bolder mb-3">
      Sign Up
    </h1>
    <!--end::Title-->

    <!--begin::Subtitle-->
    <div class="text-gray-500 fw-semibold fs-6">
      Best food court in your area
    </div>
    <!--end::Subtitle--->
  </div>
  <!--begin::Heading-->

  <!--begin::Login options-->
  <div class="row g-3 mb-9">
    <!--begin::Col-->

    <!--end::Col-->
  </div>
  <!--end::Login options-->

  <!--begin::Separator-->

  <!--end::Separator-->

  <!--begin::Input groups-->
  <div class="fv-row mb-8">
    <!--begin::Name-->
    <input type="text" placeholder="Name" name="name" autocomplete="off"
      class="form-control bg-transparent @error('name') is-invalid @enderror" value="{{ old('name') }}" />
    @error('name')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <!--end::Name-->
  </div>

  <div class="fv-row mb-8">
    <!--begin::Phone-->
    <input type="text" id="phone" name="phone" autocomplete="off"
      class="form-control bg-transparent @error('phone') is-invalid @enderror" value="{{ old('phone') }}" />
    @error('phone')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <!--end::Phone-->
  </div>

  <div class="fv-row mb-8">
    <!--begin::Name-->
    <input type="email" placeholder="Email Address" name="email" autocomplete="off"
      class="form-control bg-transparent @error('email') is-invalid @enderror" value="{{ old('email') }}" />
    @error('email')
      <div class="invalid-feedback">{{ $message }}</div>
    @enderror
    <!--end::Name-->
  </div>


  <div class="fv-row mb-8">
    <div class="position-relative d-flex align-items-center">
      <!--begin::Icon-->
      <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4"><span class="path1"></span><span
          class="path2"></span><span class="path3"></span><span class="path4"></span><span
          class="path5"></span><span class="path6"></span></i> <!--end::Icon-->

      <!--begin::Datepicker-->
      <input class="form-control form-control-solid ps-12 flatpickr-input" placeholder="Select Birthday"
        name="date_of_birth" type="date" readonly="readonly" id="kt_datepicker_dob_custom">
      <!--end::Datepicker-->
    </div>
    <label class="fs-8 fw-semibold mt-3 text-info" for="">For Special Offer & Gift</label>
  </div>

  <div class="fv-row mb-8">
    <!--begin::Accept-->
    <label class="form-check form-check-inline">
      <input class="form-check-input" type="checkbox" name="toc" value="1" />
      <span class="form-check-label fw-semibold text-gray-700 fs-base ms-1">
        I Accept the <a href="#" class="ms-1 link-primary">Terms & Conditions</a>
      </span>
    </label>
    <!--end::Accept-->
  </div>
  @if ($settings->sign_in_method == 'firebase')
    <div id="recaptcha-container" class="mb-5"></div>
  @endif


  <!--begin::Submit button-->
  <div class="d-grid mb-10">
    <button type="button" class="btn btn-primary" id="customer_form_submit_btn">
      <!--begin::Indicator label-->
      <span class="indicator-label">
        Sign up</span>
      <!--end::Indicator label-->

      <!--begin::Indicator progress-->
      <span class="indicator-progress">
        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
      </span>
      <!--end::Indicator progress-->
    </button>
  </div>
  <!--end::Submit button-->

  <!--begin::Sign up-->
  <div class="text-gray-500 text-center fw-semibold fs-6">
    Already have an Account?
    <a href="{{ route('login') }}" class="link-primary fw-semibold">
      Sign in
    </a>
  </div>
  <!--end::Sign up-->
</form>
