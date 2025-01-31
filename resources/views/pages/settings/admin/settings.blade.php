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
            <!--begin::Row-->
            <div class="row mb-5">
              <!-- Project Logo -->
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Project Logo</div>
              </div>
              <div class="col-lg-5">
                <!-- Image input -->
                <div class="image-input image-input-outline" data-kt-image-input="true"
                  style="background-image: url('{{ $settings->project_logo ?? 'assets/media/logos/default-dark.svg' }}')">
                  <!-- Preview existing logo -->
                  <div class="image-input-wrapper w-200px h-50px bgi-position-center"
                    style="background-size: 75%; background-image: url('{{ asset($settings->project_logo ?? 'assets/media/logos/default-dark.svg') }}')">
                  </div>
                  <!-- Label -->
                  <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                    data-kt-image-input-action="change" data-bs-toggle="tooltip" data-kt-initialized="1">
                    <i class="bi bi-pencil-fill fs-7"></i>
                    <!-- Inputs -->
                    <input type="file" name="project_logo">
                    <input type="hidden" name="avatar_remove">
                    <!-- /Inputs -->
                  </label>
                  <!-- Cancel -->
                  <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                    data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-kt-initialized="1">
                    <i class="bi bi-x fs-2"></i>
                  </span>
                  <!-- Remove -->
                  <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                    data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-kt-initialized="1">
                    <i class="bi bi-x fs-2"></i>
                  </span>
                </div>
                <!-- Hint -->
                <div class="form-text">Optimal Image Size: (1000x200)px</div>
                <div class="fv-plugins-message-container invalid-feedback">{{ $errors->first('project_logo') }}</div>

              </div>


              <div class="col-lg-4">
                <!-- Image input -->
                <div class="image-input image-input-outline" data-kt-image-input="true"
                  style="background-image: url('{{ $settings->project_favicon_icon ?? 'assets/media/logos/favicon.ico' }}')">
                  <!-- Preview existing logo -->
                  <div class="image-input-wrapper w-75px h-50px bgi-position-center"
                    style="background-size: 100%; background-image: url('{{ asset($settings->project_favicon_icon ?? 'assets/media/logos/favicon.ico') }}')">
                  </div>
                  <!-- Label -->
                  <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                    data-kt-image-input-action="change" data-bs-toggle="tooltip" data-kt-initialized="1">
                    <i class="bi bi-pencil-fill fs-7"></i>
                    <!-- Inputs -->
                    <input type="file" name="project_favicon_icon">
                    <input type="hidden" name="favicon_remove">
                    <!-- /Inputs -->
                  </label>
                  <!-- Cancel -->
                  <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                    data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-kt-initialized="1">
                    <i class="bi bi-x fs-2"></i>
                  </span>
                  <!-- Remove -->
                  <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                    data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-kt-initialized="1">
                    <i class="bi bi-x fs-2"></i>
                  </span>
                </div>
                <!-- Hint -->
                <div class="form-text">Facivon Icon (Before Title)</div>
                <div class="fv-plugins-message-container invalid-feedback">{{ $errors->first('project_logo') }}</div>

              </div>
            </div>
            <!-- Website Info -->
            <div class="row mb-5">
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Website Info</div>
              </div>
              <div class="col-xl-4 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="website_name"
                  value="{{ $settings->website_name ?? old('website_name') }}">
                <div class="fv-plugins-message-container invalid-feedback">{{ $errors->first('website_name') }}</div>
                <div class="form-text">Website Name</div>
              </div>
              <div class="col-xl-4 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="website_title"
                  value="{{ $settings->website_title ?? old('website_title') }}">
                <div class="fv-plugins-message-container invalid-feedback">{{ $errors->first('website_title') }}</div>
                <div class="form-text">Website Title</div>
              </div>
            </div>
            <!-- Meta Description -->
            <div class="row mb-5">
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Meta Description</div>
              </div>
              <div class="col-xl-9 fv-row fv-plugins-icon-container">
                <textarea name="meta_desc" class="form-control form-control-solid h-100px">{{ $settings->meta_desc ?? old('meta_desc') }}</textarea>
                <div class="fv-plugins-message-container invalid-feedback">{{ $errors->first('meta_desc') }}</div>
              </div>
            </div>
            <!-- Project Details -->
            <div class="row mb-5">
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Project Details</div>
              </div>
              <div class="col-xl-4 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="web_version"
                  value="{{ $settings->web_version ?? old('web_version') }}">
                <div class="fv-plugins-message-container invalid-feedback">{{ $errors->first('web_version') }}</div>
                <div class="form-text">Website Version</div>
              </div>
              <div class="col-xl-4 fv-row fv-plugins-icon-container">
                <div class="position-relative d-flex align-items-center">
                  <span class="svg-icon position-absolute ms-4 mb-1 svg-icon-2">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <path opacity="0.3"
                        d="M21 22H3C2.4 22 2 21.6 2 21V5C2 4.4 2.4 4 3 4H21C21.6 4 22 4.4 22 5V21C22 21.6 21.6 22 21 22Z"
                        fill="currentColor"></path>
                      <path
                        d="M6 6C5.4 6 5 5.6 5 5V3C5 2.4 5.4 2 6 2C6.6 2 7 2.4 7 3V5C7 5.6 6.6 6 6 6ZM11 5V3C11 2.4 10.6 2 10 2C9.4 2 9 2.4 9 3V5C9 5.6 9.4 6 10 6C10.6 6 11 5.6 11 5ZM15 5V3C15 2.4 14.6 2 14 2C13.4 2 13 2.4 13 3V5C13 5.6 13.4 6 14 6C14.6 6 15 5.6 15 5ZM19 5V3C19 2.4 18.6 2 18 2C17.4 2 17 2.4 17 3V5C17 5.6 17.4 6 18 6C18.6 6 19 5.6 19 5Z"
                        fill="currentColor"></path>
                      <path
                        d="M8.8 13.1C9.2 13.1 9.5 13 9.7 12.8C9.9 12.6 10.1 12.3 10.1 11.9C10.1 11.6 10 11.3 9.8 11.1C9.6 10.9 9.3 10.8 9 10.8C8.8 10.8 8.59999 10.8 8.39999 10.9C8.19999 11 8.1 11.1 8 11.2C7.9 11.3 7.8 11.4 7.7 11.6C7.6 11.8 7.5 11.9 7.5 12.1C7.5 12.2 7.4 12.2 7.3 12.3C7.2 12.4 7.09999 12.4 6.89999 12.4C6.69999 12.4 6.6 12.3 6.5 12.2C6.4 12.1 6.3 11.9 6.3 11.7C6.3 11.5 6.4 11.3 6.5 11.1C6.6 10.9 6.8 10.7 7 10.5C7.2 10.3 7.49999 10.1 7.89999 10C8.29999 9.90003 8.60001 9.80003 9.10001 9.80003C9.50001 9.80003 9.80001 9.90003 10.1 10C10.4 10.1 10.7 10.3 10.9 10.4C11.1 10.5 11.3 10.8 11.4 11.1C11.5 11.4 11.6 11.6 11.6 11.9C11.6 12.3 11.5 12.6 11.3 12.9C11.1 13.2 10.9 13.5 10.6 13.7C10.9 13.9 11.2 14.1 11.4 14.3C11.6 14.5 11.8 14.7 11.9 15C12 15.3 12.1 15.5 12.1 15.8C12.1 16.2 12 16.5 11.9 16.8C11.8 17.1 11.5 17.4 11.3 17.7C11.1 18 10.7 18.2 10.3 18.3C9.9 18.4 9.5 18.5 9 18.5C8.5 18.5 8.1 18.4 7.7 18.2C7.3 18 7 17.8 6.8 17.6C6.6 17.4 6.4 17.1 6.3 16.8C6.2 16.5 6.10001 16.3 6.10001 16.1C6.10001 15.9 6.2 15.7 6.3 15.6C6.4 15.5 6.6 15.4 6.8 15.4C6.9 15.4 7.00001 15.4 7.10001 15.5C7.20001 15.6 7.3 15.6 7.3 15.7C7.5 16.2 7.7 16.6 8 16.9C8.3 17.2 8.6 17.3 9 17.3C9.2 17.3 9.5 17.2 9.7 17.1C9.9 17 10.1 16.8 10.3 16.6C10.5 16.4 10.5 16.1 10.5 15.8C10.5 15.3 10.4 15 10.1 14.7C9.80001 14.4 9.50001 14.3 9.10001 14.3C9.00001 14.3 8.9 14.3 8.7 14.3C8.5 14.3 8.39999 14.3 8.39999 14.3C8.19999 14.3 7.99999 14.2 7.89999 14.1C7.79999 14 7.7 13.8 7.7 13.7C7.7 13.5 7.79999 13.4 7.89999 13.2C7.99999 13 8.2 13 8.5 13H8.8V13.1ZM15.3 17.5V12.2C14.3 13 13.6 13.3 13.3 13.3C13.1 13.3 13 13.2 12.9 13.1C12.8 13 12.7 12.8 12.7 12.6C12.7 12.4 12.8 12.3 12.9 12.2C13 12.1 13.2 12 13.6 11.8C14.1 11.6 14.5 11.3 14.7 11.1C14.9 10.9 15.2 10.6 15.5 10.3C15.8 10 15.9 9.80003 15.9 9.70003C15.9 9.60003 16.1 9.60004 16.3 9.60004C16.5 9.60004 16.7 9.70003 16.8 9.80003C16.9 9.90003 17 10.2 17 10.5V17.2C17 18 16.7 18.4 16.2 18.4C16 18.4 15.8 18.3 15.6 18.2C15.4 18.1 15.3 17.8 15.3 17.5Z"
                        fill="currentColor"></path>
                    </svg>
                  </span>
                  <input class="form-control form-control-solid ps-12 flatpickr-input" name="web_version_date"
                    placeholder="Pick a date" id="kt_datepicker_projeact_date" type="text" readonly="readonly"
                    value="{{ $settings->web_version_date ?? old('web_version_date') }}">
                </div>
                <div class="fv-plugins-message-container invalid-feedback">{{ $errors->first('web_version_date') }}
                </div>
              </div>
            </div>
            <!-- Status -->
            <div class="row">
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Status</div>
              </div>
              <div class="col-xl-9">
                <div class="form-check form-switch form-check-custom form-check-solid">
                  <label class="form-check-label fw-semibold text-gray-400 me-3" for="status">Maintenance Mode</label>
                  <input class="form-check-input" type="checkbox" value="1" id="status" name="status"
                    {{ isset($settings->status) && $settings->status ? 'checked' : '' }}>
                  <label class="form-check-label fw-semibold text-gray-400 ms-3" for="status">Active</label>
                </div>
                <div class="fv-plugins-message-container invalid-feedback">{{ $errors->first('status') }}</div>
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

@section('scripts')
  <script>
    $("#kt_datepicker_projeact_date").flatpickr();
    KTUtil.onDOMContentLoaded(function() {
      KTAddCustomer.init();
    });
  </script>
@endsection
