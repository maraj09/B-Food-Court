<div class="modal fade" id="kt_modal_add_role" tabindex="-1" aria-hidden="true">
  <!--begin::Modal dialog-->
  <div class="modal-dialog modal-dialog-centered mw-750px">
    <!--begin::Modal content-->
    <div class="modal-content">
      <!--begin::Modal header-->
      <div class="modal-header">
        <!--begin::Modal title-->
        <h2 class="fw-bold">Add a Role</h2>
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
      <div class="modal-body scroll-y mx-lg-5 my-7">
        <!--begin::Form-->
        <form class="form"
          action="{{ old('is_edit') ? '/dashboard/user-management/roles/' . old('is_edit') . '/update' : '/dashboard/user-management/roles' }}"
          method="POST">
          @csrf
          <!--begin::Scroll-->
          <div class="d-flex flex-column scroll-y me-n7 pe-7" id="kt_modal_add_role_scroll" data-kt-scroll="true"
            data-kt-scroll-activate="{default: false, lg: true}" data-kt-scroll-max-height="auto"
            data-kt-scroll-dependencies="#kt_modal_add_role_header" data-kt-scroll-wrappers="#kt_modal_add_role_scroll"
            data-kt-scroll-offset="300px">

            <!--begin::Input group-->
            <div class="fv-row mb-10">
              <!--begin::Label-->
              <label class="fs-5 fw-bold form-label mb-2">
                <span class="required">Role name</span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <input class="form-control form-control-solid @error('role_name') is-invalid @enderror"
                placeholder="Enter a role name" name="role_name" value="{{ old('role_name') }}" required />
              <!--end::Input-->

              <!--begin::Error Message-->
              @error('role_name')
                <div class="invalid-feedback">
                  {{ $message }}
                </div>
              @enderror
              <!--end::Input-->
            </div>
            <!--end::Input group-->

            <!--begin::Permissions-->
            <div class="fv-row">
              <!--begin::Label-->
              <label class="fs-5 fw-bold form-label mb-2">Role Permissions</label>
              <!--end::Label-->
              <!--begin::Table wrapper-->
              <div class="table-responsive">
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5">
                  <!--begin::Table body-->
                  <tbody class="text-gray-600 fw-semibold">
                    <!--begin::Table row-->

                    @foreach ($allPermissions as $permission)
                      <tr>
                        <!-- Permission Name -->
                        <td class="text-gray-800 text-capitalize">{{ $permission->name }}</td>
                        <!-- Checkbox to assign permission -->
                        <td>
                          <div class="d-flex">
                            <label class="form-check form-check-sm form-check-custom form-check-solid me-5">
                              <input class="form-check-input" type="checkbox" name="permissions[]"
                                value="{{ $permission->name }}" />
                              <span class="form-check-label">Assign</span>
                            </label>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                    <!--end::Table row-->
                  </tbody>
                  <!--end::Table body-->
                </table>
                <!--end::Table-->
              </div>
              <!--end::Table wrapper-->
            </div>
            <!--end::Permissions-->
          </div>
          <!--end::Scroll-->

          <!--begin::Actions-->
          <div class="text-center pt-15">
            <button type="reset" class="btn btn-light me-3">Discard</button>
            <button type="submit" class="btn btn-primary">
              <span class="indicator-label">Submit</span>
              <span class="indicator-progress">Please wait...
                <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
              </span>
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
