<div class="modal fade" id="kt_modal_add_permission" tabindex="-1" aria-hidden="true">
  <!--begin::Modal dialog-->
  <div class="modal-dialog modal-dialog-centered mw-650px">
    <!--begin::Modal content-->
    <div class="modal-content">
      <!--begin::Modal header-->
      <div class="modal-header">
        <!--begin::Modal title-->
        <h2 class="fw-bold">Add a Permission</h2>
        <!--end::Modal title-->
        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-icon-primary" data-bs-dismiss="modal" aria-label="Close">
          <i class="ki-duotone ki-cross fs-1">
            <span class="path1"></span>
            <span class="path2"></span>
          </i>
        </div>
        <!--end::Close-->
      </div>
      <!--end::Modal header-->
      <!--begin::Modal body-->
      <div class="modal-body scroll-y mx-5 mx-xl-15 my-7">
        <!--begin::Form-->
        <form class="form" action="/dashboard/user-management/permissions" method="POST">
          <!--begin::Input group-->
          @csrf
          <div class="fv-row mb-7">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold form-label mb-2">
              <span class="required">Permission Name</span>
              <span class="ms-2" data-bs-toggle="popover" data-bs-trigger="hover" data-bs-html="true"
                data-bs-content="Permission names is required to be unique.">
                <i class="ki-duotone ki-information fs-7">
                  <span class="path1"></span>
                  <span class="path2"></span>
                  <span class="path3"></span>
                </i>
              </span>
            </label>
            <!--end::Label-->

            <!--begin::Input-->
            <input class="form-control form-control-solid {{ $errors->has('name') ? 'is-invalid' : '' }}"
              placeholder="Enter a permission name" name="name" value="{{ old('name') }}" />
            <!--end::Input-->

            <!--begin::Error Message-->
            @if ($errors->has('name'))
              <div class="invalid-feedback">
                {{ $errors->first('name') }}
              </div>
            @endif
            <!--end::Error Message-->
          </div>

          <!--end::Input group-->
          <!--begin::Actions-->
          <div class="text-center pt-15">
            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal">Discard</button>
            <button type="submit" class="btn btn-primary" data-kt-permissions-modal-action="submit">
              <span class="indicator-label">Submit</span>
              <span class="indicator-progress">Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
          </div>
          <!--end::Actions-->
        </form>
        <!--end::Form-->
      </div>
      <!--end::Modal body-->
    </div>
    <!--end::Modal content-->
  </div>
  <!--end::Modal dialog-->
</div>
