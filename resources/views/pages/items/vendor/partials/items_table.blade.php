@foreach ($items as $item)
  <tr>
    <td>
      <div class="form-check form-check-sm form-check-custom form-check-solid">
        <input class="form-check-input" type="checkbox" value="1">
      </div>
    </td>
    <td data-kt-ecommerce-order-filter="order_id">
      <div class="d-flex align-items-center">
        <!--begin:: Avatar -->
        <div class="symbol symbol-circle symbol-35px">
          <a href="/vendor/items/{{ $item->id }}">
            <div class="symbol-label fs-4 bg-light-danger text-danger">
              @if ($item->item_image)
                <img src="{{ asset($item->item_image) }}" width="35px" height="35px"
                  class="object-contain symbol symbol-circle" alt="User Avatar">
              @else
                <img src="{{ asset('assets/media/svg/files/blank-image-dark.svg') }}" width="35px" height="35px"
                  alt="Default Avatar" class="symbol symbol-circle">
              @endif
            </div>
          </a>
        </div>
        @php
          // Array of available Bootstrap badge classes
          $badgeClasses = ['badge-primary', 'badge-success', 'badge-danger', 'badge-warning', 'badge-info'];

          // Randomly select a badge class
          $randomBadgeClass = $badgeClasses[array_rand($badgeClasses)];
        @endphp
        <!--end::Avatar-->
        <div class="ms-2">
          <!--begin::Title-->
          <a href="/vendor/items/{{ $item->id }}"
            class="text-gray-800 text-hover-primary fs-6 fw-bold d-block">{{ $item->item_name }}</a>
          <span class="badge {{ $randomBadgeClass }}" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
            data-bs-placement="top" data-bs-original-title="Category"
            data-kt-initialized="1">{{ $item->category->name }}</span>
          <!--end::Title-->
        </div>
      </div>
    </td>
    <td class="pe-0 text-center">
      <span class="fw-bold">₹{{ $item->price }}</span>
    </td>
    <td class="pe-0 text-center">
      <span class="fw-bold text-success">{{ $item->orderItems->count() }} Times</span>
    </td>
    <td class="text-center" data-order="2024-01-10">
      <div class="badge badge-light-success fw-bold fs-6" data-bs-toggle="tooltip"
        data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-original-title=" Total Earn"
        data-kt-initialized="1">
        ₹{{ $item->orderItems->sum(function ($item) {
            return $item->quantity * $item->price;
        }) }}
      </div>
    </td>
    <td class="text-center">
      <span class="text-muted d-block fw-bold"> {{ $item->ratings->count() }} Reviews </span>
      <span class="badge badge-warning" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
        data-bs-placement="top" data-bs-original-title="Ratings"
        data-kt-initialized="1">{{ $item->itemRating->rating ?? 0 }}<i
          class="fa-regular fa-star-half-stroke text-gray-900 ms-2"></i></span>
    </td>
    <td class="text-center">
      <span class="fw-bold">{{ $item->created_at->format('d-m-Y') }}</span>
    </td>
    <td class="text-center">
      <span
        class="{{ $item->approve ? 'text-success' : 'text-danger' }}">{{ $item->approve ? 'True' : 'False' }}</span>
    </td>
    <td class="text-end">
      <div class="form-check form-switch form-check-custom form-check-success form-check-solid">
        <input class="form-check-input status-checkbox h-15px w-30px" type="checkbox" value=""
          id="flexSwitch20x30" {{ $item->status === 1 ? 'checked' : '' }} data-item-id="{{ $item->id }}">
      </div>
    </td>
  </tr>
@endforeach
<script>
  $(".status-checkbox").change(function() {
    // Get the item ID from the data attribute
    var itemId = $(this).data("item-id");

    // Save the reference to $(this) in a variable for later use
    var $checkbox = $(this);

    // Make an AJAX request to update the status
    $.ajax({
      url: "/vendor/update-item-status/" + itemId, // Replace with your actual route
      type: "POST", // You can use 'PUT' or 'PATCH' depending on your setup
      data: {
        // Additional data to send, if any
      },
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      success: function(response) {
        showToast(response);
      },
      error: function(error) {
        console.error(error);
        showToast("error");
      },
    });
  });
</script>
