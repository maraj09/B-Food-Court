<div class="tab-pane fade {{ $lastSegment == 'edit' ? '' : 'active show' }} " id="kt_ecommerce_customer_overview"
  role="tabpanel">
  <div class="row row-cols-1 row-cols-md-2 mb-6 mb-xl-9">
    <div class="col">
      <!--begin::Card-->
      <div class="card pt-4 h-md-100 mb-6 mb-md-0">
        <!--begin::Card header-->
        <div class="card-header border-0">
          <!--begin::Card title-->
          <div class="card-title">
            <h2 class="fw-bold">Customer Points</h2>
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
                {{ $user->point->points ?? 0 }} <span class="text-muted fs-4 fw-semibold">Current Points</span>
                <a href="#" class="btn btn-icon btn-active-light-primary w-30px h-30px ms-10"
                  data-bs-toggle="modal" data-bs-target="#kt_modal_update_address">
                  <span data-bs-toggle="tooltip" data-bs-trigger="hover" title="Edit">
                    <i class="ki-duotone ki-pencil fs-3">
                      <span class="path1"></span>
                      <span class="path2"></span>
                    </i>
                  </span>
                </a>
              </div>
            </div>
            <div class="fs-7 fw-normal text-muted">Earn reward points with every purchase.</div>
          </div>
        </div>
        <!--end::Card body-->
      </div>
      <!--end::Card-->
    </div>

    <div class="col">
      <!--begin::Reward Tier-->
      <a href="#" class="card bg-info hoverable h-md-100">
        <!--begin::Body-->
        <div class="card-body">
          <i class="ki-duotone ki-award text-white fs-3x ms-n1"><span class="path1"></span><span
              class="path2"></span><span class="path3"></span></i>
          <div class="text-white fw-bold fs-2 mt-5">
            Premium Member
          </div>

          <div class="fw-semibold text-white">
            Tier Milestone Reached
          </div>
        </div>
        <!--end::Body-->
      </a>
      <!--end::Reward Tier-->
    </div>
  </div>


  <!--begin::Card-->
  <div class="card pt-4 mb-6 mb-xl-9">
    <!--begin::Card header-->
    <div class="card-header border-0">
      <!--begin::Card title-->
      <div class="card-title">
        <h2>Order History</h2>
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
                <th class="min-w-100px sorting_disabled" rowspan="1" colspan="1" aria-label="Date"
                  style="width: 227.188px;">Date</th>
              </tr>
            </thead>
            <tbody class="fs-6 fw-semibold text-gray-600">
              @foreach ($user->orders()->where('status', '!=', 'unpaid')->latest()->get() as $order)
                <tr class="odd">
                  <td data-order="{{ $order->custom_id }}">
                    <a href="/dashboard/orders/{{ $order->id }}"
                      class="text-gray-600 text-hover-primary mb-1">#{{ $order->custom_id }}</a>
                  </td>
                  <td>
                    @if ($order->status === 'paid')
                      <span class="badge badge-warning">Paid</span>
                    @elseif ($order->status === 'delivered')
                      <span class="badge badge-success">Delivered</span>
                    @elseif ($order->status === 'partial')
                      <span class="badge badge-secondary">Partial Completed</span>
                    @elseif ($order->status === 'rejected')
                      <span class="badge badge-danger">Rejected</span>
                    @elseif ($order->status === 'accepted')
                      <span class="badge badge-info">Accepted</span>
                    @elseif ($order->status === 'pending')
                      <span class="badge badge-warning">Pending</span>
                    @elseif ($order->status === 'completed')
                      <span class="badge badge-primary">Completed</span>
                    @endif
                  </td>
                  <td data-order="{{ $order->net_amount }}">₹{{ $order->net_amount }}</td>
                  <td data-order="{{ $order->created_at }}">
                    {{ \Carbon\Carbon::parse($order->created_at)->format('d M Y, g:i A') }} </td>
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
