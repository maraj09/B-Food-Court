@extends('layouts.admin.app')

@section('contents')
  @include('pages.coupons.admin.toolbar.couponsToolbar')
  <form action="/dashboard/coupons/{{ $coupon->id }}/delete" method="post" id="coupon_delete_form">
    @csrf
    @method('delete')
  </form>
  <div id="kt_app_content" class="app-content flex-column-fluid mt-20 mt-lg-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      @if (Session::has('success'))
        <div class="alert alert-success">
          {{ Session::get('success') }}
        </div>
      @endif
      <!--begin::Custom Content-->
      <div class="card">
        <!--begin::Form-->
        <form id="kt_project_settings_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" method="POST"
          action="{{ route('admin.coupons.update', $coupon->id) }}" novalidate="novalidate">
          @csrf
          @method('PUT')
          <!--begin::Card body-->
          <div class="card-body p-9">
            <!--begin:: Heder Text Row-->
            <div class="row mb-8">
              <!--begin::Col-->
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Coupon</div>
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <input type="text" class="form-control form-control-solid @error('code') is-invalid @enderror"
                  name="code" placeholder="Enter Coupon Code" value="{{ old('code', $coupon->code) }}">
                <div class="fv-plugins-message-container invalid-feedback">
                  @error('code')
                    {{ $message }}
                  @enderror
                </div>
                <div class="form-text required">Ex: BFC 50</div>
              </div>
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <input type="number" class="form-control form-control-solid @error('discount') is-invalid @enderror"
                  name="discount" placeholder="Discount Rate" value="{{ old('discount', round($coupon->discount)) }}">
                <div class="fv-plugins-message-container invalid-feedback">
                  @error('discount')
                    {{ $message }}
                  @enderror
                </div>
                <div class="form-text required">Ex: 50, 100, 200....</div>
              </div>
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <select id="discountType" type="text" class="form-select @error('discount_type') is-invalid @enderror"
                  name="discount_type" data-control="select2" data-placeholder="Select an option"
                  data-minimum-results-for-search="Infinity">
                  <option value=""></option>
                  <option value="fixed" {{ old('discount_type', $coupon->discount_type) == 'fixed' ? 'selected' : '' }}>
                    Fixed</option>
                  <option value="percentage"
                    {{ old('discount_type', $coupon->discount_type) == 'percentage' ? 'selected' : '' }}>Percentage(%)
                  </option>
                </select>
                <div class="fv-plugins-message-container invalid-feedback">
                  @error('discount_type')
                    {{ $message }}
                  @enderror
                </div>
                <div class="form-text required">Discount Rate Type</div>
              </div>
            </div>
            <!--end::Row-->
            <div class="separator separator-dashed border-primary my-10"></div>
            <!--begin:: Heder Text Row-->
            <div class="row mb-8">
              <!--begin::Col-->
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Limits</div>
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <input type="number" class="form-control form-control-solid @error('limit') is-invalid @enderror"
                  name="limit" value="{{ old('limit', $coupon->limit) }}" placeholder="Limit">
                <div class="fv-plugins-message-container invalid-feedback">
                  @error('limit')
                    {{ $message }}
                  @enderror
                </div>
                <!--begin::Hint-->
                <div class="form-text">Ex: 0, 1, 2, 3....(0 means unlimited)</div>
                <!--end::Hint-->
              </div>
              <!--begin::Col-->
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <select id="limitType" type="text" class="form-select @error('limit_type') is-invalid @enderror"
                  name="limit_type" data-control="select2" data-placeholder="Select an option"
                  data-minimum-results-for-search="Infinity">
                  <option value=""></option>
                  <option value="global" {{ old('limit_type', $coupon->limit_type) == 'global' ? 'selected' : '' }}>
                    Global</option>
                  <option value="per_user" {{ old('limit_type', $coupon->limit_type) == 'per_user' ? 'selected' : '' }}>
                    Per User</option>
                  <option value="on_order" {{ old('limit_type', $coupon->limit_type) == 'on_order' ? 'selected' : '' }}>
                    On Order</option>
                </select>
                <div class="fv-plugins-message-container invalid-feedback">
                  @error('limit_type')
                    {{ $message }}
                  @enderror
                </div>
                <!--begin::Hint-->
                <div class="form-text required">Limit Type</div>
                <!--end::Hint-->
              </div>
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <input type="number"
                  class="form-control form-control-solid @error('minimum_amount') is-invalid @enderror"
                  name="minimum_amount" value="{{ old('minimum_amount', round($coupon->minimum_amount)) }}"
                  placeholder="Minimun amount to purchase">
                <div class="fv-plugins-message-container invalid-feedback">
                  @error('minimum_amount')
                    {{ $message }}
                  @enderror
                </div>
                <!--begin::Hint-->
                <div class="form-text required">Minimun Amount to puchase</div>
                <!--end::Hint-->
              </div>
              <div class="col-xl-3">

              </div>
              <div id="maximunDiscountDiv" class="col-xl-3 fv-row fv-plugins-icon-container mt-5"
                style="display: {{ old('discount_type', $coupon->discount_type) === 'percentage' ? 'block' : 'none' }}">
                <input type="number"
                  class="form-control form-control-solid @error('maximum_amount') is-invalid @enderror"
                  name="maximum_amount"
                  value="{{ old('maximum_amount', $coupon->maximum_amount ? round($coupon->maximum_amount) : '') }}"
                  placeholder="Maximum Discount">
                <div class="fv-plugins-message-container invalid-feedback">
                  @error('maximum_amount')
                    {{ $message }}
                  @enderror
                </div>
                <!--begin::Hint-->
                <div class="form-text">Maximum Discount</div>
                <!--end::Hint-->
              </div>
              @php
                $users = \App\Models\User::role('customer')->get();
                $categories = \App\Models\ItemCategory::all();

              @endphp
              <div id="specificUserDiv" class="col-xl-3 fv-row fv-plugins-icon-container mt-5"
                style="display: {{ in_array(old('limit_type', $coupon->limit_type), ['per_user', 'on_order']) ? 'block' : 'none' }}">
                <select id="specificUser" class="form-select @error('user_id') is-invalid @enderror" name="user_id[]"
                  data-control="select2" data-placeholder="Select Users" multiple="multiple">
                  @foreach ($users as $user)
                    <option value="{{ $user->id }}"
                      {{ $coupon->users->contains($user->id) || in_array($user->id, (array) old('user_id', [])) ? 'selected' : '' }}>
                      {{ $user->name }}
                    </option>
                  @endforeach
                </select>
                <div class="fv-plugins-message-container invalid-feedback">
                  @error('user_id')
                    {{ $message }}
                  @enderror
                </div>
                <!--begin::Hint-->
                <div class="form-text">Select Specific Users (Leave empty for all users)</div>
                <!--end::Hint-->
              </div>

              <div class="col-xl-3 fv-row fv-plugins-icon-container mt-5">
                <select class="form-select @error('category_id') is-invalid @enderror" name="category_id[]"
                  data-control="select2" data-placeholder="Select Categories" multiple="multiple" id="categorySelect">
                  @foreach ($categories as $category)
                    <option value="{{ $category->id }}"
                      {{ $coupon->itemCategories->contains($category->id) || in_array($category->id, (array) old('category_id', [])) ? 'selected' : '' }}>
                      {{ $category->name }}
                    </option>
                  @endforeach
                </select>
                <div class="fv-plugins-message-container invalid-feedback">
                  @error('category_id')
                    {{ $message }}
                  @enderror
                </div>
                <!--begin::Hint-->
                <div class="form-text">Select Specific Category (Leave empty for all categories)</div>
                <!--end::Hint-->
              </div>
              <div class="separator separator-dashed border-primary my-10"></div>
            </div>
            <div class="row mb-8">
              <!--begin::Col-->
              <div class="col-xl-3">
                <div class="fs-6 fw-semibold mt-2 mb-3">Extra</div>
              </div>
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <div class="position-relative d-flex align-items-center">
                  <!--begin::Icon-->
                  <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4"><span class="path1"></span><span
                      class="path2"></span><span class="path3"></span><span class="path4"></span><span
                      class="path5"></span><span class="path6"></span></i> <!--end::Icon-->
                  <!--begin::Datepicker-->

                  <input class="form-control form-control-solid ps-12 kt_datepicker_with_time"
                    placeholder="Select a date" name="expire_date" readonly="readonly"
                    value="{{ $coupon->expire_date }}">
                  <!--end::Datepicker-->
                </div>
                <div class="fv-plugins-message-container invalid-feedback">
                  @error('expire_date')
                    {{ $message }}
                  @enderror
                </div>
                <!--begin::Hint-->
                <div class="form-text">Expire Date</div>
                <!--end::Hint-->
              </div>
              @php
                $selectedCategoryIds = $coupon->itemCategories->pluck('id')->toArray();
                if (empty($selectedCategoryIds)) {
                    // Fetch all items if no categories are selected
                    $items = \App\Models\Item::where('approve', 1)->where('status', 1)->get();
                } else {
                    // Fetch items that belong to the selected categories
                    $items = \App\Models\Item::where('approve', 1)
                        ->where('status', 1)
                        ->whereIn('category_id', $selectedCategoryIds)
                        ->get();
                }
              @endphp
              <div class="col-xl-3 fv-row fv-plugins-icon-container">
                <select class="form-select @error('item_id') is-invalid @enderror" name="item_id[]"
                  data-control="select2" data-placeholder="Select Items" multiple="multiple" id="itemSelect">
                  @foreach ($items as $item)
                    <option value="{{ $item->id }}"
                      {{ $coupon->items->contains($item->id) || (is_array(old('item_id')) && in_array($item->id, old('item_id'))) ? 'selected' : '' }}>
                      {{ $item->item_name }} (V. {{ $item->vendor->brand_name }} ₹{{ round($item->price) }})
                    </option>
                  @endforeach
                </select>
                <div class="fv-plugins-message-container invalid-feedback">
                  @error('item_id.*')
                    {{ $message }}
                  @enderror
                </div>
                <!--begin::Hint-->
                <div class="form-text">Select Specific Items(Leave empty for all items)</div>
                <!--end::Hint-->
              </div>
              <div class="separator separator-dashed border-primary my-10"></div>
            </div>
            <!--end::Row-->
            <div class="row">
              <div class="col-md-3 mb-10 mb-md-0">
                <div class="fs-6 fw-semibold mt-2 mb-3">Actions</div>
              </div>
              <div class="col-md-3 mb-10 mb-md-0">
                <div class="form-check form-switch form-check-custom form-check-solid align-items-start">
                  <label class="form-check-label fw-semibold text-gray-400 me-3 mt-2" for="status">Inactive</label>
                  <div class="text-center w-70px">
                    <input class="form-check-input" type="checkbox" value="1" name="status"
                      {{ $coupon->status ? 'checked' : '' }}>
                    <p class="mt-3">Status</p>
                  </div>
                  <label class="form-check-label fw-semibold text-gray-400 ms-3 mt-2" for="status">Active</label>
                </div>
              </div>
              <div class="col-md-3 mb-10 mb-md-0">
                <div class="form-check form-switch form-check-custom form-check-solid align-items-start">
                  <label class="form-check-label fw-semibold text-gray-400 me-3 mt-2" for="status">Inactive</label>
                  <div class="text-center w-70px">
                    <input class="form-check-input" type="checkbox" value="1" name="approved"
                      {{ $coupon->approved ? 'checked' : '' }}>
                    <p class="mt-3">Approve</p>
                  </div>
                  <label class="form-check-label fw-semibold text-gray-400 ms-3 mt-2" for="status">Active</label>
                </div>
              </div>
              <div class="col-md-3 mb-10 mb-md-0">
                <div class="form-check form-switch form-check-custom form-check-solid align-items-start">
                  <label class="form-check-label fw-semibold text-gray-400 me-3 mt-2" for="status">Inactive</label>
                  <div class="text-center w-70px">
                    <input class="form-check-input" type="checkbox" value="1" name="share_discount"
                      {{ $coupon->share_discount ? 'checked' : '' }}>
                    <p class="mt-3">Share Discount</p>
                  </div>
                  <label class="form-check-label fw-semibold text-gray-400 ms-3 mt-2" for="status">Active</label>
                </div>
              </div>
            </div>

          </div>
          <!--end::Card body-->
          <!--begin::Card footer-->
          <div class="card-footer d-flex justify-content-end py-6 px-9">
            <a href="javascript:void(0)" class="" onclick="submitParentForm(this, '#coupon_delete_form')"
              data-kt-ecommerce-order-filter="delete_row"><button type="reset"
                class="btn btn-light btn-active-light-danger me-2">DELETE</button></a>
            <button type="submit" class="btn btn-primary" id="kt_project_settings_submit">Save Changes</button>
          </div>
          <!--end::Card footer-->
          <input type="hidden">
          <div></div>
        </form>
        <!--end:Form-->
      </div>
      <!--end::Custom Content-->
    </div>
    <!--end::Content container-->
  </div>
