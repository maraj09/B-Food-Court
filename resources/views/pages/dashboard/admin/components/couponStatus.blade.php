<div class="col-xl-4 mb-xl-10 d-flex flex-row flex-column-fluid draggable">
  <!--begin::List widget 22-->
  <div class="card card-flush h-md-100 d-flex flex-row-fluid">
    <!--begin::Header-->
    <div class="card-header pt-5">
      <!--begin::Title-->
      <h3 class="card-title align-items-start flex-column draggable-handle">
        <span class="card-label fw-bold text-gray-800">Coupon Status</span>
        {{-- <span class="text-gray-400 mt-1 fw-semibold fs-6">4 active offers</span> --}}
      </h3>
      <!--end::Title-->
      <!--begin::Toolbar-->
      <div class="card-toolbar">
        <a href="#" class="btn btn-sm btn-light">All Coupons</a>
      </div>
      <!--end::Toolbar-->
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body px-5 card-scroll h-350px">
      @foreach ($coupons as $coupon)
        <!--begin::Item-->
        <div class="d-flex flex-stack">
          <!--begin::Section-->
          <div class="d-flex align-items-center me-2">
            <!--begin::Content-->
            <div class="me-5">
              <!--begin::Title-->
              <a href="/dashboard/coupons/{{ $coupon->id }}/edit"
                class="text-gray-800 fw-bold text-hover-primary fs-6">{{ $coupon->code }}</a>
              <div>
                <span class="badge badge-primary me-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                  data-bs-placement="top" data-bs-original-title="Coupon Used" data-kt-initialized="1">
                  {{ $coupon->orders_count }} Times
                </span><span class="badge badge-danger" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                  data-bs-placement="top" data-bs-original-title="Dicounted Amount"
                  data-kt-initialized="1">₹{{ $coupon->total_discount ?? 0 }}</span>
              </div>
              <!--end::Desc-->
            </div>
            <!--end::Content-->
          </div>
          <!--end::Section-->
          <!--begin::Wrapper-->
          <div class="d-flex align-items-center">
            <!--begin::Info-->
            <div class="d-flex flex-center">
              <!--begin::Action-->
              <div class="form-check form-switch form-check-custom form-check-solid">
                <input class="form-check-input coupon-checkbox h-20px w-30px" type="checkbox" value=""
                  {{ $coupon->status == 1 ? 'checked' : '' }} data-coupon-id="{{ $coupon->id }}"
                  id="flexSwitchChecked">
              </div>
              <!--end::Action-->
            </div>
            <!--end::Info-->
          </div>
          <!--end::Wrapper-->
        </div>
        <!--end::Item-->
        <!--begin::Separator-->
        <div class="separator separator-dashed my-4"></div>
        <!--end::Separator-->
      @endforeach

    </div>
    <!--end::Body-->
    <!--begin::Footer-->
    <div class="card-footer mx-auto pt-5 pb-5">
      <!--begin::Actions-->
      <a href="/dashboard/coupons/create" class="btn btn-primary btn-sm me-3">Add New Coupon</a>
      <!--end::Actions-->
    </div>
    <!--end::Footer-->
  </div>
  <!--end::List widget 22-->
</div>


{{-- <script src="{{ asset('assets/js/custom/apps/ecommerce/sales/listing.js') }}"></script> --}}

