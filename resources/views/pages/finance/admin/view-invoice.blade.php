@extends('layouts.admin.app')
@section('contents')
  @include('pages.finance.admin.toolbars.view-invoice-toolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
        <!--begin::Order details-->
        <div class="card card-flush py-4 flex-row-fluid col-12 col-xl-4">
          <!--begin::Card header-->
          <div class="card-header">
            <div class="card-title ">
              <h2>Invoice Details (INV{{ $invoice->custom_id }})</h2>
            </div>
          </div>
          <!--end::Card header-->
          <!--begin::Card body-->
          <div class="card-body pt-0">
            <div class="table-responsive">
              <!--begin::Table-->
              <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                <tbody class="fw-semibold text-gray-600">
                  <tr>
                    <td class="text-muted">
                      <div class="d-flex align-items-center">
                        <i class="ki-duotone ki-calendar fs-2 me-2">
                          <span class="path1"></span>
                          <span class="path2"></span>
                        </i>Date Added
                      </div>
                    </td>
                    <td class="fw-bold text-end">{{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}</td>
                  </tr>
                  <tr>
                    <td class="text-muted">
                      <div class="d-flex align-items-center">
                        <i class="fa-solid fa-circle-info fs-2 me-2"></i>
                        Status
                      </div>
                    </td>
                    <td class="fw-bold text-end">
                      {{ strtoupper($invoice->status) }}
                    </td>
                  </tr>
                  <tr>
                    <td class="text-muted">
                      <div class="d-flex align-items-center">
                        <i class="fa-solid fa-rotate-right fs-2 me-2"></i>
                        Recurring
                      </div>
                    </td>
                    <td class="fw-bold text-end">
                      {{ $invoice->recurring ? 'True' : 'False' }}
                    </td>
                  </tr>
                  <tr>
                    <td class="text-muted">
                      <div class="d-flex align-items-center">
                        <i class="fa-solid fa-note-sticky fs-2 me-2"></i>
                        Notes
                      </div>
                    </td>
                    <td class="fw-bold text-end">
                      {{ $invoice->notes ? 'True' : 'False' }}
                    </td>
                  </tr>
                  <tr>
                    <td class="text-muted">
                      <div class="d-flex align-items-center">
                        <i class="ki-duotone ki-truck fs-2 me-2">
                          <span class="path1"></span>
                          <span class="path2"></span>
                          <span class="path3"></span>
                          <span class="path4"></span>
                          <span class="path5"></span>
                        </i>Due Date
                      </div>
                    </td>
                    <td class="fw-bold text-end">{{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</td>
                  </tr>
                  <tr>
                    <td class="text-end w-100">
                      <div>
                        <a href="/dashboard/finance/invoices/{{ $invoice->id }}/download">
                          <button class="btn btn-warning btn-sm ms-2" id="download-button">Download</button>
                        </a>
                        <a href="/dashboard/finance/invoices/{{ $invoice->id }}/send">
                          <button class="btn btn-success btn-sm">Send</button>
                        </a>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
              <!--end::Table-->
            </div>
          </div>
          <!--end::Card body-->
        </div>
        <!--end::Order details-->
        <!--begin::Customer details-->
        <div class="card card-flush py-4 flex-row-fluid col-12 col-xl-4">
          <!--begin::Card header-->
          <div class="card-header">
            <div class="card-title">
              <h2>Bill From</h2>
            </div>
          </div>
          <!--end::Card header-->
          <!--begin::Card body-->
          <div class="card-body pt-0">
            <div class="table-responsive">
              <!--begin::Table-->
              <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                <tbody class="fw-semibold text-gray-600">
                  <tr>
                    <td class="text-muted">
                      <div class="d-flex align-items-center">
                        <i class="ki-duotone ki-profile-circle fs-2 me-2">
                          <span class="path1"></span>
                          <span class="path2"></span>
                          <span class="path3"></span>
                        </i>Category
                      </div>
                    </td>
                    <td class="fw-bold text-end">
                      <div class="d-flex align-items-center justify-content-end">
                        <!--begin:: Avatar -->
                        @if ($invoice->billFrom->logo)
                          <div class="symbol symbol-circle symbol-25px overflow-hidden me-3">
                            <a href="#">
                              <div class="symbol-label">
                                <img src="{{ asset($invoice->billFrom->logo) }}" class="w-100" />
                              </div>
                            </a>
                          </div>
                        @endif
                        <!--end::Avatar-->
                        <!--begin::Name-->
                        <a href="#"
                          class="badge {{ $invoice->billFrom->color_class }}">{{ $invoice->billFrom->name }}</a>
                        <!--end::Name-->
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-muted">
                      <div class="d-flex align-items-center">
                        <i class="fa-solid fa-user-tie fs-2 me-2"></i>
                        Name
                      </div>
                    </td>
                    <td class="fw-bold text-end">
                      <a href="#" class="text-gray-600 text-hover-primary">{{ $invoice->bill_from_name }}</a>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-muted">
                      <div class="d-flex align-items-center">
                        <i class="ki-duotone ki-sms fs-2 me-2">
                          <span class="path1"></span>
                          <span class="path2"></span>
                        </i>
                        Email
                      </div>
                    </td>
                    <td class="fw-bold text-end">{{ $invoice->bill_from_email }}</td>
                  </tr>
                  <tr>
                    <td class="text-muted">
                      <div class="d-flex align-items-center">
                        <i class="fa-solid fa-note-sticky fs-2 me-2"></i>
                        Description
                      </div>
                    </td>
                    <td class="fw-bold">{{ $invoice->bill_from_description ?? '-' }}</td>
                  </tr>
                </tbody>
              </table>
              <!--end::Table-->
            </div>
          </div>
          <!--end::Card body-->
        </div>
        <!--end::Customer details-->
        <!--begin::Documents-->
        <div class="card card-flush py-4 flex-row-fluid col-12 col-xl-4">
          <!--begin::Card header-->
          <div class="card-header">
            <div class="card-title">
              <h2>Bill To (Client)</h2>
            </div>
          </div>
          <!--end::Card header-->
          <!--begin::Card body-->
          <div class="card-body pt-0">
            <div class="table-responsive">
              <!--begin::Table-->
              <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                <tbody class="fw-semibold text-gray-600">
                  <tr>
                    <td class="text-muted">
                      <div class="d-flex align-items-center">
                        <i class="ki-duotone ki-devices fs-2 me-2">
                          <span class="path1"></span>
                          <span class="path2"></span>
                          <span class="path3"></span>
                          <span class="path4"></span>
                          <span class="path5"></span>
                        </i>Company
                      </div>
                    </td>
                    <td class="fw-bold text-end">
                      <a href="#"
                        class="text-gray-600 text-hover-primary">{{ $invoice->billTo->company_name }}</a>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-muted">
                      <div class="d-flex align-items-center">
                        <i class="ki-duotone ki-profile-circle fs-2 me-2">
                          <span class="path1"></span>
                          <span class="path2"></span>
                          <span class="path3"></span>
                        </i>Name
                      </div>
                    </td>
                    <td class="fw-bold text-end">
                      <a href="#" class="text-gray-600 text-hover-primary">{{ $invoice->bill_to_name }}</a>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-muted">
                      <div class="d-flex align-items-center">
                        <i class="ki-duotone ki-sms fs-2 me-2">
                          <span class="path1"></span>
                          <span class="path2"></span>
                        </i>Email
                      </div>
                    </td>
                    <td class="fw-bold text-end">
                      <a href="#" class="text-gray-600 text-hover-primary">{{ $invoice->bill_to_email }}</a>
                    </td>
                  </tr>
                  <tr>
                    <td class="text-muted">
                      <div class="d-flex align-items-center">
                        <i class="fa-solid fa-note-sticky fs-2 me-2"></i>
                        Description
                      </div>
                    </td>
                    <td class="fw-bold">{{ $invoice->bill_to_description ?? '-' }}</td>
                  </tr>
                </tbody>
              </table>
              <!--end::Table-->
            </div>
          </div>
          <!--end::Card body-->
        </div>
        <!--end::Documents-->
      </div>
      <div class="tab-content mt-10">
        <!--begin::Tab pane-->
        <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">
          <!--begin::Orders-->
          <div class="d-flex flex-column gap-7 gap-lg-10">
            <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
              <!--begin::Payment address-->
              <div class="card card-flush py-4 flex-row-fluid position-relative col-12 col-xl-6">
                <!--begin::Background-->
                <div class="position-absolute top-0 end-0 bottom-0 opacity-10 d-flex align-items-center me-5">
                  <i class="ki-solid ki-two-credit-cart" style="font-size: 14em"></i>
                </div>
                <!--end::Background-->
                <!--begin::Card header-->
                <div class="card-header">
                  <div class="card-title">
                    <h2>Invoice Notes</h2>
                  </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                  {{ $invoice->invoice_notes }}
                </div>
                <!--end::Card body-->
              </div>
              <!--end::Payment address-->
              <!--begin::Shipping address-->
              <div class="card card-flush py-4 flex-row-fluid position-relative col-12 col-xl-6">
                <!--begin::Background-->
                <div class="position-absolute top-0 end-0 bottom-0 opacity-10 d-flex align-items-center me-5">
                  <i class="ki-solid ki-delivery" style="font-size: 13em"></i>
                </div>
                <!--end::Background-->
                <!--begin::Card header-->
                <div class="card-header">
                  <div class="card-title">
                    <h2>Attachments</h2>
                  </div>
                </div>
                <!--end::Card header-->
                <!--begin::Card body-->
                <div class="card-body pt-0">
                  @if (!empty($invoice->attachments))
                    @foreach ($invoice->attachments as $attachment)
                      <div class="mb-2">
                        <a href="{{ asset($attachment) }}" target="_blank">
                          {{ basename($attachment) }}
                        </a>
                      </div>
                    @endforeach
                  @else
                    <p>No attachments available.</p>
                  @endif
                </div>
                <!--end::Card body-->
              </div>
              <!--end::Shipping address-->
            </div>
            <!--begin::Product List-->
            <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
              <!--begin::Card header-->
              <div class="card-header">
                <div class="card-title">
                  <h2>Invoice #INV{{ $invoice->custom_id }}</h2>
                </div>
              </div>
              <!--end::Card header-->
              <!--begin::Card body-->
              <div class="card-body pt-0">
                <div class="table-responsive">
                  <!--begin::Table-->
                  <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
                    <thead>
                      <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                        <th class="min-w-175px">Product</th>
                        <th class="min-w-70px text-end">Qty</th>
                        <th class="min-w-100px text-end">Unit Price</th>
                        <th class="min-w-70px text-end">Tax</th>
                        <th class="min-w-100px text-end">Total</th>
                      </tr>
                    </thead>
                    <tbody class="fw-semibold text-gray-600">
                      @php
                        $subtotal = 0;
                      @endphp

                      @foreach ($invoice->items as $item)
                        @php
                          $itemTotal = $item->total;
                          $subtotal += $itemTotal;
                        @endphp
                        <tr>
                          <td>
                            <div class="">
                              <!--begin::Title-->
                              <div class="">
                                <a href="#"
                                  class="fw-bold text-gray-600 text-hover-primary">{{ $item->item_name }}
                                </a>
                              </div>
                              <div class="fs-7 text-muted">
                                {{ $item->item_description }}
                              </div>
                              <!--end::Title-->
                            </div>
                          </td>
                          <td class="text-end">{{ $item->quantity }}</td>
                          <td class="text-end">₹{{ number_format($item->price, 2) }}</td>
                          <td class="text-end">₹{{ number_format($item->tax_value, 2) }}
                            <div class="fs-7 text-muted">
                              {{ $item->invoiceTax->tax_rate ?? 0 }}% {{ $item->invoiceTax->tax_title ?? '' }}
                            </div>
                          </td>
                          <td class="text-end">₹{{ number_format($itemTotal, 2) }}</td>
                        </tr>
                      @endforeach

                      <tr>
                        <td colspan="4" class="text-end">Subtotal</td>
                        <td class="text-end">₹{{ number_format($subtotal, 2) }}</td>
                      </tr>
                      <tr>
                        <td colspan="4" class="text-end">Tax
                          {{ $invoice->tax_rate > 0 ? '(' . $invoice->tax_rate . '%)' : '' }}</td>
                        <td class="text-end">₹{{ number_format($invoice->tax_value, 2) }}</td>
                      </tr>
                      <tr>
                        <td colspan="4" class="text-end">Discount
                          {{ $invoice->discount_rate > 0 ? '(' . $invoice->discount_rate . '%)' : '' }}</td>
                        <td class="text-end">₹{{ number_format($invoice->discount_value, 2) }}</td>
                      </tr>
                      <tr>
                        <td colspan="4" class="fs-3 text-gray-900 text-end">Grand Total</td>
                        <td class="text-gray-900 fs-3 fw-bolder text-end">
                          ₹{{ number_format($invoice->total_amount, 2) }}
                        </td>
                      </tr>
                    </tbody>
                  </table>
                  <!--end::Table-->
                </div>
              </div>
              <!--end::Card body-->
            </div>
            <!--end::Product List-->
          </div>
          <!--end::Orders-->
        </div>
        <!--end::Tab pane-->
        <!--end::Tab pane-->
      </div>
    </div>
  </div>
@endsection
@section('scripts')
@endsection
