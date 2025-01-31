@php
  use App\Models\Setting;
  $setting = Setting::first();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
  <title>{{ $setting->website_title ?? 'Bhopal Food Court' }}</title>
  <meta charset="utf-8" />
  <meta name="description" content="{{ $setting->meta_desc ?? '' }}" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{ asset($setting->project_favicon_icon ?? 'assets/media/logos/favicon.ico') }}" />
  <!--begin::Fonts(mandatory for all pages)-->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
  <!--end::Fonts-->
  <!--begin::Vendor Stylesheets(used for this page only)-->
  <link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet"
    type="text/css" />
  <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
  <!--end::Vendor Stylesheets-->
  <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
  <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
  <!--end::Global Stylesheets Bundle-->
  @yield('styles')
  <script>
    // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
  </script>
  @vite('resources/js/app.js')
  <script src="https://cdn.onesignal.com/sdks/web/v16/OneSignalSDK.page.js" defer></script>
  <script>
    window.OneSignalDeferred = window.OneSignalDeferred || [];
    OneSignalDeferred.push(function(OneSignal) {
      OneSignal.init({
        appId: "{{ env('ONESIGNAL_APP_ID') }}",
      });
      OneSignal.User.PushSubscription.addEventListener("change", pushSubscriptionChangeListener);
    });
  </script>
</head>



<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
  data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true"
  data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true"
  data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" data-kt-app-toolbar-fixed="true"
  data-kt-app-toolbar-fixed-mobile="true" class="app-default" data-select2-id="select2-data-kt_app_body"
  data-kt-sticky-app-header-minimize="on" data-kt-app-header-minimize="on">

  <!--begin::Theme mode setup on page load-->
  <script>
    var defaultThemeMode = "dark";
    var themeMode;
    if (document.documentElement) {
      if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
        themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
      } else {
        if (localStorage.getItem("data-bs-theme") !== null) {
          themeMode = localStorage.getItem("data-bs-theme");
        } else {
          themeMode = defaultThemeMode;
        }
      }
      if (themeMode === "system") {
        themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
      }
      document.documentElement.setAttribute("data-bs-theme", themeMode);
    }
  </script>
  <!--begin::App-->
  <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <!--begin::Page-->
    <div class="app-page flex-column flex-column-fluid" id="kt_app_page">
      @include('layouts.vendor.navbar')
      <div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
        @include('layouts.vendor.sidebar')
        <!--begin::Main-->
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
          <!--begin::Content wrapper-->
          <div class="d-flex flex-column flex-column-fluid">
            @yield('contents')
          </div>
          <!--end::Content wrapper-->
          @include('layouts.footer')
        </div>
        <!--end:::Main-->
        @yield('modules')
      </div>
    </div>
  </div>


  <!--begin::Javascript-->
  <script>
    var hostUrl = "{{ asset('assets/') . '/' }}";
  </script>
  <!--begin::Global Javascript Bundle(mandatory for all pages)-->
  <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
  <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
  <!--end::Global Javascript Bundle-->
  <!--begin::Vendors Javascript(used for this page only)-->
  <script src="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.js') }}"></script>
  <script src="https://cdn.amcharts.com/lib/5/index.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/xy.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/percent.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/radar.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/themes/Animated.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/map.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/geodata/worldLow.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/geodata/continentsLow.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/geodata/usaLow.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZonesLow.js"></script>
  <script src="https://cdn.amcharts.com/lib/5/geodata/worldTimeZoneAreasLow.js"></script>
  <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>
  <!--end::Vendors Javascript-->
  <!--begin::Custom Javascript(used for this page only)-->
  <script src="{{ asset('assets/js/widgets.bundle.js') }}"></script>
  <script src="{{ asset('assets/js/custom/widgets.js') }}"></script>
  <!--end::Custom Javascript-->
  <!--end::Javascript-->
  <script src="{{ asset('custom/assets/js/index.js') }}"></script>
  <script src="{{ asset('custom/assets/js/vendor.js') }}"></script>
  <script>
    function pushSubscriptionChangeListener(event) {
      if (event.current.token) {
        if (event.current.optedIn) {
          const subsId = event.current.id
          const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
          $.ajax({
            url: '/store-subscription-id',
            type: 'POST',
            headers: {
              'X-CSRF-TOKEN': csrfToken
            },
            contentType: 'application/json',
            data: JSON.stringify({
              subsId
            }),
            success: function(response) {
              console.log('Subscription ID stored successfully.');
            },
            error: function(xhr, status, error) {
              console.error('Failed to store subscription ID:', xhr);
            }
          });
        }
      }
    }
  </script>
  @yield('scripts')
</body>

</html>
