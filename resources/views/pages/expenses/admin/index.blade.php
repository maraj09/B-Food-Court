@extends('layouts.admin.app')
@section('contents')
  @include('pages.expenses.admin.toolbar.indexToolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid mt-20 mt-lg-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      @if (Session::has('success'))
        <div class="alert alert-success">
          {{ Session::get('success') }}
        </div>
      @endif
      @include('pages.expenses.admin.components.dashboard')
      <!--begin::Products-->
      <div class="card card-flush mt-5">
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
                class="form-control form-control-solid w-175px w-md-350px ps-12" placeholder="Search Expenses" />
            </div>
            <!--end::Search-->
          </div>
          <!--end::Card title-->
          <!--begin::Card toolbar-->
          <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
            <h3 class="card-title align-items-center flex-column">
              <span class="card-label fw-bold fs-3 mb-1">{{ $expenses->count() }}</span>
              <span class="text-muted fw-semibold fs-7">Total Expence</span>
            </h3>
            <button type="button" class="btn btn-light-primary" data-kt-menu-trigger="click"
              data-kt-menu-placement="bottom-end">
              <i class="ki-duotone ki-exit-down fs-2"><span class="path1"></span><span class="path2"></span></i>
              Export Report
            </button>
            <!--begin::Menu-->
            <div id="kt_datatable_example_export_menu"
              class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-semibold fs-7 w-200px py-4"
              data-kt-menu="true">
              <!--begin::Menu item-->
              <div class="menu-item px-3">
                <a href="#" class="menu-link px-3" data-kt-export="copy">
                  Copy to clipboard
                </a>
              </div>
              <!--end::Menu item-->
              <!--begin::Menu item-->
              <div class="menu-item px-3">
                <a href="/dashboard/expenses/export/excel" class="menu-link px-3">
                  Export as Excel
                </a>
              </div>
              <!--end::Menu item-->
              <!--begin::Menu item-->
              <div class="menu-item px-3">
                <a href="/dashboard/expenses/export/csv" class="menu-link px-3">
                  Export as CSV
                </a>
              </div>
              <!--end::Menu item-->
              <!--begin::Menu item-->
              <div class="menu-item px-3">
                <a href="/dashboard/expenses/export/pdf" class="menu-link px-3">
                  Export as PDF
                </a>
              </div>
              <!--end::Menu item-->
            </div>
            <div id="kt_datatable_example_buttons" class="d-none"></div>
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
                  <th class="min-w-75px">Expense ID</th>
                  <th class="min-w-175px">Title</th>
                  <th class="min-w-200px">Amount</th>
                  <th class="min-w-175px">Category</th>
                  <th class="min-w-100px">Details</th>
                  <th class="text-center min-w-100px">Date</th>
                  <th class="text-center min-w-100px">Payment Mode</th>
                  <th class="text-end min-w-100px">Actions</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
                @foreach ($expenses as $expense)
                  <tr>
                    <td>
                      <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" />
                      </div>
                    </td>
                    <td data-kt-ecommerce-order-filter="order_id" data-order="{{ $expense->id }}">
                      <a data-expense-id="{{ $expense->id }}"
                        class="text-gray-800 text-hover-primary fw-bold kt_drawer_expence_edit_button">#{{ $loop->index + 1 }}</a>
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="flex-grow-1">
                          <a href="#"
                            class="text-gray-900 fw-bold text-hover-primary fs-6 d-block kt_drawer_expence_edit_button"
                            data-expense-id="{{ $expense->id }}">{{ $expense->title }}</a>
                          @if ($expense->tags != null)
                            @php
                              $tags = json_decode($expense->tags, true);
                            @endphp

                            @if (is_array($tags))
                              @foreach ($tags as $tag)
                                <span class="badge badge-sm badge-light-secondary">{{ $tag['value'] }}</span>
                              @endforeach
                            @endif
                          @endif
                        </div>
                      </div>
                    </td>
                    <td data-order="{{ $expense->amount }}" class="pe-0 text-danger fs-5">
                      ₹{{ $expense->amount }}
                    </td>
                    <td>
                      <div class="d-flex align-items-center">
                        <div class="ms-2">
                          <!--begin::Title-->
                          @php
                            $bgColors = [
                                'badge-light-primary',
                                'badge-light-danger',
                                'badge-light-info',
                                'badge-light-success',
                                'badge-light-warning',
                                'badge-light-dark',
                            ];
                            $colorIndex = $expense->expense_category_id % count($bgColors);
                          @endphp
                          <span class="badge {{ $bgColors[$colorIndex] }}">{{ $expense->expenseCategory->name }}</span>
                        </div>
                      </div>
                    </td>
                    <td class="pe-0">
                      <span class="fs-7 text-gray-600">{{ $expense->details }}</span>
                    </td>
                    <td class="text-center" data-order="{{ $expense->created_at }}">
                      <span class="fw-bold">{{ \Carbon\Carbon::parse($expense->created_at)->format('d-m-Y') }}</span>
                    </td>
                    <td class="text-center" data-order="{{ $expense->payment_mode }}">
                      @if ($expense->payment_mode == 'UPI')
                        <span class="badge badge-primary">UPI</span>
                      @elseif ($expense->payment_mode == 'Cash')
                        <span class="badge badge-success">Cash</span>
                      @elseif ($expense->payment_mode == 'Net Banking')
                        <span class="badge badge-warning">Net Banking</span>
                      @elseif ($expense->payment_mode == 'Cheque')
                        <span class="badge badge-info">Cheque</span>
                      @endif
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
                          <a href="" class="menu-link px-3 kt_drawer_expence_edit_button"
                            data-expense-id="{{ $expense->id }}">View</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                          <a href="" class="menu-link px-3 kt_drawer_expence_edit_button"
                            data-expense-id="{{ $expense->id }}">Edit</a>
                        </div>
                        <!--end::Menu item-->
                        <!--begin::Menu item-->
                        <div class="menu-item px-3">
                          <form action="/dashboard/expenses/{{ $expense->id }}/delete" method="post">
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
              <tfoot>
                <tr class="fw-bold fs-6">
                  <th colspan="3" class="text-nowrap align-end">Total:</th>
                  <th colspan="2" class="text-danger fs-3"></th>
                </tr>
              </tfoot>
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
  @include('pages.expenses.admin.modules.drawers.addExpenseDrawer')
  @include('pages.expenses.admin.modules.drawers.editExpenseDrawer')
@endsection
@section('scripts')
  <script>
    Dropzone.autoDiscover = false;
    let token = $('meta[name="csrf-token"]').attr('content');
    Dropzone.options.uploadForm = new Dropzone("#expense-dropzone", {
      url: "/dashboard/expenses/store", // Temporary URL for initial upload
      paramName: "images",
      addRemoveLinks: true,
      autoProcessQueue: false,
      uploadMultiple: false,
      parallelUploads: 10,
      uploadMultiple: true,
      maxFiles: null, // Unlimited files
      maxFilesize: 2, // Max file size in MB
      acceptedFiles: "image/jpeg,image/png,image/jpg,image/gif,image/svg",
      params: {
        _token: token
      },
      resizeWidth: null,
      resizeHeight: null,
      resizeMimeType: null,
      resizeQuality: 1.0,
      init: function() {
        var submitButton = document.querySelector("#expense-form button[type=submit]");
        var myDropzone = this;

        submitButton.addEventListener("click", function(e) {
          e.preventDefault();
          e.stopPropagation();
          submitButton.setAttribute('data-kt-indicator', 'on');
          submitButton.disabled = true;

          if (myDropzone.getQueuedFiles().length > 0) {
            myDropzone.processQueue();
          } else {
            document.getElementById("expense-form").submit();
          }
        });

        this.on("sendingmultiple", function(data, xhr, formData) {
          var formElements = document.querySelector("#expense-form").elements;
          for (var i = 0; i < formElements.length; i++) {
            if (formElements[i].name) {
              formData.append(formElements[i].name, formElements[i].value);
            }
          }
        });

        this.on("successmultiple", function(files, response) {
          window.location.href = "/dashboard/expenses"
        });

        this.on("errormultiple", function(files, response) {
          var errorMessage = Object.values(response.errors)
            .flat()
            .join("<br>");
          myDropzone.removeAllFiles(true);
          Swal.fire({
            html: errorMessage,
            icon: "error",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            customClass: {
              confirmButton: "btn btn-primary",
            },
          });
          submitButton.setAttribute('data-kt-indicator', 'off');
          submitButton.disabled = false;
        });
      }
    });
  </script>
  <script>
    $(document).ready(function() {
      var input1 = document.querySelector("#kt_tagify_3");
      new Tagify(input1, {
        whitelist: {!! json_encode($allTags) !!},
        maxTags: 10,
        dropdown: {
          maxItems: 20, // maximum allowed rendered suggestions
          classname: "tagify__inline__suggestions", // custom classname for this dropdown, so it could be targeted
          enabled: 0, // show suggestions on focus
          closeOnSelect: false // do not hide the suggestions dropdown once an item has been selected
        }
      });
    });
  </script>
  <script>
    "use strict";

    var KTAppEcommerceSalesListing = (function() {
      var e, t, n, r, o;

      var filterDateRange = (e, n, a) => {
        (r = e[0] ? new Date(e[0]) : null),
        (o = e[1] ? new Date(e[1]) : null),
        $.fn.dataTable.ext.search.push(function(e, t, n) {
            var a = r,
              c = o,
              l = new Date(moment($(t[6]).text(), "DD/MM/YYYY")),
              u = new Date(moment($(t[6]).text(), "DD/MM/YYYY"));
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
          e = document.querySelector("#kt_ecommerce_sales_table");
          if (e) {
            t = $(e).DataTable({
              info: false,
              order: [],
              pageLength: 10,
              columnDefs: [{
                  orderable: false,
                  targets: 0
                },
                {
                  orderable: false,
                  targets: 8
                }
              ],

              footerCallback: function(row, data, start, end, display) {
                var api = this.api();

                // Remove the formatting to get integer data for summation
                var intVal = function(i) {
                  return typeof i === "string" ?
                    i.replace(/[\₹,]/g, "") * 1 :
                    typeof i === "number" ?
                    i : 0;
                };

                // Total over all pages
                var total = api
                  .column(3)
                  .data()
                  .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                  }, 0);

                // Total over this page
                var pageTotal = api
                  .column(3, {
                    page: "current"
                  })
                  .data()
                  .reduce(function(a, b) {
                    return intVal(a) + intVal(b);
                  }, 0);

                // Update footer
                $(api.column(3).footer()).html(
                  "₹" + pageTotal.toLocaleString() + " ( ₹" + total.toLocaleString() + " total)"
                );
              }
            });
            (() => {
              const documentTitle = 'Expenses Report';
              var buttons = new $.fn.dataTable.Buttons(e, {
                buttons: [{
                  extend: 'copyHtml5',
                  title: documentTitle,
                  exportOptions: {
                    columns: ':not(:eq(8))' // Skip the first column (index 0)
                  }
                }, ]
              }).container().appendTo($('#kt_datatable_example_buttons'))
            })();
            // Date range filter
            (() => {
              const e = document.querySelector("#kt_ecommerce_sales_flatpickr");
              n = $(e).flatpickr({
                altInput: true,
                altFormat: "d/m/Y",
                dateFormat: "Y-m-d",
                mode: "range",
                onChange: function(e, t, n) {
                  filterDateRange(e, t, n);
                }
              });
            })();

            // Search filter
            document
              .querySelector('[data-kt-ecommerce-order-filter="search"]')
              .addEventListener("keyup", function(e) {
                t.search(e.target.value).draw();
              });

            // Status filter
            (() => {
              const e = document.querySelector('[data-kt-ecommerce-order-filter="status"]');
              $(e).on("change", (e) => {
                let n = e.target.value;
                if (n === "all") n = "";
                t.column(7).search(n).draw();
              });
            })();

            // Category filter
            (() => {
              const e = document.querySelector('[data-kt-ecommerce-order-filter="category"]');
              $(e).on("change", (e) => {
                let n = e.target.value;
                if (n === "all") n = "";
                t.column(4).search(n).draw();
              });
            })();

            // Tags filter
            (() => {
              const e = document.querySelector('[data-kt-ecommerce-order-filter="tags"]');
              $(e).on("change", (e) => {
                let n = e.target.value;
                if (n === "all") n = "";
                t.column(2).search(n).draw();
              });
            })();

            // Clear all filters
            document.querySelector("#kt_ecommerce_sales_flatpickr_clear_all").addEventListener("click", (e) => {
              // Clear date range filter
              n.clear();

              // Clear status filter
              const statusFilter = document.querySelector('[data-kt-ecommerce-order-filter="status"]');
              statusFilter.value = 'all';
              $(statusFilter).trigger('change');
              t.column(7).search('').draw();

              const categoryFilter = document.querySelector('[data-kt-ecommerce-order-filter="category"]');
              categoryFilter.value = 'all';
              $(categoryFilter).trigger('change');
              t.column(4).search('').draw();

              const tagsFilter = document.querySelector('[data-kt-ecommerce-order-filter="tags"]');
              tagsFilter.value = 'all';
              $(tagsFilter).trigger('change');
              t.column(2).search('').draw();

              // Remove any custom search function
              $.fn.dataTable.ext.search = [];
              t.draw();
            });

            // Clear date range filter
            document.querySelector("#kt_ecommerce_sales_flatpickr_clear").addEventListener("click", (e) => {
              n.clear();
            });

            // Hook dropdown menu click event to datatable export buttons
            (() => {
              const exportButtons = document.querySelectorAll(
                '#kt_datatable_example_export_menu [data-kt-export]');
              exportButtons.forEach(exportButton => {
                exportButton.addEventListener('click', e => {
                  e.preventDefault();
                  const exportValue = e.target.getAttribute('data-kt-export');
                  const target = document.querySelector('.dt-buttons .buttons-' + exportValue);
                  target.click();
                });
              });
            })();
          }
        }
      };
    })();

    KTUtil.onDOMContentLoaded(function() {
      KTAppEcommerceSalesListing.init();
    });
  </script>
  <script>
    @if ($errors->any())
      document.addEventListener('DOMContentLoaded', function() {
        // Trigger the drawer to open on page load
        const cartDrawer = document.getElementById('kt_drawer_expence');
        if (cartDrawer) {
          // Check if the cart drawer element exists
          const drawer = KTDrawer.getInstance(cartDrawer);

          drawer.show(); // Open the drawer
        }
      });
    @endif
  </script>
  <script>
    $(document).ready(function() {
      $(document).on('click', '.kt_drawer_expence_edit_button', function(e) {
        e.preventDefault();

        var expenseId = $(this).data('expense-id');
        $.ajax({
          url: '/dashboard/get-expense-data',
          type: 'GET',
          data: {
            id: expenseId
          },
          success: function(response) {
            $('#kt_drawer_expence_edit').html(response.drawerContent);
          },
          error: function(xhr, status, error) {
            console.error('Error fetching Order data:', xhr);
          }
        });
      });
    });
  </script>
  <script>
    $("#income-input").click(function() {
      $('#save-text').show();
    })
    $("#income-input").change(function() {
      var newValue = $(this).val();
      var csrfToken = $('meta[name="csrf-token"]').attr("content"); // Fetch CSRF token value
      $.ajax({
        url: "/dashboard/expenses/update-income",
        type: "PUT",
        data: {
          value: newValue,
        },
        headers: {
          "X-CSRF-TOKEN": csrfToken, // Set CSRF token as header
        },
        success: function(response) {
          if (response.success) {
            // console.log("Value updated successfully");
            Swal.fire({
              text: "Value updated successfully!",
              icon: "success",
              buttonsStyling: false,
              confirmButtonText: "Ok, got it!",
              customClass: {
                confirmButton: "btn btn-primary",
              },
            }).then(function(result) {
              window.location.href = "/dashboard/expenses";
            });
          }
        },
        error: function(xhr, status, error) {
          console.error("Error:", error);
        },
      });
    });
  </script>
  <script>
    $("#budget-input").click(function() {
      $('#save-text').show();
    })
    $("#budget-input").change(function() {
      var newValue = $(this).val();
      var csrfToken = $('meta[name="csrf-token"]').attr("content"); // Fetch CSRF token value
      $.ajax({
        url: "/dashboard/expenses/update-budget",
        type: "PUT",
        data: {
          value: newValue,
        },
        headers: {
          "X-CSRF-TOKEN": csrfToken, // Set CSRF token as header
        },
        success: function(response) {
          if (response.success) {
            // console.log("Value updated successfully");
            Swal.fire({
              text: "Value updated successfully!",
              icon: "success",
              buttonsStyling: false,
              confirmButtonText: "Ok, got it!",
              customClass: {
                confirmButton: "btn btn-primary",
              },
            }).then(function(result) {
              window.location.href = "/dashboard/expenses";
            });
          }
        },
        error: function(xhr, status, error) {
          console.error("Error:", error);
        },
      });
    });
  </script>
  <script>
    $(document).ready(function() {

      function initializeCharts() {
        var chartElement = document.querySelector(".expenses-used");

        var height = parseInt(KTUtil.css(chartElement, "height"));

        if (chartElement) {
          var colorName = "{{ $percentageUsed > 99 ? 'danger' : 'primary' }}";
          var chartColor = KTUtil.getCssVariableValue("--bs-" + colorName);
          var chartColorLight = KTUtil.getCssVariableValue("--bs-" + colorName + "-light");
          var grayColor = KTUtil.getCssVariableValue("--bs-gray-700");

          new ApexCharts(chartElement, {
            series: [{{ round($percentageUsed) }}], // Modify this value with dynamic data if needed
            chart: {
              fontFamily: "inherit",
              height: height,
              type: "radialBar",
            },
            plotOptions: {
              radialBar: {
                hollow: {
                  margin: 0,
                  size: "65%" // Adjust size if needed
                },
                dataLabels: {
                  showOn: "always",
                  name: {
                    show: false,
                    fontWeight: "700"
                  },
                  value: {
                    color: grayColor,
                    fontSize: "30px",
                    fontWeight: "700",
                    offsetY: 12,
                    show: true,
                    formatter: function(value) {
                      return value + "%"; // Format the value as a percentage
                    },
                  },
                },
                track: {
                  background: chartColorLight,
                  strokeWidth: "100%",
                },
              },
            },
            colors: [chartColor],
            stroke: {
              lineCap: "round"
            },
            labels: ["Progress"], // Modify label text if needed
          }).render();
        }
      }
      // Call the function to initialize charts on page load
      KTUtil.onDOMContentLoaded(function() {
        initializeCharts();
      });
    })
  </script>
  <script>
    $(document).ready(function() {
      function initializePieCharts() {
        var chartElement = document.querySelector("#expense_category_chart");

        var height = parseInt(KTUtil.css(chartElement, "height"));

        if (chartElement) {
          new ApexCharts(chartElement, {
            series: @json($counts), // Dynamically pass the counts data
            chart: {
              fontFamily: "inherit",
              type: "donut",
              width: 250
            },
            plotOptions: {
              pie: {
                donut: {
                  size: "50%",
                  labels: {
                    value: {
                      fontSize: "10px"
                    }
                  },
                },
              },
            },
            colors: [
              KTUtil.getCssVariableValue("--bs-primary"),
              KTUtil.getCssVariableValue("--bs-danger"),
              KTUtil.getCssVariableValue("--bs-info"),
              KTUtil.getCssVariableValue("--bs-success"),
              KTUtil.getCssVariableValue("--bs-warning"),
              KTUtil.getCssVariableValue("--bs-dark"),
            ],
            stroke: {
              width: 0
            },
            labels: @json($categories), // Dynamically pass the category names
            legend: {
              show: false
            },
            fill: {
              type: "false"
            },
          }).render();
        }
      }

      KTUtil.onDOMContentLoaded(function() {
        initializePieCharts();
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      function initializeBarCharts() {
        var chartElement = document.querySelector("#expense_bar_chart");

        var height = parseInt(KTUtil.css(chartElement, "height"));
        var e = {
          self: null,
          rendered: !1
        };
        var l = KTUtil.getCssVariableValue("--bs-gray-900");
        var r = KTUtil.getCssVariableValue("--bs-border-dashed-color");

        if (chartElement) {
          new ApexCharts(chartElement, {
            series: [{
              name: "Total",
              data: [
                {{ round($expenseDetail->income / 1000000, 2) }},
                {{ round($expenseDetail->budget / 1000000, 2) }},
                {{ round($totalSpendAmount / 1000000, 2) }}
              ],
            }, ], // Dynamically pass the counts data
            chart: {
              fontFamily: "inherit",
              type: "bar",
              height: height,
              toolbar: {
                show: !1
              },
            },
            plotOptions: {
              bar: {
                horizontal: !1,
                columnWidth: ["28%"],
                borderRadius: 5,
                dataLabels: {
                  position: "top"
                },
                startingShape: "flat",
              },
            },
            legend: {
              show: !1
            },
            dataLabels: {
              enabled: !0,
              offsetY: -28,
              style: {
                fontSize: "13px",
                colors: [l]
              },
              formatter: function(e) {
                return e;
              },
            },
            stroke: {
              show: !0,
              width: 2,
              colors: ["transparent"]
            },
            xaxis: {
              categories: [
                "Income",
                "Budget",
                "Expense",
              ],
              axisBorder: {
                show: !1
              },
              axisTicks: {
                show: !1
              },
              labels: {
                style: {
                  colors: KTUtil.getCssVariableValue(
                    "--bs-gray-500"
                  ),
                  fontSize: "13px",
                },
              },
              crosshairs: {
                fill: {
                  gradient: {
                    opacityFrom: 0,
                    opacityTo: 0
                  },
                },
              },
            },
            yaxis: {
              labels: {
                style: {
                  colors: KTUtil.getCssVariableValue(
                    "--bs-gray-500"
                  ),
                  fontSize: "13px",
                },
                formatter: function(e) {
                  return parseFloat(e).toFixed(2) + "M";
                },
              },
            },
            fill: {
              opacity: 1
            },
            states: {
              normal: {
                filter: {
                  type: "none",
                  value: 0
                }
              },
              hover: {
                filter: {
                  type: "none",
                  value: 0
                }
              },
              active: {
                allowMultipleDataPointsSelection: !1,
                filter: {
                  type: "none",
                  value: 0
                },
              },
            },
            tooltip: {
              style: {
                fontSize: "12px"
              },
              y: {
                formatter: function(e) {
                  return +e + "M";
                },
              },
            },
            colors: [
              KTUtil.getCssVariableValue("--bs-primary"),
              KTUtil.getCssVariableValue("--bs-primary-light"),
            ],
            grid: {
              borderColor: r,
              strokeDashArray: 4,
              yaxis: {
                lines: {
                  show: !0
                }
              },
            },
          }).render();
        }
      }

      KTUtil.onDOMContentLoaded(function() {
        initializeBarCharts();
      });
    });
  </script>
@endsection
