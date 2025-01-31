@extends('layouts.admin.app')
@section('contents')
  @include('pages.finance.admin.toolbars.invoice-toolbar')
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
                class="form-control form-control-solid w-175px w-md-350px ps-12" placeholder="Search Invoice" />
            </div>
            <!--end::Search-->
          </div>
          <!--end::Card title-->
          <!--begin::Card toolbar-->
          <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
            <h3 class="card-title align-items-center flex-column">
              <span class="card-label fw-bold fs-3 mb-1">{{ $invoices->count() }}</span>

              <span class="text-muted fw-semibold fs-7">Total Invoices</span>
            </h3>
          </div>
          <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
          <!--begin::Table-->
          <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_invoices_table">
            <thead>
              <tr class="text-center fw-bold fs-7 text-uppercase gs-0">
                <th class="w-10px pe-2">
                  <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                      data-kt-check-target="#kt_invoices_table .form-check-input-checkbox" value="1" />
                  </div>
                </th>
                <th class="min-w-100px">ID</th>
                <th class="min-w-100px text-center">Date</th>
                <th class="min-w-200px text-center">Customer</th>
                <th class="min-w-100px text-center">Tax</th>
                <th class="min-w-100px text-center">Total Amount</th>
                <th class="min-w-175px text-center">Due Date</th>
                <th class="min-w-100px text-center">Status</th>
                <th class="text-end min-w-100px">Actions</th>
              </tr>
            </thead>
            <tbody class="fw-semibold text-gray-600">
              @foreach ($invoices as $invoice)
                <tr>
                  <td>
                    <div class="form-check form-check-sm form-check-custom form-check-solid form-check-input-checkbox">
                      <input class="form-check-input" type="checkbox" value="1" />
                    </div>
                  </td>
                  <td data-order="{{ $invoice->custom_id }}">
                    <a href="/dashboard/finance/invoices/{{ $invoice->id }}" id="kt_drawer_customer_toggle"
                      class="text-gray-900 text-hover-primary fw-bold">INV{{ $invoice->custom_id }}</a>
                    <span class="d-block">
                      <!--begin::Badges-->
                      <div class="badge {{ $invoice->billFrom->color_class }}" data-bs-toggle="tooltip"
                        data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Created For">
                        {{ $invoice->billFrom->name }}</div>
                      <!--end::Badges-->
                      <!--begin::Badges-->
                      @if ($invoice->recurring)
                        <div class="badge badge-light-success" data-bs-toggle="tooltip"
                          data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Recurring">
                          <i class="fa-solid text-gray-900 fa-arrow-rotate-right"></i>
                        </div>
                      @endif
                      <!--end::Badges-->
                    </span>
                  </td>
                  <td class="text-center" data-order="{{ $invoice->date }}">
                    <span
                      class="fw-bold text-gray-900">{{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}</span>
                    <span
                      class="text-muted d-block fw-bold">{{ \Carbon\Carbon::parse($invoice->date)->format('g:iA') }}</span>
                  </td>
                  <td class="text-center" data-order="{{ $invoice->billTo->company_name }}">
                    <span class="d-block fw-bold text-gray-900">{{ $invoice->billTo->company_name }}</span>
                    <span class="d-block fw-bold"><a href="tel:{{ $invoice->billTo->phone }}"
                        class="text-muted fw-bold text-hover-primary fs-7">{{ $invoice->billTo->phone }}</a></span>
                    <span class="d-block fw-bold"><a href="mailto:{{ $invoice->billTo->email }}"
                        class="text-muted fw-bold text-hover-primary fs-7">{{ $invoice->billTo->email }}</a></span>
                  </td>
                  <td class="pe-0 text-center" data-order="{{ $invoice->tax_value }}">
                    @if ($invoice->tax_value > 0)
                      <span class="fw-bold text-gray-900">₹{{ $invoice->tax_value }}</span>
                      <span class="badge badge-light-warning d-block fw-bold">{{ $invoice->tax_rate }}% GST</span>
                    @else
                      <span class="fw-bold text-gray-900">Nill</span>
                    @endif
                  </td>
                  <td class="pe-0 text-center" data-order="{{ $invoice->total_amount }}">
                    @php
                      $totalItems = $invoice->items->count();
                    @endphp
                    <span class="fw-bold text-gray-900">₹{{ number_format($invoice->total_amount, 2) }}</span>
                    <span class="badge badge-light-success d-block fw-bold">{{ $totalItems }} Items</span>
                  </td>
                  <td class="text-center" data-order="{{ $invoice->due_date }}">
                    <span
                      class="fw-bold text-gray-900">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</span>
                  </td>
                  <td class="text-center">
                    <!--begin::Badges-->
                    @php
                      $statusClass = '';
                      $statusText = '';

                      switch ($invoice->status) {
                          case 'paid':
                              $statusClass = 'badge-success';
                              $statusText = 'Paid';
                              break;
                          case 'pending':
                              $statusClass = 'badge-warning';
                              $statusText = 'Pending';
                              break;
                          case 'draft':
                              $statusClass = 'badge-info';
                              $statusText = 'Draft';
                              break;
                          default:
                              $statusClass = 'badge-secondary';
                              $statusText = $invoice->status;
                              break;
                      }
                    @endphp

                    <div class="badge {{ $statusClass }}" data-bs-toggle="tooltip"
                      data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="{{ $statusText }}">
                      {{ $statusText }}
                    </div>
                    <!--end::Badges-->
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
                        <a href="/dashboard/finance/invoices/{{ $invoice->id }}" class="menu-link px-3">View</a>
                      </div>
                      <!--end::Menu item-->
                      <!--begin::Menu item-->
                      <div class="menu-item px-3">
                        <a href="/dashboard/finance/invoices/{{ $invoice->id }}/edit" class="menu-link px-3">Edit</a>
                      </div>
                      <!--end::Menu item-->
                      <!--begin::Menu item-->
                      <div class="menu-item px-3">
                        <form action="/dashboard/finance/invoices/{{ $invoice->id }}" method="post">
                          @csrf
                          @method('delete')
                          <a href="javascript:void(0)" class="menu-link px-3"
                            onclick="submitParentForm(this)">Delete</a>
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
@section('scripts')
  <script>
    "use strict";
    var KTInvocieListing = (function() {
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
          (e = document.querySelector("#kt_invoices_table")) &&
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
                "all" === n && (n = ""), t.column(7).search(n).draw();
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
      KTInvocieListing.init();
    });
  </script>
@endsection
