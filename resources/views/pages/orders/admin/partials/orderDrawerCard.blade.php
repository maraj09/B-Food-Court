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
      @can('orders-management')
        <div class="me-2">
          <a href="/dashboard/customers/{{ $order->user_id }}"
            class="text-gray-900 fw-bold text-hover-primary fs-7">{{ $order->user->name }}</a>
          <span class="text-muted d-block fw-bold"><a href="tel:{{ $order->user->phone }}"
              class="text-gray-900 text-hover-primary fs-9">{{ $order->user->phone }}</a></span>
        </div>
      @endcan
      <!--end::User Info-->
      <!--begin::Order Date-->
      <div class="me-0">
        <span class="fw-bold fs-8">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</span><span
          class="text-muted d-block fw-bold fs-8">
          {{ \Carbon\Carbon::parse($order->created_at)->format('g:iA') }}</span>
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
                  <a href="/dashboard/items/{{ $item->item->id }}"
                    class="text-gray-800 text-hover-primary fs-6 fw-bold">{{ $item->item->item_name }}
                    <span class="badge badge-square badge-info ms-2" data-bs-toggle="tooltip"
                      data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                      title="Quantity">{{ $item->quantity }}</span></a>
                  <div class="d-flex flex-wrap flex-grow-1">
                    <div class="me-2">
                      <span class="text-success fw-bold">Price</span>
                      <span class="fw-bold text-gray-800 d-block fs-6">₹{{ $item->price }}</span>
                    </div>
                    <div class="me-5s">
                      <span class="text-danger fw-bold">Total</span>
                      <span class="fw-bold text-gray-800 d-block fs-6">₹{{ $item->price * $item->quantity }}</span>
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
                <div class="dropdown d-flex align-items-center">
                  <button class="btn btn-secondary dropdown-toggle ps-0 border border-light-dark py-3 w-150px "
                    type="button" id="statusDropdown{{ $item->id }}" data-bs-toggle="dropdown"
                    aria-expanded="false">
                    @if ($item->status == 'accepted')
                      <span class="status-text text-info">Accepted</span>
                    @elseif($item->status == 'completed')
                      <span class="status-text text-primary">Completed</span>
                    @elseif($item->status == 'delivered')
                      <span class="status-text text-success">Delivered</span>
                    @elseif($item->status == 'rejected')
                      <span class="status-text text-danger">Rejected</span>
                    @else
                      <span class="status-text text-warning">Pending</span>
                    @endif
                  </button>
                  <ul class="dropdown-menu w-150px" aria-labelledby="statusDropdown{{ $item->id }}">
                    @can('orders-management')
                      <li><a class="dropdown-item text-info" href="#"
                          onclick="changeStatus('{{ $item->id }}', 'accepted')">Accepted</a></li>
                      <li><a class="dropdown-item text-primary" href="#"
                          onclick="changeStatus('{{ $item->id }}', 'completed')">Completed</a></li>
                      <li><a class="dropdown-item text-success" href="#"
                          onclick="changeStatus('{{ $item->id }}', 'delivered')">Delivered</a></li>
                    @endcan
                    <li><a class="dropdown-item text-danger" href="#"
                        onclick="changeStatus('{{ $item->id }}', 'rejected')">Rejected</a></li>
                    <li><a class="dropdown-item text-warning" href="#"
                        onclick="changeStatus('{{ $item->id }}', 'pending')">Pending</a></li>
                  </ul>
                  <div class="text-center ms-3 refund-div{{ $item->id }}">
                    @if ($item->status == 'rejected' && !$item->refunded)
                      <a href="#" onclick="handleRefund({{ $item->id }}, event)"
                        class="text-danger fw-normal fs-7 text-center">Refund</a>
                    @elseif ($item->status == 'rejected' && $item->refunded)
                      <div class="text-center ms-3">
                        <span class="text-success fw-normal fs-7 text-center">Refunded</span>
                      </div>
                    @endif
                  </div>
                </div>
              </div>
              <div class="me-2 text-end">
                @php
                  $rating = \App\Models\Rating::where('order_id', $item->order_id)
                      ->where('item_id', $item->item->id)
                      ->first();
                @endphp
                @if ($rating)
                  <span class="text-gray-900 fw-bold">Rating</span>
                  <i class="fa fa-star-half-alt text-warning fs-5"></i>
                  <span class="text-gray-800 fw-bold">{{ $rating->rating }}</span>
                @else
                  <span class="text-gray-900 fw-bold">No Rating</span>
                @endif
              </div>
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
                <img src="{{ asset($event->event->image) }}" class="align-self-center" alt="">
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
    <div class="d-flex align-items-center rounded p-5 mb-7">
      <!--begin::Title-->
      <div class="flex-grow-1 me-2 text-center rounded bg-secondary p-2">
        <a href="#" class="fw-semibold text-gray-800 text-hover-primary fs-6">Total</a>
        <span class="text-gray-900 fw-bold d-block">₹{{ $orginalAmount }}</span>
      </div>
      <!--end::Title-->
      <!--begin::Title-->
      <div class="flex-grow-1 me-2 text-center rounded bg-secondary p-2">
        <a href="#" class="fw-semibold text-gray-800 text-hover-primary fs-6">Point Used</a>
        <span class="text-gray-900 fw-bold d-block">{{ $order->points }}</span>
      </div>
      <!--end::Title-->
      <!--begin::Title-->
      @if ($order->coupon_id)
        <div class="flex-grow-1 me-2 text-center rounded bg-secondary p-2">
          <a href="#" class="fw-semibold text-gray-800 text-hover-primary fs-6">Coupon</a>
          <span class="text-gray-900 fw-bold d-block">{{ $order->coupon->code }} <span
              class="badge badge-light-primary mx-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
              data-bs-placement="top" title="Coupon Amount">₹{{ $order->coupon_discount }}</span></span>
        </div>
      @endif
      <!--end::Title-->
      <!--begin::Title-->
      <div class="flex-grow-1 me-2 text-center rounded bg-secondary p-2">
        <a href="#" class="fw-semibold text-gray-800 text-hover-primary fs-6">Payment</a>
        <span class="badge badge-warning d-block px-0">{{ $order->payment_method }}</span>
      </div>
      <!--end::Title-->
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
  function changeStatus(itemId, status) {
    Swal.fire({
      text: "Are you sure you want to change status?",
      icon: "warning",
      showCancelButton: true,
      buttonsStyling: false,
      confirmButtonText: "Yes, change it!",
      cancelButtonText: "No, return",
      customClass: {
        confirmButton: "btn btn-danger",
        cancelButton: "btn btn-active-light",
      },
    }).then(function(res) {
      if (res.value) {
        // Show loading indicator
        var loadingHTML = '<div class="spinner-border text-primary" role="status">\
                                      <span class="visually-hidden">Loading...</span>\
                                  </div>';
        var dropdownButton = $(`#statusDropdown${itemId}`);
        var dropdownMenu = dropdownButton.next('.dropdown-menu');
        dropdownButton.html(loadingHTML);
        var refundDiv = $(`.refund-div${itemId}`);

        $.ajax({
          url: '{{ route('admin.orderItems.update-status') }}',
          method: 'PUT',
          data: {
            id: itemId,
            status: status,
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            if (!response.success) {
              showToast('error', response.message);
            }
            var buttonText = '';
            var buttonColor = '';
            switch (response.status) {
              case 'accepted':
                buttonText = 'Accepted';
                buttonColor = 'text-info';
                break;
              case 'completed':
                buttonText = 'Completed';
                buttonColor = 'text-primary';
                break;
              case 'delivered':
                buttonText = 'Delivered';
                buttonColor = 'text-success';
                break;
              case 'rejected':
                buttonText = 'Rejected';
                buttonColor = 'text-danger';
                break;
              default:
                buttonText = 'Pending';
                buttonColor = 'text-warning';
            }
            // Update dropdown button text and style
            dropdownButton.html('<span class="status-text ' + buttonColor + '">' + buttonText + ' </span>');
            if (response.status == 'rejected') {
              refundDiv.html(`<a href="#" onclick="handleRefund(${itemId}, event)"
                        class="text-danger fw-normal fs-7 text-center">Refund</a>`);
            } else {
              refundDiv.html(``);
            }
            // Update dropdown menu item text and style
            dropdownMenu.find('a').each(function() {
              var optionText = $(this).text();
              if (optionText.toLowerCase() === buttonText.toLowerCase()) {
                $(this).addClass('active').siblings().removeClass('active');
              }
            });
            $(document).trigger('statusChanged', [response.orderStatus, response.orderId]);
          },
          error: function(xhr, status, error) {
            console.error('Error updating status:', xhr);
            showToast('error');
          }
        });
      }
    });
  }
  $('[data-bs-toggle="tooltip"]').tooltip({
    // Options for tooltips if needed
  });
