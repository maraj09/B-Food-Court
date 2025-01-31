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
<div class="modal fade" tabindex="-1" id="add_customer_modal">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Add Customer</h3>

        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
        </div>
        <!--end::Close-->
      </div>

      <form id="kt_modal_add_customer_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" action="#">
        <div class="modal-body">
          <!--begin::Input group-->
          <div class="row g-9 mb-8">
            <!--begin::Col-->
            <div class="col-md-6 fv-row fv-plugins-icon-container">
              <label class="required fs-6 fw-semibold mb-2">Name</label>

              <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="Enter Name" autocomplete="off" />

              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
            </div>
            <!--end::Col-->

            <!--begin::Col-->
            <div class="col-md-6 fv-row fv-plugins-icon-container">
              <label class="required fs-6 fw-semibold mb-2">Date of Birth</label>

              <!--begin::Input-->
              <div class="position-relative d-flex align-items-center">
                <!--begin::Icon-->
                <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4"><span class="path1"></span><span
                    class="path2"></span><span class="path3"></span><span class="path4"></span><span
                    class="path5"></span><span class="path6"></span></i> <!--end::Icon-->

                <!--begin::Datepicker-->
                <input class="form-control form-control-solid ps-12 flatpickr-input" placeholder="Select a date"
                  name="date_of_birth" type="date" readonly="readonly" id="kt_datepicker_dob">
                <!--end::Datepicker-->
              </div>
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
              <label class="required fs-6 fw-semibold mb-2">Contact No</label>

              <input type="text" id="phone_modal_add_customer" name="phone"
                class="form-control form-control-solid mb-3 mb-lg-0" />

              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>

              <!--end::Input-->
            </div>
            <!--end::Col-->
          </div>
          <!--end::Input group-->
          <!--begin::Input group-->

          <!--end::Input group-->
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" id="kt_add_customer_close">Close</button>
          <button type="submit" id="kt_add_customer_save" class="btn btn-primary">
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
