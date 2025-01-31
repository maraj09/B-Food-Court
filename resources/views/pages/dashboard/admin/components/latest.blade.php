<div class="col-xl-4 mb-5 mb-xl-10 d-flex flex-row flex-column-fluid draggable">
  <!--begin::Card-->
  <div class="card shadow-sm d-flex flex-row-fluid">
    <!--begin::Header-->
    <div class="card-header pt-5 px-5">
      <!--begin::Title-->
      <h3 class="card-title align-items-start flex-column draggable-handle">
        <span class="card-label fw-bold text-gray-800">Latest On</span>
        <span class="text-gray-400 mt-1 fw-semibold fs-6">Selected Date</span>
      </h3>
      <!--end::Title-->
      <!--begin::Toolbar-->
      <div class="card-toolbar">
        <!--begin::Nav-->
        <ul class="nav nav-pills nav-pills-custom item position-relative mx-2" role="tablist">
          <!--begin::Item-->
          <li class="nav-item col-4 mx-0 p-0" role="presentation">
            <!--begin::Link-->
            <a class="nav-link active d-flex justify-content-center w-100 border-0 h-100" data-bs-toggle="pill"
              href="#kt_list_widget_16_tab_1" aria-selected="true" role="tab">
              <!--begin::Subtitle-->
              <span class="nav-text text-gray-800 fw-bold fs-6 mb-3">Users</span>
              <!--end::Subtitle-->
              <!--begin::Bullet-->
              <span class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
              <!--end::Bullet-->
            </a>
            <!--end::Link-->
          </li>
          <!--end::Item-->
          <!--begin::Item-->
          <li class="nav-item col-4 mx-0 px-0" role="presentation">
            <!--begin::Link-->
            <a class="nav-link d-flex justify-content-center w-100 border-0 h-100" data-bs-toggle="pill"
              href="#kt_list_widget_16_tab_2" aria-selected="false" tabindex="-1" role="tab">
              <!--begin::Subtitle-->
              <span class="nav-text text-gray-800 fw-bold fs-6 mb-3">Activity</span>
              <!--end::Subtitle-->
              <!--begin::Bullet-->
              <span class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
              <!--end::Bullet-->
            </a>
            <!--end::Link-->
          </li>
          <!--end::Item-->
          <!--begin::Item-->
          <li class="nav-item col-4 mx-0 px-0" role="presentation">
            <!--begin::Link-->
            <a class="nav-link d-flex justify-content-center w-100 border-0 h-100" data-bs-toggle="pill"
              href="#kt_list_widget_16_tab_3" aria-selected="false" tabindex="-1" role="tab">
              <!--begin::Subtitle-->
              <span class="nav-text text-gray-800 fw-bold fs-6 mb-3">Birthday</span>
              <!--end::Subtitle-->
              <!--begin::Bullet-->
              <span class="bullet-custom position-absolute z-index-2 bottom-0 w-100 h-4px bg-primary rounded"></span>
              <!--end::Bullet-->
            </a>
            <!--end::Link-->
          </li>
          <!--end::Item-->
          <!--begin::Bullet-->
          <span class="position-absolute z-index-1 bottom-0 w-100 h-4px bg-light rounded"></span>
          <!--end::Bullet-->
        </ul>
        <!--end::Nav-->
      </div>
      <!--end::Toolbar-->
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body pt-6 px-2 card-scroll h-350px">
      <!--begin::Tab Content-->
      <div class="tab-content px-1 pe-3 mb-2">
        <!--begin::Tap pane-->
        <div class="tab-pane fade show active" id="kt_list_widget_16_tab_1" role="tabpanel">
          <!--begin::Item-->
          @foreach ($newCustomer as $customer)
            <div class="d-flex align-items-center mb-7">
              <!--begin::Avatar-->
              <div class="symbol symbol-50px me-2">
                <img src="{{ asset($customer->customer->avatar ?? 'assets/media/svg/avatars/blank.svg') }}"
                  class="" alt="">
              </div>
              <!--end::Avatar-->
              <!--begin::Text-->
              <div class="flex-grow-1">
                <a href="#" class="text-dark fw-bold text-hover-primary fs-8">{{ $customer->name }}</a>
                <a href="#" class="text-muted d-block me-2 text-hover-primary">{{ $customer->email }}</a><a
                  href="#" class="text-muted d-inline me-2 text-hover-primary">{{ $customer->phone }}</a>
              </div>
              <!--end::Text-->
              <a href="whatsapp://send?phone={{ $customer->phone }}" class="btn btn-icon btn-hover-rise me-2"
                style="height:25px;width:25px">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 1219.547 1225.016">
                  <path fill="#E0E0E0"
                    d="M1041.858 178.02C927.206 63.289 774.753.07 612.325 0 277.617 0 5.232 272.298 5.098 606.991c-.039 106.986 27.915 211.42 81.048 303.476L0 1225.016l321.898-84.406c88.689 48.368 188.547 73.855 290.166 73.896h.258.003c334.654 0 607.08-272.346 607.222-607.023.056-162.208-63.052-314.724-177.689-429.463zm-429.533 933.963h-.197c-90.578-.048-179.402-24.366-256.878-70.339l-18.438-10.93-191.021 50.083 51-186.176-12.013-19.087c-50.525-80.336-77.198-173.175-77.16-268.504.111-278.186 226.507-504.503 504.898-504.503 134.812.056 261.519 52.604 356.814 147.965 95.289 95.36 147.728 222.128 147.688 356.948-.118 278.195-226.522 504.543-504.693 504.543z">
                  </path>
                  <linearGradient id="a" x1="609.77" x2="609.77" y1="1190.114" y2="21.084"
                    gradientUnits="userSpaceOnUse">
                    <stop offset="0" stop-color="#20b038"></stop>
                    <stop offset="1" stop-color="#60d66a"></stop>
                  </linearGradient>
                  <path fill="url(#a)"
                    d="M27.875 1190.114l82.211-300.18c-50.719-87.852-77.391-187.523-77.359-289.602.133-319.398 260.078-579.25 579.469-579.25 155.016.07 300.508 60.398 409.898 169.891 109.414 109.492 169.633 255.031 169.57 409.812-.133 319.406-260.094 579.281-579.445 579.281-.023 0 .016 0 0 0h-.258c-96.977-.031-192.266-24.375-276.898-70.5l-307.188 80.548z">
                  </path>
                  <path fill="#FFF" fill-rule="evenodd"
                    d="M462.273 349.294c-11.234-24.977-23.062-25.477-33.75-25.914-8.742-.375-18.75-.352-28.742-.352-10 0-26.25 3.758-39.992 18.766-13.75 15.008-52.5 51.289-52.5 125.078 0 73.797 53.75 145.102 61.242 155.117 7.5 10 103.758 166.266 256.203 226.383 126.695 49.961 152.477 40.023 179.977 37.523s88.734-36.273 101.234-71.297c12.5-35.016 12.5-65.031 8.75-71.305-3.75-6.25-13.75-10-28.75-17.5s-88.734-43.789-102.484-48.789-23.75-7.5-33.75 7.516c-10 15-38.727 48.773-47.477 58.773-8.75 10.023-17.5 11.273-32.5 3.773-15-7.523-63.305-23.344-120.609-74.438-44.586-39.75-74.688-88.844-83.438-103.859-8.75-15-.938-23.125 6.586-30.602 6.734-6.719 15-17.508 22.5-26.266 7.484-8.758 9.984-15.008 14.984-25.008 5-10.016 2.5-18.773-1.25-26.273s-32.898-81.67-46.234-111.326z"
                    clip-rule="evenodd"></path>
                  <path fill="#FFF"
                    d="M1036.898 176.091C923.562 62.677 772.859.185 612.297.114 281.43.114 12.172 269.286 12.039 600.137 12 705.896 39.633 809.13 92.156 900.13L7 1211.067l318.203-83.438c87.672 47.812 186.383 73.008 286.836 73.047h.255.003c330.812 0 600.109-269.219 600.25-600.055.055-160.343-62.328-311.108-175.649-424.53zm-424.601 923.242h-.195c-89.539-.047-177.344-24.086-253.93-69.531l-18.227-10.805-188.828 49.508 50.414-184.039-11.875-18.867c-49.945-79.414-76.312-171.188-76.273-265.422.109-274.992 223.906-498.711 499.102-498.711 133.266.055 258.516 52 352.719 146.266 94.195 94.266 146.031 219.578 145.992 352.852-.118 274.999-223.923 498.749-498.899 498.749z">
                  </path>
                </svg>
              </a>
              <a href="sms:{{ $customer->phone }}" id="kt_drawer_chat_toggle" class="btn btn-icon btn-hover-rise me-2"
                style="height:25px;width:25px">
                <span class="svg-icon svg-icon-primary svg-icon-2hx"><svg width="24" height="24"
                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.3"
                      d="M8 8C8 7.4 8.4 7 9 7H16V3C16 2.4 15.6 2 15 2H3C2.4 2 2 2.4 2 3V13C2 13.6 2.4 14 3 14H5V16.1C5 16.8 5.79999 17.1 6.29999 16.6L8 14.9V8Z"
                      fill="currentColor"></path>
                    <path
                      d="M22 8V18C22 18.6 21.6 19 21 19H19V21.1C19 21.8 18.2 22.1 17.7 21.6L15 18.9H9C8.4 18.9 8 18.5 8 17.9V7.90002C8 7.30002 8.4 6.90002 9 6.90002H21C21.6 7.00002 22 7.4 22 8ZM19 11C19 10.4 18.6 10 18 10H12C11.4 10 11 10.4 11 11C11 11.6 11.4 12 12 12H18C18.6 12 19 11.6 19 11ZM17 15C17 14.4 16.6 14 16 14H12C11.4 14 11 14.4 11 15C11 15.6 11.4 16 12 16H16C16.6 16 17 15.6 17 15Z"
                      fill="currentColor"></path>
                  </svg>
                </span>
                <!--end::Svg Icon-->
              </a>
            </div>
          @endforeach

          <!--end::Item-->
        </div>
        <!--end::Tap pane-->
        <!--begin::Tap pane-->
        <div class="tab-pane fade" id="kt_list_widget_16_tab_2" role="tabpanel">
          <!--begin::Timeline-->
          <div class="timeline-label">
            <!--begin::Item-->
            @foreach ($timelineItems as $item)
              <div class="timeline-item">
                <div class="timeline-label fw-bold text-gray-800 fs-6">{{ $item->created_at->format('H:i') }}</div>
                @if ($item instanceof \App\Models\Order)
                  <div class="timeline-badge">
                    <i class="fa fa-genderless text-success fs-1"></i>
                  </div>
                  <div class="timeline-content fw-semibold text-gray-800 ps-3">New order placed
                    <a href="/dashboard/orders/{{ $item->id }}" class="text-primary">#{{ $item->custom_id }}</a>.
                  </div>
                @elseif ($item instanceof \App\Models\Payout)
                  <div class="timeline-badge">
                    <i class="fa fa-genderless text-danger fs-1"></i>
                  </div>
                  <div class="timeline-content fw-bold text-gray-800 ps-3">Make Payout Request
                    <a href="#" class="text-primary">₹{{ $item->request_amount }}</a>. by
                    {{ $item->vendor->user->name }}
                  </div>
                @elseif ($item instanceof \App\Models\Rating)
                  <div class="timeline-badge">
                    <i class="fa fa-genderless text-info fs-1"></i>
                  </div>
                  <div class="timeline-content fw-mormal text-muted ps-3">
                    {{ $item->review }}
                  </div>
                @endif
              </div>
            @endforeach
            <!--end::Item-->
          </div>
          <!--end::Timeline-->
        </div>
        <!--end::Tap pane-->
        <!--begin::Tap pane-->
        <div class="tab-pane fade" id="kt_list_widget_16_tab_3" role="tabpanel">
          @foreach ($pastBirthdays as $customer)
            <div class="d-flex align-items-center mb-7">
              <!--begin::Avatar-->
              <div class="symbol symbol-50px me-2">
                <img src="{{ asset($customer->avatar ?? 'assets/media/svg/avatars/blank.svg') }}" class=""
                  alt="">
              </div>
              <!--end::Avatar-->
              <!--begin::Text-->
              @php

                $customerBirthday = \Carbon\Carbon::parse($customer->date_of_birth);

                $today = \Carbon\Carbon::today();

                $daysRemaining = $customerBirthday->diffInDays($today, false);

                if ($daysRemaining > 0) {
                    $daysRemaining = abs($daysRemaining) . ' days ago';
                } elseif ($daysRemaining === 0) {
                    $daysRemaining = 'Today!';
                }
              @endphp
              <div class="flex-grow-1">
                <a href="#"
                  class="text-dark fw-bold text-hover-primary fs-8 me-1 d-block">{{ $customer->user->name }}</a><span
                  class="text-muted" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                  data-bs-placement="top" data-bs-original-title="Last Active"
                  data-kt-initialized="1">{{ $daysRemaining }}</span><br>
                <span class="text-primary text-hover-dark me-1" data-bs-toggle="tooltip"
                  data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-original-title="Birthday"
                  data-kt-initialized="1">{{ $customerBirthday->format('d-m-Y') }}</span>|
                <span class="fw-semibold fs-7 d-inline text-start text-success ps-0" data-bs-toggle="tooltip"
                  data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                  data-bs-original-title="Amount Spend" data-kt-initialized="1">₹
                  {{ $customer->user->orders->sum('order_amount') }}</span>
              </div>
              <!--end::Text-->
              <span class="svg-icon svg-icon-success svg-icon-2x me-2 bg-light-success rounded-circle rounded"
                data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                aria-label="Offer Redeemed" data-bs-original-title="Offer Redeemed" data-kt-initialized="1">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                  height="24px" viewBox="0 0 24 24" version="1.1">
                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <polygon points="0 0 24 0 24 24 0 24"></polygon>
                    <path
                      d="M9.26193932,16.6476484 C8.90425297,17.0684559 8.27315905,17.1196257 7.85235158,16.7619393 C7.43154411,16.404253 7.38037434,15.773159 7.73806068,15.3523516 L16.2380607,5.35235158 C16.6013618,4.92493855 17.2451015,4.87991302 17.6643638,5.25259068 L22.1643638,9.25259068 C22.5771466,9.6195087 22.6143273,10.2515811 22.2474093,10.6643638 C21.8804913,11.0771466 21.2484189,11.1143273 20.8356362,10.7474093 L17.0997854,7.42665306 L9.26193932,16.6476484 Z"
                      fill="currentColor" fill-rule="nonzero" opacity="0.5"
                      transform="translate(14.999995, 11.000002) rotate(-180.000000) translate(-14.999995, -11.000002) ">
                    </path>
                    <path
                      d="M4.26193932,17.6476484 C3.90425297,18.0684559 3.27315905,18.1196257 2.85235158,17.7619393 C2.43154411,17.404253 2.38037434,16.773159 2.73806068,16.3523516 L11.2380607,6.35235158 C11.6013618,5.92493855 12.2451015,5.87991302 12.6643638,6.25259068 L17.1643638,10.2525907 C17.5771466,10.6195087 17.6143273,11.2515811 17.2474093,11.6643638 C16.8804913,12.0771466 16.2484189,12.1143273 15.8356362,11.7474093 L12.0997854,8.42665306 L4.26193932,17.6476484 Z"
                      fill="currentColor" fill-rule="nonzero"
                      transform="translate(9.999995, 12.000002) rotate(-180.000000) translate(-9.999995, -12.000002) ">
                    </path>
                  </g>
                </svg>
              </span>
              <span class="svg-icon svg-icon-danger svg-icon-2x me-2" data-bs-toggle="tooltip"
                data-bs-custom-class="tooltip-inverse" data-bs-placement="top" aria-label="Not Redeem"
                data-bs-original-title="Not Redeem" data-kt-initialized="1">
                <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                  xmlns="http://www.w3.org/2000/svg">
                  <path
                    d="M17.5 11H6.5C4 11 2 9 2 6.5C2 4 4 2 6.5 2H17.5C20 2 22 4 22 6.5C22 9 20 11 17.5 11ZM15 6.5C15 7.9 16.1 9 17.5 9C18.9 9 20 7.9 20 6.5C20 5.1 18.9 4 17.5 4C16.1 4 15 5.1 15 6.5Z"
                    fill="currentColor"></path>
                  <path opacity="0.3"
                    d="M17.5 22H6.5C4 22 2 20 2 17.5C2 15 4 13 6.5 13H17.5C20 13 22 15 22 17.5C22 20 20 22 17.5 22ZM4 17.5C4 18.9 5.1 20 6.5 20C7.9 20 9 18.9 9 17.5C9 16.1 7.9 15 6.5 15C5.1 15 4 16.1 4 17.5Z"
                    fill="#80FFB7"></path>
                </svg>
              </span>
              <span data-bs-toggle="modal" data-bs-target="#kt_modal_birthday_reminder"
                class="btn btn-icon btn-hover-rise me-2 notify-again-btn" data-customer-id="{{ $customer->user_id }}"
                style="height:20px;width:20px" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                title="Notify Again">
                <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/kt-products/docs/metronic/html/releases/2022-09-15-053640/core/html/src/media/icons/duotune/communication/com004.svg-->
                <span class="svg-icon svg-icon-primary svg-icon-2x">
                  <svg width="24" height="24" viewBox="0 0 24 24" fill="none"
                    xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.3"
                      d="M14 3V20H2V3C2 2.4 2.4 2 3 2H13C13.6 2 14 2.4 14 3ZM11 13V11C11 9.7 10.2 8.59995 9 8.19995V7C9 6.4 8.6 6 8 6C7.4 6 7 6.4 7 7V8.19995C5.8 8.59995 5 9.7 5 11V13C5 13.6 4.6 14 4 14V15C4 15.6 4.4 16 5 16H11C11.6 16 12 15.6 12 15V14C11.4 14 11 13.6 11 13Z"
                      fill="currentColor"></path>
                    <path
                      d="M2 20H14V21C14 21.6 13.6 22 13 22H3C2.4 22 2 21.6 2 21V20ZM9 3V2H7V3C7 3.6 7.4 4 8 4C8.6 4 9 3.6 9 3ZM6.5 16C6.5 16.8 7.2 17.5 8 17.5C8.8 17.5 9.5 16.8 9.5 16H6.5ZM21.7 12C21.7 11.4 21.3 11 20.7 11H17.6C17 11 16.6 11.4 16.6 12C16.6 12.6 17 13 17.6 13H20.7C21.2 13 21.7 12.6 21.7 12ZM17 8C16.6 8 16.2 7.80002 16.1 7.40002C15.9 6.90002 16.1 6.29998 16.6 6.09998L19.1 5C19.6 4.8 20.2 5 20.4 5.5C20.6 6 20.4 6.60005 19.9 6.80005L17.4 7.90002C17.3 8.00002 17.1 8 17 8ZM19.5 19.1C19.4 19.1 19.2 19.1 19.1 19L16.6 17.9C16.1 17.7 15.9 17.1 16.1 16.6C16.3 16.1 16.9 15.9 17.4 16.1L19.9 17.2C20.4 17.4 20.6 18 20.4 18.5C20.2 18.9 19.9 19.1 19.5 19.1Z"
                      fill="currentColor"></path>
                  </svg>
                </span>
                <!--end::Svg Icon-->
              </span>
            </div>
          @endforeach
          <!--begin::Item-->

          <!--end::Item-->
          <!--begin::Item-->
          @foreach ($upcomingBirthdays as $customer)
            @php

              $customerBirthday = \Carbon\Carbon::parse($customer->date_of_birth);

              $today = \Carbon\Carbon::today();

              $daysRemaining = $customerBirthday->diffInDays($today, false);

              if ($daysRemaining < 0) {
                  $daysRemaining = abs($daysRemaining) . ' days remaining';
              } elseif ($daysRemaining === 0) {
                  $daysRemaining = 'Today!';
              }
            @endphp
            <div class="d-flex align-items-center mb-7">
              <!--begin::Avatar-->
              <div class="symbol symbol-50px me-2">
                <img src="{{ asset($customer->avatar ?? 'assets/media/svg/avatars/blank.svg') }}" class=""
                  alt="">
              </div>
              <!--end::Avatar-->
              <!--begin::Text-->
              <div class="flex-grow-1">
                <a href="#"
                  class="text-dark fw-bold text-hover-primary fs-8 me-1 d-block">{{ $customer->user->name }}</a><span
                  class="text-muted" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                  data-bs-placement="top" data-bs-original-title="Last Active"
                  data-kt-initialized="1">{{ $daysRemaining }}</span><br>
                <span class="text-primary text-hover-dark me-1" data-bs-toggle="tooltip"
                  data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-original-title="Birthday"
                  data-kt-initialized="1">{{ $customerBirthday->format('d-m-Y') }}</span>|
                <span class="fw-semibold fs-7 d-inline text-start text-success ps-0" data-bs-toggle="tooltip"
                  data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                  data-bs-original-title="Amount Spend" data-kt-initialized="1">₹
                  {{ $customer->user->orders->sum('order_amount') }}</span>
              </div>
              <!--end::Text-->
              <a href="whatsapp://send?phone={{ $customer->user->phone }}" class="btn btn-icon btn-hover-rise me-2"
                style="height:25px;width:25px">
                <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                  viewBox="0 0 1219.547 1225.016">
                  <path fill="#E0E0E0"
                    d="M1041.858 178.02C927.206 63.289 774.753.07 612.325 0 277.617 0 5.232 272.298 5.098 606.991c-.039 106.986 27.915 211.42 81.048 303.476L0 1225.016l321.898-84.406c88.689 48.368 188.547 73.855 290.166 73.896h.258.003c334.654 0 607.08-272.346 607.222-607.023.056-162.208-63.052-314.724-177.689-429.463zm-429.533 933.963h-.197c-90.578-.048-179.402-24.366-256.878-70.339l-18.438-10.93-191.021 50.083 51-186.176-12.013-19.087c-50.525-80.336-77.198-173.175-77.16-268.504.111-278.186 226.507-504.503 504.898-504.503 134.812.056 261.519 52.604 356.814 147.965 95.289 95.36 147.728 222.128 147.688 356.948-.118 278.195-226.522 504.543-504.693 504.543z">
                  </path>
                  <linearGradient id="a" x1="609.77" x2="609.77" y1="1190.114" y2="21.084"
                    gradientUnits="userSpaceOnUse">
                    <stop offset="0" stop-color="#20b038"></stop>
                    <stop offset="1" stop-color="#60d66a"></stop>
                  </linearGradient>
                  <path fill="url(#a)"
                    d="M27.875 1190.114l82.211-300.18c-50.719-87.852-77.391-187.523-77.359-289.602.133-319.398 260.078-579.25 579.469-579.25 155.016.07 300.508 60.398 409.898 169.891 109.414 109.492 169.633 255.031 169.57 409.812-.133 319.406-260.094 579.281-579.445 579.281-.023 0 .016 0 0 0h-.258c-96.977-.031-192.266-24.375-276.898-70.5l-307.188 80.548z">
                  </path>
                  <path fill="#FFF" fill-rule="evenodd"
                    d="M462.273 349.294c-11.234-24.977-23.062-25.477-33.75-25.914-8.742-.375-18.75-.352-28.742-.352-10 0-26.25 3.758-39.992 18.766-13.75 15.008-52.5 51.289-52.5 125.078 0 73.797 53.75 145.102 61.242 155.117 7.5 10 103.758 166.266 256.203 226.383 126.695 49.961 152.477 40.023 179.977 37.523s88.734-36.273 101.234-71.297c12.5-35.016 12.5-65.031 8.75-71.305-3.75-6.25-13.75-10-28.75-17.5s-88.734-43.789-102.484-48.789-23.75-7.5-33.75 7.516c-10 15-38.727 48.773-47.477 58.773-8.75 10.023-17.5 11.273-32.5 3.773-15-7.523-63.305-23.344-120.609-74.438-44.586-39.75-74.688-88.844-83.438-103.859-8.75-15-.938-23.125 6.586-30.602 6.734-6.719 15-17.508 22.5-26.266 7.484-8.758 9.984-15.008 14.984-25.008 5-10.016 2.5-18.773-1.25-26.273s-32.898-81.67-46.234-111.326z"
                    clip-rule="evenodd"></path>
                  <path fill="#FFF"
                    d="M1036.898 176.091C923.562 62.677 772.859.185 612.297.114 281.43.114 12.172 269.286 12.039 600.137 12 705.896 39.633 809.13 92.156 900.13L7 1211.067l318.203-83.438c87.672 47.812 186.383 73.008 286.836 73.047h.255.003c330.812 0 600.109-269.219 600.25-600.055.055-160.343-62.328-311.108-175.649-424.53zm-424.601 923.242h-.195c-89.539-.047-177.344-24.086-253.93-69.531l-18.227-10.805-188.828 49.508 50.414-184.039-11.875-18.867c-49.945-79.414-76.312-171.188-76.273-265.422.109-274.992 223.906-498.711 499.102-498.711 133.266.055 258.516 52 352.719 146.266 94.195 94.266 146.031 219.578 145.992 352.852-.118 274.999-223.923 498.749-498.899 498.749z">
                  </path>
                </svg>
              </a>
              <a href="sms:{{ $customer->user->phone }}" class="btn btn-icon btn-hover-rise me-2"
                style="height:25px;width:25px">
                <!--begin::Svg Icon | path: /var/www/preview.keenthemes.com/kt-products/docs/metronic/html/releases/2022-09-15-053640/core/html/src/media/icons/duotune/communication/com007.svg-->
                <span class="svg-icon svg-icon-primary svg-icon-2hx"><svg width="24" height="24"
                    viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                    <path opacity="0.3"
                      d="M8 8C8 7.4 8.4 7 9 7H16V3C16 2.4 15.6 2 15 2H3C2.4 2 2 2.4 2 3V13C2 13.6 2.4 14 3 14H5V16.1C5 16.8 5.79999 17.1 6.29999 16.6L8 14.9V8Z"
                      fill="currentColor"></path>
                    <path
                      d="M22 8V18C22 18.6 21.6 19 21 19H19V21.1C19 21.8 18.2 22.1 17.7 21.6L15 18.9H9C8.4 18.9 8 18.5 8 17.9V7.90002C8 7.30002 8.4 6.90002 9 6.90002H21C21.6 7.00002 22 7.4 22 8ZM19 11C19 10.4 18.6 10 18 10H12C11.4 10 11 10.4 11 11C11 11.6 11.4 12 12 12H18C18.6 12 19 11.6 19 11ZM17 15C17 14.4 16.6 14 16 14H12C11.4 14 11 14.4 11 15C11 15.6 11.4 16 12 16H16C16.6 16 17 15.6 17 15Z"
                      fill="currentColor"></path>
                  </svg>
                </span>
                <!--end::Svg Icon-->
              </a>
            </div>
          @endforeach
          <!--end::Item-->

        </div>
        <!--end::Tap pane-->
      </div>
      <!--end::Tab Content-->
    </div>
    <!--end::Body-->
  </div>
  <!--end::Card-->
</div>
