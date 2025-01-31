@php
  use App\Models\Setting;
  $setting = Setting::first();
@endphp
<!DOCTYPE html>

<html lang="en">

<head>
  <title>{{ $setting->website_title ?? 'Bhopal Food Court' }}</title>
  <meta charset="utf-8" />
  <meta name="description" content="{{ $setting->meta_desc ?? '' }}" />
  <meta name="viewport" content="width=device-width, initial-scale=1" />
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <link rel="shortcut icon" href="{{ asset($setting->project_favicon_icon ?? 'assets/media/logos/favicon.ico') }}" />
  <!--begin::Fonts(mandatory for all pages)-->
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
  <!--end::Fonts-->
  <!--begin::Vendor Stylesheets(used for this page only)-->
  <link href="{{ asset('assets/plugins/custom/fullcalendar/fullcalendar.bundle.css') }}" rel="stylesheet"
    type="text/css" />
  <link href="{{ asset('assets/plugins/custom/datatables/datatables.bundle.css') }}" rel="stylesheet" type="text/css" />
  <link rel="stylesheet" href="{{ asset('custom/assets/css/intlTelInput/intlTelInput.css') }}">
  <!--end::Vendor Stylesheets-->
  <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
  <link href="{{ asset('assets/plugins/global/plugins.bundle.css') }}" rel="stylesheet" type="text/css" />
  <link href="{{ asset('assets/css/style.bundle.css') }}" rel="stylesheet" type="text/css" />
  <script>
    // Frame-busting to prevent site from being loaded within a frame without permission (click-jacking) if (window.top != window.self) { window.top.location.replace(window.self.location.href); }
  </script>
</head>
<!--end::Head-->
<!--begin::Body-->

