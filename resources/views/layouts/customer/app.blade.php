@php
  use App\Models\Setting;
  $settings = Setting::first();
@endphp
<!DOCTYPE html>
<html lang="en">

<head>
  <title>{{ $settings->website_title ?? 'Bhopal Food Court' }}</title>
  <meta charset="utf-8" />
  <meta name="description" content="{{ $settings->meta_desc ?? '' }}" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{ asset($settings->project_favicon_icon ?? 'assets/media/logos/favicon.ico') }}" />
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
  <link rel="stylesheet" href="{{ asset('custom/assets/css/intlTelInput/intlTelInput.css') }}">
  <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
  <!--end::Global Stylesheets Bundle-->
  @if ($settings->sign_in_method == 'otpless')
    <script id="otpless-sdk" type="text/javascript" data-appid="{{ env('OTP_LESS_APP_ID') }}"
      src="https://otpless.com/v2/auth.js"></script>
  @endif
  @yield('styles')
  <style>
    .iti {
      width: 100%;
      display: block;
    }

    .iti__country-name {
      color: #000;
    }

    .iti__search-input {
      background: white;
      color: #000;

    }

    .flatpickr-weeks .flatpickr-day {
      color: var(--bs-gray-500) !important;
      /* Change to your desired color */
    }
  </style>
  <script>
    // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
  </script>
  @vite('resources/js/app.js')
  @if (auth()->check())
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
  @endif

</head>
@php
  $notifications = \App\Models\Notification::where('user_id', auth()->id())->where('read', false)->get();

  // Mark the retrieved notifications as read
  foreach ($notifications as $notification) {
      $notification->update(['read' => true]);
  }

  $data = $notifications->map(function ($notification) {
      return [
          'message' => $notification->message,
          'action' => $notification->action,
      ];
  });
