<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
  <!--begin::Toolbar container-->
  <div id="kt_app_toolbar_container" class="app-container container-fluid d-flex flex-stack">
    <!--begin::Page title-->
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
      <!--begin::Title-->
      <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Clients</h1>
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
        <li class="breadcrumb-item text-muted">Clients</li>
        <!--end::Item-->
      </ul>
      <!--end::Breadcrumb-->
    </div>
    <!--end::Page title-->
    <!--begin::Actions-->
    <div class="d-flex align-items-center gap-2 gap-lg-3">
      <!--begin::Actions-->
      <div class="d-flex align-items-center gap-2 gap-lg-3">
        <!--begin::Filter menu-->
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
              <!--begin::Select2-->
              <div class="w-100 w-100 my-2">
                <select class="form-select" data-control="select2" data-hide-search="true"
                  data-placeholder="Billed Under" data-kt-ecommerce-order-filter="status">
                  <option></option>
                  <option value="all">All</option>
                  @foreach ($billingCategories as $billingCategory)
                    <option value="{{ $billingCategory->name }}">{{ $billingCategory->name }}</option>
                  @endforeach
                </select>
              </div>
              <!--end::Select2-->
            </div>
            <!--end::Form-->
          </div>
          <!--end::Menu 1-->
        </div>
        <!--end::Filter menu-->
        <!--begin::Secondary button-->
        <!--end::Secondary button-->
        <!--begin::Primary button-->
        <a href="#" class="btn btn-sm fw-bold btn-primary" data-bs-toggle="modal"
          data-bs-target="#add_client_modal">Add
          Client</a>
        <!--end::Primary button-->
      </div>
      <!--end::Actions-->
    </div>
    <!--end::Actions-->
  </div>
  <!--end::Toolbar container-->
</div>
