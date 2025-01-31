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
                <div class="fs-6 fw-semibold mt-2 mb-3">Modules</div>
              </div>
              <div class="col-xl-3">
                <div class="form-check form-switch form-check-custom form-check-solid">
                  <label class="form-check-label fw-semibold text-gray-400 me-3"
                    for="items_status">Inactive</label>
                  <input class="form-check-input" type="checkbox" value="1" id="items_status"
                    name="items_status"
                    {{ isset($settings->items_status) && $settings->items_status ? 'checked' : '' }}>
                  <label class="form-check-label fw-semibold text-gray-400 ms-3"
                    for="items_status">Active</label>
                </div>
                <div class="form-text">Items</div>
                <div class="fv-plugins-message-container invalid-feedback">
                  {{ $errors->first('items_status') }}
                </div>
              </div>
              <div class="col-xl-3">
                <div class="form-check form-switch form-check-custom form-check-solid">
                  <label class="form-check-label fw-semibold text-gray-400 me-3"
                    for="play_area_status">Inactive</label>
                  <input class="form-check-input" type="checkbox" value="1" id="play_area_status"
                    name="play_area_status"
                    {{ isset($settings->play_area_status) && $settings->play_area_status ? 'checked' : '' }}>
                  <label class="form-check-label fw-semibold text-gray-400 ms-3"
                    for="play_area_status">Active</label>
                </div>
                <div class="form-text">Play Areas</div>
                <div class="fv-plugins-message-container invalid-feedback">
                  {{ $errors->first('play_area_status') }}
                </div>
              </div>
              <div class="col-xl-3">
                <div class="form-check form-switch form-check-custom form-check-solid">
                  <label class="form-check-label fw-semibold text-gray-400 me-3"
                    for="event_status">Inactive</label>
                  <input class="form-check-input" type="checkbox" value="1" id="event_status"
                    name="event_status"
                    {{ isset($settings->event_status) && $settings->event_status ? 'checked' : '' }}>
                  <label class="form-check-label fw-semibold text-gray-400 ms-3"
                    for="event_status">Active</label>
                </div>
                <div class="form-text">Events</div>
                <div class="fv-plugins-message-container invalid-feedback">
                  {{ $errors->first('event_status') }}
                </div>
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
