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
        <!--begin::Item-->
        @php
          $orginalAmount = 0;
          $totalVendorAmount = 0;
        @endphp
        @foreach ($orderItems as $item)
          @php
            $totalAmount = $item->item->price * $item->quantity;
            $orginalAmount += $totalAmount;
            $totalVendorAmount += $item->price * $item->quantity;
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
                  <a href="/vendor/items/{{ $item->item->id }}"
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
                    <li><a class="dropdown-item text-info" href="#"
                        onclick="changeStatus('{{ $item->id }}', 'accepted')">Accepted</a></li>
                    <li><a class="dropdown-item text-primary" href="#"
                        onclick="changeStatus('{{ $item->id }}', 'completed')">Completed</a></li>
                    <li><a class="dropdown-item text-success" href="#"
                        onclick="changeStatus('{{ $item->id }}', 'delivered')">Delivered</a></li>
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
          title="Amount Paid">₹{{ $totalVendorAmount }}<span
            class="badge badge-dark ms-2">{{ $orderItems->count() }} Items</span></a>
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
            var buttonText = '';
            var buttonColor = '';
            switch (status) {
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
            if (status == 'rejected') {
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
            showToast('success', response.message);
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
