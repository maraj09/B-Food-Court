<div class="landing-header" data-kt-sticky="true" data-kt-sticky-name="landing-header"
  data-kt-sticky-offset="{default: '200px', lg: '300px'}">
  <!--begin::Container-->
  <div class="container">
    <!--begin::Wrapper-->
    <div class="d-flex align-items-center justify-content-between">
      <!--begin::Logo-->
      <div class="d-flex align-items-center flex-equal">
        {{-- @if (!Request::is('/') && !Request::is('/play-areas'))
          <div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
            <a href="{{ url()->previous() }}" class="btn btn-icon btn-active-color-primary w-35px h-35px"
              id="kt_app_sidebar_mobile_toggle">
              <i class="ki-duotone ki-black-left fs-4x">
              </i>
            </a>
          </div>
        @endif --}}
        <!--begin::Mobile menu toggle-->
        <button class="btn btn-icon btn-active-color-primary me-3 d-flex d-lg-none" id="kt_landing_menu_toggle">
          <i class="ki-duotone ki-abstract-14 fs-2hx">
            <span class="path1"></span>
            <span class="path2"></span>
          </i>
        </button>
        <!--end::Mobile menu toggle-->
        <!--begin::Logo image-->
        <a href="/">
          <img alt="Logo" src="{{ asset($settings->project_logo ?? 'assets/media/logos/default-dark.svg') }}"
            class="logo-default h-40px h-lg-50px mw-125px mw-lg-200px" />
          <img alt="Logo" src="{{ asset($settings->project_logo ?? 'assets/media/logos/default-dark.svg') }}"
            class="logo-sticky h-40px h-lg-50px mw-125px mw-lg-200px" />
        </a>
        <!--end::Logo image-->
      </div>
      <!--end::Logo-->
      <!--begin::Menu wrapper-->
      <div class="d-lg-block" id="kt_header_nav_wrapper">
        <div class="d-lg-block p-5 p-lg-0" data-kt-drawer="true" data-kt-drawer-name="landing-menu"
          data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
          data-kt-drawer-width="200px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_landing_menu_toggle"
          data-kt-swapper="true" data-kt-swapper-mode="prepend"
          data-kt-swapper-parent="{default: '#kt_body', lg: '#kt_header_nav_wrapper'}">
          <!--begin::Menu-->
          <div
            class="menu menu-column flex-nowrap menu-rounded menu-lg-row menu-title-gray-600 menu-state-title-primary nav nav-flush fs-5 fw-semibold"
            id="kt_landing_menu">
            <!--begin::Menu item-->
            <div class="menu-item">
              <!--begin::Menu link-->
              <a class="menu-link nav-link bg-transparent py-3 px-4 px-xxl-6 {{ Request::is('/') ? 'active' : '' }}"
                href="/" data-kt-scroll-toggle="true" data-kt-drawer-dismiss="true">Home</a>
              <!--end::Menu link-->
            </div>
            <!--end::Menu item-->
            <!--begin::Menu item-->
            @if ($settings->items_status)
              <div class="menu-item">
                <!--begin::Menu link-->
                <a class="menu-link nav-link bg-transparent py-3 px-4 px-xxl-6 {{ Request::is('food-items') ? 'active' : '' }}"
                  href="/food-items" data-kt-scroll-toggle="true" data-kt-drawer-dismiss="true">Food Items</a>
                <!--end::Menu link-->
              </div>
            @endif
            <!--end::Menu item-->
            <!--begin::Menu item-->
            @if ($settings->play_area_status)
              <div class="menu-item">
                <!--begin::Menu link-->
                <a class="menu-link nav-link bg-transparent py-3 px-4 px-xxl-6 {{ Request::is('play-areas') ? 'active' : '' }}"
                  href="/play-areas" data-kt-scroll-toggle="true" data-kt-drawer-dismiss="true">Play Area</a>
                <!--end::Menu link-->
              </div>
            @endif
            <!--end::Menu item-->
            <!--begin::Menu item-->
            @if ($settings->event_status)
              <div class="menu-item">
                <!--begin::Menu link-->
                <a class="menu-link nav-link py-3 px-4 px-xxl-6 bg-transparent {{ Request::is('events') ? 'active' : '' }}"
                  href="/events" data-kt-scroll-toggle="true" data-kt-drawer-dismiss="true">Events</a>
                <!--end::Menu link-->
              </div>
            @endif
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <div class="menu-item">
              <!--begin::Menu link-->
              <a class="menu-link nav-link bg-transparent py-3 px-4 px-xxl-6 {{ Request::is('contact-us') ? 'active' : '' }}"
                href="/contact-us" data-kt-scroll-toggle="true" data-kt-drawer-dismiss="true">Contact Us</a>
              <!--end::Menu link-->
            </div>
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <div class="menu-item">
              <!--begin::Menu link-->
              <a class="menu-link nav-link bg-transparent py-3 px-4 px-xxl-6" href="#pricing"
                data-kt-scroll-toggle="true" data-kt-drawer-dismiss="true">Blogs</a>
              <!--end::Menu link-->
            </div>
            <!--end::Menu item-->
          </div>
          <!--end::Menu-->
        </div>
      </div>
      <!--end::Menu wrapper-->
      <!--begin::Toolbar-->

      <div class="flex-equal text-end d-flex justify-content-end">
        <!--begin::Theme mode-->
        <div class="app-navbar-item ms-1 ms-md-4 mt-auto">
          <!--begin::Menu toggle-->
          <a href="#"
            class="btn btn-icon btn-custom btn-icon-muted btn-active-light btn-active-color-primary w-35px h-35px"
            data-kt-menu-trigger="{default:'click', lg: 'hover'}" data-kt-menu-attach="parent"
            data-kt-menu-placement="bottom-end">
            <i class="ki-duotone ki-night-day theme-light-show fs-1">
              <span class="path1"></span>
              <span class="path2"></span>
              <span class="path3"></span>
              <span class="path4"></span>
              <span class="path5"></span>
              <span class="path6"></span>
              <span class="path7"></span>
              <span class="path8"></span>
              <span class="path9"></span>
              <span class="path10"></span>
            </i>
            <i class="ki-duotone ki-moon theme-dark-show fs-1">
              <span class="path1"></span>
              <span class="path2"></span>
            </i>
          </a>
          <!--begin::Menu toggle-->
          <!--begin::Menu-->
          <div
            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-title-gray-700 menu-icon-gray-500 menu-active-bg menu-state-color fw-semibold py-4 fs-base w-150px"
            data-kt-menu="true" data-kt-element="theme-mode-menu">
            <!--begin::Menu item-->
            <div class="menu-item px-3 my-0">
              <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="light">
                <span class="menu-icon" data-kt-element="icon">
                  <i class="ki-duotone ki-night-day fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                    <span class="path5"></span>
                    <span class="path6"></span>
                    <span class="path7"></span>
                    <span class="path8"></span>
                    <span class="path9"></span>
                    <span class="path10"></span>
                  </i>
                </span>
                <span class="menu-title">Light</span>
              </a>
            </div>
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <div class="menu-item px-3 my-0">
              <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="dark">
                <span class="menu-icon" data-kt-element="icon">
                  <i class="ki-duotone ki-moon fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                </span>
                <span class="menu-title">Dark</span>
              </a>
            </div>
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <div class="menu-item px-3 my-0">
              <a href="#" class="menu-link px-3 py-2" data-kt-element="mode" data-kt-value="system">
                <span class="menu-icon" data-kt-element="icon">
                  <i class="ki-duotone ki-screen fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                  </i>
                </span>
                <span class="menu-title">System</span>
              </a>
            </div>
            <!--end::Menu item-->
          </div>
          <!--end::Menu-->
        </div>
        <!--end::Theme mode-->
        <!--begin::User menu-->
        <div class="app-navbar-item ms-1 ms-md-4" id="kt_header_user_menu_toggle">
          <!--begin::Menu wrapper-->
          @if (auth()->check())
            <div class="cursor-pointer symbol symbol-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
              data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
              <img src="{{ asset(auth()->user()->customer->avatar ?? 'assets/media/avatars/blank.png') }}"
                class="rounded-3" alt="user" />
            </div>
          @else
            <a href="/login">
              <button class="btn btn-danger btn-sm">Sign In</button>
            </a>
          @endif

          <!--begin::User account menu-->
          @if (auth()->check())
            <div
              class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
              data-kt-menu="true">
              <!--begin::Menu item-->
              <div class="menu-item px-3">
                <div class="menu-content d-flex align-items-center px-3">
                  <!--begin::Avatar-->
                  <div class="symbol symbol-50px me-5">
                    <img alt="Logo"
                      src="{{ asset(auth()->user()->customer->avatar ?? 'assets/media/avatars/blank.png') }}" />
                  </div>
                  <!--end::Avatar-->
                  <!--begin::Username-->
                  <div class="d-flex flex-column">
                    <div class="fw-bold d-flex align-items-center fs-5">{{ auth()->user()->name }}
                      <span
                        class="badge badge-warning fw-bold fs-8 px-2 py-1 ms-2">{{ auth()->user()->point->points }}
                        Points</span>
                    </div>
                    <a href="#"
                      class="fw-semibold text-muted text-hover-primary fs-7">{{ auth()->user()->email }}</a>
                    <a href="#"
                      class="fw-semibold text-muted text-hover-primary fs-7">{{ auth()->user()->phone }}</a>
                  </div>
                  <!--end::Username-->
                </div>
              </div>
              <!--end::Menu item-->
              <!--begin::Menu separator-->
              <div class="separator my-2"></div>
              <!--end::Menu separator-->
              <!--begin::Menu item-->
              <div class="menu-item px-5">
                <a href="/overview?show=orders" class="menu-link px-5">Orders</a>
              </div>
              <!--end::Menu item-->
              <!--begin::Menu item-->
              <div class="menu-item px-5">
                <a href="/overview?show=bookings" class="menu-link px-5">
                  <span class="menu-text">Bookings</span>
                  <span class="menu-badge">
                    <span class="badge badge-light-danger badge-circle fw-bold fs-7">3</span>
                  </span>
                </a>
              </div>
              <!--end::Menu item-->
              <!--begin::Menu item-->
              <div class="menu-item px-5 my-1">
                <a href="/overview?show=profile" class="menu-link px-5">Account Settings</a>
              </div>
              <!--end::Menu item-->
              <!--begin::Menu item-->
              <div class="menu-item px-5">
                <form id="logout-form" method="POST" action="{{ route('logout') }}">
                  @csrf
                  <a href="{{ route('logout') }}"
                    onclick="event.preventDefault();
                      document.getElementById('logout-form').submit();
                      OneSignalDeferred.push(function() {
                          OneSignal.User.PushSubscription.optOut();
                      });"
                    class="menu-link px-5">Sign Out</a>
                </form>
              </div>
              <!--end::Menu item-->
            </div>
          @endif
          <!--end::Menu wrapper-->
        </div>
        <!--end::User menu-->
      </div>
      <!--end::Toolbar-->
    </div>
    <!--end::Wrapper-->
  </div>
  <!--end::Container-->
</div>
