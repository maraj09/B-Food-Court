<div class="tab-pane fade {{ $lastSegment == 'orders' ? 'show active' : '' }}" id="kt_tab_pane_1" role="tabpanel">
  <!--begin::Products-->
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
            class="form-control form-control-solid w-175px w-md-350px ps-12" placeholder="Search Order" />
        </div>
        <!--end::Search-->
      </div>
      <!--end::Card title-->
      <!--begin::Card toolbar-->
      <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
        <h3 class="card-title align-items-center flex-column">
          <span
            class="card-label fw-bold fs-3 mb-1">{{ auth()->user()->orders()->where('status', '!=', 'unpaid')->count() }}</span>

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
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customer_profile_order_table">
          <thead>
            <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
              <th class="w-10px pe-2">

              </th>
              <th class="min-w-75px">Order ID</th>
              <th class="text-center min-w-100px">Date</th>
              <th class="min-w-150px">Products</th>
              <th class="min-w-100px">Total</th>
            </tr>
          </thead>
          <tbody class="fw-semibold text-gray-600">

            @foreach (auth()->user()->orders()->latest()->where('status', '!=', 'unpaid')->get() as $order)
              <tr>
                <td>
                  <div class="form-check form-check-sm form-check-custom form-check-solid">
                    <a href="#" id="kt_drawer_order_toggle" class="order-details-drawer"
                      data-order-id="{{ $order->id }}">
                      <i class="fa-solid fa-eye fs-2 text-info"></i>
                    </a>
                  </div>
                </td>
                <td data-kt-ecommerce-order-filter="order_id">
                  <a id="kt_drawer_order_toggle" data-order-id="{{ $order->id }}"
                    class="text-gray-800 text-hover-primary fw-bold order-details-drawer d-block">#{{ $order->custom_id }}</a>
                  <div class="order_{{ $order->id }}_status">
                    @if ($order->status === 'paid')
                      <span class="badge badge-warning">Paid</span>
                    @elseif ($order->status === 'pending')
                      <span class="badge badge-secondary">Partial Completed</span>
                    @elseif ($order->status === 'delivered')
                      <span class="badge badge-success">Delivered</span>
                    @elseif ($order->status === 'rejected')
                      <span class="badge badge-danger">Rejected</span>
                    @endif
                  </div>
                </td>
                <td class="text-center" data-order="{{ $order->created_at }}">
                  <span class="fw-bold">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</span><span
                    class="text-muted d-block fw-bold">
                    {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}</span>
                </td>
                <td class="pe-0">
                  <div class="symbol-group symbol-hover">
                    @foreach ($order->orderItems as $orderItem)
                      @if ($loop->index < 3)
                        @if ($orderItem->item_id)
                          <div class="symbol symbol-circle symbol-35px" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-html="true"
                            title="{{ $orderItem->item->item_name }} <span class='badge badge-primary'>{{ $orderItem->quantity }}</span>">
                            @if ($orderItem->item->item_image)
                              <img src="{{ asset($orderItem->item->item_image) }}" alt="" />
                            @else
                              <img src="{{ asset('assets/media/svg/files/blank-image-dark.svg') }}" alt="" />
                            @endif
                          </div>
                        @elseif ($orderItem->event_id)
                          <div class="symbol symbol-circle symbol-35px" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-html="true"
                            title="{{ $orderItem->event->title }} <span class='badge badge-primary'>{{ $orderItem->quantity }}</span>">
                            @if ($orderItem->event->image)
                              <img src="{{ asset($orderItem->event->image) }}" alt="" />
                            @else
                              <img src="{{ asset('assets/media/svg/files/blank-image-dark.svg') }}" alt="" />
                            @endif
                          </div>
                        @elseif ($orderItem->play_area_id)
                          <div class="symbol symbol-circle symbol-35px" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-html="true"
                            title="{{ $orderItem->playArea->title }} <span class='badge badge-primary'>{{ $orderItem->quantity }}</span>">
                            @if ($orderItem->playArea->image)
                              <img src="{{ asset($orderItem->playArea->image) }}" alt="" />
                            @else
                              <img src="{{ asset('assets/media/svg/files/blank-image-dark.svg') }}" alt="" />
                            @endif
                          </div>
                        @endif
                      @endif
                    @endforeach
                    @if (count($order->orderItems) > 3)
                      <a href="#" id="kt_drawer_order_toggle"
                        class="symbol symbol-35px symbol-circle order-details-drawer"
                        data-order-id="{{ $order->id }}" data-bs-toggle="tooltip"
                        data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-html="true"
                        title="View Items <span class='badge badge-primary'>{{ count($order->orderItems) - 3 }}</span>">
                        <span
                          class="symbol-label bg-dark text-gray-900 fs-8 fw-bold">+{{ count($order->orderItems) - 3 }}</span>
                      </a>
                    @endif
                  </div>
                </td>
                <td class="pe-0" data-order="{{ $order->net_amount }}">
                  <span class="fw-bold">₹{{ $order->net_amount }}</span>
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
  <!--end::Products-->
</div>
