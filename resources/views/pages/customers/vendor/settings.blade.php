@extends('layouts.vendor.app')
@section('contents')
  <!--begin::Toolbar-->
  @include('pages.customers.vendor.toolbar.profileToolbar')
  <!--end::Toolbar-->
  <!--begin::Content-->
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Navbar-->
      @if (Session::has('success'))
        <div class="alert alert-success">
          {{ Session::get('success') }}
        </div>
      @endif
      <div class="card mb-5 mb-xl-10 mt-20 mt-md-0">
        <div class="card-body pt-9 pb-0">
          <!--begin::Details-->
          <div class="d-flex flex-wrap flex-sm-nowrap">
            <!--begin: Pic-->
            <div class="me-7 mb-4">
              <div class="symbol symbol-100px symbol-lg-160px symbol-fixed position-relative">
                <img src="{{ asset(auth()->user()->vendor->avatar ?? 'assets/media/svg/avatars/blank.svg') }}"
                  alt="image" />
                <div
                  class="position-absolute translate-middle bottom-0 start-100 mb-6 bg-success rounded-circle border border-4 border-body h-20px w-20px">
                </div>
              </div>
            </div>
            <!--end::Pic-->
            <!--begin::Info-->
            <div class="flex-grow-1">
              <!--begin::Title-->
              <div class="d-flex justify-content-between align-items-start flex-wrap mb-2">
                <!--begin::User-->
                <div class="d-flex flex-column">
                  <!--begin::Name-->
                  <div class="d-flex align-items-center mb-2">
                    <a href="#"
                      class="text-gray-900 text-hover-primary fs-2 fw-bold me-1">{{ auth()->user()->name }}</a>
                    <a href="#">
                      <i class="ki-duotone ki-verify fs-1 text-primary">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                    </a>
                  </div>
                  <!--end::Name-->
                  <!--begin::Info-->
                  <div class="d-flex flex-wrap fw-semibold fs-6 mb-4 pe-2">
                    <a href="#" class="d-flex align-items-center text-gray-500 text-hover-primary mb-2">
                      <i class="ki-duotone ki-phone fs-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>{{ auth()->user()->phone }}</a>
                  </div>
                  <!--end::Info-->
                </div>
                <!--end::User-->
                <!--begin::Actions-->

                <!--end::Actions-->
              </div>
              <!--end::Title-->
              <!--begin::Stats-->
              @php
                use App\Models\OrderItem;
                use App\Models\VendorBank;
                $vendorId = auth()->user()->vendor->id;

                $orderItems = OrderItem::whereHas('item', function ($query) use ($vendorId) {
                    $query->where('vendor_id', $vendorId);
                })
                    ->where('status', '!=', 'rejected')
                    ->get();

                $balance = VendorBank::where('vendor_id', $vendorId)->first()->balance;
                // Calculate total sales for the vendor
                $totalSales = $orderItems->sum(function ($item) {
                    return $item->quantity * $item->price;
                });
                $totalOrders = OrderItem::whereHas('item', function ($query) use ($vendorId) {
                    $query->where('vendor_id', $vendorId);
                })->count();
              @endphp
              <div class="d-flex flex-wrap flex-stack">
                <!--begin::Wrapper-->
                <div class="d-flex flex-column flex-grow-1 pe-8">
                  <!--begin::Stats-->
                  <div class="d-flex flex-wrap">
                    <!--begin::Stat-->
                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                      <!--begin::Number-->
                      <div class="d-flex align-items-center">
                        <i class="ki-duotone ki-arrow-up fs-3 text-success me-2">
                          <span class="path1"></span>
                          <span class="path2"></span>
                        </i>
                        <div class="fs-2 fw-bold" data-kt-countup="true" data-kt-countup-value="{{ $totalSales }}"
                          data-kt-countup-prefix="₹">0</div>
                      </div>
                      <!--end::Number-->
                      <!--begin::Label-->
                      <div class="fw-semibold fs-6 text-gray-500">Sales</div>
                      <!--end::Label-->
                    </div>
                    <!--end::Stat-->
                    <!--begin::Stat-->
                    <div class="border border-gray-300 border-dashed rounded min-w-125px py-3 px-4 me-6 mb-3">
                      <!--begin::Number-->
                      <div class="d-flex align-items-center">
                        <i class="ki-duotone ki-arrow-up fs-3 text-success me-2">
                          <span class="path1"></span>
                          <span class="path2"></span>
                        </i>
                        <div class="fs-2 fw-bold" data-kt-countup="true"
                          data-kt-countup-value="{{ $orderItems->count() }}" data-kt-countup-prefix="">0</div>
                      </div>
                      <!--end::Number-->
                      <!--begin::Label-->
                      <div class="fw-semibold fs-6 text-gray-500">Orders Success</div>
                      <!--end::Label-->
                    </div>
                    <!--end::Stat-->

                  </div>
                  <!--end::Stats-->
                </div>
                <!--end::Wrapper-->
                <!--begin::Progress-->

                <!--end::Progress-->
              </div>
              <!--end::Stats-->
            </div>
            <!--end::Info-->
          </div>
          <!--end::Details-->
          <!--begin::Navs-->
          <ul class="nav nav-stretch nav-line-tabs nav-line-tabs-2x border-transparent fs-5 fw-bold">
            <!--begin::Nav item-->
            <li class="nav-item mt-2">
              <a class="nav-link text-active-primary ms-0 me-10 py-5 active" href="/vendor/settings">Overview</a>
            </li>
            <!--end::Nav item-->
          </ul>
          <!--begin::Navs-->
        </div>
      </div>
      <!--end::Navbar-->
      <!--begin::details View-->
      <div class="card mb-5 mb-xl-10">
        <!--begin::Card header-->
        <div class="card-header border-0 cursor-pointer" role="button" data-bs-toggle="collapse"
          data-bs-target="#kt_account_profile_details" aria-expanded="true" aria-controls="kt_account_profile_details">
          <!--begin::Card title-->
          <div class="card-title m-0">
            <h3 class="fw-bold m-0">Profile Details</h3>
          </div>
          <!--end::Card title-->
        </div>
        <!--begin::Card header-->
        <!--begin::Content-->
        <div id="kt_account_settings_profile_details" class="collapse show">
          <!--begin::Form-->
          <form id="kt_account_profile_details_form" class="form" method="POST"
            action="{{ route('vendor.profile.update') }}" enctype="multipart/form-data">
            @csrf
            @method('PUT')
            <!--begin::Card body-->
            <div class="card-body border-top p-9">
              <!--begin::Input group-->
              <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6">Avatar</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                  <!--begin::Image input-->
                  <div class="image-input image-input-outline" data-kt-image-input="true"
                    style="background-image: url('assets/media/svg/avatars/blank.svg')">
                    <!--begin::Preview existing avatar-->
                    <div class="image-input-wrapper w-125px h-125px"
                      style="background-image: url({{ asset(auth()->user()->vendor->avatar ?? 'assets/media/svg/avatars/blank.svg') }})">
                    </div>
                    <!--end::Preview existing avatar-->
                    <!--begin::Label-->
                    <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                      data-kt-image-input-action="change" data-bs-toggle="tooltip" title="Change avatar">
                      <i class="ki-duotone ki-pencil fs-7">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                      <!--begin::Inputs-->
                      <input type="file" name="avatar" accept=".png, .jpg, .jpeg" />
                      <input type="hidden" name="avatar_remove" />
                      <!--end::Inputs-->
                    </label>
                    <!--end::Label-->
                    <!--begin::Cancel-->
                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                      data-kt-image-input-action="cancel" data-bs-toggle="tooltip" title="Cancel avatar">
                      <i class="ki-duotone ki-cross fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                    </span>
                    <!--end::Cancel-->
                    <!--begin::Remove-->
                    <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                      data-kt-image-input-action="remove" data-bs-toggle="tooltip" title="Remove avatar">
                      <i class="ki-duotone ki-cross fs-2">
                        <span class="path1"></span>
                        <span class="path2"></span>
                      </i>
                    </span>
                    <!--end::Remove-->
                  </div>
                  <!--end::Image input-->
                  <!--begin::Hint-->
                  @error('avatar')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                  <div class="form-text">Allowed file types: png, jpg, jpeg.</div>
                  <!--end::Hint-->
                </div>
                <!--end::Col-->
              </div>
              <!--end::Input group-->
              <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Brand Name</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                  <!--begin::Row-->
                  <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-6 fv-row">
                      <input type="text" name="brand_name"
                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="brand_name"
                        value="{{ auth()->user()->vendor->brand_name }}" />
                      @error('brand_name')
                        <div class="text-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->

                    <!--end::Col-->
                  </div>
                  <!--end::Row-->
                </div>
                <!--end::Col-->
              </div>
              <!--begin::Input group-->
              <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label required fw-semibold fs-6">Brand Owner Name</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-8">
                  <!--begin::Row-->
                  <div class="row">
                    <!--begin::Col-->
                    <div class="col-lg-6 fv-row">
                      <input type="text" name="name"
                        class="form-control form-control-lg form-control-solid mb-3 mb-lg-0" placeholder="name"
                        value="{{ auth()->user()->name }}" />
                      @error('name')
                        <div class="text-danger">{{ $message }}</div>
                      @enderror
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->

                    <!--end::Col-->
                  </div>
                  <!--end::Row-->
                </div>
                <!--end::Col-->
              </div>
              <!--end::Input group-->
              <!--begin::Input group-->
              <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6">Email Address</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-4 fv-row">
                  <input type="text" name="email" class="form-control form-control-lg form-control-solid"
                    placeholder="Enter Email Address" value="{{ auth()->user()->email }}" />
                  @error('email')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
                <!--end::Col-->
              </div>


              <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6">Fassi No</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-4 fv-row">
                  <input type="text" name="fassi_no" class="form-control form-control-lg form-control-solid"
                    placeholder="fassi_no" value="{{ auth()->user()->vendor->fassi_no }}" />
                  @error('fassi_no')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
                <!--end::Col-->
              </div>

              <div class="row mb-6">
                <!--begin::Label-->
                <label class="col-lg-4 col-form-label fw-semibold fs-6">Stall No</label>
                <!--end::Label-->
                <!--begin::Col-->
                <div class="col-lg-4 fv-row">
                  <input type="text" name="stall_no" class="form-control form-control-lg form-control-solid"
                    placeholder="stall_no" value="{{ auth()->user()->vendor->stall_no }}" />
                  @error('stall_no')
                    <div class="text-danger">{{ $message }}</div>
                  @enderror
                </div>
                <!--end::Col-->
              </div>

            </div>
            <!--end::Card body-->
            <!--begin::Actions-->
            <div class="card-footer d-flex justify-content-end py-6 px-9">
              <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
              <button type="submit" class="btn btn-primary" id="kt_account_profile_details_submit">Save
                Changes</button>
            </div>
            <!--end::Actions-->
          </form>
          <!--end::Form-->
        </div>
        <!--end::Content-->
      </div>
      <!--end::details View-->
      <!--begin::Row-->

      <!--end::Row-->
    </div>
    <!--end::Content container-->
  </div>
  <!--end::Content-->
@endsection
@section('scripts')
  <script>
    $("#kt_datepicker_dob_custom").flatpickr();
  </script>
@endsection
