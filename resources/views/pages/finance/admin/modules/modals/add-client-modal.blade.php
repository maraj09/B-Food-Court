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
<div class="modal fade" tabindex="-1" id="add_client_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Add Client</h3>

        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
        </div>
        <!--end::Close-->
      </div>

      <form id="kt_modal_add_client_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="#">
        <div class="modal-body">
          <!--begin::Input group-->
          <div class="row g-9 mb-8">
            <!--begin::Col-->
            <div class="col-md-6 fv-row fv-plugins-icon-container">
              <label class="required fs-6 fw-semibold mb-2">Name</label>

              <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="Enter Name" autocomplete="off" />

            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-md-6 fv-row fv-plugins-icon-container">
              <label class="required fs-6 fw-semibold mb-2">Company Name</label>

              <!--begin::Input-->
              <input type="text" name="company_name" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="Enter company name" autocomplete="off" />
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
              <!--end::Input-->
            </div>
            <!--end::Col-->
          </div>
          <!--end::Input group-->

          <!--begin::Input group-->
          <div class="row g-9 mb-8">
            <!--begin::Col-->
            <div class="col-md-6 fv-row fv-plugins-icon-container">
              <label class="fs-6 fw-semibold mb-2">Email Address</label>

              <input type="email" name="email" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="Enter email address" />

              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-md-6 fv-row">
              <label class="fs-6 fw-semibold mb-2">Contact No</label>

              <input type="text" id="phone_modal_add_client" name="phone"
                class="form-control form-control-solid mb-3 mb-lg-0" />

              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>

              <!--end::Input-->
            </div>
            <!--end::Col-->
          </div>
          <!--end::Input group-->
          <!--begin::Input group-->
          <div class="row g-9 mb-8">
            <!--begin::Col-->
            <div class="col-md-6 fv-row fv-plugins-icon-container">
              <label class="fs-6 fw-semibold mb-2">GST NO</label>

              <input type="test" name="gst_no" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="Enter GST No" />

              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-md-6 fv-row">
              <label class="fs-6 fw-semibold mb-2">Address</label>

              <input type="text" placeholder="Enter Address" name="address"
                class="form-control form-control-solid mb-3 mb-lg-0" />

              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>

              <!--end::Input-->
            </div>
            <!--end::Col-->
          </div>
          <!--end::Input group-->
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" id="kt_add_client_close">Close</button>
          <button type="submit" id="kt_add_client_save" class="btn btn-primary">
            <span class="indicator-label">
              Submit
            </span>
            <span class="indicator-progress">
              Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
          </button>
        </div>
      </form>
    </div>
  </div>
</div>
