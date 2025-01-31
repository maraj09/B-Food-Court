<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar"
  data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px"
  data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
  <!--begin::Logo-->
  <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
    <!--begin::Logo image-->
    <a href="/">
      <img alt="Logo" src="{{ asset($setting->project_logo ?? 'assets/media/logos/default-dark.svg') }}"
        class="h-25px app-sidebar-logo-default">
      <img alt="Logo" src="{{ asset($setting->project_logo ?? 'assets/media/logos/default-small.svg') }}"
        class="h-20px app-sidebar-logo-minimize">
    </a>
    <!--end::Logo image-->
    <!--begin::Sidebar toggle-->
    <!--begin::Minimized sidebar setup:
  if (isset($_COOKIE["sidebar_minimize_state"]) && $_COOKIE["sidebar_minimize_state"] === "on") {
      1. "src/js/layout/sidebar.js" adds "sidebar_minimize_state" cookie value to save the sidebar minimize state.
      2. Set data-kt-app-sidebar-minimize="on" attribute for body tag.
      3. Set data-kt-toggle-state="active" attribute to the toggle element with "kt_app_sidebar_toggle" id.
      4. Add "active" class to to sidebar toggle element with "kt_app_sidebar_toggle" id.
  }
-->
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
        data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true"
        style="height: 490px;">
        <!--begin::Card-->
        <div class="card mb-5 mb-xl-8">
          <!--begin::Card body-->
          <div class="card-body px-2">
            <!--begin::Summary-->
            <!--begin::User Info-->
            <div class="d-flex flex-center flex-column py-5">
              <!--begin::Avatar-->
              <div class="symbol symbol-100px symbol-circle mb-7">
                @if (auth()->user()->vendor->avatar)
                  <img src="{{ asset(auth()->user()->vendor->avatar) }}" alt="image">
                @else
                  <img src="{{ asset('assets/media/avatars/blank.png') }}" alt="image">
                @endif
              </div>
              <!--end::Avatar-->
              <!--begin::Name-->
              <a href="#"
                class="fs-3 text-gray-800 text-hover-primary fw-bold mb-3">{{ auth()->user()->vendor->brand_name }}</a>
              <!--end::Name-->
              <!--begin::Position-->
              <div class="mb-9">
                <!--begin::Badge-->
                <div class="badge badge-outline badge badge-warning d-inline">Stall No.
                  {{ auth()->user()->vendor->stall_no }}</div>
                <!--begin::Badge-->
              </div>
              <!--end::Position-->
              <!--begin::Info-->
              @php
                use App\Models\OrderItem;
                use App\Models\VendorBank;
                $vendorId = auth()->user()->vendor->id;

                $orderItems = OrderItem::whereHas('item', function ($query) use ($vendorId) {
                    $query->where('vendor_id', $vendorId);
                })->get();

                $balance = VendorBank::where('vendor_id', $vendorId)->first()->balance;
                $totalEarning = VendorBank::where('vendor_id', $vendorId)->first()->total_earning;
                // Calculate total sales for the vendor
                $totalSales = $orderItems->sum(function ($item) {
                    return $item->quantity * $item->price;
                });
                $totalOrders = OrderItem::whereHas('item', function ($query) use ($vendorId) {
                    $query->where('vendor_id', $vendorId);
                })->count();
                $totalReviews = 0;
                foreach (auth()->user()->vendor->items as $item) {
                    $totalReviews += $item->ratings->count();
                }
              @endphp
              <!--begin::Info heading-->
              <div class="fw-bold mb-3">Stats
                <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="popover" data-bs-trigger="hover"
                  data-bs-html="true"
                  data-bs-content="Number of support tickets assigned, closed and pending this week."
                  data-kt-initialized="1"></i>
              </div>
              <!--end::Info heading-->
              <div class="d-flex flex-wrap flex-center text-center">
                <!--begin::Stats-->
                <div class="border border-primary border-dashed rounded py-3 px-2 mb-3">
                  <div class="fs-4 fw-bold text-gray-900">
                    <span class="w-50px">{{ $totalOrders }}</span>
                  </div>
                  <div class="fw-semibold text-primary">Orders</div>
                </div>
                <!--end::Stats-->
                <!--begin::Stats-->
                <div class="border border-success border-dashed rounded py-3 px-3 mx-2 mb-3">
                  <div class="fs-4 fw-bold text-gray-900">

                    <span class="w-50px">₹{{ $totalEarning }}</span>
                  </div>
                  <div class="fw-semibold text-success">Earning</div>
                </div>
                <!--end::Stats-->
                <!--begin::Stats-->
                <div class="border border-warning border-dashed rounded py-3 px-2 mb-3">
                  <div class="fs-4 fw-bold text-gray-900">
                    <span class="w-50px">{{ $totalReviews }}</span>
                  </div>
                  <div class="fw-semibold text-warning">Reviews</div>
                </div>
                <!--end::Stats-->
              </div>
              <!--end::Info-->
            </div>
            <!--end::User Info-->
            <!--end::Summary-->
            <!--begin::Details toggle-->
            <div class="d-flex flex-stack fs-4 py-3">
              <div class="fw-bold rotate collapsible" data-bs-toggle="collapse" href="#kt_user_view_details"
                role="button" aria-expanded="false" aria-controls="kt_user_view_details">Details
                <span class="ms-2 rotate-180">
                  <!--begin::Svg Icon | path: icons/duotune/arrows/arr072.svg-->
                  <span class="svg-icon svg-icon-3">
                    <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                      xmlns="http://www.w3.org/2000/svg">
                      <path
                        d="M11.4343 12.7344L7.25 8.55005C6.83579 8.13583 6.16421 8.13584 5.75 8.55005C5.33579 8.96426 5.33579 9.63583 5.75 10.05L11.2929 15.5929C11.6834 15.9835 12.3166 15.9835 12.7071 15.5929L18.25 10.05C18.6642 9.63584 18.6642 8.96426 18.25 8.55005C17.8358 8.13584 17.1642 8.13584 16.75 8.55005L12.5657 12.7344C12.2533 13.0468 11.7467 13.0468 11.4343 12.7344Z"
                        fill="currentColor"></path>
                    </svg>
                  </span>
                  <!--end::Svg Icon-->
                </span>
              </div>
              <span data-bs-toggle="tooltip" data-bs-trigger="hover" data-bs-original-title="Edit customer details"
                data-kt-initialized="1">
                <a href="/vendor/settings" class="btn btn-sm btn-light-primary">Edit</a>
              </span>
            </div>
            <!--end::Details toggle-->
            <div class="separator"></div>
            <!--begin::Details content-->
            <div id="kt_user_view_details" class="collapse show">
              <div class="pb-5 fs-6">
                <!--begin::Details item-->
                <div class="fw-bold mt-5">Bran Owner</div>
                <div class="text-gray-600">{{ auth()->user()->name }}</div>
                <!--begin::Details item-->
                <!--begin::Details item-->
                <div class="fw-bold mt-5">Email</div>
                <div class="text-gray-600">
                  <a href="#" class="text-gray-600 text-hover-primary">{{ auth()->user()->email }}</a>
                </div>
                <!--begin::Details item-->
                <!--begin::Details item-->
                <div class="fw-bold mt-5">Contact No.</div>
                <div class="text-gray-600"><a href="#"
                    class="text-gray-600 text-hover-primary">{{ auth()->user()->phone }}</a></div>
                <!--begin::Details item-->
                <!--begin::Details item-->
                <div class="fw-bold mt-5">Fassi No.</div>
                <div class="text-gray-600">{{ auth()->user()->vendor->fassi_no }}</div>
                <!--begin::Details item-->
              </div>
            </div>
            <!--end::Details content-->
          </div>
          <!--end::Card body-->
        </div>
        <!--end::Card-->
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
