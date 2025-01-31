<div class="card card-flush mt-10">
  <!--begin::Card body-->
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
          class="form-control form-control-solid w-175px w-md-350px ps-12" placeholder="Search Taxes" />
      </div>
      <!--end::Search-->
    </div>
    <!--end::Card title-->
    <!--begin::Card toolbar-->
    <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
      <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#kt_modal_add_tax">
        Add Tax
      </button>
    </div>
    <!--end::Card toolbar-->
  </div>
  <div class="card-body p-9">
    <table class="table align-middle table-row-dashed fs-6 gy-5" id="invoice_tax_table">
      <thead>
        <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
          <th class="w-10px pe-2">
            <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
              <input class="form-check-input" type="checkbox" data-kt-check="true"
                data-kt-check-target="#kt_ecommerce_sales_table .form-check-input" value="1" />
            </div>
          </th>
          <th class="text-center min-w-175px">Tax Title</th>
          <th class="text-center min-w-200px">Tax Rate (%)</th>
          <th class="text-end min-w-200px">Action</th>
        </tr>
      </thead>
      <tbody class="fw-semibold text-gray-600">
        @foreach ($invoiceTaxes as $invoiceTax)
          <tr>
            <td class="text-center">
              <div class="form-check form-check-sm form-check-custom form-check-solid">
                <input class="form-check-input" type="checkbox" value="1" />
              </div>
            </td>
            <td class="text-center">
              {{ $invoiceTax->tax_title }}
            </td>
            <td class="text-center">
              {{ $invoiceTax->tax_rate }}%
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
                  <a href="" class="menu-link px-3 edit_invoice_tax" data-tax-id="{{ $invoiceTax->id }}" data-bs-toggle="modal" data-bs-target="#kt_modal_edit_tax">Edit</a>
                </div>
                <!--end::Menu item-->
                <!--begin::Menu item-->
                <div class="menu-item px-3">
                  <form action="/dashboard/settings/taxes/{{ $invoiceTax->id }}/delete" method="post">
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
  </div>
</div>
