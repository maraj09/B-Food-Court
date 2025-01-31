<div id="kt_app_footer" class="app-footer ">
  <!--begin::Footer container-->
  <div class="container d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
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
    <!--end::Menu-->
  </div>
  <!--end::Footer container-->
</div>
<!--begin::Scrolltop-->
<div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
  <i class="ki-duotone ki-arrow-up">
    <span class="path1"></span>
    <span class="path2"></span>
  </i>
</div>
<!--end::Scrolltop-->
