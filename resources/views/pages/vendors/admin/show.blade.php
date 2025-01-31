@extends('layouts.admin.app')
@section('contents')
  @php
    $lastSegment = request()->segment(count(request()->segments()));
  @endphp
  <style>
    .iti {
      width: 100%;
      display: block;
    }

    .iti__country-name {
      color: #000;
    }

    .iti__search-input {
      background: white;
      color: #000;

    }
  </style>
  <div class="app-main flex-column flex-row-fluid" id="kt_app_main">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">

      <!--begin::Toolbar-->
      @include('pages.vendors.admin.toolbar.showToolbar')
      <!--end::Toolbar-->

      <!--begin::Content-->
      <div id="kt_app_content" class="app-content flex-column-fluid mt-20 mt-lg-0">
        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container  container-xxl ">
          <!--begin::Layout-->
          <div class="d-flex flex-column flex-xl-row">
            <!--begin::Sidebar-->
            <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">

              <!--begin::Card-->
              <div class="card mb-5 mb-xl-8">
                <!--begin::Card body-->
                <div class="card-body pt-15">
                  <!--begin::Summary-->
                  <div class="d-flex flex-center flex-column mb-5">
                    <!--begin::Avatar-->
                    <div class="symbol symbol-150px symbol-circle mb-7">
                      @if ($user->vendor->avatar)
                        <img src="{{ asset($user->vendor->avatar) }}" alt="image">
                      @else
                        <img src="{{ asset('assets/media/svg/avatars/blank-dark.svg') }}" alt="image">
                      @endif
                    </div>
                    <!--end::Avatar-->

                    <!--begin::Name-->
                    <p href="" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">
                      {{ $user->vendor->brand_name }} </p>
                    <!--end::Name-->

                    <!--begin::Email-->
                    <p href="#" class="fs-5 fw-semibold text-muted text-hover-primary mb-6">
                      {{ $user->email }} </p>
                    <!--end::Email-->
                  </div>
                  <!--end::Summary-->

                  <!--begin::Details toggle-->
                  <div class="d-flex flex-stack fs-4 py-3">
                    <div class="fw-bold">
                      Details
                    </div>

                    <!--begin::Badge-->
                    {{-- <div class="badge badge-light-info d-inline">Premium user</div> --}}
                    <!--begin::Badge-->
                  </div>
                  <!--end::Details toggle-->

                  <div class="separator separator-dashed my-3"></div>

                  <!--begin::Details content-->
                  <div class="pb-5 fs-6">
                    <!--begin::Details item-->
                    <div class="fw-bold mt-5">Account ID</div>
                    <div class="text-gray-600">ID-{{ $user->id }}</div>
                    <!--begin::Details item-->
                    <!--begin::Details item-->
                    <div class="fw-bold mt-5">Email Address</div>
                    <div class="text-gray-600"><a href="#"
                        class="text-gray-600 text-hover-primary">{{ $user->email }}</a></div>
                    <!--begin::Details item-->
                    <!--begin::Details item-->
                    <div class="fw-bold mt-5">Contact Address</div>
                    <div class="text-gray-600">Fassi no: {{ $user->vendor->fassi_no }} <br>Stall no:
                      {{ $user->vendor->stall_no }}<br>Contact no: {{ $user->phone }}</div>
                    <!--begin::Details item-->
                    <!--begin::Details item-->
                    <div class="fw-bold mt-5">Owner</div>
                    <div class="text-gray-600">{{ $user->name }}</div>
                    <div class="fw-bold mt-5">Documents</div>
                    <div class="text-gray-600">
                      @if (!empty($user->vendor->documents))
                        <ul>
                          @foreach ($user->vendor->documents as $document)
                            <li>
                              <a href="{{ asset($document['filepath']) }}" target="_blank">
                                {{ $document['filename'] }}
                              </a>
                            </li>
                          @endforeach
                        </ul>
                      @else
                        <p>No documents uploaded.</p>
                      @endif
                    </div>
                    <!--begin::Details item-->
                    <!--begin::Details item-->
                    <div class="fw-bold mt-5">Latest Transaction</div>
                    <div class="text-gray-600"><a href="/dashboard/payouts"
                        class="text-gray-600 text-hover-primary">#{{ $user->vendor->payouts->last()->id ?? null }}</a>
                    </div>
                    <!--begin::Details item-->
                  </div>
                  <!--end::Details content-->
                </div>
                <!--end::Card body-->
              </div>
              <!--end::Card-->
            </div>
            <!--end::Sidebar-->

            <!--begin::Content-->
            <div class="flex-lg-row-fluid ms-lg-15">
              <!--begin:::Tabs-->
              <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8"
                role="tablist">
                <!--begin:::Tab item-->
                <li class="nav-item" role="presentation">
                  <a class="nav-link text-active-primary pb-4 {{ $lastSegment == 'edit' ? '' : 'active' }}"
                    data-bs-toggle="tab" href="#kt_ecommerce_customer_overview" aria-selected="true"
                    role="tab">Overview</a>
                </li>
                <!--end:::Tab item-->
                <li class="nav-item" role="presentation">
                  <a class="nav-link text-active-primary pb-4 {{ $lastSegment == 'orders' ? 'active' : '' }}"
                    data-bs-toggle="tab" href="#kt_ecommerce_vendor_orders" aria-selected="true" role="tab">Orders</a>
                </li>
                <!--begin:::Tab item-->
                <li class="nav-item" role="presentation">
                  <a class="nav-link text-active-primary pb-4  {{ $lastSegment == 'edit' ? 'active' : '' }}"
                    data-bs-toggle="tab" href="#kt_ecommerce_customer_general" aria-selected="false"
                    role="tab">General Settings</a>
                </li>
                <!--end:::Tab item-->
              </ul>
              <!--end:::Tabs-->

              <!--begin:::Tab content-->
              <div class="tab-content" id="myTabContent">
                @if (Session::has('success'))
                  <div class="alert alert-success">
                    {{ Session::get('success') }}
                  </div>
                @endif
                <!--begin:::Tab pane-->
                @include('pages.vendors.admin.components.show.overview')
                <!--end:::Tab pane-->
                @include('pages.vendors.admin.components.show.orders')
                <!--begin:::Tab pane-->
                @include('pages.vendors.admin.components.show.generalSettings')
                <!--end:::Tab pane-->

              </div>
              <!--end:::Tab content-->
            </div>
            <!--end::Content-->
          </div>
          <!--end::Layout-->
        </div>
        <!--end::Content container-->
      </div>
      <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
  </div>
