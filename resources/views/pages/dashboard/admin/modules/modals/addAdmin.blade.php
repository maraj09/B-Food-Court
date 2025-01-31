<div class="modal fade show" id="kt_modal_new_target" tabindex="-1" aria-modal="true" role="dialog">
  <!--begin::Modal dialog-->
  <div class="modal-dialog modal-dialog-centered mw-650px">
    <!--begin::Modal content-->
    <div class="modal-content rounded">
      <!--begin::Modal header-->
      <div class="modal-header pb-0 border-0 justify-content-end">
        <!--begin::Close-->
        <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal">
          <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
        </div>
        <!--end::Close-->
      </div>
      <!--begin::Modal header-->

      <!--begin::Modal body-->
      <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
        <!--begin:Form-->
        <form id="kt_modal_new_target_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="#">
          <!--begin::Heading-->
          <div class="mb-13 text-center">
            <!--begin::Title-->
            <h1 class="mb-3">Add Admin</h1>
            <!--end::Title-->

            <!--begin::Description-->
            <div class="text-muted fw-semibold fs-5">
              If you need more admins, you can add here but be carefull because admin will have full access of the
              website.
            </div>
            <!--end::Description-->
          </div>
          <!--end::Heading-->

          <!--begin::Input group-->
          <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
            <!--begin::Label-->
            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
              <span class="required">Name</span>
            </label>
            <!--end::Label-->

            <input type="text" class="form-control form-control-solid" placeholder="Enter Name" name="name">
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
          </div>
          <!--end::Input group-->

          <!--begin::Input group-->
          <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
            <!--begin::Label-->
            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
              <span class="required">Email</span>
            </label>
            <!--end::Label-->

            <input type="text" class="form-control form-control-solid" placeholder="Enter Email address"
              name="email">
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
          </div>
          <!--end::Input group-->


          <!--begin::Input group-->
          <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
            <!--begin::Label-->
            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
              <span class="required">Password</span>
            </label>
            <!--end::Label-->

            <input type="text" class="form-control form-control-solid" placeholder="Enter Password" name="password">
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
          </div>
          <!--end::Input group-->


          <!--begin::Input group-->
          <div class="d-flex flex-column mb-8 fv-row fv-plugins-icon-container">
            <!--begin::Label-->
            <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
              <span class="required">Confirm Password</span>
            </label>
            <!--end::Label-->

            <input type="text" class="form-control form-control-solid" placeholder="Enter Password Again"
              name="password_confirmation">
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
          </div>
          <!--end::Input group-->

          <!--begin::Actions-->
          <div class="text-center">
            <button type="reset" id="kt_modal_new_target_cancel" class="btn btn-light me-3">
              Cancel
            </button>

            <button type="submit" id="kt_modal_new_target_submit" class="btn btn-primary">
              <span class="indicator-label">
                Submit
              </span>
              <span class="indicator-progress">
                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
              </span>
            </button>
          </div>
          <!--end::Actions-->
        </form>
        <!--end:Form-->
      </div>
      <!--end::Modal body-->
    </div>
    <!--end::Modal content-->
  </div>
  <!--end::Modal dialog-->
</div>
