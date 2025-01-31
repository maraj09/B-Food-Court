<div class="card-footer d-flex flex-wrap p-0">
  <a href="#" id="kt_drawer_shopping_cart_toggle" class="btn btn-danger w-100 fw-bold flex-1">Place Order
    <span class="badge badge-primary badge ms-2 view-cart-span">{{ auth()->user()->cartItems->count() }}
      Items</span></a>
  @php
    $latestOrder = \App\Models\Order::where('user_id', auth()->id())
        ->whereNotIn('status', ['unpaid', 'delivered'])
        ->orderBy('created_at', 'desc')
        ->first();
  @endphp
  @if ($latestOrder)
    <div id="kt_carousel_2_carousel" class="carousel carousel-custom slide bg-light-dark pointer-event flex-1 w-100"
      data-bs-ride="carousel" data-bs-interval="3000">
      <!--begin::Heading-->
      <div class="d-flex align-items-center justify-content-between flex-wrap">
        <!--begin::Label-->
        <span class="fs-5 fw-bold text-gray-600 ps-2">Order ID : <span
            class="fs-5 fw-bold text-gray-900 ps-2">#{{ $latestOrder->custom_id }}</span>
          <span class="badge badge-primary badge">{{ $latestOrder->orderItems()->whereNot('item_id', null)->count() }}
            Items</span>
        </span>
        <!--end::Label-->
        <!--begin::Carousel Indicators-->
        <ol
          class="p-0 m-0 carousel-indicators carousel-indicators-bullet carousel-indicators-active-danger align-middle">
          @foreach ($latestOrder->orderItems()->whereNot('item_id', null)->get() as $index => $orderItem)
            <li data-bs-target="#kt_carousel_2_carousel" data-bs-slide-to="{{ $index }}"
              class="ms-1 {{ $index === 0 ? 'active' : '' }}" aria-current="true"></li>
          @endforeach
        </ol>
        <!--end::Carousel Indicators-->
      </div>
      <!--end::Heading-->
      <!--begin::Carousel-->
      <div class="carousel-inner align-middle p-2">
        @foreach ($latestOrder->orderItems()->whereNot('item_id', null)->get() as $index => $orderItem)
          <div class="carousel-item {{ $index === 0 ? 'active' : '' }}">
            <a href="#" class="fs-6 text-gray-800 text-hover-primary fw-bold">{{ $orderItem->item->item_name }}
              <span class="text-muted ms-1">x {{ $orderItem->quantity }}</span></a>
            <span class="badge badge-outline badge-warning ms-1">Stall No.
              {{ $orderItem->item->vendor->stall_no }}</span>
            <span class="ms-1 order_item_{{ $orderItem->id }}_status">
              <span
                class="badge 
              @if ($orderItem->status == 'accepted') badge-info
              @elseif($orderItem->status == 'completed')
                  badge-primary
              @elseif($orderItem->status == 'delivered')
                  badge-success
              @elseif($orderItem->status == 'rejected')
                  badge-danger
              @else
                  badge-warning @endif
          ">{{ ucfirst($orderItem->status) }}</span>
            </span>
          </div>
        @endforeach
      </div>
      <!--end::Carousel-->
    </div>
  @endif
</div>