@endsection
@section('modules')
  @include('pages.vendors.admin.modules.drawers.showOrderDrawer')
@endsection
@section('scripts')
  <script>
    var quill = new Quill('#kt_docs_quill_basic', {
      modules: {
        toolbar: true
      },
      placeholder: 'Type your text here...',
      theme: 'snow' // or 'bubble'
    });
    quill.on('text-change', function(delta, oldDelta, source) {
      document.getElementById("quill_html").value = quill.root.innerHTML;
    });
  </script>
  <script>
    $(document).ready(function() {
      const input = document.querySelector("#phone");
      var iti = window.intlTelInput(input, {
        utilsScript: "{{ asset('custom/assets/js/intlTelInput/utils.js') }}",
        separateDialCode: true,
        initialCountry: "auto",
        onlyCountries: ["bd", "in"],
        initialCountry: "bd",
      });
      @if ($errors->has('phone'))
        $('.iti').addClass('is-invalid');
      @endif
      const form = document.querySelector('#kt_ecommerce_vendor_profile');
      const phoneInput = document.querySelector('#phone');

      form.addEventListener('submit', function(event) {
        // Prevent the default form submission behavior
        event.preventDefault();

        // Update the value of the hidden input field with the phone number
        phoneInput.value = iti.getNumber();

        // Now, you can submit the form programmatically
        form.submit();
      });
    })
  </script>
  <script src="{{ asset('assets/js/custom/apps/ecommerce/customers/details/transaction-history.js') }}"></script>
  <script>
    // Add event listener for click on remove document link
    document.addEventListener('DOMContentLoaded', function() {
      const removeLinks = document.querySelectorAll('.remove-document');

      removeLinks.forEach(link => {
        link.addEventListener('click', function(e) {
          e.preventDefault();

          const vendorId = this.getAttribute('data-vendor-id');
          const filePath = this.getAttribute('data-filepath');

          // Show SweetAlert confirmation dialog
          Swal.fire({
            text: 'You are about to remove this document. This action cannot be undone.',
            icon: 'warning',
            showCancelButton: !0,
            buttonsStyling: !1,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, return",
            customClass: {
              confirmButton: "btn btn-danger",
              cancelButton: "btn btn-active-light",
            },
          }).then((result) => {
            if (result.isConfirmed) {
              // If user confirms, perform the removal action via AJAX
              axios.delete(`/dashboard/${vendorId}/remove-document`, {
                  data: {
                    filepath: filePath
                  }
                })
                .then(response => {
                  Swal.fire({
                    text: response.data.message,
                    icon: 'success',
                    confirmButtonText: 'Ok, got it!',
                    customClass: {
                      confirmButton: 'btn btn-primary'
                    }
                  }).then((res) => {
                    location.reload();
                  });
                })
                .catch(error => {
                  console.error('An error occurred:', error);
                  Swal.fire({
                    text: 'Error occurred while removing document. Please try again.',
                    icon: 'error',
                    confirmButtonText: 'Ok, got it!',
                    customClass: {
                      confirmButton: 'btn btn-primary'
                    }
                  });
                });
            }
          });
        });
      });
    });
  </script>
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
              }, ],
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
                "all" === n && (n = ""), t.column(8).search(n).draw();
              });
            })(),
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
    $(document).ready(function() {
      $(document).on('click', '.order-details-drawer', function(e) {
        e.preventDefault();
        var orderId = $(this).data('order-id');
        var vendorId = $(this).data('vendor-id');
        $.ajax({
          url: '/dashboard/get-order-data-for-vendor',
          type: 'GET',
          data: {
            orderId: orderId,
            vendorId: vendorId,
          },
          success: function(response) {
            $('#kt_drawer_order').html(response.drawerContent);
          },
          error: function(xhr, status, error) {
            console.error('Error fetching Order data:', xhr);
          }
        });
      });
    });
  </script>
@endsection
