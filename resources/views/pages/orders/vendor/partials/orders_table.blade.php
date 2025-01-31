@foreach ($orders as $order)
  <tr>
    <td>
      <div class="form-check form-check-sm form-check-custom form-check-solid">
        <input class="form-check-input" type="checkbox" value="1" />
      </div>
    </td>
    <td data-kt-ecommerce-order-filter="order_id">
      <a id="kt_drawer_order_toggle" class="text-gray-800 text-hover-primary fw-bold">{{ $order->first()->order->id }}</a>
    </td>
    <td class="pe-0">
      <div class="symbol-group symbol-hover">
        @foreach ($order as $orderItem)
          @if ($loop->index < 6)
            <div class="symbol symbol-circle symbol-35px" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
              data-bs-placement="top" data-bs-html="true"
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
    <td class="pe-0">
      <span
        class="fw-bold">₹{{ $order->sum(function ($item) {
            return $item->price * $item->quantity;
        }) }}
      </span>
    </td>
    <td class="text-center" data-order="2024-01-12">
      <span class="fw-bold">{{ \Carbon\Carbon::parse($order->first()->order->created_at)->format('d/m/Y') }}</span><span
        class="text-muted d-block fw-bold">
        {{ \Carbon\Carbon::parse($order->first()->order->created_at)->format('h:i A') }}</span>
    </td>
    <td class="text-center" data-order="2024-01-10">
      <a href="/vendor/orders/{{ $order->first()->order->id }}" class="btn btn-info btn-sm">Details</a>
    </td>
  </tr>
@endforeach
<script src="{{ asset('assets/plugins/global/plugins.bundle.js') }}"></script>
<script src="{{ asset('assets/js/scripts.bundle.js') }}"></script>
