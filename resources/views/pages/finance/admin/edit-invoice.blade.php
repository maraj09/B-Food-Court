@extends('layouts.admin.app')
@section('contents')
  <style>
    .dropzone .dz-preview .dz-image {
      width: 100%;
      height: auto;
      border-radius: 0;
      overflow: hidden;
    }

    .dropzone .dz-preview .dz-image img {
      width: 100%;
      height: auto;
      object-fit: cover;
    }
  </style>
  @include('pages.finance.admin.toolbars.invoice-toolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Layout-->
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <div class="d-flex flex-column flex-lg-row">
        <!--begin::Content-->
        <form action="/dashboard/finance/invoices/{{ $invoice->id }}/update" method="POST" enctype="multipart/form-data"
          class="d-flex flex-column flex-lg-row" id="invoice-form">
          <!--begin::Form-->
          @csrf
          <input type="hidden" name="subtotal" id="subtotal">
          <input type="hidden" name="tax_value" id="tax_value" value="{{ $invoice->tax_value }}">
          <input type="hidden" name="tax_rate" id="tax_rate" value="{{ $invoice->tax_rate }}">
          <input type="hidden" name="discount_rate" id="discount_rate" value="{{ $invoice->discount_rate }}">
          <input type="hidden" name="discount_value" id="discount_value" value="{{ $invoice->discount_value }}">
          <input type="hidden" name="total_amount" id="total_amount" value="{{ $invoice->total_amount }}">
          <input type="hidden" name="status" id="status" value="{{ $invoice->status }}">

          <div class="flex-lg-row-fluid mb-10 mb-lg-0 me-lg-7 me-xl-10">
            <!--begin::Card-->
            <div class="card">
              <!--begin::Card body-->
              <div class="card-body p-12">

                <!--begin::Wrapper-->
                <div class="d-flex flex-column align-items-start flex-xxl-row">
                  <!--begin::Input group-->
                  <div class="d-flex align-items-center flex-equal fw-row me-4 order-2" data-bs-toggle="tooltip"
                    data-bs-trigger="hover" title="Specify invoice date">
                    <!--begin::Date-->
                    <div class="fs-6 fw-bold text-gray-700 text-nowrap">Date:</div>
                    <!--end::Date-->
                    <!--begin::Input-->
                    <div class="position-relative d-flex align-items-center w-175px">
                      <!--begin::Datepicker-->
                      <input class="form-control form-control-transparent fw-bold pe-5 select2-date-time"
                        placeholder="Select date" value="{{ $invoice->date }}" name="date" />
                      <!--end::Datepicker-->
                      <!--begin::Icon-->
                      <i class="ki-duotone ki-down fs-4 position-absolute ms-4 end-0"></i>
                      <!--end::Icon-->
                    </div>
                    <!--end::Input-->
                  </div>
                  <!--end::Input group-->
                  <!--begin::Input group-->
                  <div class="d-flex flex-center flex-equal fw-row text-nowrap order-1 order-xxl-2 me-4"
                    data-bs-toggle="tooltip" data-bs-trigger="hover" title="Enter invoice number">
                    <span class="fs-2x fw-bold text-gray-800">Invoice #</span>
                    <input type="number" name="custom_id"
                      class="form-control form-control-flush fw-bold text-muted fs-2x w-125px"
                      value="{{ $invoice->custom_id }}" placehoder="..." />
                  </div>
                  <!--end::Input group-->
                  <!--begin::Input group-->
                  <div class="d-flex align-items-center justify-content-end flex-equal order-3 fw-row"
                    data-bs-toggle="tooltip" data-bs-trigger="hover" title="Specify invoice due date">
                    <!--begin::Date-->
                    <div class="fs-6 fw-bold text-gray-700 text-nowrap">Due Date:</div>
                    <!--end::Date-->
                    <!--begin::Input-->
                    <div class="position-relative d-flex align-items-center w-175px">
                      <!--begin::Datepicker-->
                      <input class="form-control form-control-transparent fw-bold pe-5 select2-date-time"
                        placeholder="Select date" name="due_date" value="{{ $invoice->due_date }}" />
                      <!--end::Datepicker-->
                      <!--begin::Icon-->
                      <i class="ki-duotone ki-down fs-4 position-absolute end-0 ms-4"></i>
                      <!--end::Icon-->
                    </div>
                    <!--end::Input-->
                  </div>
                  <!--end::Input group-->
                </div>
                <!--end::Top-->
                <!--begin::Separator-->
                <div class="separator separator-dashed my-10"></div>
                <!--end::Separator-->
                <!--begin::Wrapper-->
                <div class="mb-0">
                  <!--begin::Row-->
                  <div class="row gx-10 mb-5">
                    <!--begin::Col-->
                    <div class="col-lg-6">
                      <label class="form-label fs-6 fw-bold text-gray-700 mb-3">Bill From</label>
                      <!--begin::Select-->
                      <select name="bill_from" aria-label="Select a Timezone" data-control="select2"
                        data-placeholder="Select Provider" class="form-select form-select-solid mb-5">
                        <option value=""></option>
                        @foreach ($billCategories as $billCategory)
                          <option data-kt-flag="{{ asset($billCategory->logo) }}" value="{{ $billCategory->id }}"
                            {{ $billCategory->id == $invoice->bill_from ? 'selected' : '' }}>
                            {{ $billCategory->name }}
                          </option>
                        @endforeach
                      </select>
                      <!--end::Select-->
                      <!--begin::Input group-->
                      <div class="mb-5">
                        <input type="text" name="bill_from_name" class="form-control form-control-solid"
                          placeholder="Name" value="{{ $invoice->bill_from_name }}" />
                      </div>
                      <!--end::Input group-->
                      <!--begin::Input group-->
                      <div class="mb-5">
                        <input type="text" name="bill_from_email" class="form-control form-control-solid"
                          placeholder="Email" value="{{ $invoice->bill_from_email }}" />
                      </div>
                      <!--end::Input group-->
                      <!--begin::Input group-->
                      <div class="mb-5">
                        <textarea name="bill_from_description" class="form-control form-control-solid" rows="3"
                          placeholder="Who is this invoice from?">{{ $invoice->bill_from_description }}</textarea>
                      </div>
                      <!--end::Input group-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-lg-6">
                      <label class="form-label fs-6 fw-bold text-gray-700 mb-3">Bill To</label>
                      <!--begin::Select-->
                      <select id="bill_to_select" name="bill_to" aria-label="Select a Timezone" data-control="select2"
                        data-placeholder="Select Bill To" class="form-select form-select-solid mb-5">
                        <option value=""></option>
                        @foreach ($clients as $client)
                          <option value="{{ $client->id }}" data-name="{{ $client->company_name }}"
                            data-email="{{ $client->email }}" {{ $client->id == $invoice->bill_to ? 'selected' : '' }}>
                            {{ $client->name }}
                          </option>
                        @endforeach

                      </select>
                      <!--end::Select-->
                      <!--begin::Input group-->
                      <div class="mb-5">
                        <input type="text" name="bill_to_name" class="form-control form-control-solid"
                          placeholder="Name" value="{{ $invoice->bill_to_name }}" id="bill_to_name" />
                      </div>
                      <!--end::Input group-->
                      <!--begin::Input group-->
                      <div class="mb-5">
                        <input type="text" name="bill_to_email" class="form-control form-control-solid"
                          placeholder="Email" value="{{ $invoice->bill_to_email }}" id="bill_to_email" />
                      </div>
                      <!--end::Input group-->
                      <!--begin::Input group-->
                      <div class="mb-5">
                        <textarea name="bill_to_description" class="form-control form-control-solid" rows="3"
                          placeholder="What is this invoice for?">{{ $invoice->bill_to_description }}</textarea>
                      </div>
                      <!--end::Input group-->
                    </div>
                    <!--end::Col-->
                  </div>
                  <!--end::Row-->
                  <!--begin::Table wrapper-->
                  <div class="table-responsive mb-10">
                    <!--begin::Table-->
                    <table class="table g-5 gs-0 mb-0 fw-bold text-gray-700" data-kt-element="items">
                      <!--begin::Table head-->
                      <thead>
                        <tr class="border-bottom fs-7 fw-bold text-gray-700 text-uppercase">
                          <th class="min-w-300px w-425px">Item</th>
                          <th class="min-w-100px w-50px">QTY</th>
                          <th class="min-w-150px w-100px">Price</th>
                          <th class="min-w-150px w-100px">Tax</th>
                          <th class="min-w-100px w-150px text-end">Total</th>
                          <th class="min-w-75px w-75px text-end">Action</th>
                        </tr>
                      </thead>
                      <!--end::Table head-->
                      <!--begin::Table body-->
                      <tbody>
                        @php
                          $subTotal = 0;
                        @endphp
                        @foreach ($invoice->items as $invoiceItem)
                          @php
                            $subTotal += $invoiceItem->total;
                          @endphp
                          <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
                            <td class="pe-7">
                              <input type="text" class="form-control form-control-solid mb-2" name="name[]"
                                placeholder="Item name" value="{{ $invoiceItem->item_name }}" />
                              <input type="text" class="form-control form-control-solid" name="description[]"
                                placeholder="Description" value="{{ $invoiceItem->item_description }}" />
                            </td>
                            <td class="ps-0">
                              <input class="form-control form-control-solid input-item-quantity" type="number"
                                min="1" name="quantity[]" placeholder="1" data-kt-element="quantity"
                                value="{{ $invoiceItem->quantity }}" />
                            </td>
                            <td>
                              <input type="number" class="form-control form-control-solid text-end" name="price[]"
                                placeholder="0.00" data-kt-element="price" value="{{ $invoiceItem->price }}" />
                            </td>
                            <td>
                              <select name="tax[]" class="form-control form-control-solid select-tax-rate">
                                <option value="0">0.00</option>
                                @foreach ($invoiceTaxes as $invoiceTax)
                                  <option value="{{ $invoiceTax->id }}" data-tax-rate="{{ $invoiceTax->tax_rate }}"
                                    {{ $invoiceItem->invoiceTax && $invoiceItem->invoiceTax->id == $invoiceTax->id ? 'selected' : '' }}>
                                    {{ $invoiceTax->tax_title }}
                                    {{ $invoiceTax->tax_rate }}%</option>
                                @endforeach
                              </select>
                              <input type="hidden" name="tax_values[]" value="{{ $invoiceItem->tax_value }}">
                            </td>
                            <td class="pt-8 text-end text-nowrap">₹
                              <span data-kt-element="total">{{ $invoiceItem->total }}</span>
                              <input type="hidden" class="form-control form-control-solid text-end"
                                value="{{ $invoiceItem->total }}" name="total[]" />
                            </td>
                            <td class="pt-5 text-end">
                              <button type="button" class="btn btn-sm btn-icon btn-active-color-primary"
                                data-kt-element="remove-item">
                                <i class="ki-duotone ki-trash fs-3">
                                  <span class="path1"></span>
                                  <span class="path2"></span>
                                  <span class="path3"></span>
                                  <span class="path4"></span>
                                  <span class="path5"></span>
                                </i>
                              </button>
                            </td>
                          </tr>
                        @endforeach
                      </tbody>
                      <!--end::Table body-->
                      <!--begin::Table foot-->
                      <tfoot>
                        <tr class="border-top border-top-dashed align-top fs-6 fw-bold text-gray-700">
                          <th class="text-primary">
                            <button class="btn btn-link py-1" data-kt-element="add-item">Add item</button>
                          </th>
                          <th colspan="2" class="border-bottom border-bottom-dashed ps-0">
                            <div class="d-flex flex-column align-items-start">
                              <div class="fs-5 mb-2">Subtotal</div>
                              <button type="button"
                                class="btn btn-link py-1 {{ $invoice->tax_value ? 'd-none' : '' }}"
                                data-kt-element="add-tax">Add
                                tax</button>
                              <div class="additional-tax {{ $invoice->tax_value ? '' : 'd-none' }}">
                                <span>Additional Tax: <span
                                    class="additional-tax-rate">{{ $invoice->tax_rate > 0 ? $invoice->tax_rate . '%' : '' }}</span></span>
                                <button type="button" class="btn btn-sm btn-icon btn-active-color-primary"
                                  data-kt-element="remove-additional-tax">
                                  <i class="ki-duotone ki-cross fs-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                  </i>
                                </button>
                              </div>
                              <button type="button"
                                class="btn btn-link py-1 {{ $invoice->discount_value ? 'd-none' : '' }}"
                                data-kt-element="add-discount">Add
                                discount</button>
                              <div class="additional-discount {{ $invoice->discount_value ? '' : 'd-none' }}">
                                <span>Discount: <span
                                    class="additional-discount-rate">{{ $invoice->discount_rate > 0 ? $invoice->discount_rate . '%' : '' }}</span></span>
                                <button type="button" class="btn btn-sm btn-icon btn-active-color-primary"
                                  data-kt-element="remove-additional-discount">
                                  <i class="ki-duotone ki-cross fs-3">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                  </i>
                                </button>
                              </div>
                            </div>
                          </th>
                          <th colspan="2" class="border-bottom border-bottom-dashed text-end">₹
                            <span data-kt-element="sub-total">{{ $subTotal }}</span><br>
                            <span
                              class="{{ $invoice->tax_value ? '' : 'd-none' }} additional-tax-amount mt-3">{{ $invoice->tax_value }}</span><br>
                            <span class="{{ $invoice->discount_value ? '' : 'd-none' }} additional-discount-amount mt-3"
                              data-kt-element="sub-total">{{ $invoice->discount_value }}</span>
                          </th>
                        </tr>
                        <tr class="align-top fw-bold text-gray-700">
                          <th></th>
                          <th colspan="2" class="fs-4 ps-0">Total</th>
                          <th colspan="2" class="text-end fs-4 text-nowrap">₹
                            <span data-kt-element="grand-total">{{ $invoice->total_amount }}</span>
                          </th>
                        </tr>
                      </tfoot>
                      <!--end::Table foot-->
                    </table>
                  </div>

                  <!--end::Table-->
                  <!--end::Item template-->
                  <div class="row">
                    <div class="col-6">
                      <!--begin::Notes-->
                      <div class="mb-0">
                        <label class="form-label fs-6 fw-bold text-gray-700">Notes</label>
                        <textarea name="invoice_notes" class="form-control form-control-solid" rows="3"
                          placeholder="Thanks for your business">{{ $invoice->invoice_notes }}</textarea>
                      </div>
                      <!--end::Notes-->
                    </div>
                    <div class="col-6">
                      <!--begin::Input group-->
                      <label class="form-label fs-6 fw-bold text-gray-700">Attachements</label>
                      <!--begin::Dropzone-->
                      <div class="dropzone" id="invoice-attachements">
                        <!--begin::Message-->
                        <div class="dz-message needsclick">
                          <i class="ki-duotone ki-file-up fs-3x text-primary"><span class="path1"></span><span
                              class="path2"></span></i>
                          <!--begin::Info-->
                          <div class="ms-4">
                            <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop files here or click to upload.</h3>
                            <span class="fs-7 fw-semibold text-gray-500">Upload up to 10 files</span>
                          </div>
                          <!--end::Info-->
                        </div>
                      </div>
                      <!--end::Dropzone-->
                      <!--end::Input group-->
                    </div>
                  </div>
                </div>
                <!--end::Wrapper-->

              </div>
              <!--end::Card body-->
            </div>
            <!--end::Card-->
          </div>
          <!--end::Content-->
          <!--begin::Sidebar-->
          <div class="flex-lg-auto min-w-lg-300px">
            <!--begin::Card-->
            <div class="card" data-kt-sticky="true" data-kt-sticky-name="invoice"
              data-kt-sticky-offset="{default: false, lg: '200px'}" data-kt-sticky-width="{lg: '250px', lg: '300px'}"
              data-kt-sticky-left="auto" data-kt-sticky-top="150px" data-kt-sticky-animation="false"
              data-kt-sticky-zindex="95">
              <!--begin::Card body-->
              <div class="card-body p-10">
                <!--begin::Input group-->
                <div class="mb-10">
                  <!--begin::Label-->
                  <label class="form-label fw-bold fs-6 text-gray-700">Attache File or Biils</label>
                  <!--end::Label-->
                </div>
                <!--end::Input group-->
                <!--begin::Separator-->
                <div class="separator separator-dashed mb-8"></div>
                <!--end::Separator-->
                <!--begin::Input group-->
                <div class="mb-8">
                  <!--begin::Option-->
                  <label
                    class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                    <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">Recurring</span>
                    <input name="recurring" class="form-check-input" type="checkbox" value=""
                      {{ $invoice->recurring ? 'checked' : '' }} />
                  </label>
                  <!--end::Option-->
                  <!--begin::Option-->
                  <label
                    class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack mb-5">
                    <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">Late fees</span>
                    <input name="late_fees" class="form-check-input" value="" type="checkbox"
                      {{ $invoice->late_fees ? 'checked' : '' }} />
                  </label>
                  <!--end::Option-->
                  <!--begin::Option-->
                  <label class="form-check form-switch form-switch-sm form-check-custom form-check-solid flex-stack">
                    <span class="form-check-label ms-0 fw-bold fs-6 text-gray-700">Notes</span>
                    <input name="notes" class="form-check-input" value="" type="checkbox"
                      {{ $invoice->notes ? 'checked' : '' }} />
                  </label>
                  <!--end::Option-->
                </div>
                <!--end::Input group-->
                <!--begin::Separator-->
                <div class="separator separator-dashed mb-8"></div>
                <!--end::Separator-->
                <!--begin::Actions-->
                <div class="mb-0">
                  <!--begin::Row-->
                  <div class="row mb-5">
                    <!--begin::Col-->
                    <div class="col">
                      <a href="/dashboard/finance/invoices/{{ $invoice->id }}/previewInvoice"
                        class="btn btn-light btn-active-light-primary w-100">Preview</a>
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col">
                      <a href="/dashboard/finance/invoices/{{ $invoice->id }}/download"
                        class="btn btn-light btn-active-light-primary w-100">Download</a>
                    </div>
                    <!--end::Col-->
                  </div>
                  <!--end::Row-->
                  <div class="col p-0">
                    <button type="submit" href="#" class="btn btn-primary w-100 dropdown-toggle"
                      id="kt_invoice_submit_button" data-bs-toggle="dropdown">
                      <i class="ki-duotone ki-triangle fs-3">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                      </i>Send Invoice
                      <ul class="dropdown-menu p-0">
                        <li><a href="#" class="dropdown-item btn btn-light-warning w-100 rounded-0 py-2"
                            onclick="handleFormSubmission(this, 'pending')" data-invoice-status="pending">Save
                            &
                            Send</a></li>
                        <li><a href="#" class="dropdown-item btn btn-light-success w-100 rounded-0 py-2"
                            onclick="handleFormSubmission(this, 'draft')" data-invoice-status="draft">Save &
                            Send
                            Later</a></li>
                        <li><a href="#" class="dropdown-item btn btn-light-danger w-100 rounded-0 py-2"
                            onclick="handleFormSubmission(this, 'paid')" data-invoice-status="paid">Save &
                            Record Payment</a></li>
                      </ul>
                    </button>
                  </div>
                </div>
                <!--end::Actions-->
              </div>
              <!--end::Card body-->
            </div>
            <!--end::Card-->
          </div>
          <!--end::Form-->
        </form>
        <!--end::Sidebar-->
      </div>
      <!--end::Layout-->
    </div>
    <!--end::Content container-->
  </div>
@endsection
@section('scripts')
  <script>
    $(".select2-date-time").flatpickr({
      disableMobile: true,
      enableTime: true,
      dateFormat: "Y-m-d H:i",
      defaultHour: 0,
      defaultMinute: 0
    });
  </script>
  <script>
    $(document).ready(function() {
      $('#bill_to_select').on('change', function() {
        var selectedOption = $(this).find('option:selected');
        var name = selectedOption.data('name');
        var email = selectedOption.data('email');

        $('#bill_to_name').val(name);
        $('#bill_to_email').val(email);
      });

      $('.select-tax-rate').select2({
        placeholder: '0.00'
      });
    });
  </script>
  <script>
    $(document).ready(function() {
      // Function to calculate totals
      let taxAmount = {{ $invoice->tax_value }};
      let isTaxInPercentage = {{ $invoice->tax_rate > 0 ? 'true' : 'false' }};
      let taxValue = {{ $invoice->tax_rate > 0 ? $invoice->tax_rate . '%' : $invoice->tax_value }};
      let taxRate = {{ $invoice->tax_rate }};

      let discountAmount = {{ $invoice->discount_value }};
      let isDiscountInPercentage = {{ $invoice->discount_rate > 0 ? 'true' : 'false' }};;
      let discountValue =
        {{ $invoice->discount_rate > 0 ? $invoice->discount_rate . '%' : $invoice->discount_value }};;
      let discountRate = {{ $invoice->discount_rate }};


      function calculateTotals() {
        let subtotal = 0;

        $('tr[data-kt-element="item"]').each(function() {
          const quantity = parseFloat($(this).find('input[name="quantity[]"]').val()) || 0;
          const price = parseFloat($(this).find('input[name="price[]"]').val()) || 0;
          const selectedTaxElement = $(this).find('select[name="tax[]"] option:selected');
          const tax = parseFloat(selectedTaxElement.data('tax-rate')) || 0;
          const total = (quantity * price) + ((quantity * price * tax) / 100);
          $(this).find('[data-kt-element="total"]').text(total.toFixed(2));
          $(this).find('input[name="total[]"]').val(total.toFixed(2));
          $(this).find('input[name="tax_values[]"]').val(((quantity * price * tax) / 100).toFixed(2));

          subtotal += total;
        });

        if (isTaxInPercentage) {
          taxValue = parseFloat(taxRate.slice(0, -1)) || 0;
          taxAmount = (subtotal * taxValue) / 100;
        } else {
          taxValue = parseFloat(taxValue) || 0;
          taxAmount = taxValue;
        }

        if (isDiscountInPercentage) {
          discountValue = parseFloat(discountRate.slice(0, -1)) || 0;
          discountAmount = ((subtotal + taxAmount) * discountValue) / 100;
        } else {
          discountValue = parseFloat(discountValue) || 0;
          discountAmount = discountValue;
        }

        const calculatedTaxAmount = taxAmount;
        const grandTotal = subtotal + calculatedTaxAmount - discountAmount;


        $('[data-kt-element="sub-total"]').first().text(subtotal.toFixed(2));
        $('[data-kt-element="grand-total"]').text(grandTotal.toFixed(2));
        $('.additional-tax-amount').text(calculatedTaxAmount.toFixed(2));
        $('.additional-discount-amount').text(discountAmount.toFixed(2));

        $('input[name="tax_rate"]').val(isTaxInPercentage ? parseFloat(taxRate.slice(0, -1)) : 0);
        $('input[name="tax_value"]').val(calculatedTaxAmount);
        $('input[name="total_amount"]').val(grandTotal);
        $('input[name="subtotal"]').val(subtotal.toFixed(2));
        $('input[name="discount_rate"]').val(isDiscountInPercentage ? parseFloat(discountRate.slice(0, -1)) : 0);
        $('input[name="discount_value"]').val(discountAmount);
      }

      // Add new item row
      $('[data-kt-element="add-item"]').on('click', function(e) {
        e.preventDefault();
        const newItemRow = `
          <tr class="border-bottom border-bottom-dashed" data-kt-element="item">
            <td class="pe-7">
              <input type="text" class="form-control form-control-solid mb-2" name="name[]" placeholder="Item name" />
              <input type="text" class="form-control form-control-solid" name="description[]" placeholder="Description" />
            </td>
            <td class="ps-0">
              <input class="form-control form-control-solid input-item-quantity" type="number" min="1" name="quantity[]" placeholder="1" value="1" data-kt-element="quantity" />
            </td>
            <td>
              <input type="number" class="form-control form-control-solid text-end" name="price[]" placeholder="0.00" data-kt-element="price" />
            </td>
            <td>
              <select name="tax[]" data-control="select2" data-placeholder="0.00" class="form-control form-control-solid">
                <option value="0">0.00</option>
                @foreach ($invoiceTaxes as $invoiceTax)
                  <option value="{{ $invoiceTax->id }}" data-tax-rate="{{ $invoiceTax->tax_rate }}">{{ $invoiceTax->tax_title }} {{ $invoiceTax->tax_rate }}%</option>
                @endforeach
              </select>
              <input type="hidden" name="tax_values[]">
            </td>
            <td class="pt-8 text-end text-nowrap">₹
              <span data-kt-element="total">0.00</span>
              <input type="hidden" class="form-control form-control-solid text-end" name="total[]" />
            </td>
            <td class="pt-5 text-end">
              <button type="button" class="btn btn-sm btn-icon btn-active-color-primary" data-kt-element="remove-item">
                <i class="ki-duotone ki-trash fs-3"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span></i>
              </button>
            </td>
          </tr>
        `;

        $('table tbody').append(newItemRow);
        $('select[name="tax[]"]').last().select2({
          placeholder: "0.00"
        });
        $('select[name="tax[]"]').on('select2:select', function() {
          calculateTotals();
        });
      });

      // Remove item row
      $(document).on('click', '[data-kt-element="remove-item"]', function() {
        if ($('tr[data-kt-element="item"]').length < 2) {
          return
        }
        $(this).closest('tr[data-kt-element="item"]').remove();
        calculateTotals();
      });

      // Calculate totals when input values change
      $(document).on('input', 'input[name="quantity[]"], input[name="price[]"]', function() {
        calculateTotals();
      });

      $('select[name="tax[]"]').on('select2:select', function() {
        calculateTotals();
      });

      // Handle Add Tax button
      $('[data-kt-element="add-tax"]').on('click', function() {
        taxRate = prompt("Enter tax rate (%)");
        isTaxInPercentage = taxRate.slice(-1) === '%';
        const subtotal = parseFloat($('[data-kt-element="sub-total"]').first().text()) || 0;
        if (isTaxInPercentage) {
          taxValue = parseFloat(taxRate.slice(0, -1)) || 0;
          taxAmount = (subtotal * taxValue) / 100;
        } else {
          taxValue = parseFloat(taxRate) || 0;
          taxAmount = taxValue;
        }

        $('.additional-tax-amount').text(taxAmount.toFixed(2));
        $('.additional-tax-amount').removeClass('d-none');
        $('.additional-tax').removeClass('d-none');
        $('.additional-tax-rate').text(isTaxInPercentage ? `(${parseFloat(taxRate.slice(0, -1))}%)` :
          `(₹${parseFloat(taxRate)})`);

        calculateTotals();
        $(this).addClass('d-none');
      });

      // Handle Add Discount button
      $('[data-kt-element="add-discount"]').on('click', function() {
        discountRate = prompt("Enter discount amount");
        isDiscountInPercentage = discountRate.slice(-1) === '%';
        const subtotal = parseFloat($('[data-kt-element="sub-total"]').first().text()) || 0;

        if (isDiscountInPercentage) {
          discountValue = parseFloat(discountRate.slice(0, -1)) || 0;
          discountAmount = ((subtotal + taxAmount) * discountValue) / 100;
        } else {
          discountValue = parseFloat(discountRate) || 0;
          discountAmount = discountValue;
        }

        $('.additional-discount-amount').text(discountAmount.toFixed(2));
        $('.additional-discount-amount').removeClass('d-none');
        $('.additional-discount').removeClass('d-none');
        $('.additional-discount-rate').text(isDiscountInPercentage ?
          `(${parseFloat(discountRate.slice(0, -1))}%)` :
          `(₹${parseFloat(discountRate)})`);
        calculateTotals();
        $(this).addClass('d-none');
      });

      // Remove additional tax row
      $(document).on('click', '[data-kt-element="remove-additional-tax"]', function() {
        taxAmount = 0;
        isTaxInPercentage = false;
        taxValue = 0;
        taxRate = 0;
        $('.additional-tax-amount').text('0.00');
        $('.additional-tax-amount').addClass('d-none');
        $('.additional-tax').addClass('d-none');
        $('[data-kt-element="add-tax"]').removeClass('d-none');
        calculateTotals();
      });

      // Remove additional discount row
      $(document).on('click', '[data-kt-element="remove-additional-discount"]', function() {
        discountAmount = 0;
        isDiscountInPercentage = false;
        discountValue = 0;
        discountRate = 0;
        $('.additional-discount-amount').text('0.00');
        $('.additional-discount-amount').addClass('d-none');
        $('.additional-discount').addClass('d-none');
        $('[data-kt-element="add-discount"]').removeClass('d-none');
        calculateTotals();
      });
    });
  </script>
  <script>
    Dropzone.autoDiscover = false;
    let token = $('meta[name="csrf-token"]').attr('content');
    Dropzone.options.uploadForm = new Dropzone("#invoice-attachements", {
      url: "/dashboard/finance/invoices/{{ $invoice->id }}/update", // Temporary URL for initial upload
      paramName: "attachments",
      addRemoveLinks: true,
      autoProcessQueue: false,
      uploadMultiple: false,
      parallelUploads: 10,
      uploadMultiple: true,
      maxFiles: null, // Unlimited files
      maxFilesize: 5, // Max file size in MB
      params: {
        _token: token
      },
      resizeWidth: null,
      resizeHeight: null,
      resizeMimeType: null,
      resizeQuality: 1.0,
      init: function() {
        var submitButton = document.querySelector("#invoice-form-submit");
        var myDropzone = this;
        @if (isset($fileData))
          @foreach ($fileData as $image)

            var mockFile = {
              name: "{{ $image['name'] }}",
              size: "{{ $image['size'] }}",
              url: "{{ asset($image['path']) }}",
              type: "{{ pathinfo($image['path'], PATHINFO_EXTENSION) }}"
            };
            myDropzone.emit("addedfile", mockFile);
            myDropzone.emit("thumbnail", mockFile, mockFile.url);
            myDropzone.emit("complete", mockFile);
            // myDropzone.displayExistingFile(mockFile, "{{ asset($image['path']) }}");
            var hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'existing_images[]';
            hiddenInput.value = "{{ $image['path'] }}";
            hiddenInput.className = 'existing-image-input';
            document.querySelector('#invoice-form').appendChild(hiddenInput);
            // Add an event listener for removing existing images
            mockFile.previewElement.querySelector(".dz-remove").addEventListener("click", function() {
              // Remove the hidden input field for this image
              document.querySelectorAll('.existing-image-input').forEach(function(input) {
                if (input.value === "{{ $image['path'] }}") {
                  input.remove();
                }
              });

              // Optionally, mark the image for deletion on the server
              var removeInput = document.createElement('input');
              removeInput.type = 'hidden';
              removeInput.name = 'remove_images[]';
              removeInput.value = "{{ $image['path'] }}";
              document.querySelector('#invoice-form').appendChild(removeInput);
            });
          @endforeach
        @endif

        this.on("sendingmultiple", function(data, xhr, formData) {
          var formElements = document.querySelector("#invoice-form").elements;
          for (var i = 0; i < formElements.length; i++) {
            if (formElements[i].name) {
              formData.append(formElements[i].name, formElements[i].value);
            }
          }
          formData.append('status', currentStatus);
        });

        this.on("successmultiple", function(files, response) {
          window.location.href = "/dashboard/finance/invoices"
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

    var currentStatus = ''; // Global variable to hold the current status

    function handleFormSubmission(element, status) {
      event.preventDefault();
      event.stopPropagation();
      currentStatus = status; // Update the global status variable

      var submitButton = document.querySelector("#kt_invoice_submit_button");
      submitButton.setAttribute('data-kt-indicator', 'on');
      submitButton.disabled = true;

      var myDropzone = Dropzone.forElement("#invoice-attachements");

      if (myDropzone.getQueuedFiles().length > 0) {
        myDropzone.processQueue();
      } else {
        let formData = $('#invoice-form').serialize();
        formData += `&status=${status}`;
        $.ajax({
          url: $('#invoice-form').attr('action'),
          type: $('#invoice-form').attr('method'),
          data: formData,
          success: function(response) {
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
                window.location.href = "/dashboard/finance/invoices";
              }
            });
          },
          error: function(error) {
            var errors = error.responseJSON.errors;
            var errorMessage = Object.values(errors)
              .flat()
              .join("<br>");
            myDropzone.removeAllFiles(true);
            Swal.fire({
              html: errorMessage,
              icon: "error",
              buttonsStyling: false,
              confirmButtonText: "Ok, got it!",
              customClass: {
                confirmButton: "btn btn-primary",
              },
            });
            submitButton.setAttribute('data-kt-indicator', 'off');
            submitButton.disabled = false;
          }
        });
      }
    }
  </script>
@endsection
