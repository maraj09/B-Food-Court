@foreach ($orders as $order)
  <tr>
    <td>
      <div class="form-check form-check-sm form-check-custom form-check-solid">
        <input class="form-check-input" type="checkbox" value="1" />
      </div>
    </td>
    <td data-kt-ecommerce-order-filter="order_id">
      <a id="kt_drawer_order_toggle" class="text-gray-800 text-hover-primary fw-bold">#{{ $order->id }}</a>
    </td>
    <td>
      <div class="d-flex align-items-center">
        <div class="flex-grow-1">
          <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-6">{{ $order->user->name }}</a>
          <span class="text-muted d-block fw-bold"><a href="tel:{{ $order->user->phone }}"
              class="text-gray-900 text-hover-primary fs-7">{{ $order->user->phone }}</a></span>
        </div>
      </div>
    </td>
    <td class="pe-0">
      <div class="symbol-group symbol-hover">
        @foreach ($order->orderItems as $orderItem)
          @if ($loop->index < 3)
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
        @if (count($order->orderItems) > 3)
          <a href="#" class="symbol symbol-35px symbol-circle" data-bs-toggle="tooltip"
            data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-html="true"
            title="View Items <span class='badge badge-primary'>{{ count($order->orderItems) - 3 }}</span>">
            <span class="symbol-label bg-dark text-gray-900 fs-8 fw-bold">+{{ count($order->orderItems) - 3 }}</span>
          </a>
        @endif
      </div>
    </td>
    <td>
      <div class="d-flex align-items-center">
        <div class="ms-2">
          <!--begin::Title-->
          @if ($order->status === 'paid')
            <span class="badge badge-warning">Paid</span>
          @elseif ($order->status === 'delivered')
            <span class="badge badge-success">Delivered</span>
          @elseif ($order->status === 'partial')
            <span class="badge badge-secondary">Partial Completed</span>
          @endif
          <!--end::Title-->
        </div>
      </div>
    </td>
    <td class="pe-0">
      <span class="fw-bold">₹{{ $order->order_amount }}</span>
    </td>
    <td class="text-center" data-order="2024-01-10">
      <span class="fw-bold">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</span><span
        class="text-muted d-block fw-bold">
        {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}</span>
    </td>
    <td class="text-center" data-order="UPI">
      @if ($order->payment_method === 'upi')
        <span class="badge badge-primary">UPI</span>
      @elseif ($order->payment_method === 'cash')
        <span class="badge badge-success">Cash</span>
      @elseif ($order->payment_method === 'card')
        <span class="badge badge-info">Card</span>
      @else
        <span class="badge badge-warning">{{ strtoupper($order->payment_method) }}</span>
      @endif
    </td>
    <td class="text-end">
      <div class="menu-item px-3">
        <a href="/dashboard/orders/{{ $order->id }}" class="menu-link px-3">View</a>
      </div>
      <div class="menu-item px-3">
        <form action="/dashboard/orders/{{ $order->id }}/delete" method="post">
          @csrf
          @method('delete')
          <a href="javascript:void(0)" class="menu-link px-3 text-danger" onclick="submitParentForm(this)">Delete</a>
        </form>
      </div>
    </td>
  </tr>
@endforeach
<!--end::Javascript-->
<script src="{{ asset('custom/assets/js/index.js') }}"></script>
