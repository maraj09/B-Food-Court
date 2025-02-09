<div id="kt_app_header" class="app-header" data-kt-sticky="true" data-kt-sticky-activate="{default: true, lg: true}"
  data-kt-sticky-name="app-header-minimize" data-kt-sticky-offset="{default: '200px', lg: '0'}"
  data-kt-sticky-animation="false">
  <!--begin::Header container-->
  <div class="app-container container-fluid d-flex align-items-stretch justify-content-between"
    id="kt_app_header_container">
    <!--begin::Sidebar mobile toggle-->
    <div class="d-flex align-items-center d-lg-none ms-n3 me-1 me-md-2" title="Show sidebar menu">
      <div class="btn btn-icon btn-active-color-primary w-35px h-35px" id="kt_app_sidebar_mobile_toggle">
        <i class="ki-duotone ki-abstract-14 fs-2 fs-md-1">
          <span class="path1"></span>
          <span class="path2"></span>
        </i>
      </div>
    </div>
    <!--end::Sidebar mobile toggle-->
    <!--begin::Mobile logo-->
    <div class="d-flex align-items-center flex-grow-1 flex-lg-grow-0">
      <a href="/" class="d-lg-none">
        <img alt="Logo" src="{{ asset($setting->project_logo ?? 'assets/media/logos/default-small.svg') }}"
          class="h-30px">
      </a>
    </div>
    <!--end::Mobile logo-->
    <!--begin::Header wrapper-->
    @php
      use App\Models\OrderItem;
      use App\Models\Item;
      use App\Models\Coupon;
      $vendorId = auth()->user()->vendor->id;

      $totalItems = Item::where('vendor_id', $vendorId)->count();
      $totalOrders = OrderItem::whereHas('item', function ($query) use ($vendorId) {
          $query->where('vendor_id', $vendorId);
      })
          ->where('status', '!=', 'unpaid')
          ->count();
      $totalCoupons = Coupon::where('vendor_id', $vendorId)->where('approved', 1)->where('status', 1)->count();
    @endphp
    <div class="d-flex align-items-stretch justify-content-between flex-lg-grow-1" id="kt_app_header_wrapper">
      <!--begin::Menu wrapper-->
      <div class="app-header-menu app-header-mobile-drawer align-items-stretch" data-kt-drawer="true"
        data-kt-drawer-name="app-header-menu" data-kt-drawer-activate="{default: true, lg: false}"
        data-kt-drawer-overlay="true" data-kt-drawer-width="250px" data-kt-drawer-direction="end"
        data-kt-drawer-toggle="#kt_app_header_menu_toggle" data-kt-swapper="true"
        data-kt-swapper-mode="{default: 'append', lg: 'prepend'}"
        data-kt-swapper-parent="{default: '#kt_app_body', lg: '#kt_app_header_wrapper'}">
        <!--begin::Menu-->
        <div class="menu menu-rounded menu-column menu-lg-row my-5 my-lg-0 align-items-stretch fw-semibold px-2 px-lg-0"
          id="kt_app_header_menu" data-kt-menu="true">
          <!--begin:Menu item-->
          <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
            class="menu-item menu-lg-down-accordion me-0 me-lg-2">
            <!--begin:Menu link-->
            <a href="/vendor/dashboard" class="menu-link {{ Request::is('*dashboard*') ? 'active' : '' }}">
              <span class="menu-icon mx-6 py-0">
                <i class="ki-duotone ki-element-11 fs-2 me-1">
                  <span class="path1"></span>
                  <span class="path2"></span>
                  <span class="path3"></span>
                  <span class="path4"></span>
                </i>
                Dashboard
              </span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->
          <!--begin:Menu item-->
          <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
            class="menu-item menu-lg-down-accordion me-0 me-lg-2">
            <!--begin:Menu link-->
            <a href="/vendor/orders" class="menu-link px-5 {{ Request::is('*orders*') ? 'active' : '' }}">
              <span class="menu-icon mx-6 py-0">
                <i class="fa-solid fa-money-bill-wheat fs-2 me-1"></i>Orders
              </span>
              <span class="badge badge-success mx-2">{{ $totalOrders }}</span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->
          <!--begin:Menu item-->
          <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
            class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
            <!--begin:Menu link-->
            <a href="/vendor/items" class="menu-link px-5 {{ Request::is('*items*') ? 'active' : '' }}">
              <span class="menu-icon mx-6 py-0">
                <i class="fa-regular fa-folder-open fs-2 me-1"></i>Items
              </span>
              <span class="badge badge-warning mx-2">{{ $totalItems }}</span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->
          <!--begin:Menu item-->
          <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
            class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
            <!--begin:Menu link-->
            <a href="/vendor/coupons" class="menu-link px-5 {{ Request::is('*coupons*') ? 'active' : '' }}">
              <span class="menu-icon mx-6 py-0">
                <i class="fa-solid fa-tachograph-digital fs-2 me-1"></i>Coupons
              </span>
              <span class="badge badge-danger mx-5">{{ $totalCoupons }}</span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->
          <!--begin:Menu item-->
          <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
            class="menu-item menu-lg-down-accordion me-0 me-lg-2">
            <!--begin:Menu link-->
            <a href="/vendor/reports" class="menu-link px-5 {{ Request::is('*reports*') ? 'active' : '' }}">
              <span class="menu-icon mx-6 py-0">
                <i class="fa-solid fa-chart-pie fs-2 me-1"></i>Reports
              </span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->
          <!--begin:Menu item-->
          <div data-kt-menu-trigger="{default: 'click', lg: 'hover'}" data-kt-menu-placement="bottom-start"
            class="menu-item menu-lg-down-accordion menu-sub-lg-down-indention me-0 me-lg-2">
            <!--begin:Menu link-->
            <a href="/vendor/payouts" class="menu-link px-5 {{ Request::is('*payouts*') ? 'active' : '' }}">
              <span class="menu-icon mx-6 py-0">
                <i class="fa-solid fa-indian-rupee-sign fs-2 me-1"></i>Payouts
              </span>
            </a>
            <!--end:Menu link-->
          </div>
          <!--end:Menu item-->
        </div>
        <!--end::Menu-->
      </div>
      <!--end::Menu wrapper-->
      <!--begin::Navbar-->
      <div class="app-navbar flex-shrink-0">
        <!--begin::Search-->
        <div class="app-navbar-item align-items-stretch ms-1 ms-md-4">
          <!--begin::Search-->

          <!--end::Search-->
        </div>
        <!--end::Search-->
        <!--begin::Notifications-->

        <!--end::Notifications-->
        <!--begin::Theme mode-->
        <div class="app-navbar-item ms-1 ms-md-4">
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
              <a href="#" class="menu-link px-3 py-2 active" data-kt-element="mode" data-kt-value="dark">
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
          <div class="cursor-pointer symbol symbol-35px" data-kt-menu-trigger="{default: 'click', lg: 'hover'}"
            data-kt-menu-attach="parent" data-kt-menu-placement="bottom-end">
            @if (auth()->user()->vendor->avatar)
              <img src="{{ asset(auth()->user()->vendor->avatar) }}" class="rounded-3" alt="user">
            @else
              <img src="{{ asset('assets/media/avatars/blank.png') }}" class="rounded-3" alt="user">
            @endif
          </div>
          <!--begin::User account menu-->
          <div
            class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg menu-state-color fw-semibold py-4 fs-6 w-275px"
            data-kt-menu="true">
            <!--begin::Menu item-->
            <div class="menu-item px-3">
              <div class="menu-content d-flex align-items-center px-3">
                <!--begin::Avatar-->
                <div class="symbol symbol-50px me-5">
                  @if (auth()->user()->vendor->avatar)
                    <img alt="Logo" src="{{ asset(auth()->user()->vendor->avatar) }}">
                  @else
                    <img alt="Logo" src="{{ asset('assets/media/avatars/blank.png') }}">
                  @endif
                </div>
                <!--end::Avatar-->
                <!--begin::Username-->
                <div class="d-flex flex-column">
                  <div class="fw-bold d-flex align-items-center fs-5">{{ auth()->user()->name }}
                  </div>
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
            <div class="menu-item px-5 my-1">
              <a href="/vendor/settings" class="menu-link px-5">Account Settings</a>
            </div>
            <!--end::Menu item-->
            <!--begin::Menu item-->
            <div class="menu-item px-5">
              <form method="POST" action="{{ route('logout') }}">
                @csrf
                <a href="{{ route('logout') }}"
                  onclick="event.preventDefault();
              this.closest('form').submit();"
                  class="menu-link px-5">Sign Out</a>
              </form>
            </div>
            <!--end::Menu item-->
          </div>
          <!--end::User account menu-->
          <!--end::Menu wrapper-->
        </div>
        <!--end::User menu-->
        <!--begin::Header menu toggle-->
        <div class="app-navbar-item d-lg-none ms-2 me-n2" title="Show header menu">
          <div class="btn btn-flex btn-icon btn-active-color-primary w-30px h-30px" id="kt_app_header_menu_toggle">
            <i class="ki-duotone ki-element-4 fs-1">
              <span class="path1"></span>
              <span class="path2"></span>
            </i>
          </div>
        </div>
        <!--end::Header menu toggle-->
        <!--begin::Aside toggle-->
        <!--end::Header menu toggle-->
      </div>
      <!--end::Navbar-->
    </div>
    <!--end::Header wrapper-->
  </div>
  <div class="progress-container bg-dark mt-n2 mt-md-n1">
    <div class="progress-bar" id="myBar"></div>
  </div>
  <!--end::Header container-->
</div>
