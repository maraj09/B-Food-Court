@extends('layouts.admin.app')

@section('contents')
  @include('pages.settings.admin.toolbar.settingsToolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid mt-20 mt-lg-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      @if (Session::has('success'))
        <div class="alert alert-success">
          {{ Session::get('success') }}
        </div>
      @endif
      <!--begin::Custom Content-->
      <div class="card">
        <!--begin::Form-->
        <form id="kt_project_settings_form" action="/dashboard/settings/store" method="POST"
          class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
          @csrf
          <!--begin::Card body-->
          <div class="card-body p-9">
            <!--begin:: Heder Text Row-->
            <div class="row mb-8">
              <!--begin::Col-->
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Email</div>
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="smtp_host"
                  value="{{ old('smtp_host', $settings->smtp_host ?? '') }}">
                @error('smtp_host')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">SMTP Host</div>
                <!--end::Hint-->
              </div>
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="smtp_port"
                  value="{{ old('smtp_port', $settings->smtp_port ?? '') }}">
                @error('smtp_port')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">SMTP Port</div>
                <!--end::Hint-->
              </div>
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <input type="email" class="form-control form-control-solid" name="email"
                  value="{{ old('email', $settings->email ?? '') }}">
                @error('email')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Email</div>
                <!--end::Hint-->
              </div>
            </div>
            <!--end::Row-->
            <div class="separator separator-dashed border-primary my-10"></div>
            <!--begin:: Heder Text Row-->
            <!-- Additional form fields go here -->
            <div class="row mb-8">
              <!--begin::Col-->
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">PUSH</div>
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="api_id"
                  value="{{ old('api_id', $settings->api_id ?? '') }}">
                @error('api_id')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">API ID</div>
                <!--end::Hint-->
              </div>
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="api_key"
                  value="{{ old('api_key', $settings->api_key ?? '') }}">
                @error('api_key')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">API Key</div>
                <!--end::Hint-->
              </div>
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="api_secret"
                  value="{{ old('api_secret', $settings->api_secret ?? '') }}">
                @error('api_secret')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">API Secret</div>
                <!--end::Hint-->
              </div>
            </div>
            <!--end::Row-->
            <div class="separator separator-dashed border-primary my-10"></div>
            <!--begin::Row-->
            <!--begin:: Heder Text Row-->
            <div class="row mb-8">
              <!--begin::Col-->
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">WhatsApp</div>
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="wp_phone_number_id"
                  value="{{ old('wp_phone_number_id', $settings->wp_phone_number_id ?? '') }}">
                @error('wp_phone_number_id')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Phone number ID:</div>
                <!--end::Hint-->
              </div>
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="wp_business_account_id"
                  value="{{ old('wp_business_account_id', $settings->wp_business_account_id ?? '') }}">
                @error('wp_business_account_id')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">WhatsApp Business Account ID:</div>
                <!--end::Hint-->
              </div>
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="permanent_access_token"
                  value="{{ old('permanent_access_token', $settings->permanent_access_token ?? '') }}">
                @error('permanent_access_token')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Permanent access token:</div>
                <!--end::Hint-->
              </div>
            </div>
            <!--end::Row-->
            <div class="separator separator-dashed border-primary my-10"></div>
            <!--begin::Row-->
            <div class="row mb-8">
              <!--begin::Col-->
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Counter</div>
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="businesses"
                  value="{{ old('businesses', $settings->businesses ?? '') }}">
                @error('businesses')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Businesses</div>
                <!--end::Hint-->
              </div>
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="members"
                  value="{{ old('members', $settings->members ?? '') }}">
                @error('members')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Members</div>
                <!--end::Hint-->
              </div>
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="events"
                  value="{{ old('events', $settings->events ?? '') }}">
                @error('events')
                  <div class="text-danger">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Events</div>
                <!--end::Hint-->
              </div>
            </div>
            <!--end::Row-->
            <!--begin::Clients Row-->
            <div class="row mb-8">
              <!--begin::Col-->
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Our Clients</div>
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <input type="hidden" name="status" value="{{ $settings->status ? 1 : 0 }}" >
              <div class="col-xl-9 fv-row fv-plugins-icon-container">
                <div class="mb-10">
                  <label class="form-label">Select Clients</label>
                  <input class="form-control d-flex align-items-center" name="our_clients"
                    value="{{ old('our_clients', $settings->our_clients ?? '') }}" id="kt_tagify_users" />
                </div>
              </div>
              <!--end::Col-->
            </div>
            <!--end::Row-->
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
