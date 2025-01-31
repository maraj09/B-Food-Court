<div id="kt_app_toolbar" class="app-toolbar py-3 py-lg-6">
  <!--begin::Toolbar container-->
  <div id="kt_app_toolbar_container" class="app-container container-xxl d-flex flex-stack">
    <!--begin::Page title-->
    <div class="page-title d-flex flex-column justify-content-center flex-wrap me-3">
      <!--begin::Title-->
      <h1 class="page-heading d-flex text-gray-900 fw-bold fs-3 flex-column justify-content-center my-0">Invoice Details
      </h1>
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
        <li class="breadcrumb-item text-muted">Inoices</li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item">
          <span class="bullet bg-gray-500 w-5px h-2px"></span>
        </li>
        <!--end::Item-->
        <!--begin::Item-->
        <li class="breadcrumb-item text-muted">INV{{ $invoice->custom_id }}</li>
        <!--end::Item-->
      </ul>
      <!--end::Breadcrumb-->
    </div>
    <!--end::Page title-->
    <!--begin::Actions-->
    <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
      <a href="/dashboard/finance/invoices" class="btn btn-icon btn-light btn-active-secondary btn-sm ms-auto me-lg-n7">
        <i class="ki-duotone ki-left fs-2"></i>
      </a>
      <!--end::Button-->
      <!--begin::Button-->
      <a href="/dashboard/finance/invoices/{{ $invoice->id }}/edit" class="btn btn-success btn-sm me-lg-n7">Edit Invoice</a>
      <!--end::Button-->
      <!--begin::Button-->
      <a href="/dashboard/finance/invoices/create" class="btn btn-primary btn-sm">Add New Invoice</a>
      <!--end::Button-->
      <!--end::Primary button-->
    </div>
    <!--end::Actions-->
  </div>
  <!--end::Toolbar container-->
</div>
