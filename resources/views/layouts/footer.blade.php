<!--begin::Footer-->
<div id="kt_app_footer" class="app-footer">
  <!--begin::Footer container-->
  <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
    <!--begin::Copyright-->
    <div class="text-gray-900 order-2 order-md-1">
      @php
        $copyright = App\Models\Setting::first()->copyright ?? '';
      @endphp

      <span class="text-muted fw-semibold me-1">
        {{ $copyright }}&copy
      </span>
      <a href="/" target="_blank" class="text-gray-800 text-hover-primary">Bhopal Food Court</a>
    </div>
    <!--end::Copyright-->
    <!--begin::Menu-->
    {{-- <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
      <li class="menu-item">
        <a href="https://keenthemes.com" target="_blank" class="menu-link px-2">About</a>
      </li>
      <li class="menu-item">
        <a href="https://devs.keenthemes.com" target="_blank" class="menu-link px-2">Support</a>
      </li>
      <li class="menu-item">
        <a href="https://1.envato.market/EA4JP" target="_blank" class="menu-link px-2">Purchase</a>
      </li>
    </ul> --}}
    <!--end::Menu-->
  </div>
  <!--end::Footer container-->
  <!--begin::Add Order-->
  @can('place-new-order-drawer')
    <button id="kt_drawer_shopping_cart_toggle" class="btn btn-success hover-elevate-up app-layout-builder-toggle lh-1 py-4"
      data-bs-custom-class="tooltip-inverse" data-bs-toggle="tooltip" data-bs-placement="left" data-bs-dismiss="click"
      data-bs-trigger="hover" data-bs-original-title="Metronic Builder" data-kt-initialized="1">
      <i class="fa-solid fa-bowl-rice fs-4 me-1 pulse pulse-dark"><span class="path1"></span><span
          class="path2"></span><span class="path3"></span><span class="path4"></span></i> New Order
      <span class="pulse-ring"></span>
    </button>
  @endcan


  <!--end::Add Order-->
  <!--begin::Scrolltop-->
  <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
    <i class="ki-duotone ki-arrow-up">
      <span class="path1"></span>
      <span class="path2"></span>
    </i>
  </div>
  <!--end::Scrolltop-->
