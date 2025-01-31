@extends('layouts.admin.app')
@section('contents')
  @include('pages.finance.admin.toolbars.clients-toolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Products-->
      @if (Session::has('success'))
        <div class="alert alert-success">
          {{ Session::get('success') }}
        </div>
      @endif
      <div class="card card-flush mt-sm-5 mt-20">
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
                class="form-control form-control-solid w-175px w-md-350px ps-12" placeholder="Search Clients" />
            </div>
            <!--end::Search-->
          </div>
          <!--end::Card title-->
          <!--begin::Card toolbar-->
          <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
            <h3 class="card-title align-items-center flex-column">
              <span class="card-label fw-bold fs-3 mb-1">{{ $clients->count() }}</span>

              <span class="text-muted fw-semibold fs-7">Total Clients</span>
            </h3>
          </div>
          <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
          <!--begin::Table-->
          <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_clients_table">
            <thead>
              <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                <th class="w-10px pe-2">
                  <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                      data-kt-check-target="#kt_clients_table .form-check-input" value="1" />
                  </div>
                </th>
                <th class="text-center min-w-175px">Client</th>
                <th class="text-center min-w-200px">Contact</th>
                <th class="text-center min-w-75px">Total Invoice</th>
                <th class="text-center min-w-125px">Total Amount</th>
                <th class="text-center min-w-100px">Billed Under</th>
                <th class="text-end min-w-100px">Actions</th>
              </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
              @foreach ($clients as $client)
                <tr>
                  <td class="text-center">
                    <div class="form-check form-check-sm form-check-custom form-check-solid">
                      <input class="form-check-input" type="checkbox" value="1" />
                    </div>
                  </td>
                  <td class="text-center" data-order="{{ $client->company_name }}">
                    <a href="#" data-client-id="{{ $client->id }}"
                      class="text-gray-900 text-hover-primary fw-bold kt_drawer_client_edit_button">
                      {{ $client->company_name }}
                    </a>
                    <span class="text-muted d-block fw-bold">{{ $client->name }}</span>
                  </td>
                  <td class="text-center" data-order="{{ $client->phone }}">
                    <div class="d-flex align-items-center">
                      <div class="flex-grow-1">
                        <a href="tel:{{ $client->phone }}"
                          class="text-gray-900 fw-bold text-hover-primary fs-6">{{ $client->phone }}</a>
                        <span class="text-muted d-block fw-bold"><a href="mailto:{{ $client->email }}"
                            class="text-gray-900 text-hover-primary fs-7">{{ $client->email }}</a></span>
                      </div>
                    </div>
                  </td>
                  <td class="pe-0 text-center">
                    <span class="fw-bold">{{ $client->invoices->count() }}</span>
                  </td>
                  @php
                    $invoiceTotals = \App\Models\Invoice::selectRaw('status, SUM(total_amount) as total_amount')
                        ->where('bill_to', $client->id)
                        ->groupBy('status')
                        ->get()
                        ->keyBy('status');

                    // Access the totals like this
                    $pendingTotal = $invoiceTotals->get('pending')->total_amount ?? 0;
                    $draftTotal = $invoiceTotals->get('draft')->total_amount ?? 0;
                    $paidTotal = $invoiceTotals->get('paid')->total_amount ?? 0;
                  @endphp
                  <td class="pe-0 text-center">
                    <!--begin::Badges-->
                    <div class="badge badge-light-warning" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                      data-bs-placement="top" title="Pending">₹{{ $pendingTotal }}</div>
                    <!--end::Badges-->
                    <!--begin::Badges-->
                    <div class="badge badge-light-danger" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                      data-bs-placement="top" title="Draft">₹{{ $draftTotal }}</div>
                    <!--end::Badges-->
                    <!--begin::Badges-->
                    <div class="badge badge-light-success" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                      data-bs-placement="top" title="Paid">₹{{ $paidTotal }}</div>
                    <!--end::Badges-->
                  </td>
                  <td class="text-center">
                    <span
                      class="badge {{ $client->billingCategory->color_class ?? '' }}">{{ $client->billingCategory->name ?? '-' }}</span>
                  </td>
                  <td class="text-end">
                    <a href="#" class="btn btn-sm btn-light btn-flex btn-center btn-active-light-primary"
                      data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end">Actions
                      <i class="ki-duotone ki-down fs-5 ms-1"></i></a>
                    <!--begin::Menu-->
                    <div
                      class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-125px py-4"
                      data-kt-menu="true">
                      <!--begin::Menu item-->
                      <div class="menu-item px-3">
                        <a href="" class="menu-link px-3 kt_drawer_client_edit_button"
                          data-client-id="{{ $client->id }}">View</a>
                      </div>
                      <!--end::Menu item-->
                      <!--begin::Menu item-->
                      <div class="menu-item px-3">
                        <a href="" class="menu-link px-3 kt_drawer_client_edit_button"
                          data-client-id="{{ $client->id }}">Edit</a>
                      </div>
                      <!--end::Menu item-->
                      <!--begin::Menu item-->
                      <div class="menu-item px-3">
                        <form action="/dashboard/finance/clients/{{ $client->id }}/delete" method="post">
                          @csrf
                          @method('delete')
                          <a href="javascript:void(0)" class="menu-link px-3" onclick="submitParentForm(this)">Delete</a>
                        </form>
                      </div>
                      <!--end::Menu item-->
                    </div>
                    <!--end::Menu-->
                  </td>
                </tr>
              @endforeach
            </tbody>
          </table>
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
  @include('pages.finance.admin.modules.modals.add-client-modal')
  @include('pages.finance.admin.modules.modals.edit-client-modal')
@endsection
@section('scripts')
  <script>
    var KTAddClient = (function() {
      var modal, form, submitButton, cancelButton, drawer;
      const input = document.querySelector("#phone_modal_add_customer");
      var iti = window.intlTelInput(document.querySelector("#phone_modal_add_client"), {
        utilsScript: utilsScript,
        separateDialCode: true,
        initialCountry: "auto",
        initialCountry: "in",
      });
      return {
        init: function() {
          modal = new bootstrap.Modal(
            document.querySelector("#add_client_modal")
          );
          form = document.querySelector("#kt_modal_add_client_form");
          submitButton = document.querySelector("#kt_add_client_save");
          cancelButton = document.querySelector("#kt_add_client_close");

          // Initialize FormValidation
          var fv = FormValidation.formValidation(form, {
            fields: {
              name: {
                validators: {
                  notEmpty: {
                    message: "Name is required",
                  },
                },
              },
              company_name: {
                validators: {
                  notEmpty: {
                    message: "Company name is required",
                  },
                },
              },
              phone: {
                validators: {
                  callback: {
                    message: "Invalid phone number",
                    callback: function(input) {
                      // Get the phone number value
                      var phoneNumber = input.value;

                      // Validate the phone number format using intl-tel-input
                      if (phoneNumber === '') {
                        return true;
                      }
                      var isValidPhoneNumber = iti.isValidNumber();

                      return isValidPhoneNumber;
                    },
                  },
                },
              },
              email: {
                validators: {
                  regexp: {
                    regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                    message: "The value is not a valid email address",
                  },
                },
              },

            },
            plugins: {
              trigger: new FormValidation.plugins.Trigger(),
              bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: ".fv-row",
                eleInvalidClass: "",
                eleValidClass: "",
              }),
            },
          });

          // Submit form with AJAX
          submitButton.addEventListener("click", function(e) {
            e.preventDefault();
            let phoneNumber = iti.getNumber();
            fv.validate().then(function(isValid) {
              if (isValid === "Valid") {
                // You can customize the AJAX request here
                submitButton.setAttribute("data-kt-indicator", "on");
                submitButton.disabled = true;
                var formData = new FormData(form);

                // Append CSRF token to the headers
                formData.append(
                  "_token",
                  document.querySelector('meta[name="csrf-token"]')
                  .content
                );
                formData.set("phone", phoneNumber);
                $.ajax({
                  url: "/dashboard/finance/clients/store", // Update with your actual API endpoint
                  type: "POST",
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function(response) {
                    if (response.success) {
                      Swal.fire({
                        text: "Form has been successfully submitted!",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                          confirmButton: "btn btn-primary",
                        },
                      }).then(function(result) {
                        if (result.isConfirmed) {
                          modal.hide();
                          form.reset(); // Reset the form after successful submission
                          location.reload();
                        }
                      });
                    }
                  },
                  error: function(error) {
                    // Registration failed, show error messages
                    var errors = error.responseJSON.errors;
                    console.log(errors);
                    var errorMessage = Object.values(errors)
                      .flat()
                      .join("<br>");

                    Swal.fire({
                      html: errorMessage,
                      icon: "error",
                      buttonsStyling: !1,
                      confirmButtonText: "Ok, got it!",
                      customClass: {
                        confirmButton: "btn btn-primary",
                      },
                    });
                  },
                  complete: function() {
                    submitButton.removeAttribute(
                      "data-kt-indicator"
                    );
                    submitButton.disabled = false;
                  },
                });
              } else {
                Swal.fire({
                  text: "Sorry, looks like there are some errors detected, please try again.",
                  icon: "error",
                  buttonsStyling: !1,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                    confirmButton: "btn btn-primary",
                  },
                });
              }
            });
          });
          cancelButton.addEventListener("click", function(t) {
            t.preventDefault(), (form.reset(), modal.hide());
          });
        },
      };
    })();
    KTUtil.onDOMContentLoaded(function() {
      KTAddClient.init();
    });
  </script>
  <script>
    $(document).ready(function() {
      $(document).on('click', '.kt_drawer_client_edit_button', function(e) {
        e.preventDefault();
        var modal = new bootstrap.Modal(
          document.querySelector("#edit_client_modal")
        );
        modal.show();
        var clientId = $(this).data('client-id');
        $.ajax({
          url: '/dashboard/get-client-data',
          type: 'GET',
          data: {
            id: clientId
          },
          success: function(response) {
            $('#edit_client_modal_content').html(response.drawerContent);
          },
          error: function(xhr, status, error) {
            console.error('Error fetching Order data:', xhr);
          }
        });
      });
    });
  </script>
  <script>
    "use strict";
    var KTClientListing = (function() {
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
                l = new Date(moment($(t[2]).text(), "DD/MM/YYYY")),
                u = new Date(moment($(t[2]).text(), "DD/MM/YYYY"));
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
          (e = document.querySelector("#kt_clients_table")) &&
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
                "all" === n && (n = ""), t.column(5).search(n).draw();
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
      KTClientListing.init();
    });
  </script>
@endsection
