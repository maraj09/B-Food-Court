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
<div class="tab-pane fade {{ $lastSegment == 'edit' ? 'active show' : '' }}" id="kt_ecommerce_customer_general"
  role="tabpanel">
  <!--begin::Card-->
  <div class="card pt-4 mb-6 mb-xl-9">
    <!--begin::Card header-->
    <div class="card-header border-0">
      <!--begin::Card title-->
      <div class="card-title">
        <h2>Profile</h2>
      </div>
      <!--end::Card title-->
    </div>
    <!--end::Card header-->

    <!--begin::Card body-->
    <div class="card-body pt-0 pb-5">
      <!--begin::Form-->
      <form method="POST" class="form fv-plugins-bootstrap5 fv-plugins-framework"
        action="/dashboard/customers/{{ $user->id }}" id="kt_ecommerce_customer_profile" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!--begin::Row-->
        <div class="row row-cols-1 row-cols-md-2">
          <!--begin::Col-->
          <div class="col">
            <!--begin::Input group-->
            <div class="fv-row mb-7 fv-plugins-icon-container">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2 required">Customer Name</label>
              <!--end::Label-->

              <!--begin::Input-->
              <input type="text" class="form-control form-control-solid @error('name') is-invalid @enderror"
                placeholder="Enter Brand Name" name="name" value="{{ $user->name }}">
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <!--end::Input-->

            </div>

            <!--end::Input group-->
          </div>
          <!--end::Col-->

          <!--begin::Col-->
          <div class="col">
            <!--begin::Input group-->
            <div class="fv-row mb-7 fv-plugins-icon-container">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">
                <span class="required">Date of Birth</span>
              </label>
              <!--end::Label-->

              <!--begin::Input-->
              <div class="position-relative d-flex align-items-center">
                <!--begin::Icon-->
                <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4"><span class="path1"></span><span
                    class="path2"></span><span class="path3"></span><span class="path4"></span><span
                    class="path5"></span><span class="path6"></span></i>
                <!--end::Icon-->
                <input
                  class="form-control form-control-solid ps-12 flatpickr-input @error('date_of_birth') is-invalid @enderror"
                  placeholder="Select a date" name="date_of_birth" type="text" readonly="readonly"
                  id="kt_datepicker_dob" value="{{ $user->customer->date_of_birth }}">
              </div>
              @error('date_of_birth')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <!--end::Input-->

            </div>

            <!--end::Input group-->
          </div>
          <!--end::Col-->
        </div>
        <!--end::Row-->

        <!--begin::Row-->
        <div class="row row-cols-1 row-cols-md-2">
          <!--begin::Col-->
          <div class="col">
            <!--begin::Input group-->
            <div class="fv-row mb-7 fv-plugins-icon-container">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">
                <span class="">Email Address</span>
              </label>
              <!--end::Label-->

              <!--begin::Input-->
              <input type="email" class="form-control form-control-solid @error('email') is-invalid @enderror"
                placeholder="Enter email address" name="email" value="{{ $user->email }}">
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <!--end::Input-->

            </div>
            <!--end::Input group-->
          </div>
          <!--end::Col-->

          <!--begin::Col-->
          <div class="col">
            <!--begin::Input group-->
            <div class="fv-row mb-7">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">
                <span class="required">Contact No:</span>
              </label>
              <!--end::Label-->

              <!--begin::Input-->
              <input class="form-control form-control-solid @error('phone') is-invalid @enderror"
                placeholder="Enter Mobile number" id="phone" name="phone" value="{{ $user->phone }}">
              @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <!--end::Input-->
            </div>
            <!--end::Input group-->
          </div>
          <!--end::Col-->
        </div>
        <!--end::Row-->
        <!--begin::Row-->
        <!--end::Row-->
        <div class="d-flex justify-content-end">
          <!--begin::Button-->
          <button type="submit" onclick="this.setAttribute('data-kt-indicator', 'on')"
            id="kt_ecommerce_customer_profile_submit" class="btn btn-success">
            <span class="indicator-label">
              Save
            </span>
            <span class="indicator-progress">
              Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
          </button>
          <!--end::Button-->
        </div>
      </form>
      <!--end::Form-->
    </div>
    <!--end::Card body-->
  </div>
  <!--end::Card-->
</div>
