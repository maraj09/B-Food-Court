<div class="tab-pane fade {{ $lastSegment == 'profile' ? 'show active' : '' }}" id="kt_tab_pane_4" role="tabpanel">
  <!--begin::Points Log--->
  <div class="card mb-5 mb-xl-10">
    <!--begin::Card header-->
    <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
      data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
      <!--begin::Card title-->
      <div class="card-title m-0">
        <h3 class="fw-bold m-0">Profile Details</h3>
      </div>
      <!--end::Card title-->
    </div>
    <!--begin::Card header-->
    <!--begin::Content-->
    <div id="kt_account_settings_profile_details" class="collapse show">
      <!--begin::Form-->
      <form id="kt_account_profile_details_form" class="form" method="POST" action="{{ route('profile.update') }}"
        enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!--begin::Card body-->
        <div class="card-body border-top p-9">
          <!--begin::Input group-->
          <div class="row mb-6">
            <!--begin::Label-->
            <label class="col-lg-4 col-form-label fw-semibold fs-6">Avatar</label>
            <!--end::Label-->
            <!--begin::Col-->
            <div class="col-lg-8">
              <!--begin::Image input-->
              <div class="image-input image-input-outline" data-kt-image-input="true"
                style="background-image: url('assets/media/svg/avatars/blank.svg')">
                <!--begin::Preview existing avatar-->
                <div class="image-input-wrapper w-125px h-125px"
                  style="background-image: url({{ asset(auth()->user()->customer->avatar ?? 'assets/media/svg/avatars/blank.svg') }})">
                </div>
                <!--end::Preview existing avatar-->
                <!--begin::Label-->
                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                  data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                  <i class="ki-duotone ki-pencil fs-7">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  <!--begin::Inputs-->
                  <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                  <input type="hidden" name="avatar_remove" />
                  <!--end::Inputs-->
                </label>
                <!--end::Label-->
                <!--begin::Cancel-->
                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                  data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                  <i class="ki-duotone ki-cross fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                </span>
                <!--end::Cancel-->
                <!--begin::Remove-->
                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                  data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                  <i class="ki-duotone ki-cross fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                </span>
                <!--end::Remove-->
              </div>
              <!--end::Image input-->
              <!--begin::Hint-->
              @error('avatar')
                <div class="text-danger">{{ $message }}</div>
              @enderror
              <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
              <!--end::Hint-->
            </div>
            <!--end::Col-->
          </div>
          <!--end::Input group-->
          <!--begin::Input group-->
          <div class="row mb-6">
            <!--begin::Label-->
            <label class="col-lg-4 col-form-label required fw-semibold fs-6">Name</label>
            <!--end::Label-->
            <!--begin::Col-->
            <div class="col-lg-8">
              <!--begin::Row-->
              <div class="row">
                <!--begin::Col-->
                <div class="col-lg-6 fv-row">
                  <input type="text" name="name"
                    class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="name"
                    value="{{ auth()->user()->name }}" />
                  @error('name')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
                <!--end::Col-->
                <!--begin::Col-->

                <!--end::Col-->
              </div>
              <!--end::Row-->
            </div>
            <!--end::Col-->
          </div>
          <!--end::Input group-->
          <!--begin::Input group-->
          <div class="row mb-6">
            <!--begin::Label-->
            <label class="col-lg-4 col-form-label fw-semibold fs-6">Email Address</label>
            <!--end::Label-->
            <!--begin::Col-->
            <div class="col-lg-4 fv-row">
              <input type="text" name="email" class="form-control form-control-lg form-control-solid"
                placeholder="email" value="{{ auth()->user()->email }}" />
              @error('email')
                <div class="text-danger">{{ $message }}</div>
              @enderror
            </div>
            <!--end::Col-->
          </div>
          <div class="row mb-6">
            <!--begin::Label-->
            <label class="col-lg-4 col-form-label fw-semibold fs-6 required">Birthday</label>
            <!--end::Label-->
            <!--begin::Col-->
            <div class="col-lg-4 position-relative d-flex align-items-center">
              <!--begin::Icon-->
              <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4"><span class="path1"></span><span
                  class="path2"></span><span class="path3"></span><span class="path4"></span><span
                  class="path5"></span><span class="path6"></span></i> <!--end::Icon-->

              <!--begin::Datepicker-->
              <input class="form-control form-control-solid ps-12 flatpickr-input" placeholder="Select Birthday"
                name="date_of_birth" type="date" value="{{ auth()->user()->customer->date_of_birth }}"
                readonly="readonly" id="kt_datepicker_dob_custom">
              @error('date_of_birth')
                <div class="text-danger">{{ $message }}</div>
              @enderror
              <!--end::Datepicker-->
            </div>
            <!--end::Col-->
          </div>
        </div>
        <!--end::Card body-->
        <!--begin::Actions-->
        <div class="card-footer d-flex justify-content-end py-6 px-9">
          <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
          <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save
            Changes</button>
        </div>
        <!--end::Actions-->
      </form>
      <!--end::Form-->
    </div>
    <!--end::Content-->
  </div>
  <!--end::Points Log-->
</div>
