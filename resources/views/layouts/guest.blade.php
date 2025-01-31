@php
  use App\Models\Setting;
  $settings = Setting::first();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
  <title>Bhopal Food Court</title>
  <meta charset="utf-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{ asset('assets/media/logos/favicon.ico') }}" />
  <!--begin::Fonts(mandatory for all pages)-->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
  <link rel="stylesheet" href="{{ asset('custom/assets/css/intlTelInput/intlTelInput.css') }}">
  <!--end::Fonts-->

  <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
  <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
  <!--end::Global Stylesheets Bundle-->

  <!--begin::Google tag-->
  <script async src="https://www.googletagmanager.com/gtag/js?id=UA-37564768-1"></script>
  <script>
    window.dataLayer = window.dataLayer || [];

    function gtag() {
      dataLayer.push(arguments);
    }
    gtag('js', new Date());
    gtag('config', 'UA-37564768-1');
  </script>
  <!--end::Google tag-->
  <script>
    // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking)
    if (window.top != window.self) {
      window.top.location.replace(window.self.location.href);
    }
  </script>
  @if ($settings->sign_in_method == 'otpless')
    <script id="otpless-sdk" type="text/javascript" data-appid="{{ env('OTP_LESS_APP_ID') }}"
      src="https://otpless.com/v2/auth.js"></script>
  @endif
</head>

<body id="kt_body" class="app-blank bgi-size-cover bgi-attachment-fixed bgi-position-center">
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
  <!--end::Theme mode setup on page load-->
  <!--Begin::Google Tag Manager (noscript) -->
  <noscript><iframe src="https://www.googletagmanager.com/ns.html?id=GTM-5FS8GGP" height="0" width="0"
      style="display:none;visibility:hidden"></iframe></noscript>
  <!--End::Google Tag Manager (noscript) -->

  <!--begin::Root-->
  <div class="d-flex flex-column flex-root" id="kt_app_root">
    <!--begin::Page bg image-->
    <style>
      body {
        background-image: url("{{ asset('assets/media/auth/bg10.jpeg') }}");

      }

      [data-bs-theme="dark"] body {
        background-image: url("{{ asset('assets/media/auth/bg10-dark.jpeg') }}");
      }
    </style>
    <!--end::Page bg image-->
    <div class="d-flex flex-column flex-lg-row flex-column-fluid">
      @yield('contents')
    </div>
  </div>

  <!--end::Root-->
  <!--begin::Javascript-->
  <script>
    var hostUrl = "{{ asset('assets/') . '/' }}";
  </script>

  <!--begin::Global Javascript Bundle(mandatory for all pages)-->
  <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
  <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
  <script src="{{ asset('custom/assets/js/intlTelInput/intlTelInput.js') }}"></script>
  <script src="https://www.gstatic.com/firebasejs/6.0.2/firebase.js"></script>

  <!--end::Global Javascript Bundle-->
  <!--begin::Custom Javascript(used for single page only)-->
  <script>
    const firebaseConfig = {
      apiKey: "{{ env('FIREBASE_apiKey') }}",
      authDomain: "{{ env('FIREBASE_authDomain') }}",
      projectId: "{{ env('FIREBASE_projectId') }}",
      storageBucket: "{{ env('FIREBASE_storageBucket') }}",
      messagingSenderId: "{{ env('FIREBASE_messagingSenderId') }}",
      appId: "{{ env('FIREBASE_appId') }}",
      measurementId: "{{ env('FIREBASE_measurementId') }}"
    };
    firebase.initializeApp(firebaseConfig);
  </script>
  @yield('scripts')
  <!--end::Custom Javascript-->
  <!--end::Javascript-->

</body>
