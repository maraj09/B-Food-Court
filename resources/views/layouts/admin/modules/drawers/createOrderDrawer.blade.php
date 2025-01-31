@php
  $settings = \App\Models\Setting::first();
  $items = \App\Models\Item::whereHas('vendor', function ($query) {
      $query->where('approve', 1);
  })
      ->where('status', 1)
      ->where('approve', 1)
      ->get();
  $customerUsers = \App\Models\User::role('customer')->get();
  $coupons = \App\Models\Coupon::where('status', 1)->get();
@endphp
<div id="kt_shopping_cart" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="cart" data-kt-drawer-activate="true"
  data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'320px', 'md': '700px'}" data-kt-drawer-direction="end"
  data-kt-drawer-toggle="#kt_drawer_shopping_cart_toggle" data-kt-drawer-close="#kt_drawer_shopping_cart_close">
  <!--begin::Messenger-->
  <div class="card shadow-none rounded-0 w-100">
    <div class="card-header bg-primary">
      <h3 class="card-title align-items-start flex-column">
        <select id="kt_docs_select2_rich_content" class="form-select w-100px w-md-250px rounded-start-0 fs-6"
          name="..." data-placeholder="Select Item">
          <option></option>
          @foreach ($items as $item)
            <option value="{{ $item->id }}" data-kt-rich-content-price="{{ intval($item->price) }}"
              data-item-price={{ $item->price }} data-kt-rich-content-vendor="{{ $item->vendor->brand_name }}"
              data-kt-rich-content-icon="{{ asset($item->item_image) }}"
              data-kt-rich-content-stall="{{ $item->vendor->stall_no }}"
              data-kt-rich-content-rating="{{ $item->itemRating->rating ?? 0.0 }}">{{ $item->item_name }}</option>
          @endforeach
        </select>
      </h3>
      <div class="card-toolbar">
        <!--begin::Default example-->
        <div class="input-group">
          <span class="input-group-text" data-bs-custom-class="tooltip-dark" data-bs-toggle="tooltip"
            data-bs-placement="top" data-bs-delay-hide="1000" title="Add Customer">
            <a href="#" data-bs-toggle="modal" data-bs-target="#add_customer_modal">
              <span
                class="svg-icon text-gray-900 svg-icon-2"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Communication/Add-user.svg--><svg
                  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                  height="24px" viewBox="0 0 24 24" version="1.1">
                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <polygon points="0 0 24 0 24 24 0 24" />
                    <path
                      d="M18,8 L16,8 C15.4477153,8 15,7.55228475 15,7 C15,6.44771525 15.4477153,6 16,6 L18,6 L18,4 C18,3.44771525 18.4477153,3 19,3 C19.5522847,3 20,3.44771525 20,4 L20,6 L22,6 C22.5522847,6 23,6.44771525 23,7 C23,7.55228475 22.5522847,8 22,8 L20,8 L20,10 C20,10.5522847 19.5522847,11 19,11 C18.4477153,11 18,10.5522847 18,10 L18,8 Z M9,11 C6.790861,11 5,9.209139 5,7 C5,4.790861 6.790861,3 9,3 C11.209139,3 13,4.790861 13,7 C13,9.209139 11.209139,11 9,11 Z"
                      fill="currentcolor" fill-rule="nonzero" opacity="0.3" />
                    <path
                      d="M0.00065168429,20.1992055 C0.388258525,15.4265159 4.26191235,13 8.98334134,13 C13.7712164,13 17.7048837,15.2931929 17.9979143,20.2 C18.0095879,20.3954741 17.9979143,21 17.2466999,21 C13.541124,21 8.03472472,21 0.727502227,21 C0.476712155,21 -0.0204617505,20.45918 0.00065168429,20.1992055 Z"
                      fill="currentcolor" fill-rule="nonzero" />
                  </g>
                </svg><!--end::Svg Icon--></span>
            </a>
          </span>
          <div class="overflow-hidden">
            <select id="kt_docs_select2_badge"
              class="form-select w-100px w-md-250px rounded-start-0 fs-6 new_order_drawer_select_customer"
              name="..." data-placeholder="Select Customer">
              <option></option>
              @foreach ($customerUsers as $customerUser)
                <option value="{{ $customerUser->id }}"
                  data-kt-select2-badge="{{ $customerUser->point->points ?? 0 }} Points"
                  data-kt-customer-info="{{ $customerUser->phone }}">
                  {{ $customerUser->name }}
                </option>
              @endforeach
            </select>
          </div>
        </div>
        <!--end::Default example-->
      </div>
    </div>
    <!--begin::Card body-->
    <div class="card-body p-2 scroll h-auto">
      <!--begin::row-->
      <div class="row">
        <div class="col px-5" id="selectedItemsDetails">

        </div>
      </div>
      <!--end::row-->
    </div>
    <!--end::Card body-->
    <!--begin::Card footer-->
    <div class="card-footer px-3 py-0">
      <!--begin::Heading-->
      <div class="d-flex align-items-center justify-content-between flex-wrap py-2 breakup_coupon_container">
        <!--begin::Label-->
        <span class="fs-4 fw-bold pe-2" data-bs-toggle="collapse" data-bs-target="#kt_docs_card_billbreakup">Bill
          Breakup<i class="fas fa-info-circle text-primary ms-2 collapsible cursor-pointer" data-bs-toggle="tooltip"
            data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Bill Details"></i></span>
        <!--end::Label-->
        <span
          class="btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning ms-auto px-3 py-1 applied_coupon_head"
          data-bs-toggle="collapse" data-bs-target="#kt_docs_card_applied_coupon">Coupons<i
            class="fas fa-info-circle text-primary ms-2 collapsible cursor-pointer" data-bs-toggle="tooltip"
            data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Applied Coupon"></i></span>

      </div>
      <!--end::Heading-->
      <!--begin::Bill Breakup-->
      <div id="kt_docs_card_billbreakup" class="collapse hide border border-dashed border-danger my-2 rounded-1">
        <div class="d-flex flex-column p-2" id="billbreakup">
        </div>
      </div>
      <!--end::Bill Breakup-->
      <!--begin::Bill Breakup-->
      <div id="kt_docs_card_applied_coupon" class="collapse hide border border-dashed border-danger my-2 rounded-1">
        <div class="d-flex flex-column p-2">
          @foreach ($coupons as $coupon)
            <li class="d-flex align-items-center py-2 couponButton" data-code="{{ $coupon->code }}"
              data-discount="{{ $coupon->discount }}" data-discount-type="{{ $coupon->discount_type }}">
              <span
                class="btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning px-3 py-1 coupon_span_{{ $coupon->code }}">{{ $coupon->code }}
                : {{ $coupon->discount_type == 'fixed' ? '₹' . $coupon->discount : $coupon->discount . '%' }}</span>
            </li>
          @endforeach
        </div>
      </div>
      <!--end::Bill Breakup-->
      <div class="row row-cols-2">
        <div class="col p-0">
          <a href="#" id="kt_drawer_example_basic_button"
            class="btn btn-warning hover-elevate-up w-100 rounded-0 py-2" data-bs-toggle="tooltip"
            data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Clear All"
            onclick="adminCartDrawerClear()">Clear Now</a>
        </div>
        <div class="col p-0">
          <a href="" class="btn btn-danger hover-elevate-up w-100 rounded-0 py-2 dropdown-toggle"
            data-bs-toggle="dropdown" role="button" aria-expanded="false" id="total_price_in_pay_now">₹ 0<span
              class="badge badge-dark ms-2">0 Items</span></a>
          <ul class="dropdown-menu p-0">
            @if ($settings->payment_mode_upi_status)
              <li><a href="#" onclick="placeOrder('upi', event)"
                  class="dropdown-item btn btn-light-warning w-100 rounded-0 py-2">UPI</a></li>
            @endif
            @if ($settings->payment_mode_cash_status)
              <li><a href="#" onclick="placeOrder('cash', event)"
                  class="dropdown-item btn btn-light-success w-100 rounded-0 py-2">Cash</a></li>
            @endif
            @if ($settings->payment_mode_card_status)
              <li><a href="#" onclick="placeOrder('card', event)"
                  class="dropdown-item btn btn-light-danger w-100 rounded-0 py-2">Card</a></li>
            @endif
          </ul>
        </div>
      </div>
      <!--end::View component-->
    </div>
    <!--end::Card footer-->
  </div>
  <!--end::Messenger-->
</div>
