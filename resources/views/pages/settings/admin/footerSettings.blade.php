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
        <form method="POST" action="/dashboard/settings/store" enctype="multipart/form-data"
          class="form fv-plugins-bootstrap5 fv-plugins-framework">
          @csrf
          <input type="hidden" name="status" value="{{ $settings->status ? 1 : 0 }}">
          <!--begin::Card body-->
          <div class="card-body p-9">
            <!--begin::Row-->
            <div class="row mb-5">
              <!--begin::Col-->
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Footer Logo</div>
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-lg-8">
                <!--begin::Image input-->
                <div class="image-input image-input-outline" data-kt-image-input="true"
                  style="background-image: url('https://test.takneek.in/wp-content/uploads/2022/05/logo-b.png')">
                  <!--begin::Preview existing avatar-->
                  <div class="image-input-wrapper w-200px h-50px bgi-position-center"
                    style="background-size: 75%; background-image: url('{{ asset($settings->footer_logo) }}')">
                  </div>
                  <!--end::Preview existing avatar-->
                  <!--begin::Label-->
                  <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                    data-kt-image-input-action="change" data-bs-toggle="tooltip" data-kt-initialized="1">
                    <i class="bi bi-pencil-fill fs-7"></i>
                    <!--begin::Inputs-->
                    <input type="file" name="footer_logo" accept=".png, .jpg, .jpeg">
                    <input type="hidden" name="avatar_remove">
                    <!--end::Inputs-->
                  </label>
                  <!--end::Label-->
                  <!--begin::Cancel-->
                  <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                    data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-kt-initialized="1">
                    <i class="bi bi-x fs-2"></i>
                  </span>
                  <!--end::Cancel-->
                  <!--begin::Remove-->
                  <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-white shadow"
                    data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-kt-initialized="1">
                    <i class="bi bi-x fs-2"></i>
                  </span>
                  <!--end::Remove-->
                </div>
                <!--end::Image input-->
                <!--begin::Hint-->
                <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                <div class="fv-plugins-message-container invalid-feedback">{{ $errors->first('footer_logo') }}</div>

                <!--end::Hint-->
              </div>
              <!--end::Col-->
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <div class="row mb-8">
              <!--begin::Col-->
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Footer Banner</div>
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-xl-8 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="footer_banner_heading"
                  value="{{ old('footer_banner_heading', $settings->footer_banner_heading ?? '') }}">
                @error('footer_banner_heading')
                  <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Banner Heading</div>
                <!--end::Hint-->
              </div>
            </div>
            <!--end::Row-->
            <!--begin::Row-->
            <!--begin::Row-->
            <div class="row mb-8">
              <!--begin::Col-->
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Banner Subheading</div>
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-xl-4 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="footer_banner_sub_heading"
                  value="{{ old('footer_banner_sub_heading', $settings->footer_banner_sub_heading ?? '') }}">
                @error('footer_banner_sub_heading')
                  <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Sub Heading</div>
                <!--end::Hint-->
              </div>
              <!--begin::Col-->
              <div class="col-xl-4 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="footer_banner_sub_heading_url"
                  value="{{ old('footer_banner_sub_heading_url', $settings->footer_banner_sub_heading_url ?? '') }}">
                @error('footer_banner_sub_heading_url')
                  <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Button</div>
                <!--end::Hint-->
              </div>
            </div>
            <!--end::Row-->

            <!--end::Row-->
            <!--begin::Row-->
            <!--begin::Row-->
            <div class="row mb-8">
              <!--begin::Col-->
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Footer Description</div>
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-xl-9 fv-row fv-plugins-icon-container">
                <textarea name="footer_desc" class="form-control form-control-solid h-100px">{{ old('footer_desc', $settings->footer_desc ?? '') }}</textarea>
                @error('footer_desc')
                  <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <!--begin::Col-->
            </div>
            <!--end::Row-->

            <!--end::Row-->
            <!--begin::Row-->
            <!--begin::Row-->
            <div class="row mb-8">
              <!--begin::Col-->
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Company Info</div>
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-xl-4 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="company_contact_email"
                  value="{{ old('company_contact_email', $settings->company_contact_email ?? '') }}">
                @error('company_contact_email')
                  <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Contact Email</div>
                <!--end::Hint-->
              </div>
              <!--begin::Col-->
              <div class="col-xl-4 fv-row fv-plugins-icon-container">
                <input id="kt_tagify_footer_contact" type="text" class="form-control form-control-solid"
                  name="company_contact_phone"
                  value="@if ($settings->company_contact_phone) {{ implode(', ', array_column(json_decode($settings->company_contact_phone, true), 'value')) }} @endif">
                @error('company_contact_phone')
                  <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Contact No.</div>
                <!--end::Hint-->
              </div>
              <!--begin::Col-->
            </div>
            <!--end::Row-->

            <!--end::Row-->
            <!--begin::Row-->
            <div class="row mb-8">
              <!--begin::Col-->
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Company Address</div>
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-xl-6 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="company_address"
                  value="{{ old('company_address', $settings->company_address ?? '') }}">
                @error('company_address')
                  <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Address</div>
                <!--end::Hint-->
              </div>
              <!--end::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <div class="input-group mb-5">
                  <span class="input-group-text" id="basic-addon1"><i class="fa-solid fa-location-crosshairs"></i></span>
                  <input type="text" class="form-control" name="company_address_map_link"
                    value="{{ old('company_address_map_link', $settings->company_address_map_link ?? '') }}">
                </div>
                @error('company_address_map_link')
                  <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Address Map Link</div>
                <!--end::Hint-->
              </div>
            </div>

            <!--end::Row-->
            <!--begin::Row-->
            <div class="row mb-8">
              <!--begin::Col-->
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Social Info</div>
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <div class="input-group mb-5">
                  <span class="input-group-text" id="basic-addon1"><i class="fab fa-facebook-f"></i></span>
                  <input type="text" class="form-control" name="facebook" placeholder="Facebook.com"
                    aria-label="Username" aria-describedby="basic-addon1"
                    value="{{ old('facebook', $settings->facebook ?? '') }}">
                </div>
                @error('facebook')
                  <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Facebook</div>
                <!--end::Hint-->
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <div class="input-group mb-5">
                  <span class="input-group-text" id="basic-addon1"><i class="fab fa-instagram"></i></span>
                  <input type="text" class="form-control" name="instagram" placeholder="Instagram.com"
                    aria-label="Username" aria-describedby="basic-addon1"
                    value="{{ old('instagram', $settings->instagram ?? '') }}">
                </div>
                @error('instagram')
                  <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Instagram</div>
                <!--end::Hint-->
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <div class="input-group mb-5">
                  <span class="input-group-text" id="basic-addon1"><i class="fab fa-twitter"></i></span>
                  <input type="text" class="form-control" name="twitter" placeholder="Twitter.com"
                    aria-label="Username" aria-describedby="basic-addon1"
                    value="{{ old('twitter', $settings->twitter ?? '') }}">
                </div>
                @error('twitter')
                  <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Twitter</div>
                <!--end::Hint-->
              </div>
              <!--end::Col-->
            </div>
            <!--begin::Row-->
            <div class="row mb-8">
              <!--begin::Col-->
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3"></div>
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <div class="input-group mb-5">
                  <span class="input-group-text" id="basic-addon1"><i class="fab fa-youtube"></i></span>
                  <input type="text" class="form-control" name="youtube" placeholder="Youtube.com"
                    aria-label="Username" aria-describedby="basic-addon1"
                    value="{{ old('youtube', $settings->youtube ?? '') }}">
                </div>
                @error('youtube')
                  <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Youtube</div>
                <!--end::Hint-->
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <div class="input-group mb-5">
                  <span class="input-group-text" id="basic-addon1"><i class="fab fa-whatsapp"></i></span>
                  <input type="text" class="form-control" name="whatsapp" placeholder="Whatsapp Link"
                    aria-label="Username" aria-describedby="basic-addon1"
                    value="{{ old('whatsapp', $settings->whatsapp ?? '') }}">
                </div>
                @error('whatsapp')
                  <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Whatsapp</div>
                <!--end::Hint-->
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <div class="input-group mb-5">
                  <span class="input-group-text" id="basic-addon1"><i class="fab fa-linkedin-in"></i></span>
                  <input type="text" class="form-control" name="linkedin" placeholder="Linkedin.com"
                    aria-label="Username" aria-describedby="basic-addon1"
                    value="{{ old('linkedin', $settings->linkedin ?? '') }}">
                </div>
                @error('linkedin')
                  <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Linkedin</div>
                <!--end::Hint-->
              </div>
              <!--end::Col-->
            </div>

            <!--end::Row-->
            <!--begin::Row-->
            <div class="row mb-8">
              <!--begin::Col-->
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Copyright</div>
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-xl-4 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid" name="copyright"
                  value="{{ old('copyright', $settings->copyright ?? '') }}">
                @error('copyright')
                  <div class="fv-plugins-message-container invalid-feedback">{{ $message }}</div>
                @enderror
                <!--begin::Hint-->
                <div class="form-text">Copyright Year</div>
                <!--end::Hint-->
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
          <input type="hidden">
          <div></div>
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
    $(document).ready(function() {
      var input1 = document.querySelector("#kt_tagify_footer_contact");
      new Tagify(input1);
    });
  </script>
@endsection
