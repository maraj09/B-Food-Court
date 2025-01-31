<!--begin::Sidebar-->

<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
  data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
  data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
  <!--begin::Logo-->
  <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
    <!--begin::Logo image-->
    <a href="/" class="mx-auto">
      <img alt="Logo" src="{{ asset($setting->project_logo ?? 'assets/media/logos/default-dark.svg') }}"
        class="w-125px app-sidebar-logo-default" />
      <img alt="Logo" src="{{ asset($setting->project_logo ?? 'assets/media/logos/default-small.svg') }}"
        class="h-20px app-sidebar-logo-minimize" />
    </a>
    <!--end::Logo image-->
    <!--begin::Sidebar toggle-->
    <div id="kt_app_sidebar_toggle"
      class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary h-30px w-30px position-absolute top-50 start-100 translate-middle rotate"
      data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body"
      data-kt-toggle-name="app-sidebar-minimize">
      <i class="ki-duotone ki-black-left-line fs-3 rotate-180">
        <span class="path1"></span>
        <span class="path2"></span>
      </i>
    </div>
    <!--end::Sidebar toggle-->
  </div>
  <!--end::Logo-->
  <!--begin::sidebar menu-->
  <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
    <!--begin::Menu wrapper-->
    <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper">
      <!--begin::Scroll wrapper-->
      <div id="kt_app_sidebar_menu_scroll" class="scroll-y my-5 ms-2" data-kt-scroll="true"
        data-kt-scroll-activate="true" data-kt-scroll-height="auto"
        data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer"
        data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
        <!--begin::Menu-->
        <div class="menu menu-column menu-rounded menu-sub-indention fw-semibold fs-6" id="#kt_app_sidebar_menu"
          data-kt-menu="true" data-kt-menu-expand="false">
          <!--begin:Menu item-->
          <!--begin:Menu link-->
          @php
            $user = Auth::user();
            $permissions = ['cashier-play-area-management', 'cashier-events-management', 'cashier-items-management'];
            $hasOnlySpecifiedPermissions = $user
                ->roles()
                ->first()
                ->permissions->pluck('name')
                ->diff($permissions)
                ->isEmpty();
          @endphp
          @if ($user->hasAnyPermission($permissions) && $hasOnlySpecifiedPermissions)
            <div class="menu-item ms-4">
              <a class="menu-link {{ Request::is('dashboard/orders/create') ? 'active' : '' }}"
                href="/dashboard/orders/create">
                <span class="menu-icon">
                  <i class="fa-solid fa-money-bill-1-wave fs-2"></i>
                </span>
                <span class="menu-title mx-5">Cashierboard</span>
              </a>
            </div>
          @elseif ($user->hasAnyPermission($permissions) && !auth()->user()->hasPermissionTo('orders-management'))
            <div class="menu-item ms-4">
              <a class="menu-link {{ Request::is('*dashboard') ? 'active' : '' }}" href="/dashboard">
                <span class="menu-icon">
                  <i class="ki-duotone ki-element-11 fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                  </i>
                </span>
                <span class="menu-title mx-5">Dashboard</span>
              </a>
            </div>
            <div class="menu-item ms-4">
              <a class="menu-link {{ Request::is('dashboard/orders/create') ? 'active' : '' }}"
                href="/dashboard/orders/create">
                <span class="menu-icon">
                  <i class="fa-solid fa-money-bill-1-wave fs-2"></i>
                </span>
                <span class="menu-title mx-5">Cashierboard</span>
              </a>
            </div>
          @else
            <div class="menu-item ms-4">
              <a class="menu-link {{ Request::is('*dashboard') ? 'active' : '' }}" href="/dashboard">
                <span class="menu-icon">
                  <i class="ki-duotone ki-element-11 fs-2">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                  </i>
                </span>
                <span class="menu-title mx-5">Dashboard</span>
              </a>
            </div>
          @endif
          <!--end:Menu link-->
          <!--end:Menu item-->
          <!--begin:Menu item-->
          @canany(['orders-management', 'cashier-view-orders'])
            <div class="menu-item">
              <!--begin:Menu link-->
              <a class="menu-link {{ Request::is('*orders*') ? 'active' : '' }}" href="/dashboard/orders">
                <span class="menu-icon ">
                  <i class="fa-solid fa-money-bill-wheat fs-2"></i>
                </span>
                <span class="menu-title mr-auto">Orders</span>
                <span class="badge badge-success">{{ App\Models\Order::where('status', '!=', 'unpaid')->count() }}</span>
              </a>
              <!--end:Menu link-->
            </div>
          @endcanany
          <!--end:Menu item-->
          <!--begin:Menu item-->
          @can('customers-management')
            <div class="menu-item">
              <!--begin:Menu link-->
              <a class="menu-link {{ Request::is('*customers*') ? 'active' : '' }}" href="/dashboard/customers">
                <span class="menu-icon">
                  <i class="fa-solid fa-person-booth fs-2"></i>
                </span>
                <span class="menu-title">Customers</span>
                <span class="badge badge-primary">{{ App\Models\Customer::all()->count() }}</span>
              </a>
              <!--end:Menu link-->
            </div>
          @endcan
          <!--end:Menu item-->
          <!--begin:Menu item-->
          @can('vendors-management')
            <div class="menu-item">
              <!--begin:Menu link-->
              <a class="menu-link {{ Request::is('*vendors*') ? 'active' : '' }}" href="/dashboard/vendors">
                <span class="menu-icon">
                  <i class="fa-solid fa-people-roof fs-2"></i>
                </span>
                <span class="menu-title">Vendors</span>
                <span class="badge badge-info">{{ App\Models\Vendor::all()->count() }}</span>
              </a>
              <!--end:Menu link-->
            </div>
          @endcan
          <!--end:Menu item-->
          @can('items-management')
            <div data-kt-menu-trigger="click"
              class="menu-item {{ (Request::is('*items*') ? 'show' : Request::is('admin/categories*')) ? 'show' : '' }} menu-accordion">
              <span class="menu-link ">
                <span class="menu-icon">
                  <i class="fa-regular fa-folder-open fs-2"></i>
                </span>
                <span class="menu-title active">Food Items</span>
                <span class="menu-arrow me-3"></span>
                <span class="badge badge-warning">{{ App\Models\Item::all()->count() }}</span>
              </span>
              <div class="menu-sub menu-sub-accordion">
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*items*') ? 'active' : '' }} " href="/dashboard/items">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Food Items</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('admin/categories*') ? 'active' : '' }} "
                    href="/dashboard/categories">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Category</span>
                  </a>
                </div>
              </div>
            </div>
          @endcan

          @can('play-areas-management')
            <div class="menu-item">
              <!--begin:Menu link-->
              <a class="menu-link {{ Request::is('*play-areas*') ? 'active' : '' }}" href="/dashboard/play-areas">
                <span class="menu-icon">
                  <i class="fa-solid fa-baseball-bat-ball fs-2"></i>
                </span>
                <span class="menu-title">Play Areas</span>
                <span class="badge badge-secondary">{{ App\Models\PlayArea::all()->count() }}</span>
              </a>
              <!--end:Menu link-->
            </div>
          @endcan

          @can('events-management')
            <div class="menu-item">
              <!--begin:Menu link-->
              <a class="menu-link {{ Request::is('*events*') ? 'active' : '' }}" href="/dashboard/events">
                <span class="menu-icon">
                  <i class="fa-solid fa-calendar-check fs-2"></i>
                </span>
                <span class="menu-title">Events</span>
                <span class="badge badge-primary">{{ App\Models\Event::all()->count() }}</span>
              </a>
              <!--end:Menu link-->
            </div>
          @endcan

          <!--end:Menu item-->
          <!--begin:Menu item-->
          @can('coupons-management')
            <div data-kt-menu-trigger="click"
              class="menu-item {{ (Request::is('*coupons*') ? 'show' : Request::is('*coupon-logs*')) ? 'show' : '' }} menu-accordion">
              <span class="menu-link ">
                <span class="menu-icon">
                  <i class="fa-solid fa-tachograph-digital fs-2"></i>
                </span>
                <span class="menu-title active">Coupons</span>
                <span class="menu-arrow me-3"></span>
                <span class="badge badge-danger">{{ App\Models\Coupon::all()->count() }}</span>
              </span>
              <div class="menu-sub menu-sub-accordion">
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*coupons*') ? 'active' : '' }} " href="/dashboard/coupons">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Coupons</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*coupon-logs*') ? 'active' : '' }} "
                    href="/dashboard/coupon-logs">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Coupon Logs</span>
                  </a>
                </div>
              </div>
            </div>
          @endcan
          <!--end:Menu item-->
          @can('points-management')
            <div data-kt-menu-trigger="click"
              class="menu-item {{ (Request::is('*points*') ? 'show' : Request::is('*point-logs*')) ? 'show' : '' }} menu-accordion">
              <span class="menu-link ">
                <span class="menu-icon">
                  <i class="fa-solid fa-star fs-2"></i>
                </span>
                <span class="menu-title active">Points</span>
                <span class="menu-arrow me-3"></span>
                <span class="badge badge-success">{{ App\Models\Point::first()->value }}</span>
              </span>
              <div class="menu-sub menu-sub-accordion">
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*points*') ? 'active' : '' }} " href="/dashboard/points">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Points</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*point-logs*') ? 'active' : '' }} " href="/dashboard/point-logs">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Point Logs</span>
                  </a>
                </div>
              </div>
            </div>
          @endcan

          @can('notifications-management')
            <div data-kt-menu-trigger="click"
              class="menu-item {{ Request::is('*notifications*') ? 'show' : '' }} menu-accordion">
              <span class="menu-link ">
                <span class="menu-icon">
                  <i class="fa-solid fa-bell fs-2"></i>
                </span>
                <span class="menu-title active">Notifications</span>
                <span class="menu-arrow me-3"></span>
              </span>
              <div class="menu-sub menu-sub-accordion">
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*notifications/email-templates*') ? 'active' : '' }} "
                    href="/dashboard/notifications/email-templates/1">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Email Templates</span>
                  </a>
                </div>
                {{-- <div class="menu-item">
                  <a class="menu-link {{ Request::is('*notifications/push-templates*') ? 'active' : '' }} "
                    href="/dashboard/notifications/push-templates">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Push Templates</span>
                  </a>
                </div> --}}
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*notifications/promotions*') ? 'active' : '' }} "
                    href="/dashboard/notifications/promotions">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Promotions</span>
                  </a>
                </div>
              </div>
            </div>
          @endcan
          <!--end:Menu item-->
          @can('billing-clients-management')
            <div data-kt-menu-trigger="click"
              class="menu-item {{ Request::is('*finance*') ? 'show' : '' }} menu-accordion">
              <span class="menu-link ">
                <span class="menu-icon">
                  <i class="fa-solid fa-file-invoice fs-2"></i>
                </span>
                <span class="menu-title active">Billing & Clients</span>
                <span class="menu-arrow me-3"></span>
              </span>
              <div class="menu-sub menu-sub-accordion">
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*finance/billing-categories*') ? 'active' : '' }} "
                    href="/dashboard/finance/billing-categories">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Billing Categories</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*finance/clients*') ? 'active' : '' }} "
                    href="/dashboard/finance/clients">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Clients</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*finance/invoices*') ? 'active' : '' }} "
                    href="/dashboard/finance/invoices">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Invoice</span>
                  </a>
                </div>
              </div>
            </div>
          @endcan

          @can('payouts-management')
            <div class="menu-item">
              <!--begin:Menu link-->
              <a class="menu-link {{ Request::is('*payouts*') ? 'active' : '' }}" href="/dashboard/payouts">
                <span class="menu-icon">
                  <i class="fa-solid fa-indian-rupee-sign fs-2"></i>
                </span>
                <span class="menu-title">Payouts</span>
              </a>
              <!--end:Menu link-->
            </div>
          @endcan
          @can('expenses-management')
            <div class="menu-item mx-2">
              <!--begin:Menu link-->
              <a class="menu-link {{ Request::is('*expenses*') ? 'active' : '' }}" href="/dashboard/expenses">
                <span class="menu-icon">
                  <i class="fa-solid fa-money-bill-transfer fs-2"></i>
                </span>
                <span class="menu-title">Expenses</span>
              </a>
              <!--end:Menu link-->
            </div>
          @endcan
          @can('reports-management')
            <div class="menu-item">
              <!--begin:Menu link-->
              <a href="/dashboard/reports" class="menu-link {{ Request::is('*reports*') ? 'active' : '' }}"
                href="Reports.html">
                <span class="menu-icon">
                  <i class="fa-solid fa-chart-pie fs-2"></i>
                </span>
                <span class="menu-title">Reports</span>
              </a>
              <!--end:Menu link-->
            </div>
          @endcan
          <!--begin:Menu item-->

          <!--end:Menu item-->
          @can('user-management')
            <div data-kt-menu-trigger="click"
              class="menu-item {{ Request::is('*user-management*') ? 'show' : '' }} menu-accordion">
              <span class="menu-link">
                <span class="menu-icon">
                  <i class="ki-duotone ki-abstract-28 fs-2">
                    <span class="path1"></span><span class="path2"></span>
                  </i>
                </span>
                <span class="menu-title">
                  User Management
                </span>
                <span class="menu-arrow"></span>
              </span>
              <div class="menu-sub menu-sub-accordion">
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*user-management/users') ? 'active' : '' }} "
                    href="/dashboard/user-management/users">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Users</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*user-management/roles*') ? 'active' : '' }} "
                    href="/dashboard/user-management/roles">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Roles</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*user-management/permissions') ? 'active' : '' }} "
                    href="/dashboard/user-management/permissions">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Permissions</span>
                  </a>
                </div>
              </div>
            </div>
          @endcan
          <!--begin:Menu item-->
          @can('app-settings-management')
            <div data-kt-menu-trigger="click"
              class="menu-item {{ Request::is('*settings*') ? 'show' : '' }} menu-accordion">
              <span class="menu-link ">
                <span class="menu-icon">
                  <!--begin::Svg Icon | path: icons/duotune/finance/fin002.svg-->
                  <span class="svg-icon svg-icon-2">
                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                      fill="none">
                      <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                        <rect x="0" y="0" width="24" height="24" />
                        <path
                          d="M7,3 L17,3 C19.209139,3 21,4.790861 21,7 C21,9.209139 19.209139,11 17,11 L7,11 C4.790861,11 3,9.209139 3,7 C3,4.790861 4.790861,3 7,3 Z M7,9 C8.1045695,9 9,8.1045695 9,7 C9,5.8954305 8.1045695,5 7,5 C5.8954305,5 5,5.8954305 5,7 C5,8.1045695 5.8954305,9 7,9 Z"
                          fill="currentColor" />
                        <path
                          d="M7,13 L17,13 C19.209139,13 21,14.790861 21,17 C21,19.209139 19.209139,21 17,21 L7,21 C4.790861,21 3,19.209139 3,17 C3,14.790861 4.790861,13 7,13 Z M17,19 C18.1045695,19 19,18.1045695 19,17 C19,15.8954305 18.1045695,15 17,15 C15.8954305,15 15,15.8954305 15,17 C15,18.1045695 15.8954305,19 17,19 Z"
                          fill="currentColor" opacity="0.3" />
                      </g>
                    </svg>
                  </span>
                  <!--end::Svg Icon-->
                </span>
                <span class="menu-title">Settings</span>
                <span class="menu-arrow"></span>
              </span>
              <div class="menu-sub menu-sub-accordion">
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*settings/general') ? 'active' : '' }} "
                    href="/dashboard/settings/general">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">General</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*settings/notification') ? 'active' : '' }} "
                    href="/dashboard/settings/notification">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Notfication</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*settings/footer') ? 'active' : '' }} "
                    href="/dashboard/settings/footer">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Footer</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*settings/payment') ? 'active' : '' }} "
                    href="/dashboard/settings/payment">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Payment Settings</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*settings/page') ? 'active' : '' }} "
                    href="/dashboard/settings/page">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Page Settings</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*settings/modules') ? 'active' : '' }} "
                    href="/dashboard/settings/modules">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Modules Settings</span>
                  </a>
                </div>
                <div class="menu-item">
                  <a class="menu-link {{ Request::is('*settings/authentication') ? 'active' : '' }} "
                    href="/dashboard/settings/authentication">
                    <span class="menu-bullet">
                      <span class="bullet bullet-dot"></span>
                    </span>
                    <span class="menu-title">Authentication Settings</span>
                  </a>
                </div>
              </div>
            </div>
          @endcan
          <!--end:Menu item-->
        </div>
        <!--end::Menu-->
      </div>
      <!--end::Scroll wrapper-->
    </div>
    <!--end::Menu wrapper-->
  </div>
  <!--end::sidebar menu-->
  <!--begin::Footer-->
  <div class="app-sidebar-footer flex-column-auto pt-2 pb-6 px-6" id="kt_app_sidebar_footer">
    <form method="POST" action="{{ route('logout') }}">
      @csrf
      <a href="{{ route('logout') }}" onclick="event.preventDefault();
        this.closest('form').submit();"
        class="btn btn-flex flex-center btn-custom btn-primary overflow-hidden text-nowrap px-0 h-40px w-100"
        data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-dismiss-="click"
        data-bs-original-title="200+ in-house components and 3rd-party plugins" data-kt-initialized="1">
        <span class="btn-label">Logout</span>
        <i class="ki-duotone ki-document btn-icon fs-2 m-0">
          <span class="path1"></span>
          <span class="path2"></span>
        </i>
      </a>
    </form>
  </div>
  <!--end::Footer-->
</div>
<!--end::Sidebar-->
