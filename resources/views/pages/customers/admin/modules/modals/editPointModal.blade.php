<div class="modal fade" id="kt_modal_update_address" tabindex="-1" aria-hidden="true">
  <!--begin::Modal dialog-->
  <div class="modal-dialog modal-dialog-centered mw-650px">
    <!--begin::Modal content-->
    <div class="modal-content">
      <!--begin::Form-->
      <form class="form" action="/dashboard/customers/{{ $user->id }}/edit-points" method="POST"
        id="kt_modal_update_address_form">
        <!--begin::Modal header-->
        @csrf
        <div class="modal-header" id="kt_modal_update_address_header">
          <!--begin::Modal title-->
          <h2 class="fw-bold">Edit Points</h2>
          <!--end::Modal title-->
          <!--begin::Close-->
          <div id="kt_modal_update_address_close" data-bs-dismiss="modal"
            class="btn btn-icon btn-sm btn-active-icon-primary">
            <i class="ki-duotone ki-cross fs-1">
              <span class="path1"></span>
              <span class="path2"></span>
            </i>
          </div>
          <!--end::Close-->
        </div>
        <!--end::Modal header-->
        <!--begin::Modal body-->
        <div class="modal-body py-10 px-lg-17">
          <!--begin::Scroll-->
          <div id="kt_modal_update_address_billing_info" class="collapse show">
            <!--begin::Input group-->
            <div class="d-flex flex-column mb-7 fv-row">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2 required">Enter New Points</label>
              <!--end::Label-->
              <!--begin::Input-->
              <input class="form-control form-control-solid" placeholder="" name="points"
                value="{{ $user->point->points ?? 0 }}" />

              @error('points')
                <div class="text-danger">{{ $message }}</div>
              @enderror
              <!--end::Input-->
            </div>
          </div>
          <!--end::Scroll-->
        </div>
        <!--end::Modal body-->
        <!--begin::Modal footer-->
        <div class="modal-footer flex-center">
          <!--begin::Button-->
          <button type="reset" id="kt_modal_update_address_cancel" class="btn btn-light me-3">Discard</button>
          <!--end::Button-->
          <!--begin::Button-->
          <button type="submit" id="kt_modal_update_address_submit" class="btn btn-primary">
            <span class="indicator-label">Submit</span>
            <span class="indicator-progress">Please wait...
              <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
          </button>
          <!--end::Button-->
        </div>
        <!--end::Modal footer-->
      </form>
      <!--end::Form-->
    </div>
  </div>
</div>
