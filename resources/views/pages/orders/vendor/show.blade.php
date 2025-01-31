@extends('layouts.vendor.app')
@section('contents')
  @include('pages.orders.vendor.toolbar.showToolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <div class="card card-flush py-4 flex-row-fluid overflow-hidden">
        <!--begin::Card header-->
        <div class="card-header">
          <div class="card-title">
            <h2>Order #{{ $order->custom_id }}</h2>
          </div>
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
          <div class="table-responsive">
            <!--begin::Table-->
            <table class="table align-middle table-row-dashed fs-6 gy-5 mb-0">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="min-w-175px">Product</th>
                  <th class="min-w-70px text-end">Qty</th>
                  <th class="min-w-100px text-end">Unit Price</th>
                  <th class="min-w-100px text-end">Total</th>
                  <th class="min-w-100px text-end">Status</th>
                </tr>
              </thead>
              @php
                $totalVendorAmount = 0;
              @endphp
              <tbody class="fw-semibold text-gray-600">
                @foreach ($order->orderItems as $orderItem)
                  @if ($orderItem->item->vendor_id == auth()->user()->vendor->id)
                    @php
                      $totalVendorAmount += $orderItem->price * $orderItem->quantity;
                    @endphp
                    <tr>
                      <td>
                        <div class="d-flex align-items-center">
                          <!--begin::Thumbnail-->
                          <a href="/vendor/items/{{ $orderItem->item->id }}" class="symbol symbol-50px">
                            <span class="symbol-label"
                              style="background-image:url({{ asset($orderItem->item->item_image) }});"></span>
                          </a>
                          <!--end::Thumbnail-->
                          <!--begin::Title-->
                          <div class="ms-5">
                            <a href="/vendor/items/{{ $orderItem->item->id }}"
                              class="fw-bold text-gray-600 text-hover-primary">{{ $orderItem->item->item_name }}</a>

                          </div>
                          <!--end::Title-->
                        </div>
                      </td>
                      <td class="text-end">{{ $orderItem->quantity }}</td>
                      <td class="text-end">₹{{ $orderItem->price }}</td>
                      <td class="text-end">₹{{ $orderItem->price * $orderItem->quantity }}</td>
                      <td class="text-end">
                        @if ($orderItem->status == 'rejected')
                          <button class="btn btn-secondary" type="button">
                            <span class="status-text text-danger">Rejected</span>
                          </button>
                        @else
                          <div class="dropdown">
                            <button class="btn btn-secondary dropdown-toggle" type="button"
                              id="statusDropdown{{ $orderItem->id }}" data-bs-toggle="dropdown" aria-expanded="false">
                              @if ($orderItem->status == 'accepted')
                                <span class="status-text text-info">Accepted</span>
                              @elseif($orderItem->status == 'completed')
                                <span class="status-text text-primary">Completed</span>
                              @elseif($orderItem->status == 'delivered')
                                <span class="status-text text-success">Delivered</span>
                              @elseif($orderItem->status == 'rejected')
                                <span class="status-text text-danger">Rejected</span>
                              @else
                                <span class="status-text text-warning">Pending</span>
                              @endif
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="statusDropdown{{ $orderItem->id }}">
                              <li><a class="dropdown-item text-info" href="#"
                                  onclick="changeStatus('{{ $orderItem->id }}', 'accepted')">Accepted</a></li>
                              <li><a class="dropdown-item text-primary" href="#"
                                  onclick="changeStatus('{{ $orderItem->id }}', 'completed')">Completed</a></li>
                              <li><a class="dropdown-item text-success" href="#"
                                  onclick="changeStatus('{{ $orderItem->id }}', 'delivered')">Delivered</a></li>
                              <li><a class="dropdown-item text-danger" href="#"
                                  onclick="changeStatus('{{ $orderItem->id }}', 'rejected')">Rejected</a></li>
                              <li><a class="dropdown-item text-warning" href="#"
                                  onclick="changeStatus('{{ $orderItem->id }}', 'pending')">Pending</a></li>
                            </ul>
                          </div>
                        @endif
                      </td>
                    </tr>
                  @endif
                @endforeach
                <tr>
                  <td colspan="3" class="text-end">Subtotal</td>
                  <td class="text-end">
                    ₹{{ $subTotal }}
                  </td>
                </tr>
                <tr>
                  <td colspan="3" class="text-end">Point Discount</td>
                  <td class="text-end">₹{{ $order->discount }}</td>
                </tr>
                <tr>
                  <td colspan="3" class="text-end">Coupon Discount</td>
                  <td class="text-end">₹{{ $order->coupon_discount }}</td>
                </tr>
                <tr>
                  <td colspan="3" class="fs-3 text-gray-900 text-end">Grand Total</td>
                  <td class="text-gray-900 fs-3 fw-bolder text-end">
                    ₹{{ $totalVendorAmount }}</td>
                </tr>
              </tbody>
            </table>
            <!--end::Table-->
          </div>
        </div>
        <!--end::Card body-->
      </div>
    </div>
  </div>
@endsection
@section('modules')
  @include('pages.dashboard.vendor.modules.toasts.status')
@endsection
@section('scripts')
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

          $.ajax({
            url: '{{ route('orderItems.update-status') }}',
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
                  // Disable the dropdown button
                  dropdownButton.removeClass('dropdown-toggle').removeAttr('data-bs-toggle');
                  break;
                default:
                  buttonText = 'Pending';
                  buttonColor = 'text-warning';
              }
              // Update dropdown button text and style
              dropdownButton.html('<span class="status-text ' + buttonColor + '">' + buttonText + ' </span>');
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
  </script>
@endsection