<body id="kt_app_body" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true"
  data-kt-app-header-fixed-mobile="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true"
  data-kt-app-sidebar-minimize="on" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true"
  data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true"
  data-kt-app-toolbar-fixed="true" data-kt-app-toolbar-fixed-mobile="true" class="app-default">
  <!--begin::Theme mode setup on page load-->
  <script>
    var defaultThemeMode = "light";
    var themeMode;
    if (document.documentElement) {
      if (document.documentElement.hasAttribute("data-bs-theme-mode")) {
        themeMode = document.documentElement.getAttribute("data-bs-theme-mode");
      } else {
        if (localStorage.getItem("data-bs-theme") !== null) {
          themeMode = localStorage.getItem("data-bs-theme");
        } else {
          themeMode = defaultThemeMode;
        }
      }
      if (themeMode === "system") {
        themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light";
      }
      document.documentElement.setAttribute("data-bs-theme", themeMode);
    }
  </script>
  <!--end::Theme mode setup on page load-->
  <!--begin::App-->
  <div class="d-flex flex-column flex-root app-root" id="kt_app_root">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
      <!--begin::Body-->
      <div class="scroll-y flex-column-fluid px-10 " data-kt-scroll="true" data-kt-scroll-activate="true"
        data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_header_nav" data-kt-scroll-offset="5px"
        data-kt-scroll-save-state="true"
        style="background-color:#D5D9E2; --kt-scrollbar-color: #d9d0cc; --kt-scrollbar-hover-color: #d9d0cc">
        <!--begin::Email template-->
        <style>
          html,
          body {
            padding: 0;
            margin: 0;
            font-family: Inter, Helvetica, "sans-serif";
          }

          a:hover {
            color: #009ef7;
          }
        </style>
        <div id="#kt_app_body_content"
          style="background-color:#D5D9E2; font-family:Arial,Helvetica,sans-serif; line-height: 1.5; min-height: 100%; font-weight: normal; font-size: 15px; color: #2F3044; margin:0; padding:0; width:100%;">
          <div style="background-color:#ffffff; border-radius: 24px; margin:40px auto; max-width: 600px;">
            <!--begin::Invoice 2 main-->
            <div class="card">
              <!--begin::Body-->
              <div class="card-body p-lg-10">
                <!--begin::Layout-->
                <div class="d-flex flex-column flex-xl-row">
                  <!--begin::Content-->
                  <div class="flex-lg-row-fluid mb-10 mb-xl-0">
                    <!--begin::Invoice 2 content-->
                    <div class="mt-n1">
                      <!--begin::Top-->
                      <div class="d-flex flex-stack pb-10">
                        <!--begin::Action-->
                        <span class="fs-2hx fw-bolder text-gray-900">Invoice</span>
                        <!--end::Action-->
                        <!--begin::Logo-->
                        <a href="#">
                          <img class="h-60px" alt="Logo" src="{{ asset($invoice->billFrom->logo) }}" />
                        </a>
                        <!--end::Logo-->
                      </div>
                      <!--end::Top-->
                      <!--begin::Wrapper-->
                      <div class="m-0">
                        <!--begin::Section-->
                        <div class="d-flex align-items-center flex-row-fluid flex-wrap mb-5">
                          <div class="flex-grow-1 me-2">
                            <!--begin::Label-->
                            <div class="fw-bold fs-3 text-gray-800">#{{ $invoice->custom_id }}</div>
                            <!--end::Label-->
                          </div>
                          <div class="my-2">
                            <!--end::Info-->
                            <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                              <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                                <!--begin::Download-->
                                <a href="/dashboard/finance/invoices/{{ $invoice->id }}/download" class="btn btn-sm btn-warning me-2">Download</a>
                                <!--end::Download-->
                                <!--begin::Pay Now-->
                                <a href="#" class="btn btn-sm btn-success">Pay Now</a>
                                <!--end::Pay Now-->
                                </span>
                              </div>
                            </div>
                            <!--end::Info-->
                          </div>
                        </div>
                        <!--end::Section-->
                        <!--begin::Section-->
                        <div class="d-flex align-items-center flex-row-fluid flex-wrap mb-5">
                          <div class="flex-grow-1 me-2">
                            <!--end::Label-->
                            <div class="fw-semibold fs-7 text-gray-600 mb-1">Issue Date:</div>
                            <!--end::Label-->
                            <!--end::Col-->
                            <div class="fw-bold fs-6 text-gray-800">
                              {{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}</div>
                            <!--end::Col-->
                          </div>
                          <div class="my-2">
                            <!--end::Label-->
                            <div class="fw-semibold fs-7 text-gray-600 mb-1">Due Date:</div>
                            <!--end::Label-->
                            <!--end::Info-->
                            <div class="fw-bold fs-6 text-gray-800 d-flex align-items-center flex-wrap">
                              <span
                                class="pe-2">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</span>
                              <span class="fs-7 text-danger d-flex align-items-center">
                                @php
                                  $dueDate = \Carbon\Carbon::parse($invoice->due_date);
                                  $daysDue = $dueDate->diffInDays(\Carbon\Carbon::now());
                                @endphp
                                <span class="bullet bullet-dot bg-danger me-2"></span>Due in {{ $daysDue }}
                                days</span>
                            </div>
                            <!--end::Info-->
                          </div>
                        </div>
                        <!--end::Section-->
                        <!--begin::Section-->
                        <div class="d-flex align-items-center flex-row-fluid flex-wrap mb-5">
                          <div class="flex-grow-1 me-2">
                            <!--end::Label-->
                            <div class="fw-semibold fs-7 text-gray-600 mb-1">Bill Form:</div>
                            <!--end::Label-->
                            <!--end::Text-->
                            <div class="fw-bold fs-6 text-gray-800">{{ $invoice->billFrom->name }}</div>
                            <!--end::Text-->
                            <!--end::Description-->
                            <div class="fw-semibold fs-7 text-gray-600">
                              {{ $invoice->billFrom->address }}
                            </div>
                            <!--end::Description-->
                            <!--end::GST-->
                            <div class="fw-semibold fs-7 text-gray-600">GSTIN: {{ $invoice->billFrom->gst_no }}</div>
                            <!--end::GST-->
                          </div>
                          <div class="my-2">
                            <!--end::Label-->
                            <div class="fw-semibold fs-7 text-gray-600 mb-1">Bill To:</div>
                            <!--end::Label-->
                            <!--end::Text-->
                            <div class="fw-bold fs-6 text-gray-800">{{ $invoice->billTo->company_name }}</div>
                            <!--end::Text-->
                            <!--end::Description-->
                            <div class="fw-semibold fs-7 text-gray-600">
                              {{ $invoice->billTo->address }}
                            </div>
                            <!--end::Description-->
                            <!--end::GST-->
                            <div class="fw-semibold fs-7 text-gray-600">GSTIN: {{ $invoice->billTo->gst_no }}</div>
                            <!--end::GST-->
                          </div>
                        </div>
                        <!--end::Section-->
                        <!--begin::Content-->
                        <div class="flex-grow-1">
                          <!--begin::Table-->
                          <div class="table-responsive border-bottom mb-9">
                            <table class="table mb-3">
                              <thead>
                                <tr class="border-bottom fs-6 fw-bold text-muted">
                                  <th class="min-w-175px pb-2">Title</th>
                                  <th class="min-w-70px text-center pb-2">Quantity</th>
                                  <th class="min-w-80px text-center pb-2">Rate</th>
                                  <th class="min-w-80px text-center pb-2">Tax</th>
                                  <th class="min-w-100px text-end pb-2">Amount</th>
                                </tr>
                              </thead>
                              <tbody>
                                @php
                                  $subtotal = 0;
                                @endphp

                                @foreach ($invoice->items as $invoiceItem)
                                  @php
                                    $subtotal += $invoiceItem->total;
                                  @endphp
                                  <tr class="fw-bold text-gray-700 fs-5 text-center">
                                    <td class="d-block text-start pt-6">
                                      {{ $invoiceItem->item_name }}<br />
                                      <span class="fw-normal fs-8 d-block">{{ $invoiceItem->item_description }}</span>
                                    </td>
                                    <td class="pt-6">{{ $invoiceItem->quantity }}</td>
                                    <td class="pt-6">₹{{ number_format($invoiceItem->price, 2) }}</td>
                                    <td class="pt-6">
                                      ₹{{ number_format($invoiceItem->tax_value, 2) }}<br />
                                      @if ($invoiceItem->invoiceTax && $invoiceItem->invoiceTax->tax_rate > 0)
                                        <span class="fw-normal fs-8">{{ $invoiceItem->invoiceTax->tax_rate }}%
                                          {{ $invoiceItem->invoiceTax->tax_title }}</span>
                                      @endif
                                    </td>
                                    <td class="pt-6 text-gray-900 fw-bolder">
                                      ₹{{ number_format($invoiceItem->total, 2) }}
                                    </td>
                                  </tr>
                                @endforeach
                              </tbody>

                            </table>
                          </div>
                          <!--end::Table-->
                          <!--begin::Container-->
                          <div class="d-flex justify-content-end">
                            <!--begin::Section-->
                            <div class="mw-300px">
                              <!--begin::Item-->
                              <div class="d-flex flex-stack mb-3">
                                <!--begin::Accountname-->
                                <div class="fw-semibold pe-10 text-gray-600 fs-7">Subtotal:</div>
                                <!--end::Accountname-->
                                <!--begin::Label-->
                                <div class="text-end fw-bold fs-6 text-gray-800">₹ {{ $subtotal }}</div>
                                <!--end::Label-->
                              </div>
                              <!--end::Item-->
                              <!--begin::Item-->
                              <div class="d-flex flex-stack mb-3">
                                <!--begin::Accountname-->
                                <div class="fw-semibold pe-10 text-gray-600 fs-7">Tax
                                  {{ $invoice->tax_rate != 0 ? $invoice->tax_rate . '%' : '' }}</div>
                                <!--end::Accountname-->
                                <!--begin::Label-->
                                <div class="text-end fw-bold fs-6 text-gray-800">₹ {{ $invoice->tax_value }}</div>
                                <!--end::Label-->
                              </div>
                              <!--end::Item-->
                              <div class="d-flex flex-stack mb-3">
                                <!--begin::Accountname-->
                                <div class="fw-semibold pe-10 text-gray-600 fs-7">Discount
                                  {{ $invoice->discount_rate != 0 ? $invoice->discount_rate . '%' : '' }}</div>
                                <!--end::Accountname-->
                                <!--begin::Label-->
                                <div class="text-end fw-bold fs-6 text-gray-800">₹ {{ $invoice->discount_value }}
                                </div>
                                <!--end::Label-->
                              </div>
                              <!--begin::Item-->
                              <div class="d-flex flex-stack">
                                <!--begin::Code-->
                                <div class="fw-semibold pe-10 text-gray-900 fs-7">Total</div>
                                <!--end::Code-->
                                <!--begin::Label-->
                                <div class="text-end fw-bold fs-6 text-gray-900">₹ {{ $invoice->total_amount }}</div>
                                <!--end::Label-->
                              </div>
                              <!--end::Item-->
                            </div>
                            <!--end::Section-->
                          </div>
                          <!--end::Container-->
                        </div>
                        <!--end::Content-->
                      </div>
                      <!--end::Wrapper-->
                    </div>
                    <!--end::Invoice 2 content-->
                  </div>
                  <!--end::Content-->
                </div>
                <!--end::Layout-->
              </div>
              <!--end::Body-->
            </div>
            <!--end::Invoice 2 main-->
          </div>
        </div>
        <!--end::Email template-->
      </div>
      <!--end::Body-->
    </div>
    <!--end::Content wrapper-->
  </div>
  <!--end::App-->
  <!--begin::Drawers-->
  <!--begin::Scrolltop-->
  <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
    <i class="ki-duotone ki-arrow-up">
      <span class="path1"></span>
      <span class="path2"></span>
    </i>
  </div>
  <!--end::Scrolltop-->
  <!--begin::Javascript-->
  <script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
  <script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
  <!--end::Custom Javascript-->
  <!--end::Custom Javascript-->
  <!--end::Custom Javascript-->
  <!--end::Javascript-->

</body>
<!--end::Body-->

</html>