@endsection
@section('scripts')
  <script>
    $(document).ready(function() {
      // Initially check the selected option on page load
      togglePercentageField();

      // Add change event listener to the select dropdown
      $('#discountType').change(function() {
        togglePercentageField();
      });

      // Function to toggle visibility of percentageField based on selected option
      function togglePercentageField() {
        var selectedOption = $('#discountType').val();
        if (selectedOption === 'percentage') {
          $('#maximunDiscountDiv').css('display', 'block');
        } else {
          $('#maximunDiscountDiv').css('display', 'none');
        }
      }
    });
  </script>
  <script>
    $(document).ready(function() {
      // Initially check the selected option on page load
      toggleSpecificUserField();

      // Add change event listener to the select dropdown
      $('#limitType').change(function() {
        toggleSpecificUserField();
      });

      // Function to toggle visibility of percentageField based on selected option
      function toggleSpecificUserField() {
        var selectedOption = $('#limitType').val();
        if (selectedOption === 'per_user' || selectedOption === 'on_order') {
          $('#specificUserDiv').css('display', 'block');
        } else {
          $('#specificUserDiv').css('display', 'none');
        }
      }
    });
  </script>
  <script>
    $(".kt_datepicker_with_time").flatpickr({
      enableTime: true,
      dateFormat: "Y-m-d H:i",
    });
  </script>
  <script>
    $(document).ready(function() {
      $('#categorySelect').on('change', function() {
        let categoryIds = $(this).val();

        $.ajax({
          url: '/dashboard/items-by-category',
          type: 'GET',
          data: {
            category_ids: categoryIds
          },
          success: function(response) {
            $('#itemSelect').empty();
            response.forEach(function(item) {
              $('#itemSelect').append(new Option(
                `${item.item_name} (V. ${item.vendor.brand_name} ₹${Math.round(item.price)})`,
                item.id
              ));
            });
          }
        });
      });
    });
  </script>
@endsection
