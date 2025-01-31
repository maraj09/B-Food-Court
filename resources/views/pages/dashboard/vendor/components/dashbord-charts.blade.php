@php
  $totalReviews = 0;
  foreach (auth()->user()->vendor->items as $item) {
      $totalReviews += $item->ratings->count();
  }
@endphp
<div class="card card-flush h-md-100 d-flex flex-row-fluid">
  <!--begin::Header-->
  <div class="card-header draggable-handle border-0 py-0">
    <h3 class="card-title align-items-start flex-column">
      <span class="card-label fw-bold text-gray-800">Hello, Vendor</span>
      <span class="text-gray-600 mt-1 fw-semibold fs-7">Welcome Back</span>
    </h3>
    <div class="card-toolbar">
      <!--begin::Input group-->
      <div class="form-floating">
        <select class="form-select" id="durationSelector" aria-label="Floating label select example">
          <option value="lifetime" selected>Lifetime</option>
          <option value="today">Today</option>
          <option value="week">This Week</option>
          <option value="month">This Month</option>
        </select>
        <label for="floatingSelect"><span class="text-dark">Select Duration</span></label>
      </div>
      <!--end::Input group-->
    </div>
  </div>
  <!--end::Header-->
  <!--begin::Body-->
  <div class="card-body p-0 mt-n5">
    <!--begin::Chart-->
    <div class="vendor-dashboard-chart" data-kt-color="info" style="height: 200px"></div>
    <!--end::Chart-->
    <!--begin::Stats-->
    <div class="card-p mt-n5 position-relative">
      <!--begin::Row-->
      <div class="row g-0">
        <!--begin::Col-->
        <div
          class="col hover-elevate-up bg-light-primary px-4 py-8 rounded-2 mb-7 mx-4 border border-primary border-dashed border-active active">
          <!--begin::Item-->
          <div class="d-flex flex-stack">
            <!--begin::Section-->
            <div class="d-flex align-items-center me-2">
              <!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
              <span class="svg-icon svg-icon-3x svg-icon-primary d-block my-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                  height="24px" viewBox="0 0 24 24" version="1.1">
                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <rect x="0" y="0" width="24" height="24" />
                    <path
                      d="M12.5,19 C8.91014913,19 6,16.0898509 6,12.5 C6,8.91014913 8.91014913,6 12.5,6 C16.0898509,6 19,8.91014913 19,12.5 C19,16.0898509 16.0898509,19 12.5,19 Z M12.5,16.4 C14.6539105,16.4 16.4,14.6539105 16.4,12.5 C16.4,10.3460895 14.6539105,8.6 12.5,8.6 C10.3460895,8.6 8.6,10.3460895 8.6,12.5 C8.6,14.6539105 10.3460895,16.4 12.5,16.4 Z M12.5,15.1 C11.0640597,15.1 9.9,13.9359403 9.9,12.5 C9.9,11.0640597 11.0640597,9.9 12.5,9.9 C13.9359403,9.9 15.1,11.0640597 15.1,12.5 C15.1,13.9359403 13.9359403,15.1 12.5,15.1 Z"
                      fill="currentColor" opacity="0.3" />
                    <path
                      d="M22,13.5 L22,13.5 C22.2864451,13.5 22.5288541,13.7115967 22.5675566,13.9954151 L23.0979976,17.8853161 C23.1712756,18.4226878 22.7950533,18.9177172 22.2576815,18.9909952 C22.2137086,18.9969915 22.1693798,19 22.125,19 L22.125,19 C21.5576012,19 21.0976335,18.5400324 21.0976335,17.9726335 C21.0976335,17.9415812 21.0990414,17.9105449 21.1018527,17.8796201 L21.4547321,13.9979466 C21.4803698,13.7159323 21.7168228,13.5 22,13.5 Z"
                      fill="currentColor" opacity="0.3" />
                    <path d="M24,5 L24,12 L21,12 L21,8 C21,6.34314575 22.3431458,5 24,5 Z" fill="#000000"
                      transform="translate(22.500000, 8.500000) scale(-1, 1) translate(-22.500000, -8.500000) " />
                    <path
                      d="M0.714285714,5 L1.03696911,8.32873399 C1.05651593,8.5303749 1.22598532,8.68421053 1.42857143,8.68421053 C1.63115754,8.68421053 1.80062692,8.5303749 1.82017375,8.32873399 L2.14285714,5 L2.85714286,5 L3.17982625,8.32873399 C3.19937308,8.5303749 3.36884246,8.68421053 3.57142857,8.68421053 C3.77401468,8.68421053 3.94348407,8.5303749 3.96303089,8.32873399 L4.28571429,5 L5,5 L5,8.39473684 C5,9.77544872 3.88071187,10.8947368 2.5,10.8947368 C1.11928813,10.8947368 -7.19089982e-16,9.77544872 -8.8817842e-16,8.39473684 L0,5 L0.714285714,5 Z"
                      fill="currentColor" />
                    <path
                      d="M2.5,12.3684211 L2.5,12.3684211 C2.90055463,12.3684211 3.23115721,12.6816982 3.25269782,13.0816732 L3.51381042,17.9301218 C3.54396441,18.4900338 3.11451066,18.9683769 2.55459863,18.9985309 C2.53641556,18.9995101 2.51820943,19 2.5,19 L2.5,19 C1.93927659,19 1.48472045,18.5454439 1.48472045,17.9847204 C1.48472045,17.966511 1.48521034,17.9483049 1.48618958,17.9301218 L1.74730218,13.0816732 C1.76884279,12.6816982 2.09944537,12.3684211 2.5,12.3684211 Z"
                      fill="currentColor" opacity="0.3" />
                  </g>
                </svg>
              </span>
              <!--end::Svg Icon-->
            </div>
            <!--end::Section-->
            <!--begin::Label-->
            <div id="total-ordered" class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{ $totalOrders }}"
              data-kt-countup-prefix="+">0</div>
            <!--end::Label-->
          </div>
          <!--end::Item-->
          <a href="#" class="text-primary fw-semibold fs-6">Total Orders</a>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div
          class="col hover-elevate-up bg-light-success px-4 py-8 rounded-2 mb-7 border border-success border-dashed border-active active">
          <!--begin::Item-->
          <div class="d-flex flex-stack">
            <!--begin::Section-->
            <div class="d-flex align-items-center me-2">
              <!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
              <span class="svg-icon svg-icon-3x svg-icon-success d-block my-2">
                <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 512 512" viewBox="0 0 512 512">
                  <path fill="currentColor" opacity="0.3"
                    d="M21 394.1c-.1 0-.2 0-.4-.1C20.7 394 20.9 394.1 21 394.1zM491 117.9c.1 0 .2 0 .4.1C491.3 118 491.1 117.9 491 117.9zM20.6 118c.1 0 .2 0 .4-.1C20.9 117.9 20.7 118 20.6 118zM491.4 394c-.1 0-.2 0-.4.1C491.1 394.1 491.3 394 491.4 394zM12 127.9c0-2.1.6-4 1.7-5.6C12.6 123.9 12 125.8 12 127.9l0 256.3c0 2.1.6 4 1.7 5.6-1.1-1.6-1.7-3.5-1.7-5.6V127.9zM500 127.9c0-2.1-.6-4-1.7-5.6C499.4 123.9 500 125.8 500 127.9l0 256.3c0 2.1-.6 4-1.7 5.6 1.1-1.6 1.7-3.5 1.7-5.6V127.9zM256.1 344.6c48.7 0 88.3-39.6 88.3-88.3s-39.6-88.3-88.3-88.3c-48.7 0-88.3 39.6-88.3 88.3S207.4 344.6 256.1 344.6zM218.8 224H265c-4-8-11.9-13.8-21.4-14.8h-24.8v-15h78.7v15h-24.3c3.6 4.3 6.3 9.3 7.9 14.8h16.4v15h-14.8c-1.4 19.7-16.3 35.7-35.5 38.6l33.9 40.8-11.5 9.6-45.1-54.2c-2.1-2.6-2.3-6.2-.4-9 1.9-2.8 5.3-4 8.5-2.9 2.7.9 5.4 1.3 8.3 1.3 13.9 0 25.4-10.6 26.9-24.1h-48.8V224z" />
                  <path fill="currentColor" opacity="0.3"
                    d="M32,374.1h448V137.9H32V374.1z M428.6,148.3h30.6c5.5,0,10,4.5,10,10v30.6c0,5.5-4.5,10-10,10s-10-4.5-10-10
                                                                                                v-20.6h-20.6c-5.5,0-10-4.5-10-10S423,148.3,428.6,148.3z M428.6,343.7h20.6v-20.6c0-5.5,4.5-10,10-10s10,4.5,10,10v30.6
                                                                                                c0,5.5-4.5,10-10,10h-30.6c-5.5,0-10-4.5-10-10S423,343.7,428.6,343.7z M256.1,148.1c59.7,0,108.3,48.6,108.3,108.3
                                                                                                s-48.6,108.3-108.3,108.3S147.8,316,147.8,256.3S196.4,148.1,256.1,148.1z M42.8,158.3c0-5.5,4.5-10,10-10h30.6c5.5,0,10,4.5,10,10
                                                                                                s-4.5,10-10,10H62.8v20.6c0,5.5-4.5,10-10,10s-10-4.5-10-10V158.3z M42.8,323.1c0-5.5,4.5-10,10-10s10,4.5,10,10v20.6h20.6
                                                                                                c5.5,0,10,4.5,10,10s-4.5,10-10,10H52.8c-5.5,0-10-4.5-10-10V323.1z" />
                  <path fill="currentColor"
                    d="M500,127.9c0-2.1-0.6-4-1.7-5.6c-1.6-2.3-4-3.9-6.9-4.3c-0.1,0-0.2,0-0.4-0.1c-0.3,0-0.7-0.1-1-0.1H22
                                                                                                c-0.3,0-0.7,0-1,0.1c-0.1,0-0.2,0-0.4,0.1c-2.9,0.4-5.4,2-6.9,4.3c-1.1,1.6-1.7,3.5-1.7,5.6v256.3c0,2.1,0.6,4,1.7,5.6
                                                                                                c1.6,2.3,4,3.9,6.9,4.3c0.1,0,0.2,0,0.4,0.1c0.3,0,0.7,0.1,1,0.1h468c0.3,0,0.7,0,1-0.1c0.1,0,0.2,0,0.4-0.1c2.9-0.4,5.3-2,6.9-4.3
                                                                                                c1.1-1.6,1.7-3.5,1.7-5.6V127.9z M480,374.1H32V137.9h448V374.1z" />
                  <path fill="currentColor"
                    d="M256.1 364.6c59.7 0 108.3-48.6 108.3-108.3s-48.6-108.3-108.3-108.3-108.3 48.6-108.3 108.3S196.4 364.6 256.1 364.6zM256.1 168.1c48.7 0 88.3 39.6 88.3 88.3s-39.6 88.3-88.3 88.3c-48.7 0-88.3-39.6-88.3-88.3S207.4 168.1 256.1 168.1zM52.8 198.9c5.5 0 10-4.5 10-10v-20.6h20.6c5.5 0 10-4.5 10-10s-4.5-10-10-10H52.8c-5.5 0-10 4.5-10 10v30.6C42.8 194.4 47.3 198.9 52.8 198.9zM428.6 168.3h20.6v20.6c0 5.5 4.5 10 10 10s10-4.5 10-10v-30.6c0-5.5-4.5-10-10-10h-30.6c-5.5 0-10 4.5-10 10S423 168.3 428.6 168.3zM428.6 363.7h30.6c5.5 0 10-4.5 10-10v-30.6c0-5.5-4.5-10-10-10s-10 4.5-10 10v20.6h-20.6c-5.5 0-10 4.5-10 10S423 363.7 428.6 363.7zM52.8 363.7h30.6c5.5 0 10-4.5 10-10s-4.5-10-10-10H62.8v-20.6c0-5.5-4.5-10-10-10s-10 4.5-10 10v30.6C42.8 359.3 47.3 363.7 52.8 363.7z" />
                  <path fill="currentColor"
                    d="M240.7,263.1c-2.8,0-5.6-0.4-8.3-1.3c-3.2-1-6.6,0.2-8.5,2.9c-1.9,2.8-1.7,6.4,0.4,9l45.1,54.2l11.5-9.6
                                                                                                l-33.9-40.8c19.2-2.9,34.2-18.9,35.5-38.6h14.8v-15H281c-1.6-5.5-4.3-10.5-7.9-14.8h24.3v-15h-78.7v15h24.8
                                                                                                c9.5,1,17.4,6.8,21.4,14.8h-46.2v15h48.8C266.1,252.5,254.6,263.1,240.7,263.1z" />
                </svg>
              </span>
              <!--end::Svg Icon-->
            </div>
            <!--end::Section-->
            <!--begin::Label-->
            <div id="vendor-earnings" class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{ $totalEarning }}"
              data-kt-countup-prefix="₹">0</div>
            <!--end::Label-->
          </div>
          <!--end::Item-->
          <a href="#" class="text-success fw-semibold fs-6">Total Earning</a>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div
          class="col hover-elevate-up bg-light-danger px-4 py-8 rounded-2 mx-4 mb-7 border border-danger border-dashed border-active active">
          <!--begin::Item-->
          <div class="d-flex flex-stack">
            <!--begin::Section-->
            <div class="d-flex align-items-center me-2">
              <!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
              <span class="svg-icon svg-icon-3x svg-icon-danger d-block my-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                  height="24px" viewBox="0 0 24 24" version="1.1">
                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <rect x="0" y="0" width="24" height="24" />
                    <rect fill="currentColor" opacity="0.3" x="4" y="4" width="8" height="16" />
                    <path
                      d="M6,18 L9,18 C9.66666667,18.1143819 10,18.4477153 10,19 C10,19.5522847 9.66666667,19.8856181 9,20 L4,20 L4,15 C4,14.3333333 4.33333333,14 5,14 C5.66666667,14 6,14.3333333 6,15 L6,18 Z M18,18 L18,15 C18.1143819,14.3333333 18.4477153,14 19,14 C19.5522847,14 19.8856181,14.3333333 20,15 L20,20 L15,20 C14.3333333,20 14,19.6666667 14,19 C14,18.3333333 14.3333333,18 15,18 L18,18 Z M18,6 L15,6 C14.3333333,5.88561808 14,5.55228475 14,5 C14,4.44771525 14.3333333,4.11438192 15,4 L20,4 L20,9 C20,9.66666667 19.6666667,10 19,10 C18.3333333,10 18,9.66666667 18,9 L18,6 Z M6,6 L6,9 C5.88561808,9.66666667 5.55228475,10 5,10 C4.44771525,10 4.11438192,9.66666667 4,9 L4,4 L9,4 C9.66666667,4 10,4.33333333 10,5 C10,5.66666667 9.66666667,6 9,6 L6,6 Z"
                      fill="currentColor" fill-rule="nonzero" />
                  </g>
                </svg>
              </span>
              <!--end::Svg Icon-->
            </div>
            <!--end::Section-->
            <!--begin::Label-->
            <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{ $totalItems }}"
              data-kt-countup-prefix="+">0</div>
            <!--end::Label-->
          </div>
          <!--end::Item-->
          <a href="#" class="text-danger fw-semibold fs-6">Total Items</a>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div
          class="col hover-elevate-up bg-light-secondary px-4 py-8 rounded-2 mb-7 border border-gray-700 border-dashed border-active active">
          <!--begin::Item-->
          <div class="d-flex flex-stack">
            <!--begin::Section-->
            <div class="d-flex align-items-center me-2">
              <!--begin::Svg Icon | path: icons/duotune/general/gen032.svg-->
              <span class="svg-icon svg-icon-3x text-gray-700 d-block my-2">
                <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px"
                  height="24px" viewBox="0 0 24 24" version="1.1">
                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                    <rect x="0" y="0" width="24" height="24" />
                    <path opacity="0.3" fill-rule="evenodd" clip-rule="evenodd"
                      d="M14.4862 18L12.7975 21.0566C12.5304 21.54 11.922 21.7153 11.4386 21.4483C11.2977 21.3704 11.1777 21.2597 11.0887 21.1255L9.01653 18H5C3.34315 18 2 16.6569 2 15V6C2 4.34315 3.34315 3 5 3H19C20.6569 3 22 4.34315 22 6V15C22 16.6569 20.6569 18 19 18H14.4862Z"
                      fill="currentColor" />
                    <path fill-rule="evenodd" clip-rule="evenodd"
                      d="M6 7H15C15.5523 7 16 7.44772 16 8C16 8.55228 15.5523 9 15 9H6C5.44772 9 5 8.55228 5 8C5 7.44772 5.44772 7 6 7ZM6 11H11C11.5523 11 12 11.4477 12 12C12 12.5523 11.5523 13 11 13H6C5.44772 13 5 12.5523 5 12C5 11.4477 5.44772 11 6 11Z"
                      fill="currentColor" />
                  </g>
                </svg>
              </span>
              <!--end::Svg Icon-->
            </div>
            <!--end::Section-->
            <!--begin::Label-->
            <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{ $totalReviews }}"
              data-kt-countup-prefix="+">
              0</div>
            <!--end::Label-->
          </div>
          <!--end::Item-->
          <a href="#" class="text-gray-900 fw-semibold fs-6">Total Reviews</a>
        </div>
        <!--end::Col-->
      </div>
      <!--end::Row-->
    </div>
    <!--end::Stats-->
  </div>
  <!--end::Body-->
</div>
