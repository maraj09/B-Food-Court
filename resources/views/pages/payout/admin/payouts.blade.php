@extends('layouts.admin.app')
@section('contents')
  @include('pages.payout.admin.toolbar.payoutsToolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid mt-20 mt-lg-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Products-->
      @if (Session::has('success'))
        <div class="alert alert-success">
          {{ Session::get('success') }}
        </div>
      @endif
      <div class="card card-flush">
        <!--begin::Card header-->
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
          <!--begin::Card title-->
          <div class="card-title">
            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative my-1">
              <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
              <input type="text" data-kt-ecommerce-order-filter="search"
                class="form-control form-control-solid w-175px w-md-350px ps-12" placeholder="Search Payouts" />

            </div>
            <!--end::Search-->
          </div>
          <!--end::Card title-->
          <!--begin::Card toolbar-->
          <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
            <h3 class="card-title align-items-center flex-column">
              <span class="card-label fw-bold fs-3 mb-1">{{ $payouts->count() }}</span>

              <span class="text-muted fw-semibold fs-7">Total Payouts</span>
            </h3>
          </div>
          <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
          <!--begin::Table-->
          <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="w-10px pe-2">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                      <input class="form-check-input" type="checkbox" data-kt-check="true"
                        data-kt-check-target="#kt_ecommerce_sales_table .form-check-input" value="1" />
                    </div>
                  </th>
                  <th class="min-w-175px">Vendor</th>
                  <th class="text-center min-w-75px">Amount</th>
                  <th class="text-center min-w-175px">Transaction ID</th>
                  <th class="text-center min-w-75px">Date</th>
                  <th class="text-center min-w-100px">Status</th>
                  <th class="text-center min-w-100px">Payment Image</th>
                  <th class="text-center min-w-100px">Remark</th>
                  <th class="text-center min-w-100px">Action</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
                @foreach ($payouts as $payout)
                  @php
                    $pendingAmount = \App\Models\Payout::where('vendor_id', $payout->vendor_id)
                        ->where('status', '!=', 'transferred')
                        ->sum('request_amount');
                  @endphp
                  <tr>
                    <td>
                      <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" />
                      </div>
                    </td>
                    <td data-kt-ecommerce-order-filter="order_id">
                      <div class="d-flex align-items-center">
                        <!--begin:: Avatar -->
                        <div class="symbol symbol-circle symbol-35px overflow-hidden">
                          <a href="/dashboard/vendors/{{ $payout->vendor->user_id }}">
                            @if ($payout->vendor->avatar)
                              <img src="{{ asset($payout->vendor->avatar) }}" width="35px" />
                            @else
                              <img src="{{ asset('assets/media/svg/avatars/blank-dark.svg') }}" width="35px"
                                height="35px" />
                            @endif
                          </a>
                        </div>
                        <!--end::Avatar-->
                        <div class="ms-2">
                          <!--begin::Title-->
                          <a href="/dashboard/vendors/{{ $payout->vendor->user_id }}"
                            class="text-gray-800 text-hover-primary fs-6 fw-bold">{{ $payout->vendor->brand_name }}</a>
                          <!--end::Title-->
                        </div>
                      </div>
                    </td>
                    <td class="pe-0 text-center" data-order="{{ $payout->request_amount }}">
                      <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                          <span class="badge badge-danger fw-bold fs-6">₹{{ $payout->request_amount }}</span>
                          <div class="fs-6 text-gray-800 d-block" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Request Date">
                            {{ \Carbon\Carbon::parse($payout->created_at)->format('d-m-Y') }}
                          </div>
                        </div>
                      </div>
                    </td>
                    <td class="pe-0 text-center" data-order="{{ $payout->payment_mode }}">
                      <span class="fw-bold">{{ $payout->transaction_id ? $payout->transaction_id : '-' }}</span>
                      @if ($payout->payment_mode == 'upi')
                        <div class="badge badge-light-primary fw-bold fs-6 d-block" data-bs-toggle="tooltip"
                          data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title=" Payment Mode">UPI</div>
                      @elseif ($payout->payment_mode == 'cash')
                        <div class="badge badge-light-success fw-bold fs-6 d-block" data-bs-toggle="tooltip"
                          data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title=" Payment Mode">Cash</div>
                      @elseif ($payout->payment_mode == 'cheque')
                        <div class="badge badge-light-warning fw-bold fs-6 d-block" data-bs-toggle="tooltip"
                          data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title=" Payment Mode">Cheque
                        </div>
                      @elseif ($payout->payment_mode == 'bank_transfer')
                        <div class="badge badge-light-info fw-bold fs-6 d-block" data-bs-toggle="tooltip"
                          data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title=" Payment Mode">Bank
                          Transfer
                        </div>
                      @else
                        <div>
                          -
                        </div>
                      @endif
                    </td>
                    <td class="pe-0 text-center" data-order="{{ $payout->date }}">
                      <span
                        class="fw-bold text-success">{{ \Carbon\Carbon::parse($payout->date)->format('d-m-Y') }}</span>
                    </td>
                    <td class="text-center">
                      @if ($payout->status == 'transferred')
                        <div class="badge badge-light-success fw-bold fs-6">Transferred</div>
                      @elseif ($payout->status == 'hold')
                        <div class="badge badge-light-danger fw-bold fs-6">On Hold</div>
                      @elseif ($payout->status == 'progress')
                        <div class="badge badge-light-primary fw-bold fs-6">In Progress</div>
                      @else
                        <div class="badge badge-light-warning fw-bold fs-6">Pending</div>
                      @endif
                    </td>
                    <td class="text-center">
                      @if ($payout->payment_image)
                        <div class="symbol symbol-50px symbol-2by3">
                          <img src="{{ asset($payout->payment_image) }}" alt="" />
                        </div>
                      @else
                        -
                      @endif
                    </td>
                    <td class="text-center">
                      <span class="fs-8">{{ $payout->remark ? $payout->remark : '-' }}</span>
                    </td>
                    <td class="text-center">
                      <button class="btn btn-active-info" data-pending-amount="{{ $pendingAmount }}"
                        data-amount="{{ $payout->request_amount }}" data-remark="{{ $payout->remark }}"
                        data-image="{{ $payout->payment_image }}"
                        data-amount-balance="{{ $payout->vendor->vendorBank->balance }}"
                        data-payout-id="{{ $payout->id }}" data-vendor-id='{{ $payout->vendor_id }}'
                        data-payout-status='{{ $payout->status }}' data-payout-date='{{ $payout->date }}'
                        data-payout-transaction-id ='{{ $payout->transaction_id }}'
                        data-payout-payment-method ='{{ $payout->payment_mode }}'
                        data-vendor-amount-paid ='{{ $payout->vendor->vendorBank->amount_paid }}'
                        id="kt_add_payout_toggle">
                        Edit
                      </button>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!--end::Table-->
        </div>
        <!--end::Card body-->
      </div>
      <!--end::Products-->
    </div>
    <!--end::Content container-->
  </div>
@endsection
@section('modules')
  @include('pages.payout.admin.modules.drawers.addPayoutDrawer')
@endsection
@section('scripts')
  <script>
    "use strict";
    var KTAppEcommerceSalesListing = (function() {
      var e,
        t,
        n,
        r,
        o,
        a = (e, n, a) => {
          (r = e[0] ? new Date(e[0]) : null),
          (o = e[1] ? new Date(e[1]) : null),
          $.fn.dataTable.ext.search.push(function(e, t, n) {
              var a = r,
                c = o,
                l = new Date(moment($(t[4]).text(), "DD/MM/YYYY")),
                u = new Date(moment($(t[4]).text(), "DD/MM/YYYY"));
              return (
                (null === a && null === c) ||
                (null === a && c >= u) ||
                (a <= l && null === c) ||
                (a <= l && c >= u)
              );
            }),
            t.draw();
        };
      return {
        init: function() {
          (e = document.querySelector("#kt_ecommerce_sales_table")) &&
          ((t = $(e).DataTable({
              info: !1,
              order: [],
              pageLength: 10,
              columnDefs: [{
                  orderable: !1,
                  targets: 0
                },
                {
                  orderable: !1,
                  targets: 8
                },
                {
                  orderable: !1,
                  targets: 6
                },
              ],
            })).on("draw", function() {

            }),
            (() => {
              const e = document.querySelector("#kt_ecommerce_sales_flatpickr");
              n = $(e).flatpickr({
                altInput: !0,
                altFormat: "d/m/Y",
                dateFormat: "Y-m-d",
                mode: "range",
                onChange: function(e, t, n) {
                  a(e, t, n);
                },
              });
            })(),
            document
            .querySelector('[data-kt-ecommerce-order-filter="search"]')
            .addEventListener("keyup", function(e) {
              t.search(e.target.value).draw();
            }),
            (() => {
              const e = document.querySelector(
                '[data-kt-ecommerce-order-filter="status"]'
              );
              $(e).on("change", (e) => {
                let n = e.target.value;
                "all" === n && (n = ""), t.column(3).search(n).draw();
              });
            })(),
            (() => {
              const e = document.querySelector(
                '[data-kt-ecommerce-order-filter="vendor"]'
              );
              $(e).on("change", (e) => {
                let n = e.target.value;
                "all" === n && (n = ""), t.column(1).search(n).draw();
              });
            })(),
            (() => {
              const e = document.querySelector(
                '[data-kt-ecommerce-order-filter="condition"]'
              );
              $(e).on("change", (e) => {
                let n = e.target.value;
                "all" === n && (n = ""), t.column(5).search(n).draw();
              });
            })(),
            document
            .querySelector("#kt_ecommerce_sales_flatpickr_clear_all")
            .addEventListener("click", (e) => {
              // Clear date range filter
              n.clear();

              // Clear status filter
              const statusFilter = document.querySelector('[data-kt-ecommerce-order-filter="status"]');
              statusFilter.value = 'all';
              $(statusFilter).trigger('change');
              t.column(3).search('').draw();

              const vendorFilter = document.querySelector('[data-kt-ecommerce-order-filter="vendor"]');
              vendorFilter.value = 'all';
              $(vendorFilter).trigger('change');
              t.column(1).search('').draw();

              const conditionFilter = document.querySelector('[data-kt-ecommerce-order-filter="condition"]');
              conditionFilter.value = 'all';
              $(conditionFilter).trigger('change');
              t.column(5).search('').draw();

              // Remove any custom search function
              $.fn.dataTable.ext.search = [];
              t.draw();
            }),
            document
            .querySelector("#kt_ecommerce_sales_flatpickr_clear")
            .addEventListener("click", (e) => {
              n.clear();
            }));
        },
      };
    })();
    KTUtil.onDOMContentLoaded(function() {
      KTAppEcommerceSalesListing.init();
    });
  </script>
  <script>
    // Get a reference to the form
    var form = document.getElementById('payout_form');

    // Get a reference to the button
    var button = document.querySelector('#payout_form_submit_btn');

    // Add event listener to the button
    button.addEventListener('click', function(event) {
      // Prevent the default form submission behavior
      event.preventDefault();
      button.setAttribute("data-kt-indicator", "on");
      button.disabled = true;

      // Submit the form using JavaScript
      form.submit();
    });

    @if ($errors->any())
      document.addEventListener('DOMContentLoaded', function() {
        // Trigger the drawer to open on page load
        const cartDrawer = document.getElementById('kt_add_payout');
        if (cartDrawer) {
          // Check if the cart drawer element exists
          const drawer = KTDrawer.getInstance(cartDrawer);

          drawer.show(); // Open the drawer
        }
      });
    @endif

    var KTAddItem = (function() {
      var modal, drawer;

      return {
        init: function() {
          modal = document.querySelector("#kt_add_payout");
          drawer = KTDrawer.getInstance(modal);

          document.querySelectorAll(".btn-active-info").forEach(function(button) {
            button.addEventListener("click", function(e) {
              e.preventDefault();
              // Retrieve the payout data from the button's data attributes
              var amount = e.target.dataset.amount;
              var remark = e.target.dataset.remark;
              var image = e.target.dataset.image;
              var id = e.target.dataset.payoutId;
              $('#balance_amount').html('₹' + e.target.dataset.amountBalance);
              $('#amount_paid').html('₹' + e.target.dataset.vendorAmountPaid);
              $('#pending_amount').html('₹' + e.target.dataset.pendingAmount);
              $('.text-danger').html('');

              // Update the drawer fields with the payout data
              document.querySelector("input[name='request_amount']").value = amount;
              document.querySelector("input[name='payout_id']").value = id;
              document.querySelector("textarea[name='remark']").value = remark;
              document.querySelector("input[name='transaction_id']").value = e.target.dataset
                .payoutTransactionId;
              document.querySelector("input[name='date']").value = e.target.dataset.payoutDate;

              // Select the <select> elements
              var vendorSelect = document.querySelector("select[name='vendorId']");
              var statusSelect = document.querySelector("select[name='status']");
              var paymentModeSelect = document.querySelector("select[name='payment_mode']");

              // Set the selected options based on dataset attributes
              vendorSelect.value = e.target.dataset.vendorId;
              statusSelect.value = e.target.dataset.payoutStatus;
              paymentModeSelect.value = e.target.dataset.payoutPaymentMethod;

              vendorSelect.querySelectorAll("option").forEach(function(option) {
                if (option.value === e.target.dataset.vendorId) {
                  option.setAttribute('selected', 'selected');
                }
              });
              $(vendorSelect).trigger('change.select2');

              // Set the selected option for status
              statusSelect.querySelectorAll("option").forEach(function(option) {
                if (option.value === e.target.dataset.payoutStatus) {
                  option.setAttribute('selected', 'selected');
                }
              });
              $(statusSelect).trigger('change.select2');

              // Set the selected option for payment_mode
              paymentModeSelect.querySelectorAll("option").forEach(function(option) {
                if (option.value === e.target.dataset.payoutPaymentMethod) {
                  option.setAttribute('selected', 'selected');
                }
              });
              $(paymentModeSelect).trigger('change.select2');


              // Display the image in the drawer (if available)
              var imagePreview = document.querySelector(".image-input-wrapper");

              imagePreview.style.backgroundImage = `url({{ asset('${image}') }})`;

              payoutMode = e.target.dataset.payoutId ? 'edit' : 'create';

              // Update the hidden input with the mode
              document.querySelector("#payout_mode").value = payoutMode;

              // Update the drawer title based on the mode
              var drawerTitle = payoutMode === 'edit' ? 'Edit Payout' : 'New Payout';
              document.querySelector("#kt_drawer_order_toggle").innerText = drawerTitle;

              // Show the drawer
              var drawerElement = document.getElementById("kt_add_payout");
              drawerElement.classList.add("show");

              // Optional: Add overlay if needed
              var overlayElement = document.querySelector(".modal-backdrop");
              if (overlayElement) {
                overlayElement.classList.add("show");
              }
            });
          });
        },
      };
    })();
  </script>

  <script>
    KTUtil.onDOMContentLoaded(function() {
      KTAddItem.init();
    });
  </script>

  <script>
    $(document).ready(function() {
      // Attach change event listener to the select element
      $('select[name="vendorId"]').change(function() {
        // Get the selected vendor ID
        var vendorId = $(this).val();

        // Send Ajax request to the controller
        $.ajax({
          url: '{{ route('vendor.getBank') }}',
          method: 'POST',
          data: {
            vendor_id: vendorId,
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            // Update the bank information on the page
            $('#balance_amount').html('₹' + response.bankInfo.balance);
            $('#amount_paid').html('₹' + response.bankInfo.amount_paid);
            $('#pending_amount').html('₹' + parseFloat(response.pendingAmount).toFixed(2));
          },
          error: function(xhr, status, error) {
            console.error('Error fetching bank information:', xhr);
          }
        });
      });
    });
  </script>
  <script>
    const toggleButton = document.getElementById('kt_add_payout_toggle');
    const drawerForm = document.querySelector('#payout_form'); // Replace with your form's actual selector

    toggleButton.addEventListener('click', (e) => {
      if (drawerForm && document.querySelector("#payout_mode").value == 'edit') {

        drawerForm.reset(); // Reset the form using the reset() method
        $('#balance_amount').html('₹' + '0.00');
        $('#amount_paid').html('₹' + '0.00');
        $('#pending_amount').html('₹' + '0.00');
        var vendorSelect = document.querySelector("select[name='vendorId']");
        var statusSelect = document.querySelector("select[name='status']");
        var paymentModeSelect = document.querySelector("select[name='payment_mode']");


        vendorSelect.value = '';
        vendorSelect.querySelectorAll("option").forEach(function(option) {
          if (option.value === '') {
            option.setAttribute('selected', 'selected');
          }
        });
        $(vendorSelect).trigger('change.select2');

        statusSelect.value = '';
        statusSelect.querySelectorAll("option").forEach(function(option) {
          if (option.value === '') {
            option.setAttribute('selected', 'selected');
          }
        });
        $(statusSelect).trigger('change.select2');

        paymentModeSelect.value = '';
        paymentModeSelect.querySelectorAll("option").forEach(function(option) {
          if (option.value === '') {
            option.setAttribute('selected', 'selected');
          }
        });
        $(paymentModeSelect).trigger('change.select2');

        payoutMode = e.target.dataset.payoutId ? 'edit' : 'create';

        // Update the hidden input with the mode
        document.querySelector("#payout_mode").value = payoutMode;
        // Update the drawer title based on the mode
        var drawerTitle = payoutMode === 'edit' ? 'Edit Payout' : 'New Payout';
        document.querySelector("#kt_drawer_order_toggle").innerText = drawerTitle;
        var imagePreview = document.querySelector(".image-input-wrapper");

        imagePreview.style.backgroundImage = `url({{ asset('/assets/media/svg/files/upload.svg') }})`;
      }
    });
    $("#kt_datepicker_dob_custom").flatpickr({
      disableMobile: true
    });
  </script>
@endsection
