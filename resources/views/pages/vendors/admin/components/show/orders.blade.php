<div class="tab-pane fade {{ $lastSegment == 'orders' ? 'active show' : '' }} " id="kt_ecommerce_vendor_orders"
  role="tabpanel">
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
            class="form-control form-control-solid w-175px w-md-350px ps-12" placeholder="Search Order" name="search"
            id="searchInput" />

        </div>
        <!--end::Search-->
      </div>
      <!--end::Card title-->
      <!--begin::Card toolbar-->
      <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
        <h3 class="card-title align-items-center flex-column">
          <span class="card-label fw-bold fs-3 mb-1">{{ $orders->count() }}</span>

          <span class="text-muted fw-semibold fs-7">Total Orders</span>
        </h3>
      </div>
      <!--end::Card toolbar-->
    </div>
    <!--end::Card header-->
    <!--begin::Card body-->
    <div class="card-body pt-0">
      <!--begin::Table-->
      <div class="table-responsive">
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table"
          data-kt-ecommerce-order-filter="order_id" data-kt-ecommerce-order-filter="item_name">
          <thead>
            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
              <th class="w-10px pe-2">
                <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                  <input class="form-check-input" type="checkbox" data-kt-check="true"
                    data-kt-check-target="#kt_ecommerce_sales_table .form-check-input" value="1" />
                </div>
              </th>
              <th class="min-w-75px">Order ID</th>
              <th class="min-w-200px">Items</th>
              <th class="min-w-100px">Total</th>
              <th class="text-center min-w-100px">Date</th>
              <th class="text-center min-w-100px">Status</th>
            </tr>
          </thead>
          <tbody class="fw-semibold text-gray-600">
            @foreach ($orders as $order)
              <tr>
                <td>
                  <div class="form-check form-check-sm form-check-custom form-check-solid">
                    <input class="form-check-input" type="checkbox" value="1" />
                  </div>
                </td>
                <td data-kt-ecommerce-order-filter="order_id">
                  <a id="kt_drawer_order_toggle" data-order-id="{{ $order->first()->order_id }}"
                    data-vendor-id="{{ $user->vendor->id }}"
                    class="text-gray-800 text-hover-primary fw-bold order-details-drawer">#{{ $order->first()->order->custom_id }}</a>
                </td>
                <td class="pe-0">
                  <div class="symbol-group symbol-hover">
                    @foreach ($order as $orderItem)
                      @if ($loop->index < 6)
                        <div class="symbol symbol-circle symbol-35px" data-bs-toggle="tooltip"
                          data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-html="true"
                          title="{{ $orderItem->item->item_name }} <span class='badge badge-primary'>{{ $orderItem->quantity }}</span>">

                          @if ($orderItem->item->item_image)
                            <img src="{{ asset($orderItem->item->item_image) }}" alt="" />
                          @else
                            <img src="{{ asset('assets/media/svg/files/blank-image-dark.svg') }}" alt="" />
                          @endif
                        </div>
                      @endif
                    @endforeach
                    @if (count($order) >= 6)
                      <a href="#" class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip"
                        data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-html="true"
                        title="View Items <span class='badge badge-primary'>{{ count($order) - 6 }}</span>">
                        <span class="symbol-label bg-dark text-gray-900 fs-8 fw-bold">+{{ count($order) - 6 }}</span>
                      </a>
                    @endif
                  </div>
                </td>
                <td class="pe-0"
                  data-order="{{ $order->sum(function ($item) {
                      return $item->price * $item->quantity;
                  }) }}">
                  <span
                    class="fw-bold">₹{{ $order->sum(function ($item) {
                        return $item->price * $item->quantity;
                    }) }}
                  </span>
                </td>
                <td class="text-center" data-order="{{ $order->first()->order->created_at }}">
                  <span
                    class="fw-bold">{{ \Carbon\Carbon::parse($order->first()->order->created_at)->format('d/m/Y') }}</span><span
                    class="text-muted d-block fw-bold">
                    {{ \Carbon\Carbon::parse($order->first()->order->created_at)->format('h:i A') }}</span>
                </td>
                <td class="text-center">
                  @if ($order->first()->order->status === 'paid')
                    <span class="badge badge-warning">Paid</span>
                  @elseif ($order->first()->order->status === 'pending')
                    <span class="badge badge-secondary">Partial Completed</span>
                  @elseif ($order->first()->order->status === 'delivered')
                    <span class="badge badge-success">Delivered</span>
                  @elseif ($order->first()->order->status === 'rejected')
                    <span class="badge badge-danger">Rejected</span>
                  @endif
                  {{-- <a href="/vendor/orders/{{ $order->first()->order->id }}" class="btn btn-info btn-sm">Details</a> --}}
                </td>
              </tr>
            @endforeach
          </tbody>
        </table>
      </div>
      <!--end::Table-->
    </div>
    <!--end::Card body-->
  </div>
</div>
