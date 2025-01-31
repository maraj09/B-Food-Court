<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
  <!--begin::Toolbar container-->
  <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
    <!--begin::Page title-->
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
      <!--begin::Title-->
      <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Events</h1>
      <!--end::Title-->
      <!--begin::Breadcrumb-->
      <ul class="breadcrumb breadcrumb-separatorless fw-semibold fs-7 my-0 pt-1">
        <!--begin::Item-->
        <li class="breadcrumb-item text-muted">
          <a href="/" class="text-muted text-hover-primary">Home</a>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item">
          <span class="bullet bg-gray-500 w-5px h-2px"></span>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item text-muted">Events</li>
        <!--end::Item-->
      </ul>
      <!--end::Breadcrumb-->
    </div>
    <!--end::Page title-->
    <!--begin::Actions-->
    <div class="d-flex align-items-center gap-2 gap-lg-3">
      <!--begin::Actions-->
      <div class="d-flex align-items-center gap-2 gap-lg-3">
        <div class="m-0">
          <!--begin::Menu toggle-->
          <a href="#"
            class="btn btn-sm btn-flex btn-secondary fw-bold  border border-danger border-dashed border-active active"
            data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">
            <i class="ki-duotone ki-filter fs-6 text-danger me-1">
              <span class="path1"></span>
              <span class="path2"></span>
            </i>Filter</a>
          <!--end::Menu toggle-->
          <!--begin::Menu 1-->
          <div class="menu menu-sub menu-sub-dropdown w-250px w-md-300px" data-kt-menu="true"
            id="kt_menu_65a12143712c8">
            <!--begin::Header-->
            <div class="px-7 py-5">
              <div class="fs-5 text-danger fw-bold">Filter Options</div>
            </div>
            <!--end::Header-->
            <!--begin::Menu separator-->
            <div class="separator border-gray-200"></div>
            <!--end::Menu separator-->
            <!--begin::Form-->
            <div class="px-7 py-5">
              <!--begin::Flatpickr-->
              <div class="input-group w-100 my-2">
                <input class="form-control rounded rounded-end-0" placeholder="Pick date range"
                  id="kt_ecommerce_sales_flatpickr" />
                <button class="btn btn-icon btn-light" id="kt_ecommerce_sales_flatpickr_clear">
                  <i class="ki-duotone ki-cross fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                </button>
              </div>
              <!--end::Flatpickr-->
            </div>
            <!--end::Form-->
          </div>
          <!--end::Menu 1-->
        </div>
        <!--begin::Primary button-->
        <button id="kt_create_event_toggle" class="btn btn-sm fw-bold btn-primary" title="Add play area"
          data-bs-toggle="tooltip" data-bs-placement="left" data-bs-dismiss="click" data-bs-trigger="hover">
          <span id="kt_engage_demos_label">Add Event</span>
        </button>
        <!--end::Primary button-->
      </div>
      <!--end::Actions-->
    </div>
    <!--end::Actions-->
  </div>
  <!--end::Toolbar container-->
</div>
