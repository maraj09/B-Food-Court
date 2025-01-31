<div id="kt_add_vendor" class="bg-body drawer drawer-end" data-kt-drawer="true" data-kt-drawer-name="vendor"
  data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}"
  data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_add_vendor_toggle" data-kt-drawer-close="#kt_add_vendor_close"
  style="width: 500px !important;">
  <!--begin::Messenger-->
  <div class="card card-flush w-100 rounded-0">
    <!--begin::Card header-->
    <div class="card-header bg-dark">
      <!--begin::Title-->
      <h3 class="card-title text-gray-900 fw-bold">Add Vendor</h3>
      <!--end::Title-->
      <!--begin::Card toolbar-->
      <div class="card-toolbar">
        <!--begin::Close-->
        <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_add_vendor_close">
          <i class="ki-duotone ki-cross fs-2">
            <span class="path1"></span>
            <span class="path2"></span>
          </i>
        </div>
        <!--end::Close-->
      </div>
      <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body bg-active-light hover-scroll-overlay-y h-400px pt-5">
      <form class="form" action="#" enctype="multipart/form-data" id="kt_modal_update_user_form">
        <!--begin::User form-->
        <div id="kt_modal_update_user_user_info" class="collapse show">
          <!--begin::Input group-->
          <div class="mb-7 bg-active-light">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2">
              <span class="">Vendor Logo</span>
              <span class="ms-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                data-bs-placement="top" title="Allowed file types: png, jpg, jpeg. Maximum image size 2MB."
                aria-label="Allowed file types: png, jpg, jpeg. Maximum image size 2MB.">
                <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span
                    class="path3"></span></i> </span>
            </label>
            <!--end::Label-->
            <!--begin::Image input wrapper-->
            <div class="mt-1">
              <!--begin::Image placeholder-->
              <style>
                .image-input-placeholder {
                  background-image: url("{{ asset('assets/media/svg/avatars/blank.svg') }}");
                }

                [data-bs-theme="dark"] .image-input-placeholder {
                  background-image: url("{{ asset('assets/media/svg/avatars/blank-dark.svg') }}");
                }
              </style>
              <!--end::Image placeholder-->
              <!--begin::Image input-->
              <div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
                <!--begin::Preview existing avatar-->
                <div class="image-input-wrapper w-200px h-125px bg-contain image-input-placeholder"></div>
                <!--end::Preview existing avatar-->

                <!--begin::Edit-->
                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px shadow"
                  data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                  data-bs-placement="top" aria-label="Change avatar" data-bs-original-title="Change avatar"
                  data-kt-initialized="1">
                  <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
                  <!--begin::Inputs-->
                  <input type="file" value="{{ asset('assets/media/avatars/300-5.jpg') }}" name="avatar"
                    accept=".png, .jpg, .jpeg">
                  <input type="hidden" name="avatar_remove">
                  <!--end::Inputs-->
                </label>

                <!--end::Edit-->
                <!--begin::Cancel-->
                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px shadow"
                  data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel avatar"
                  data-bs-custom-class="tooltip-inverse" data-bs-original-title="Cancel avatar" data-kt-initialized="1">
                  <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                </span>
                <!--end::Cancel-->
                <!--begin::Remove-->
                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px shadow"
                  data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove avatar"
                  data-bs-custom-class="tooltip-inverse" data-bs-original-title="Remove avatar" data-kt-initialized="1">
                  <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                </span>
                <!--end::Remove-->

              </div>
              <!--end::Image input-->
            </div>
            <span class="form-text">*Maximum image size 2MB</span>
            <!--end::Image input wrapper-->
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
          </div>
          <!--end::Input group-->
          <!--begin::Input group-->
          <div class="row g-9 mb-7">
            <!--begin::Col-->
            <div class="col-md-6 fv-row">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2 required">Brand Name</label>
              <!--end::Label-->

              <!--begin::Input-->
              <input class="form-control form-control-solid" placeholder="Enter Vendor Brand Name" name="brand_name"
                placeholder="Enter Vendor Brand Name">
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
              <!--end::Input-->
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-md-6 fv-row">
              <!--begin::Label-->
              <label class="required fs-6 fw-semibold mb-2">Commission % </label>
              <!--end::Label-->
              <!--begin::Input-->
              <input class="form-control form-control-solid" name="commission" placeholder="Commission %"
                value="25">
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
              <!--end::Input-->
            </div>
            <!--end::Col-->
          </div>
          <!--end::Input group-->
          <!--begin::Input group-->
          <div class="row g-9 mb-7">
            <div class="col-md-6 fv-row">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2 required">Brand Owner</label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type="text" class="form-control form-control-solid" name="name"
                placeholder="Enter Vendor Brand Owner">
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
              <!--end::Input-->
            </div>
            <!--end::Input group-->
            <!--begin::Input group-->
            <div class="col-md-6 fv-row">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">Email</label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type="text" class="form-control form-control-solid" name="email"
                placeholder="Enter Vendor Email">
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
              <!--end::Input-->
            </div>
            <!--end::Input group-->
          </div>

          <!--begin::Input group-->
          <div class="fv-row mb-7">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2 required">Contact No</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input type="text" id="phone" class="form-control form-control-solid" name="phone"
              placeholder="">
            <!--end::Input-->
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
          </div>
          <!--end::Input group-->

          <!--begin::Input group-->
          <div class="row g-9 mb-7">
            <!--begin::Col-->
            <div class="col-md-6 fv-row">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">Fassi No.</label>
              <!--end::Label-->

              <!--begin::Input-->
              <input class="form-control form-control-solid" placeholder="Fassi No." name="fassi_no">
              <!--end::Input-->
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-md-6 fv-row">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">Stall No.</label>
              <!--end::Label-->
              <!--begin::Input-->
              <input class="form-control form-control-solid" placeholder="Stall No." name="stall_no">
              <!--end::Input-->
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
            </div>
            <!--end::Col-->
          </div>
          <!--end::Input group-->
          <div class="row mb-7">
            <div class="fv-row">
              <label class="fs-6 fw-semibold mb-2">Documents</label>
              <div class="input-group mb-3">
                <input type="file" name="documents[]" class="form-control custom-file-input" id="customFile"
                  multiple>
              </div>
            </div>
          </div>
          <div class="row">
            <div class="fv-row">
              <label class="fs-6 fw-semibold mb-2">Details</label>
              <input type="hidden" id="quill_html" name="details"></input>
              <div id="kt_docs_quill_basic" name="kt_docs_quill_basic">
              </div>
            </div>
          </div>
          <div class="p-20"></div>

          <!--begin::Input group-->

          <!--end::Input group-->
        </div>
        <!--end::User form-->
      </form>
    </div>
    <!--end::Card body-->
    <!--begin::Card footer-->
    <div class="card-footer bg-dark p-2 d-flex justify-content-end">
      <!--end::Action-->
      {{-- <div class="d-flex justify-content-end">
          <a href="#" id="kt_add_vendor_submit" class="btn btn-primary d-flex justify-content-end">Add Vendor</a>
        </div>
        <span class="indicator-progress">
          Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span> --}}
      <button type="submit" id="kt_add_vendor_submit" class="btn btn-primary">
        <span class="indicator-label">
          Add Vendor
        </span>
        <span class="indicator-progress">
          Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span>
      </button>
      <!--end::Action-->
    </div>
    <!--end::Card footer-->
  </div>
  <!--end::Messenger-->
</div>
