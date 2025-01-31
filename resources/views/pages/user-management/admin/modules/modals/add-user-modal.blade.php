<div class="modal fade" id="kt_modal_add_user" tabindex="-1" aria-hidden="true">
  <!--begin::Modal dialog-->
  <div class="modal-dialog modal-dialog-centered mw-650px">
    <!--begin::Modal content-->
    <div class="modal-content">
      <!--begin::Modal header-->
      <div class="modal-header" id="kt_modal_add_user_header">
        <!--begin::Modal title-->
        <h2 class="fw-bold">Add User</h2>
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
      <div class="modal-body px-5 my-7">
        <!--begin::Form-->
        <form method="POST" class="form"
          action="{{ old('is_edit') ? '/dashboard/user-management/users/' . old('is_edit') . '/update' : '/dashboard/user-management/users' }}">
          @csrf
          <input type="hidden" name="is_edit" value="0">
          <!--begin::Scroll-->
          <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_user_scroll" data-kt-scroll="true"
            data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
            data-kt-scroll-dependencies="#kt_modal_add_user_header" data-kt-scroll-wrappers="#kt_modal_add_user_scroll"
            data-kt-scroll-offset="300px">

            <!--begin::Input group-->
            <div class="fv-row mb-7">
              <!--begin::Label-->
              <label class="required fw-semibold fs-6 mb-2">Full Name</label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="Full name" value="{{ old('name') }}" />
              <!--end::Input-->
              @if ($errors->has('name'))
                <div class="text-danger">{{ $errors->first('name') }}</div>
              @endif
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="fv-row mb-7">
              <!--begin::Label-->
              <label class="required fw-semibold fs-6 mb-2">Email</label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type="email" name="email" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="example@bfc.com" value="{{ old('email') }}" />
              <!--end::Input-->
              @if ($errors->has('email'))
                <div class="text-danger">{{ $errors->first('email') }}</div>
              @endif
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="fv-row mb-7">
              <!--begin::Label-->
              <label class="required fw-semibold fs-6 mb-2">Password</label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type="password" name="password" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="********" value="" />
              <!--end::Input-->
              @if ($errors->has('password'))
                <div class="text-danger">{{ $errors->first('password') }}</div>
              @endif
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="fv-row mb-7">
              <!--begin::Label-->
              <label class="required fw-semibold fs-6 mb-2">Confirm Password</label>
              <!--end::Label-->
              <!--begin::Input-->
              <input type="password" name="password_confirmation" class="form-control form-control-solid mb-3 mb-lg-0"
                placeholder="********" value="" />
              <!--end::Input-->
            </div>
            <!--end::Input group-->

            <!--begin::Input group-->
            <div class="mb-5">
              <!--begin::Label-->
              <label class="required fw-semibold fs-6 mb-5">Role</label>
              <!--end::Label-->
              <!--begin::Roles-->
              @foreach ($roles as $role)
                <!--begin::Input row-->
                <div class="d-flex fv-row">
                  <!--begin::Radio-->
                  <div class="form-check form-check-custom form-check-solid">
                    <!--begin::Input-->
                    <input class="form-check-input me-3" name="user_role" type="radio" value="{{ $role->id }}"
                      id="kt_modal_update_role_option_{{ $role->id }}"
                      {{ old('user_role') == $role->id ? 'checked' : '' }} />
                    <!--end::Input-->
                    <!--begin::Label-->
                    <label class="form-check-label" for="kt_modal_update_role_option_{{ $role->id }}">
                      <div class="fw-bold text-gray-800">{{ $role->name }}</div>
                    </label>
                    <!--end::Label-->
                  </div>
                  <!--end::Radio-->
                </div>
                <div class='separator separator-dashed my-5'></div>
                <!--end::Input row-->
              @endforeach
              @if ($errors->has('user_role'))
                <div class="text-danger">{{ $errors->first('user_role') }}</div>
              @endif
              <!--end::Roles-->
            </div>
            <!--end::Input group-->
          </div>
          <!--end::Scroll-->

          <!--begin::Actions-->
          <div class="text-center pt-10">
            <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal"
              aria-label="Close">Discard</button>
            <button type="submit" class="btn btn-primary" data-kt-users-modal-action="submit">
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