</script>
<script>
  function handleRefund(itemId, e) {
    e.preventDefault();

    Swal.fire({
      text: 'You are about to refund. This action cannot be undone.',
      icon: 'warning',
      showCancelButton: true,
      buttonsStyling: false,
      confirmButtonText: "Yes, refund it!",
      cancelButtonText: "No, return",
      customClass: {
        confirmButton: "btn btn-danger",
        cancelButton: "btn btn-active-light",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        var loadingHTML = `<div class="spinner-border spinner-loading-${itemId} text-primary" role="status">\
                                <span class="visually-hidden">Loading...</span>\
                            </div>`;
        var refundDiv = $(`.refund-div${itemId}`);
        refundDiv.append(loadingHTML);
        // If user confirms, perform the removal action via AJAX
        axios.post(`/dashboard/refund`, {
            itemId: itemId,
            _token: '{{ csrf_token() }}'
          })
          .then(response => {
            Swal.fire({
              text: response.data.message,
              icon: 'success',
              confirmButtonText: 'Ok, got it!',
              customClass: {
                confirmButton: 'btn btn-primary'
              }
            }).then((res) => {
              refundDiv.html(
                '<span class="text-success fw-normal fs-7 text-center">Refunded</span>'
              );
            });
          })
          .catch(error => {
            console.error('An error occurred:', error);
            Swal.fire({
              text: 'Error occurred while refunding. Please try again.',
              icon: 'error',
              confirmButtonText: 'Ok, got it!',
              customClass: {
                confirmButton: 'btn btn-primary'
              }
            });
          }).finally(() => {
            $(`.spinner-loading-${itemId}`).remove();
          });
      }
    });
  }
</script>
