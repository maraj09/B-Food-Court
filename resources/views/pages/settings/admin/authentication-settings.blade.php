@extends('layouts.admin.app')
@section('contents')
  @include('pages.settings.admin.toolbar.settingsToolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid mt-20 mt-lg-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Custom Content-->
      @if (Session::has('success'))
        <div class="alert alert-success">
          {{ Session::get('success') }}
        </div>
      @endif
      <div class="card">
        <!--begin::Form-->
        <form id="kt_project_settings_form" action="/dashboard/settings/store" enctype="multipart/form-data" method="POST"
          class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
          @csrf
          <!--begin::Card body-->
          <div class="card-body p-9">
            <!-- Status -->
            <div class="row">
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Signin Method</div>
              </div>
              <div class="col-xl-3">
                <select name="sign_in_method" class="form-select" data-control="select2">
                  <option value="firebase" {{ $settings->sign_in_method == 'firebase' ? 'selected' : '' }}>Firebase
                  </option>
                  <option value="otpless" {{ $settings->sign_in_method == 'otpless' ? 'selected' : '' }}>Otpless
                  </option>
                </select>
              </div>

            </div>
          </div>
          <!--end::Card body-->
          <!--begin::Card footer-->
          <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
            <button type="submit" class="btn btn-primary" id="kt_project_settings_submit">Save Changes</button>
          </div>
          <!--end::Card footer-->
        </form>
        <!--end:Form-->
      </div>
      <!--end::Custom Content-->
    </div>
    <!--end::Content container-->
  </div>
@endsection
