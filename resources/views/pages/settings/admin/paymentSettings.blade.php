@extends('layouts.admin.app')

@section('contents')
  @include('pages.settings.admin.toolbar.settingsToolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid mt-20 mt-lg-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Custom Content-->
      @if (Session::has('success'))
        <div class="alert alert-success">
          {{ Session::get('success') }}
        </div>
      @endif
      @include('pages.settings.admin.components.payment-settings.payment-info-mode')
      @include('pages.settings.admin.components.payment-settings.invoice-tax-table')
      <!--end::Custom Content-->
    </div>
    <!--end::Content container-->
  </div>
@endsection
@section('modules')
  @include('pages.settings.admin.modules.modals.add-tax-modal')
  @include('pages.settings.admin.modules.modals.edit-tax-modal')
@endsection
@section('scripts')
  <script>
    $("#kt_datepicker_projeact_date").flatpickr();
    KTUtil.onDOMContentLoaded(function() {
      KTAddCustomer.init();
    });
  </script>
  <script>
    // Function to open the modal
    function openModal(modalId) {
      var modal = new bootstrap.Modal(document.querySelector(modalId));
      modal.show();
    }

    // Check for any validation errors
    @if ($errors->any())
      openModal("#kt_modal_add_tax");
    @endif
  </script>
  <script>
    $(document).ready(function() {
      $(document).on('click', '.edit_invoice_tax', function(e) {
        e.preventDefault();

        var taxId = $(this).data('tax-id');

        $.ajax({
          url: '/dashboard/settings/taxes/' + taxId + '/edit', // Updated URL to use RESTful route
          type: 'GET',
          success: function(response) {
            $('#edit_tax_modal_body').html(response.drawerContent);
          },
          error: function(xhr, status, error) {
            var errorMessage = 'Error fetching play area data.';
            if (xhr.status === 404) {
              errorMessage = 'Play area not found.';
            }
            console.error(errorMessage);
            Swal.fire({
              text: errorMessage,
              icon: 'error',
              buttonsStyling: false,
              confirmButtonText: 'Ok, got it!',
              customClass: {
                confirmButton: 'btn btn-primary',
              },
            });
          }
        });
      });
    });
  </script>
  <script>
    "use strict";
    var KTPermissionListing = (function() {
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
          (e = document.querySelector("#invoice_tax_table")) &&
          ((t = $(e).DataTable({
              info: !1,
              order: [],
              pageLength: 10,
              columnDefs: [{
                orderable: !1,
                targets: 0
              }, {
                orderable: !1,
                targets: 3
              }],
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
      KTPermissionListing.init();
    });
  </script>
@endsection
