<div id="kt_add_payout" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="cart" data-kt-drawer-activate="true"
  data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end"
  data-kt-drawer-toggle="#kt_add_payout_toggle" data-kt-drawer-close="#kt_add_payout_close">
  <!--begin::Messenger-->
  <div class="card card-flush w-100 rounded-0">
    <!--begin::Card header-->
    <div class="card-header bg-dark">
      <!--begin::Title-->
      <div class="card-title">
        <!--begin::User-->
        <div class="d-flex justify-content-center flex-column me-3">
          <a id="kt_drawer_order_toggle" class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1">Payout
            Request</a>
          <!--begin::Info-->
          {{-- <div class="mb-0 lh-1" >
            <span class="fs-5 fw-semibold text-gray-900">Vendor Name</span>
            <span class="fs-7 fw-semibold text-warning d-block">Payout Request Date</span>
          </div> --}}
          <!--end::Info-->
        </div>
        <!--end::User-->
      </div>
      <!--end::Title-->
      <!--begin::Card toolbar-->
      <div class="card-toolbar">
        <!--begin::Close-->
        <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_add_payout_close">
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
      <form class="form" action="{{ route('vendor.payout.submit') }}" enctype="multipart/form-data" method="POST"
        id="payout_form">
        @csrf <!-- Add CSRF protection -->
        <input type="hidden" id="payout_mode" name="payout_mode" value="create">
        <input type="hidden" id="payout_id" name="payout_id" value="">
        <!--begin::User form-->
        <div id="kt_modal_update_user_user_info" class="collapse show">
          <!--begin::Input group-->
          <div class="row g-9 mb-7">
            <!--begin::Col-->
            <div class="col-md-4 fv-row">
              <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                  <span class="badge badge-light-success fw-bold fs-2">₹{{ $vendorBank->amount_paid }}</span>
                  <div class="fs-4 text-gray-800 d-block">Amount Paid</div>
                </div>
              </div>
            </div>
            <!--end::Col-->
            <div class="col-md-4 fv-row">
              <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                  <span class="badge badge-light-info fw-bold fs-2">₹{{ number_format($pendingAmount, 2) }}</span>
                  <div class="fs-4 text-gray-800 d-block">Pending Amount</div>
                </div>
              </div>
            </div>
            <!--begin::Col-->
            <div class="col-md-4 fv-row">
              <div class="d-flex align-items-center">
                <div class="flex-grow-1">
                  <span class="badge badge-light-warning fw-bold fs-2">₹{{ $vendorBank->balance }}</span>
                  <div class="fs-4 text-gray-800 d-block">Balance Amount</div>
                </div>
              </div>
            </div>
            <!--end::Col-->
          </div>
          <!--end::Input group-->
          <!--begin::Input group-->
          <div class="row g-9 mb-7">
            <!--begin::Col-->
            <div class="col fv-row">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">Request Amount <i class="fa-solid fa-circle-info ms-2"
                  data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                  title="Can not request more than balance amount"></i></label>
              <!--end::Label-->
              <!--begin::Input-->
              <input class="form-control form-control-solid" placeholder="Request Amount" name="request_amount"
                value="{{ old('request_amount') }}">
              <!-- Display error message for request amount -->
              @error('request_amount')
                <div class="text-danger">{{ $message }}</div>
              @enderror
              <!--end::Input-->
            </div>
            <!--end::Col-->
          </div>
          <!--end::Input group-->
          <!--begin::Input group-->
          <div class="fv-row mb-7">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2">Remark</label>
            <!--end::Label-->
            <!--begin::Input-->
            <textarea class="form-control" name="remark" data-kt-autosize="true">{{ old('remark') }}</textarea>
            <!-- Display error message for remark -->
            @error('remark')
              <div class="text-danger">{{ $message }}</div>
            @enderror
            <!--end::Input-->
          </div>
          <!--end::Input group-->
          <!--begin::Input group-->
          <div class="mb-7 bg-active-light d-none">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2">
              <span>Payment Image</span>
              <span class="ms-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                data-bs-placement="top" title="Allowed file types: png, jpg, jpeg."
                aria-label="Allowed file types: png, jpg, jpeg.">
                <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span
                    class="path3"></span></i> </span>
            </label>
            <!--end::Label-->
            <!--begin::Image input wrapper-->
            <div class="mt-1">
              <!--begin::Image placeholder-->
              <style>
                .image-input-placeholder {
                  background-image: url('/metronic8/demo1/assets/media/svg/avatars/blank.svg');
                }

                [data-bs-theme="dark"] .image-input-placeholder {
                  background-image: url('/metronic8/demo1/assets/media/svg/avatars/blank-dark.svg');
                }
              </style>
              <!--end::Image placeholder-->
              <!--begin::Image input-->
              <div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
                <!--begin::Preview existing avatar-->
                <div class="image-input-wrapper w-150px h-150px"
                  style="background-image: url('/assets/media/svg/files/upload.svg')"></div>
                <!--end::Preview existing avatar-->

                <!--begin::Edit-->
                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px shadow"
                  data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                  data-bs-placement="top" title="Change avatar">
                  <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
                  <!--begin::Inputs-->
                  <input type="file" name="payment_image" accept=".png, .jpg, .jpeg">
                  <input type="hidden" name="payment_image">
                  <!--end::Inputs-->
                </label>
                <!--end::Edit-->
                <!--begin::Cancel-->
                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px shadow"
                  data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel avatar"
                  data-bs-custom-class="tooltip-inverse" title="Cancel avatar">
                  <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                </span>
                <!--end::Cancel-->
                <!--begin::Remove-->
                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px shadow"
                  data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove avatar"
                  data-bs-custom-class="tooltip-inverse" title="Remove avatar">
                  <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                </span>
                <!--end::Remove-->
              </div>
              <!--end::Image input-->
            </div>
            @error('payment_image')
              <div class="text-danger">{{ $message }}</div>
            @enderror
            <!--end::Image input wrapper-->
          </div>
          <!--end::Input group-->
        </div>
        <!--end::User form-->
        <!-- Submit Button -->
      </form>
    </div>

    <!--end::Card body-->
    <!--begin::Card footer-->
    <div class="card-footer bg-dark p-2">
      <!--end::Action-->
      <div class="d-flex justify-content-end">
        <button id="payout_form_submit_btn" type="submit" class="btn btn-primary">
          <span class="indicator-label">
            Request/Update
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
</div>
