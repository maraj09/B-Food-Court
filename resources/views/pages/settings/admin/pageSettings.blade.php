@extends('layouts.admin.app')

@section('contents')
  @include('pages.settings.admin.toolbar.settingsToolbar')
  <style>
    .quill-settings-editor {
      width: 100%;
      height: 350px;
    }
  </style>
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      @if (Session::has('success'))
        <div class="alert alert-success">
          {{ Session::get('success') }}
        </div>
      @endif
      <!--begin::Custom Content-->
      <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
        <div class="d-grid">
          <ul class="nav nav-tabs flex-nowrap text-nowrap">
            <li class="nav-item">
              <a class="nav-link active btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                data-bs-toggle="tab" href="#kt_tab_pane_1">About Us</a>
            </li>
            <li class="nav-item">
              <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                data-bs-toggle="tab" href="#kt_tab_pane_2">Contact Us</a>
            </li>
            <li class="nav-item">
              <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                data-bs-toggle="tab" href="#kt_tab_pane_3">Terms and Conditions</a>
            </li>
            <li class="nav-item">
              <a class="nav-link btn btn-active-light btn-color-gray-600 btn-active-color-primary rounded-bottom-0"
                data-bs-toggle="tab" href="#kt_tab_pane_4">Refund</a>
            </li>
          </ul>
        </div>
        <!--end::Toolbar container-->
      </div>
      <div class="tab-content" id="myTabContent">
        <div class="tab-pane fade show active" id="kt_tab_pane_1" role="tabpanel">
          <div class="py-5">
            <form action="/dashboard/settings/store" method="POST">
              @csrf
              <input type="hidden" id="about_us" name="about_us"></input>
              <div id="quill_about_us" class="quill-settings-editor">
                {!! $settings->about_us !!}
              </div>
              @error('about_us')
                <div class="text-danger">{{ $message }}</div>
              @enderror
              <button type="submit" class="btn btn-primary mt-5">Submit</button>
            </form>
          </div>
        </div>
        <div class="tab-pane fade mt-5" id="kt_tab_pane_2" role="tabpanel">
          <form class="form" action="/dashboard/settings/store" id="kt_modal_update_user_form" method="POST">
            <!--begin::User form-->
            @csrf
            <div id="kt_modal_update_user_user_info" class="collapse show">
              <!--begin::Input group-->
              <div class="row g-9 mb-7">
                <!--begin::Col-->
                <div class="col-md-6 fv-row">
                  <!--begin::Label-->
                  <label class="fs-6 fw-semibold mb-2">Google Map Embed Link</label>
                  <!--end::Label-->
                  <!--begin::Input-->
                  <input class="form-control form-control-solid" placeholder="Enter Google Map Link" name="embed_map_link"
                    value="{{ $settings->embed_map_link }}">

                  @error('embed_map_link')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                  <!--end::Input-->
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-md-6 fv-row">
                  <!--begin::Label-->
                  <label class="fs-6 fw-semibold mb-2">Email<i class="fa-solid fa-circle-info ms-2"
                      data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                      title="Email"></i></label>
                  <!--end::Label-->
                  <!--begin::Input-->
                  <input class="form-control form-control-solid" name="contact_us_email" placeholder="Email"
                    value="{{ $settings->contact_us_email }}">
                  <!--end::Input-->

                  @error('contact_us_email')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
                <!--end::Col-->
              </div>
              <button type="submit" class="btn btn-primary mt-5">Submit</button>
              <!--end::Input group-->
            </div>
            <!--end::User form-->
          </form>
        </div>
        <div class="tab-pane fade" id="kt_tab_pane_3" role="tabpanel">
          <div class="py-5">
            <form action="/dashboard/settings/store" method="POST">
              @csrf
              <input type="hidden" id="terms" name="terms"></input>
              <div id="quill_terms" class="quill-settings-editor">
                {!! $settings->terms !!}
              </div>
              @error('terms')
                <div class="text-danger">{{ $message }}</div>
              @enderror
              <button type="submit" class="btn btn-primary mt-5">Submit</button>
            </form>
          </div>
        </div>
        <div class="tab-pane fade" id="kt_tab_pane_4" role="tabpanel">
          <div class="py-5">
            <form action="/dashboard/settings/store" method="POST">
              @csrf
              <input type="hidden" id="refund" name="refund"></input>
              <div id="quill_refund" class="quill-settings-editor">
                {!! $settings->refund !!}
              </div>
              @error('refund')
                <div class="text-danger">{{ $message }}</div>
              @enderror
              <button type="submit" class="btn btn-primary mt-5">Submit</button>
            </form>
          </div>
        </div>
      </div>
      <!--end::Custom Content-->
    </div>
    <!--end::Content container-->
  </div>
@endsection
@section('scripts')
  <script>
    var quillAboutUs = new Quill('#quill_about_us', {
      modules: {
        toolbar: true
      },
      placeholder: 'Type your text here...',
      theme: 'snow' // or 'bubble'
    });
    quillAboutUs.on('text-change', function(delta, oldDelta, source) {
      document.getElementById("about_us").value = quillAboutUs.root.innerHTML;
    });

    var quillTerms = new Quill('#quill_terms', {
      modules: {
        toolbar: true
      },
      placeholder: 'Type your text here...',
      theme: 'snow' // or 'bubble'
    });
    quillTerms.on('text-change', function(delta, oldDelta, source) {
      document.getElementById("terms").value = quillTerms.root.innerHTML;
    });

    var quillRefund = new Quill('#quill_refund', {
      modules: {
        toolbar: true
      },
      placeholder: 'Type your text here...',
      theme: 'snow' // or 'bubble'
    });
    quillRefund.on('text-change', function(delta, oldDelta, source) {
      document.getElementById("refund").value = quillRefund.root.innerHTML;
    });
  </script>
@endsection
