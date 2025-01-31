<div id="kt_create_event" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="cart" data-kt-drawer-activate="true"
  data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end"
  data-kt-drawer-toggle="#kt_create_event_toggle" data-kt-drawer-close="#kt_create_event_close">
  <!--begin::Messenger-->
  <div class="card card-flush w-100 rounded-0">
    <!--begin::Card header-->
    <div class="card-header bg-dark">
      <!--begin::Title-->
      <h3 class="card-title text-gray-900 fw-bold">Add Event</h3>
      <!--end::Title-->
      <!--begin::Card toolbar-->
      <div class="card-toolbar">
        <!--begin::Close-->
        <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_create_event_close">
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
      <form class="form" id="kt_modal_add_event_form">
        <!--begin::User form-->
        <!--begin::Input group-->
        <div class="mb-7 bg-active-light">
          <!--begin::Label-->
          <label class="fs-6 fw-semibold mb-2">
            <span>Event Image</span>
            <span class="ms-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
              title="Allowed file types: png, jpg, jpeg. Maximum image size 2MB."
              aria-label="Allowed file types: png, jpg, jpeg. Maximum image size 2MB.">
              <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span
                  class="path3"></span></i> </span>
          </label>
          <!--end::Label-->
          <!--begin::Image input wrapper-->
          <div class="mt-1">
            <!--begin::Image placeholder-->
            <style>
              .image-input-placeholder {
                background-image: url("{{ asset('assets/media/svg/files/blank-image-dark.svg') }}");
                background-size: contain;
              }

              [data-bs-theme="dark"] .image-input-placeholder {
                background-image: url("{{ asset('assets/media/svg/files/blank-image-dark.svg') }}");
                background-size: contain;
              }
            </style>
            <!--end::Image placeholder-->
            <!--begin::Image input-->
            <div class="image-input image-input-outline image-input-placeholder fv-row" data-kt-image-input="true">
              <!--begin::Preview existing avatar-->
              <div class="image-input-wrapper w-350px h-150px"
                style="background-image: url({{ asset('assets/media/svg/files/blank-image-dark.svg') }}); background-size: contain;">
              </div>
              <!--end::Preview existing avatar-->

              <!--begin::Edit-->
              <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px shadow"
                data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                data-bs-placement="top" title="Change avatar">
                <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
                <!--begin::Inputs-->
                <input type="file" name="image" accept=".png, .jpg, .jpeg">
                <input type="hidden" name="avatar_remove">
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
          <!--end::Image input wrapper-->
          <span class="form-text">*Maximum image size 2MB</span>
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="row g-9 mb-7">
          <!--begin::Col-->
          <div class="col fv-row">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2">Event Name</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input class="form-control form-control-solid w-100" placeholder="Event Name" name="title" value="">
            <!--end::Input-->
          </div>
          <!--end::Col-->
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="row g-9 mb-7">
          <!--begin::Col-->
          <div class="col-md-6 fv-row">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2">Price <i class="fa-solid fa-circle-info ms-2" data-bs-toggle="tooltip"
                data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-html="true"
                title="Price can be 0 also"></i></label>
            <!--end::Label-->

            <!--begin::Input-->
            <input class="form-control form-control-solid" placeholder="Ticket Price" name="price" value="">
            <!--end::Input-->
          </div>
          <!--end::Col-->
          <!--begin::Col-->
          <div class="col-md-6 fv-row">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2">Start Date and Time<i class="fa-solid fa-circle-info ms-2"
                data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                data-bs-html="true" title="Select Start Date and Time"></i></label>
            <!--end::Label-->
            <!--begin::Input-->
            <input class="form-control form-control-solid kt_datepicker_with_time" name="start_date"
              placeholder="Pick date & time" />
            <!--end::Input-->
          </div>
          <!--end::Col-->
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="row g-9 mb-7">
          <!--begin::Col-->
          <div class="col-md-6 fv-row">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2">End Date and Time</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input class="form-control form-control-solid kt_datepicker_with_time" name="end_date"
              placeholder="Pick date & time" />
            <!--end::Input-->
          </div>
          <!--end::Col-->
          <!--begin::Col-->
          <div class="col-md-6 fv-row">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2">Total Seats</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input type="number" class="form-control form-control-solid" placeholder="Total Seats for Booking"
              name="seats" value="">
            <!--end::Input-->
          </div>
          <!--end::Col-->
        </div>
        <!--end::Input group-->
        <!--begin::Input group-->
        <div class="fv-row mb-7">
          <!--begin::Label-->
          <label class="fs-6 fw-semibold mb-2">Event Details</label>
          <!--end::Label-->
          <!--begin::Input-->
          <textarea class="form-control" data-kt-autosize="true" name="details"></textarea>
          <!--end::Input-->
        </div>
        <!--end::Input group-->

        <!--end::User form-->
      </form>
    </div>
    <!--end::Card body-->
    <!--begin::Card footer-->
    <div class="card-footer bg-dark p-2">
      <!--end::Action-->
      <div class="d-flex justify-content-end">
        <button type="submit" id="kt_add_event_submit" class="btn btn-primary">
          <span class="indicator-label">
            Add Event
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
