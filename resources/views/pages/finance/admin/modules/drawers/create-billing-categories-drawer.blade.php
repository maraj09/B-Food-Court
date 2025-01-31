<div id="kt_drawer_billing_category" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat"
  data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}"
  data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_drawer_billing_category_button"
  data-kt-drawer-close="#kt_drawer_billing_category_close">
  <!--begin::Messenger-->
  <form id="" action="/dashboard/finance/billing-categories/store" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card w-100 border-0 rounded-0 h-100" id="kt_drawer_chat_messenger">
      <!--begin::Card header-->
      <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
        <!--begin::Title-->
        <div class="card-title">
          <!--begin::User-->
          <div class="d-flex justify-content-center flex-column me-3">

            <!--begin::Info-->
            <div class="mb-0 lh-1">
              <span class="badge badge-success badge-circle w-10px h-10px me-1" data-bs-toggle="tooltip"
                data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Add Category"></span>
              <span class="fs-7 fw-semibold text-muted">Add Category</span>
            </div>
            <!--end::Info-->
          </div>
          <!--end::User-->
        </div>
        <!--end::Title-->
        <!--begin::Card toolbar-->
        <div class="card-toolbar">
          <!--begin::User Info-->

          <!--end::User Info-->
          <!--begin::Close-->
          <div class="btn btn-sm btn-icon btn-active-color-primary" id="kt_drawer_billing_category_close">
            <i class="ki-duotone ki-cross-square fs-2">
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
      <div class="card-body px-10 scroll h-auto">
        <!--begin::row-->
        <div class="row g-9 mb-7">
          <!--begin::Col-->
          <div class="col-md-6 fv-row">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2 required ">Name</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input class="form-control form-control-solid @error('name') is-invalid @enderror"
              placeholder="Category Name" name="name" value="{{ old('name') }}">
            <!--end::Input-->
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <!--end::Col-->
          <!--begin::Col-->
          <div class="col-md-6 fv-row">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2">GST No</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input type="text" class="form-control form-control-solid @error('gst_no') is-invalid @enderror"
              placeholder="GST No" name="gst_no" value="{{ old('gst_no') }}">
            <!--end::Input-->
            @error('gst_no')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <!--end::Col-->
        </div>
        <!--end::row--><!--begin::row-->
        <div class="row g-9 mb-7">
          <!--begin::Col-->

          <div class="col-md-6 fv-row">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2">Address</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input type="text" class="form-control form-control-solid @error('address') is-invalid @enderror"
              placeholder="Address" name="address" value="{{ old('address') }}">
            <!--end::Input-->
            @error('address')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <!--end::Input-->
          </div>

          <!--end::Col-->
          <!--begin::Col-->
          <div class="col-md-6 fv-row">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2 required">Color</label>
            <!--end::Label-->
            <select class="form-select form-select-solid @error('color_class') is-invalid @enderror"
              data-control="select2" data-placeholder="Payment Mode" name="color_class">
              <option></option>
              <option value="badge-light" {{ old('color_class') == 'badge-light' ? 'selected' : '' }}>
                Light</option>
              <option value="badge-primary" {{ old('color_class') == 'badge-primary' ? 'selected' : '' }}>
                Blue</option>
              <option value="badge-secondary" {{ old('color_class') == 'badge-secondary' ? 'selected' : '' }}>
                Gray</option>
              <option value="badge-success" {{ old('color_class') == 'badge-success' ? 'selected' : '' }}>
                Green</option>
              <option value="badge-info" {{ old('color_class') == 'badge-info' ? 'selected' : '' }}>
                Violet</option>
              <option value="badge-warning" {{ old('color_class') == 'badge-warning' ? 'selected' : '' }}>
                Yellow</option>
              <option value="badge-danger" {{ old('color_class') == 'badge-danger' ? 'selected' : '' }}>
                Red</option>
              <option value="badge-dark" {{ old('color_class') == 'badge-dark' ? 'selected' : '' }}>
                Black</option>
              <option value="badge-white" {{ old('color_class') == 'badge-white' ? 'selected' : '' }}>
                White</option>
              <option value="badge-light-primary" {{ old('color_class') == 'badge-light-primary' ? 'selected' : '' }}>
                Light Blue</option>
              <option value="badge-light-secondary"
                {{ old('color_class') == 'badge-light-secondary' ? 'selected' : '' }}>
                Light Gray</option>
              <option value="badge-light-success" {{ old('color_class') == 'badge-light-success' ? 'selected' : '' }}>
                Light Green</option>
              <option value="badge-light-info" {{ old('color_class') == 'badge-light-info' ? 'selected' : '' }}>
                Light Violet</option>
              <option value="badge-light-warning" {{ old('color_class') == 'badge-light-warning' ? 'selected' : '' }}>
                Light Yellow</option>
              <option value="badge-light-danger" {{ old('color_class') == 'badge-light-danger' ? 'selected' : '' }}>
                Light Red</option>
              <option value="badge-light-dark" {{ old('color_class') == 'badge-light-dark' ? 'selected' : '' }}>
                Light Black</option>
            </select>
            <!--end::Input-->
            @error('color_class')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <!--end::Col-->
        </div>
        <!--end::row-->
        <div class="row g-9 mb-7">
          <!--begin::Col-->
          <div class="col fv-row">
            <div class="mb-7 bg-active-light">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">
                <span>Brand Logo</span>
                <span class="ms-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                  data-bs-placement="top" title="Allowed file types: png, jpg, jpeg. Maximum image size 2MB."
                  aria-label="Allowed file types: png, jpg, jpeg. Maximum image size 2MB.">
                  <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span
                      class="path2"></span><span class="path3"></span></i> </span>
              </label>
              <!--end::Label-->
              <!--begin::Image input wrapper-->
              <div class="mt-1">
                <!--begin::Image placeholder-->
                <style>
                  .image-input-placeholder {
                    background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }});
                  }

                  [data-bs-theme="dark"] .image-input-placeholder {
                    background-image: url({{ asset('assets/media/svg/avatars/blank-dark.svg') }});
                  }
                </style>
                <!--end::Image placeholder-->
                <!--begin::Image input-->
                <div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
                  <!--begin::Preview existing avatar-->
                  <div class="image-input-wrapper w-150px h-150px"
                    style="background-image: url({{ asset('assets/media/svg/files/blank-image-dark.svg') }}"></div>
                  <!--end::Preview existing avatar-->

                  <!--begin::Edit-->
                  <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px shadow"
                    data-kt-image-input-action="change" data-bs-toggle="tooltip"
                    data-bs-custom-class="tooltip-inverse" data-bs-placement="top" aria-label="Change avatar"
                    data-bs-original-title="Change avatar" data-kt-initialized="1">
                    <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
                    <!--begin::Inputs-->
                    <input type="file" name="logo" accept=".png, .jpg, .jpeg">
                    <input type="hidden" name="avatar_remove">
                    <!--end::Inputs-->
                  </label>
                  <!--end::Edit-->
                  <!--begin::Cancel-->
                  <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px shadow"
                    data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel avatar"
                    data-bs-custom-class="tooltip-inverse" data-bs-original-title="Cancel avatar"
                    data-kt-initialized="1">
                    <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                  </span>
                  <!--end::Cancel-->
                  <!--begin::Remove-->
                  <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px shadow"
                    data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove avatar"
                    data-bs-custom-class="tooltip-inverse" data-bs-original-title="Remove avatar"
                    data-kt-initialized="1">
                    <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                  </span>
                  <!--end::Remove-->
                </div>
                <!--end::Image input-->
              </div>
              <span class="form-text">*Maximum image size 2MB</span>
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
              <!--end::Image input wrapper-->
            </div>
          </div>
          <!--end::Col-->
        </div>
        <!--end::row-->
      </div>
      <!--end::Card body-->
      <!--begin::Card footer-->
      <div class="card-footer bg-dark p-2 mt-auto">
        <!--end::Action-->
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-primary"
            onclick="{ this.setAttribute('data-kt-indicator', 'on'); this.disabled = true; this.form.submit(); }">
            <span class="indicator-label">
              Add Expense
            </span>
            <span class="indicator-progress">
              Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
          </button>
        </div>
        <!--end::Action-->
      </div>
      <!--end::Card footer-->
    </div>
    <!--end::Messenger-->
  </form>
</div>
