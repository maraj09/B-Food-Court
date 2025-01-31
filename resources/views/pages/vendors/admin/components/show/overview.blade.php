<div class="tab-pane fade {{ $lastSegment == 'edit' ? '' : 'active show' }} " id="kt_ecommerce_customer_overview"
  role="tabpanel">
  <div class="row row-cols-1 row-cols-md-2 mb-6 mb-xl-9">
    <div class="col-md-4">
      <!--begin::Card-->
      <div class="card pt-4 h-md-100 mb-6 mb-md-0">
        <!--begin::Card header-->
        <div class="card-header border-0">
          <!--begin::Card title-->
          <div class="card-title">
            <h2 class="fw-bold ps-4">Commission</h2>
          </div>
          <!--end::Card title-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body pt-0">
          <div class="fw-bold fs-2">
            <div class="d-flex">
              <i class="ki-duotone ki-heart text-info fs-2x"><span class="path1"></span><span
                  class="path2"></span></i>
              <div class="ms-2">
                {{ $user->vendor->commission }} <span class="text-muted fs-4 fw-semibold">%</span>
              </div>
            </div>
            <div class="fs-7 fw-normal text-muted">Earn reward points with every purchase.</div>
          </div>
        </div>
        <!--end::Card body-->
      </div>
      <!--end::Card-->
    </div>

    <div class="col-md-8">
      <!--begin::Card-->
      <div class="card pt-4 h-md-100 mb-6 mb-md-0">
        <!--begin::Card header-->
        <div class="card-header border-0">
          <!--begin::Card title-->
          <div class="card-title">
            <h2 class="fw-bold ps-4">Details</h2>
          </div>
          <!--end::Card title-->
        </div>
        <!--end::Card header-->

        <!--begin::Card body-->
        <div class="card-body pt-0">
          {!! $user->vendor->details !!}
        </div>
        <!--end::Card body-->
      </div>
      <!--end::Card-->
    </div>


  </div>


  <!--begin::Card-->
  <div class="card pt-4 mb-6 mb-xl-9">
    <!--begin::Card header-->
    <div class="card-header border-0">
      <!--begin::Card title-->
      <div class="card-title">
        <h2>Transaction History</h2>
      </div>
      <!--end::Card title-->
    </div>
    <!--end::Card header-->

    <!--begin::Card body-->
    <div class="card-body pt-0 pb-5">
      <!--begin::Table-->
      <div id="kt_table_customers_payment_wrapper" class="dataTables_wrapper dt-bootstrap4 no-footer">
        <div class="table-responsive">
          <table class="table align-middle table-row-dashed gy-5 dataTable no-footer" id="kt_table_customers_payment">
            <thead class="border-bottom border-gray-200 fs-7 fw-bold">
              <tr class="text-start text-muted text-uppercase gs-0">
                <th class="min-w-100px sorting" tabindex="0" aria-controls="kt_table_customers_payment" rowspan="1"
                  colspan="1" aria-label="order No.: activate to sort column ascending" style="width: 144.578px;">
                  order No.</th>
                <th class="sorting" tabindex="0" aria-controls="kt_table_customers_payment" rowspan="1"
                  colspan="1" aria-label="Status: activate to sort column ascending" style="width: 122.688px;">Status
                </th>
                <th class="sorting" tabindex="0" aria-controls="kt_table_customers_payment" rowspan="1"
                  colspan="1" aria-label="Amount: activate to sort column ascending" style="width: 118.375px;">Amount
                </th>
                <th class="min-w-100px sorting" tabindex="0" aria-controls="kt_table_customers_payment" rowspan="1"
                  colspan="1" aria-label="Rewards: activate to sort column ascending" style="width: 148.922px;">
                  Payment Method</th>
                <th class="min-w-100px sorting_disabled" rowspan="1" colspan="1" aria-label="Date"
                  style="width: 227.188px;">Date</th>
              </tr>
            </thead>
            <tbody class="fs-6 fw-semibold text-gray-600">
              @foreach ($user->vendor->payouts as $payout)
                <tr class="odd">
                  <td>
                    <a href="/metronic8/demo1/apps/ecommerce/sales/details.html"
                      class="text-gray-600 text-hover-primary mb-1">#{{ $payout->id }}</a>
                  </td>
                  <td>
                    <span class="badge badge-light-success">{{ $payout->status }}</span>
                  </td>
                  <td data-order="{{ $payout->request_amount }}">
                    ₹{{ $payout->request_amount }} </td>
                  <td>
                    {{ $payout->payment_mode }} </td>
                  <td>
                    {{ $payout->created_at }}</td>
                </tr>
              @endforeach
            </tbody>
          </table>
        </div>
      </div>
      <!--end::Table-->
    </div>
    <!--end::Card body-->
  </div>
  <!--end::Card-->
</div>