@endphp

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
  data-kt-app-sidebar-minimize="on" data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="false"
  data-kt-app-sidebar-fixed="false" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true"
  data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
  data-kt-app-toolbar-fixed="true" data-kt-app-toolbar-fixed-mobile="true" class="app-default"
  data-select2-id="select2-data-kt_app_body" data-kt-sticky-app-header-minimize="on" data-kt-app-header-minimize="on">

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
      @include('layouts.customer.navbar')
      <div class="app-wrapper flex-column flex-row-fluid mt-n10" id="kt_app_wrapper">
        {{-- @include('layouts.customer.sidebar') --}}
        <!--begin::Main-->
        <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
          <!--begin::Content wrapper-->
          <div class="d-flex flex-column flex-column-fluid">
            @yield('contents')
          </div>
          <!--end::Content wrapper-->
          @yield('footer')
          @include('layouts.customer.footer')
        </div>
        <!--end:::Main-->
        @yield('modules')
        @include('pages.items.customer.modules.toasts.pointsToast')
        @include('layouts.customer.modules.modals.name-email-submit-modal')
        @include('layouts.customer.modules.modals.birthday-submit-modal')
        @if (auth()->check())
          @include('layouts.customer.modules.drawers.ordersDrawer')
        @endif
      </div>
    </div>
  </div>

  <!--begin::Javascript-->
  {{-- @vite('resources/js/app.js') --}}
  <!--begin::Global Javascript Bundle(mandatory for all pages)-->
  <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
  <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
  <script src="{{ asset('custom/assets/js/intlTelInput/intlTelInput.js') }}"></script>
  <script src="{{ asset('custom/assets/js/index.js') }}"></script>
  <script src="{{ asset('assets/plugins/custom/datatables/datatables.bundle.js') }}"></script>

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
  <script>
    // Array of notifications
    const notifications = @json($data);

    // Function to display SweetAlerts sequentially
    function displayNotifications(index) {
      if (index < notifications.length) {
        setTimeout(function() {
          if (notifications[index].action == 'debited') {
            showPointToast('error', notifications[index].message, 'Points debited');
          } else {
            showPointToast('success', notifications[index].message);
          }
          displayNotifications(index + 1);
        }, 500);
      }
    }

    // Start displaying notifications
    displayNotifications(0);
  </script>
  <!--end::Custom Javascript-->
  <!--end::Javascript-->
  @php
    $isUserLoggedIn = auth()->check();
    $userHasNoEmail = $isUserLoggedIn && !auth()->user()->email;
    $userHasNoDob = $isUserLoggedIn && !auth()->user()->customer->date_of_birth;
    $userHasOrders = $isUserLoggedIn && auth()->user()->orders()->where('status', '!=', 'unpaid')->count() > 0;
    $userHasTwoOrMoreOrders = $isUserLoggedIn && auth()->user()->orders()->where('status', '!=', 'unpaid')->count() > 1;
    $hasEmailErrors = $errors->has('email') || $errors->has('name');
    $hasDobErrors = $errors->has('date_of_birth');
  @endphp
  <script>
    $("#kt_datepicker_dob_custom_today").flatpickr({
      disableMobile: true,
      maxDate: "today",
    });

    const isUserLoggedIn = @json($isUserLoggedIn);
    const userHasNoEmail = @json($userHasNoEmail);
    const userHasOrders = @json($userHasOrders);
    const hasEmailErrors = @json($hasEmailErrors);
    const userHasTwoOrMoreOrders = @json($userHasTwoOrMoreOrders);
    const userHasNoDob = @json($userHasNoDob);
    const hasDobErrors = @json($hasDobErrors);
    let birthdayModalShowed = false;

    if (isUserLoggedIn) {
      if ((userHasNoEmail && userHasOrders) || hasEmailErrors) {
        const shareDetailsModal = new bootstrap.Modal(document.getElementById('shareDetailsModal'), {
          backdrop: 'static',
          keyboard: false
        });
        shareDetailsModal.show();
      }

      if (!birthdayModalShowed) {
        if ((userHasNoDob && userHasTwoOrMoreOrders) || hasDobErrors) {
          let clickCount = 0;

          // Track clicks
          document.addEventListener('click', () => {
            clickCount++;
            if (clickCount >= 4) {
              showModal();
            }
          });

          // Set timer
          const timer = setTimeout(() => {
            showModal();
          }, 20000); // 20 seconds

          function showModal() {
            if (birthdayModalShowed) return;
            const shareBirthdayModal = new bootstrap.Modal(document.getElementById('shareBirthdayModal'), {
              keyboard: false
            });
            shareBirthdayModal.show();
            birthdayModalShowed = true;
            clearTimeout(timer); // Clear the timer to prevent multiple shows
          }
        }
      }
    }
  </script>
  @if (auth()->check())
    @php
      $nonRatedDeliveredOrder = \App\Models\Order::where('status', 'delivered')
          ->where('user_id', auth()->id())
          ->whereHas('orderItems', function ($query) {
              $query->doesntHave('rating')->whereNotNull('item_id');
          })
          ->orderBy('created_at', 'desc')
          ->first();
    @endphp
    @if ($nonRatedDeliveredOrder)
      <script>
        $(document).ready(function() {
          var orderId = `{{ $nonRatedDeliveredOrder->id }}`;
          $.ajax({
            url: '/get-order-data',
            type: 'GET',
            data: {
              id: orderId
            },
            success: function(response) {
              var drawer = KTDrawer.getInstance(document.querySelector("#kt_drawer_order"));
              drawer.show();
              $('#kt_drawer_order').html(response.drawerContent);
            },
            error: function(xhr, status, error) {
              console.error('Error fetching Order data:', xhr);
            }
          });
        });
      </script>
    @endif
    <script>
      document.addEventListener('DOMContentLoaded', () => {
        Echo.private('orders.{{ auth()->id() }}')
          .listen('OrderStatusChangeEvent', (e) => {
            var statusBadge = '';
            switch (e.orderItem.status) {
              case 'accepted':
                statusBadge = '<span class="badge badge-info">Accepted</span>';
                break;
              case 'completed':
                statusBadge = '<span class="badge badge-primary">Completed</span>';
                break;
              case 'delivered':
                statusBadge = '<span class="badge badge-success">Delivered</span>';
                break;
              case 'rejected':
                statusBadge = '<span class="badge badge-danger">Rejected</span>';
                break;
              default:
                statusBadge = `<span class="badge badge-warning">${e.orderItem.status.capitalize()}</span>`;
            }
            $(`.order_item_${e.orderItem.id}_status`).html(statusBadge);
            if (e.order.status == 'delivered') {
              $('#kt_carousel_2_carousel').css('display', 'none');
              var orderId = `${e.order.id}`;
              $.ajax({
                url: '/get-order-data',
                type: 'GET',
                data: {
                  id: orderId
                },
                success: function(response) {
                  var drawer = KTDrawer.getInstance(document.querySelector("#kt_drawer_order"));
                  drawer.show();
                  $('#kt_drawer_order').html(response.drawerContent);
                },
                error: function(xhr, status, error) {
                  console.error('Error fetching Order data:', xhr);
                }
              });
            }
            var statusBadge = '';
            switch (e.order.status) {
              case 'delivered':
                statusBadge = '<span class="badge badge-success">Delivered</span>';
                break;
              case 'rejected':
                statusBadge = '<span class="badge badge-danger">Rejected</span>';
                break;
              default:
                statusBadge = '<span class="badge badge-secondary">Partial Completed</span>';
            }
            $(`.order_${e.order.id}_status`).html(statusBadge);
          });
      });
    </script>
  @endif

  @yield('scripts')

</body>

</html>
