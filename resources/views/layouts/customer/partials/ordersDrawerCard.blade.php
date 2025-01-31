<div class="card w-100 border-0 rounded-0" id="kt_drawer_chat_messenger">
  <!--begin::Card header-->
  <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
    <!--begin::Title-->
    <div class="card-title">
      <!--begin::User-->
      <div class="d-flex justify-content-center flex-column me-3">
        <a id="kt_drawer_order_toggle"
          class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1">#{{ $order->custom_id }}</a>
        <!--begin::Info-->
        <div class="mb-0 lh-1">
          <span class="badge badge-success badge-circle w-10px h-10px me-1" data-bs-toggle="tooltip"
            data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Order Delivered"></span>
          <span class="fs-7 fw-semibold text-muted">Order ID</span>
        </div>
        <!--end::Info-->
      </div>
      <!--end::User-->
    </div>
    <!--end::Title-->
    <!--begin::Card toolbar-->
    <div class="card-toolbar">
      <!--begin::User Info-->
      <div class="me-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
        title="Print Bill">
        <i class="las la-print text-gray-900 fs-1"></i>
      </div>
      <!--end::User Info-->
      <!--begin::Order Date-->
      <div class="me-0">
        <span class="fw-bold fs-8">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</span><span
          class="text-muted d-block fw-bold fs-8">
          {{ \Carbon\Carbon::parse($order->created_at)->format('h:i A') }}</span>
      </div>
      <!--end::Order Date-->
      <!--begin::Close-->
      <div class="btn btn-sm btn-icon btn-active-color-primary" id="kt_drawer_order_close">
        <i class="ki-duotone ki-cross-square fs-2">
          <span class="path1"></span>
          <span class="path2"></span>
        </i>
      </div>
      <!--end::Close-->
    </div>
    <!--end::Card toolbar-->
  </div>
  <!--end::Card header-->
  <!--begin::Card body-->
  <div class="card-body p-2 scroll h-auto">
    <!--begin::row-->
    <div class="row">
      <div class="col px-5">
        <!--begin::Item-->
        <!--end::Item-->
        @php
          $orginalAmount = 0;
        @endphp
        @foreach ($order->orderItems()->whereNot('item_id', null)->get() as $item)
          @php
            $totalAmount = $item->item->price * $item->quantity;
            $orginalAmount += $totalAmount;
          @endphp
          <div class="d-block align-items-center border-dashed border-gray-900 bg-light rounded p-3 mb-7">
            <div class="d-flex align-items-sm-center mb-2">
              <!--begin::Symbol-->
              <div class="symbol symbol-50px symbol-circle me-2">
                <img src="{{ asset($item->item->item_image) }}" class="align-self-center" alt="">
              </div>
              <!--end::Symbol-->
              <!--begin::Section-->
              <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                <div class="flex-grow-1 me-5">
                  <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">{{ $item->item->item_name }}
                    <span class="badge badge-square badge-info ms-2" data-bs-toggle="tooltip"
                      data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                      title="Quantity">{{ $item->quantity }}</span></a>
                  <div class="d-flex flex-wrap flex-grow-1">
                    <div class="me-2">
                      <span class="text-success fw-bold">Price</span>
                      <span class="fw-bold text-gray-800 d-block fs-6">₹{{ round($item->price) }}</span>
                    </div>
                    <div class="me-5s">
                      <span class="text-danger fw-bold">Total</span>
                      <span
                        class="fw-bold text-gray-800 d-block fs-6">₹{{ round($item->price * $item->quantity) }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <span>
                <div class="me-2 text-end">
                  <span class="text-info fw-bold">Vendor</span>
                  <span class="fw-bold text-gray-800 d-block fs-6">{{ $item->item->vendor->brand_name }}<span
                      class="badge badge-primary badge-sm ms-2" data-bs-toggle="tooltip"
                      data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                      title="Stall No.">{{ $item->item->vendor->stall_no }}</span></span>
                </div>
              </span>
              <!--end::Section-->
            </div>
            <div class="d-flex flex-stack flex-wrap flex-grow-1">
              <div class="fw-bold fs-3 text-info">
                @if ($item->status == 'accepted')
                  <span class="badge badge-info">Accepted</span>
                @elseif($item->status == 'completed')
                  <span class="badge badge-primary">Completed</span>
                @elseif($item->status == 'delivered')
                  <span class="badge badge-success">Delivered</span>
                @elseif($item->status == 'rejected')
                  <span class="badge badge-danger">Rejected</span>
                @else
                  <span class="badge badge-warning">Pending</span>
                @endif
              </div>
              <div class="me-2 text-end" id="kt_accordion_2">
                @php
                  $userRating = $item->item->ratings
                      ->where('order_id', $item->order->id)
                      ->where('user_id', auth()->id())
                      ->first();
                @endphp
                @if (!is_null($userRating?->rating) && $item->status == 'delivered')
                  <span class="text-gray-900 fw-bold">Rating</span>
                  <i class="fa fa-star-half-alt text-warning fs-5"></i>
                  <span class="text-gray-800 fw-bold">{{ $userRating?->rating }}</span>
                @elseif (is_null($userRating?->rating) && $item->status == 'delivered')
                  <span class="text-gray-900 fw-bold accordion-header collapsed text-hover-primary cursor-pointer"
                    data-bs-toggle="collapse" data-bs-target="#kt_accordion_2_item_{{ $loop->index }}">
                    Please Rate Us <i class="ki-duotone ki-down fs-1 rotate-180 ms-2"></i></span>
                @else
                  <span class="text-gray-900 fw-bold">No Rating</span>
                @endif
              </div>
            </div>
            <div class="accordion accordion-icon-toggle">
              <!--begin::Item-->
              <div class="mt-5 mb-3">
                <!--begin::Body-->
                <div id="kt_accordion_2_item_{{ $loop->index }}" class="collapse fs-6"
                  data-bs-parent="#kt_accordion_2">
                  <div class="rating mb-2 rating-active">
                    @for ($i = 1; $i <= 5; $i++)
                      <i class="far fa-star text-warning" style="font-size: 22px"
                        data-rating="{{ $i }}"></i>
                    @endfor
                    <input type="hidden" name="item_id" value="{{ $item->item->id }}">
                    <input type="hidden" name="order_id" value="{{ $item->order->id }}">
                    <input type="hidden" name="order_item_id" value="{{ $item->id }}">
                  </div>
                  @if ($item->status == 'delivered' && is_null($userRating?->review))
                    <form class="review-form">
                      <input type="hidden" name="item_id" value="{{ $item->item->id }}">
                      <input type="hidden" name="order_id" value="{{ $item->order->id }}">
                      <input type="hidden" name="order_item_id" value="{{ $item->id }}">
                      <textarea class="form-control" name="review" data-kt-autosize="true" placeholder="Add Your Review"></textarea>
                      <button class="btn btn-warning btn-sm mt-3">Submit</button>
                    </form>
                  @else
                    <div class="mt-3">
                      {{ $userRating?->review }}
                    </div>
                  @endif
                </div>
                <!--end::Body-->
              </div>
              <!--end::Item-->
            </div>
          </div>
        @endforeach
        @foreach ($order->orderItems()->whereNot('play_area_id', null)->get() as $playArea)
          @php
            $start = \Carbon\Carbon::parse($playArea->play_area_start_time ?? 0);
            $end = \Carbon\Carbon::parse($playArea->play_area_end_time ?? 0);

            $duration = $start->diff($end);
            $durationFormatted = $duration->h . 'H';
            if ($duration->i > 0) {
                $durationFormatted .= ' ' . $duration->i . 'Min';
            }
            // Calculate the difference in minutes
            $durationInMinutes = $start->diffInMinutes($end);

            // Convert minutes to hours (including fractions)
            $durationInHours = $durationInMinutes / 60;

            // Get the price per hour from the play area
            $pricePerHour = $playArea->playArea->price;

            // Get the number of players
            $playersCount = $playArea->quantity ?? 1;

            // Calculate the total price
            $totalAmount = round($pricePerHour * $durationInHours * $playersCount);
            $orginalAmount += $totalAmount;
          @endphp
          <div class="d-block align-items-center border-dashed border-gray-900 bg-light rounded p-3 mb-7">
            <div class="d-flex align-items-sm-center mb-2">
              <!--begin::Symbol-->
              <div class="symbol symbol-50px symbol-2by3 me-2">
                <img src="{{ asset($playArea->playArea->image) }}" class="align-self-center" alt="">
              </div>
              <!--end::Symbol-->
              <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                <div class="flex-grow-1 me-5">
                  <a href="#"
                    class="text-gray-800 text-hover-primary fs-6 fw-bold">{{ $playArea->playArea->title }}</a>
                  <div class="d-flex flex-wrap flex-grow-1">
                    <div class="me-2">
                      <span class="text-success fw-bold">Price</span>
                      <span
                        class="fw-bold text-gray-800 d-block fs-6">₹{{ round($playArea->playArea->price) }}/Hour/Player</span>
                    </div>
                    <div class="mx-3">
                      <span class="text-warning fw-bold">Hours</span>
                      <span class="fw-bold text-gray-800 text-center d-block fs-6">{{ $durationFormatted }}</span>
                    </div>
                    <div class="mx-2">
                      <span class="text-info fw-bold">Players</span>
                      <span class="fw-bold text-gray-800 text-center d-block fs-6">{{ $playArea->quantity }}P</span>
                    </div>
                    <div class="mx-3">
                      <span class="text-danger fw-bold">Total</span>
                      <span class="fw-bold text-gray-800 text-center d-block fs-6">₹{{ $totalAmount }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <!--end::Section-->
            </div>
            <!--end::Info-->
            <div class="d-flex align-items-start flex-stack flex-wrap flex-grow-1 py-2">
              <div class="fs-3 text-info w-150px form-group">
                <div class="position-relative d-flex align-items-center DatePickerContainer">
                  <!--begin::Icon-->
                  <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                    <span class="path3"></span>
                    <span class="path4"></span>
                    <span class="path5"></span>
                    <span class="path6"></span>
                  </i>
                  <!--end::Icon-->

                  <!--begin::Datepicker-->
                  <input class="form-control form-control-sm kt_datepicker_dob_custom ps-12 datePicker"
                    placeholder="Pick date" value="{{ $playArea->play_area_date ?? '' }}" disabled />
                  <!--end::Datepicker-->
                </div>
                <div class="invalid-feedback"></div>
              </div>
              <div class="me-2 w-25 form-group">
                <div class="StartPickerContainer">
                  <input class="form-control form-control-sm flatpickr-input startTimePicker" placeholder="Start Time"
                    type="text" disabled readonly="readonly" value="{{ $playArea->play_area_start_time ?? '' }}">
                  <div class="invalid-feedback"></div>
                </div>
              </div>
              <div class="me-2 w-25 form-group">
                <div class="EndPickerContainer">
                  <input class="form-control form-control-sm flatpickr-input endTimePicker" placeholder="End Time"
                    type="text" readonly="readonly" disabled value="{{ $playArea->play_area_end_time ?? '' }}">
                  <div class="invalid-feedback"></div>
                </div>
              </div>
            </div>
          </div>
        @endforeach
        @foreach ($order->orderItems()->whereNot('event_id', null)->get() as $event)
          @php
            $totalAmount = $event->event->price * $event->quantity;
            $orginalAmount += $totalAmount;
          @endphp
          <div class="d-block align-items-center border-dashed border-gray-900 bg-light rounded p-3 mb-7">
            <div class="d-flex align-items-sm-center mb-2">
              <!--begin::Symbol-->
              <div class="symbol symbol-50px symbol-circle me-2">
                <img src="{{ $event->event->image }}" class="align-self-center" alt="">
              </div>
              <!--end::Symbol-->
              <!--begin::Section-->
              <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                <div class="flex-grow-1 me-5">
                  <a href="#"
                    class="text-gray-800 text-hover-primary fs-6 fw-bold">{{ $event->event->title }}<span
                      class="badge badge-square badge-info ms-2" data-bs-toggle="tooltip"
                      data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                      title="Booked">{{ $event->quantity }}</span></a>
                  <div class="d-flex flex-wrap flex-grow-1">
                    <div class="me-2">
                      <span class="text-success fw-bold">Price</span>
                      <span class="fw-bold text-gray-800 d-block fs-6">₹{{ round($event->event->price) }}</span>
                    </div>
                    <div class="me-5s">
                      <span class="text-danger fw-bold">Total</span>
                      <span
                        class="fw-bold text-gray-800 d-block fs-6">₹{{ round($event->event->price * (int) $event->quantity) }}</span>
                    </div>
                  </div>
                </div>
              </div>
              <!--end::Section-->
            </div>
            <div class="d-flex align-items-sm-center my-3">
              <!--begin::Section-->
              <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                {{ $event->event->details }}
              </div>
              <!--end::Section-->
            </div>
          </div>
        @endforeach
        <!--end::Item-->
      </div>
    </div>
    <!--end::row-->
  </div>
  <!--end::Card body-->
  <!--begin::Card footer-->
  <div class="card-footer px-3 py-0">
    <div class="accordion-item">
      <h2 class="accordion-header" id="kt_accordion_1_header_1">
        <button class="accordion-button collapsed py-2" type="button" data-bs-toggle="collapse"
          data-bs-target="#kt_accordion_1_body_1" aria-expanded="true" aria-controls="kt_accordion_1_body_1">
          <a href="#" class="fw-semibold text-gray-800 text-hover-primary fs-5"><i
              class="fa-solid fa-receipt text-primary fs-1 mx-2"></i> Bill Details</a>
        </button>
      </h2>
      <div id="kt_accordion_1_body_1" class="accordion-collapse collapse" aria-labelledby="kt_accordion_1_header_1"
        data-bs-parent="#kt_accordion_1">
        <div class="accordion-body px-2">
          <div class="d-flex align-items-center rounded py-5">
            <!--begin::Title-->
            <div class="flex-grow-1 me-2 text-center rounded bg-secondary p-2">
              <a href="#" class="fw-semibold text-gray-800 text-hover-primary fs-6">Items</a>
              <span class="text-gray-900 fw-bold d-block">{{ $order->orderItems->count() }}</span>
            </div>
            <!--end::Title-->
            <!--begin::Title-->
            <div class="flex-grow-1 me-2 text-center rounded bg-secondary p-2">
              <a href="#" class="fw-semibold text-gray-800 text-hover-primary fs-6">Point</a>
              <span class="badge badge-warning d-block px-0" data-bs-toggle="tooltip"
                data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                title="Point Used">{{ $order->points }}</span>
            </div>
            <!--end::Title-->
            <!--begin::Title-->
            @if ($order->coupon_id)
              <div class="flex-grow-1 me-2 text-center rounded bg-secondary p-2">
                <a href="#" class="fw-semibold text-gray-800 text-hover-primary fs-6">Coupon</a>
                <span class="badge badge-primary mx-2 d-block" data-bs-toggle="tooltip"
                  data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                  title="Coupon Code">{{ $order->coupon->code }}</span>
              </div>
            @endif
            <!--end::Title-->
            <!--begin::Title-->
            <div class="flex-grow-1 me-2 text-center rounded bg-secondary p-2">
              <a href="#" class="fw-semibold text-gray-800 text-hover-primary fs-6">Payment</a>
              <span class="badge badge-info d-block px-0" data-bs-toggle="tooltip"
                data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                title="Payment Mode">{{ $order->payment_method }}</span>
            </div>
            <!--end::Title-->
          </div>
          <div class="table-responsive">
            <!--begin::Table-->
            <table class="table table-row-dashed table-row-gray-300 align-middle gs-0 gy-4">
              <!--begin::Table body-->
              <tbody>
                <tr>
                  <td>
                    Subtotal
                  </td>
                  <td>
                    ₹{{ $orginalAmount }}
                  </td>
                </tr>
                @if ($order->discount > 0)
                  <tr>
                    <td>
                      Point Discount
                    </td>
                    <td>
                      ₹{{ $order->discount }}
                    </td>
                  </tr>
                @endif
                @if ($order->coupon_discount > 0)
                  <tr>
                    <td>
                      Coupon Discount
                    </td>
                    <td>
                      ₹{{ $order->coupon_discount }}
                    </td>
                  </tr>
                @endif
                @if ($order->gst_amount > 0)
                  <tr>
                    <td>
                      GST
                    </td>
                    <td>
                      ₹{{ $order->gst_amount }}
                    </td>
                  </tr>
                @endif
                @if ($order->sgt_amount > 0)
                  <tr>
                    <td>
                      SGST
                    </td>
                    <td>
                      ₹{{ $order->sgt_amount }}
                    </td>
                  </tr>
                @endif
                @if ($order->service_tax > 0)
                  <tr>
                    <td>
                      Tax
                    </td>
                    <td>
                      ₹{{ $order->service_tax }}
                    </td>
                  </tr>
                @endif
                <tr class="fs-3">
                  <td>
                    Grand Total
                  </td>
                  <td>
                    ₹{{ $order->net_amount }}
                  </td>
                </tr>
              </tbody>
              <!--end::Table body-->
            </table>
            <!--end::Table-->
          </div>
        </div>
      </div>
    </div>
    <div class="row">
      <div class="col p-0">
        <a href="#" class="btn btn-danger hover-elevate-up w-100 rounded-0 py-2" data-bs-toggle="tooltip"
          data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
          title="Amount Paid">₹{{ $order->net_amount }}<span
            class="badge badge-dark ms-2">{{ $order->orderItems->count() }} Items</span></a>
      </div>
    </div>
    <!--end::View component-->
  </div>
  <!--end::Card footer-->
</div>
<script>
  $('[data-bs-toggle="tooltip"]').tooltip({
    // Options for tooltips if needed
  });
</script>
<script>
  $(document).ready(function() {
    $('.rating-active').on('click', 'i', function() {
      var rating = $(this).data('rating');
      var item_id = $(this).closest('.rating-active').find('input[name="item_id"]').val();
      var order_id = $(this).closest('.rating-active').find('input[name="order_id"]').val();
      var order_item_id = $(this).closest('.rating-active').find('input[name="order_item_id"]').val();
      var ratingIcons = $(this).closest('.rating-active').find('i');
      var ratingContainer = $(this).closest('.rating-active');
      $.ajax({
        type: 'POST',
        url: '{{ route('rate.item') }}',
        data: {
          item_id: item_id,
          order_id: order_id,
          order_item_id: order_item_id,
          rating: rating,
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          showToast(response, response.message);
          highlightStars(rating, ratingIcons);
          ratingContainer.off('click', 'i');
        },
        error: function(xhr, status, error) {
          var errorMessage = xhr.responseJSON.message;
          alert(errorMessage);
        }
      });
    })

    function highlightStars(rating, ratingIcons) {
      ratingIcons.removeClass('fas').addClass('far'); // Reset all stars
      for (var i = 1; i <= rating; i++) {
        ratingIcons.filter('[data-rating="' + i + '"]').removeClass('far').addClass('fas');
      }
    }
  });
</script>
<script>
  $(document).ready(function() {
    $('.review-form').on('submit', function(event) {
      event.preventDefault(); // Prevent the default form submission
      var form = $(this); // Store the reference to the form

      var orderId = form.find('input[name="order_id"]').val();
      var itemId = form.find('input[name="item_id"]').val();
      var reviewText = form.find('textarea[name="review"]').val();
      var order_item_id = form.find('input[name="order_item_id"]').val();

      $.ajax({
        type: 'POST',
        url: '{{ route('submit.review') }}',
        data: {
          order_id: orderId,
          item_id: itemId,
          review: reviewText,
          order_item_id: order_item_id,
          _token: '{{ csrf_token() }}'
        },
        success: function(response) {
          // Show success message
          showToast(response, response.message);
          form.html(`<div class="mt-3">
                                ${reviewText}
                              </div>`)
        },
        error: function(xhr, status, error) {
          var errorMessage = xhr.responseJSON.message;
          alert(errorMessage);
        }
      });
    })
    $(".datePicker").flatpickr({
      dateFormat: "Y-m-d",
      minDate: "today",
      onChange: function(selectedDates, dateStr, instance) {
        var playAreaCartId = $(instance.element).data('play-area-cart-id');
        submitDateTime(playAreaCartId, 'date', dateStr);
      }
    });

    $(".startTimePicker").flatpickr({
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i",
      time_24hr: true,
      onChange: function(selectedDates, timeStr, instance) {
        var playAreaCartId = $(instance.element).data('play-area-cart-id');
        submitDateTime(playAreaCartId, 'start_time', timeStr);
      }
    });

    $(".endTimePicker").flatpickr({
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i",
      time_24hr: true,
      onChange: function(selectedDates, timeStr, instance) {
        var playAreaCartId = $(instance.element).data('play-area-cart-id');
        submitDateTime(playAreaCartId, 'end_time', timeStr);
      }
    });
  });
</script>
