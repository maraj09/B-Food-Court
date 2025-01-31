@extends('layouts.admin.app')
@section('contents')
  @include('pages.orders.admin.toolbar.orderViewToolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid mt-20 mt-lg-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Order details page-->
      <div class="d-flex flex-column gap-7 gap-lg-10">
        <div class="d-flex flex-wrap flex-stack gap-5 gap-lg-10">
          <!--begin:::Tabs-->
          <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-lg-n2 me-auto">
            <!--begin:::Tab item-->
            <li class="nav-item">
              <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                href="#kt_ecommerce_sales_order_summary">Order Summary</a>
            </li>
          </ul>
        </div>
        <!--begin::Order summary-->
        <div class="d-flex flex-column flex-xl-row gap-7 gap-lg-10">
          <!--begin::Order details-->
          <div class="card card-flush py-4 flex-row-fluid">
            <!--begin::Card header-->
            <div class="card-header">
              <div class="card-title">
                <h2>Order Details (#{{ $order->custom_id }})</h2>
              </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
              <div class="table-responsive">
                <!--begin::Table-->
                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                  <tbody class="fw-semibold text-gray-600">
                    <tr>
                      <td class="text-muted">
                        <div class="d-flex align-items-center">
                          <i class="ki-duotone ki-calendar fs-2 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                          </i>Date Added
                        </div>
                      </td>
                      <td class="fw-bold text-end">{{ \Carbon\Carbon::parse($order->created_at)->format('d/m/Y') }}</td>
                    </tr>
                    <tr>
                      <td class="text-muted">
                        <div class="d-flex align-items-center">
                          <i class="ki-duotone ki-wallet fs-2 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                            <span class="path4"></span>
                          </i>Payment Method
                        </div>
                      </td>
                      <td class="fw-bold text-end">
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
                    </tr>
                  </tbody>
                </table>
                <!--end::Table-->
              </div>
            </div>
            <!--end::Card body-->
          </div>
          <!--end::Order details-->
          <!--begin::Customer details-->
          <div class="card card-flush py-4 flex-row-fluid">
            <!--begin::Card header-->
            <div class="card-header">
              <div class="card-title">
                <h2>Customer Details</h2>
              </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
              <div class="table-responsive">
                <!--begin::Table-->
                <table class="table align-middle table-row-bordered mb-0 fs-6 gy-5 min-w-300px">
                  <tbody class="fw-semibold text-gray-600">
                    <tr>
                      <td class="text-muted">
                        <div class="d-flex align-items-center">
                          <i class="ki-duotone ki-profile-circle fs-2 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                            <span class="path3"></span>
                          </i>Customer
                        </div>
                      </td>
                      <td class="fw-bold text-end">
                        <div class="d-flex align-items-center justify-content-end">
                          <!--begin:: Avatar -->
                          <div class="symbol symbol-circle symbol-25px overflow-hidden me-3">
                            <a href="apps/ecommerce/customers/details.html">
                              <img src="{{ asset('assets/media/avatars/blank.png') }}" alt="Dan Wilson" class="w-25px" />
                            </a>
                          </div>
                          <!--end::Avatar-->
                          <!--begin::Name-->
                          <a href="apps/ecommerce/customers/details.html"
                            class="text-gray-600 text-hover-primary">{{ $order->user->name }}</a>
                          <!--end::Name-->
                        </div>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-muted">
                        <div class="d-flex align-items-center">
                          <i class="ki-duotone ki-sms fs-2 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                          </i>Email
                        </div>
                      </td>
                      <td class="fw-bold text-end">
                        <a href="apps/user-management/users/view.html"
                          class="text-gray-600 text-hover-primary">{{ $order->user->email }}</a>
                      </td>
                    </tr>
                    <tr>
                      <td class="text-muted">
                        <div class="d-flex align-items-center">
                          <i class="ki-duotone ki-phone fs-2 me-2">
                            <span class="path1"></span>
                            <span class="path2"></span>
                          </i>Phone
                        </div>
                      </td>
                      <td class="fw-bold text-end">{{ $order->user->customer->contact_no }}</td>
                    </tr>
                  </tbody>
                </table>
                <!--end::Table-->
              </div>
            </div>
            <!--end::Card body-->
          </div>
          <!--end::Customer details-->
        </div>
        <!--end::Order summary-->
        <!--begin::Tab content-->
        <div class="tab-content">
          <!--begin::Tab pane-->
          <div class="tab-pane fade show active" id="kt_ecommerce_sales_order_summary" role="tab-panel">
            <!--begin::Orders-->
            <div class="d-flex flex-column gap-7 gap-lg-10">
              <!--begin::Product List-->
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
                      <tbody class="fw-semibold text-gray-600">
                        @foreach ($order->orderItems as $orderItem)
                          <tr>
                            <td>
                              <div class="d-flex align-items-center">
                                <!--begin::Thumbnail-->
                                <a href="/dashboard/items/{{ $orderItem->item->id }}" class="symbol symbol-50px">
                                  <span class="symbol-label"
                                    style="background-image:url({{ asset($orderItem->item->item_image) }});"></span>
                                </a>
                                <!--end::Thumbnail-->
                                <!--begin::Title-->
                                <div class="ms-5">
                                  <a href="/dashboard/items/{{ $orderItem->item->id }}"
                                    class="fw-bold text-gray-600 text-hover-primary">{{ $orderItem->item->item_name }}</a>
                                </div>
                                <!--end::Title-->
                              </div>
                            </td>
                            <td class="text-end">{{ $orderItem->quantity }}</td>
                            <td class="text-end">₹{{ $orderItem->price }}</td>
                            <td class="text-end">₹{{ $orderItem->price * $orderItem->quantity }}</td>
                            <td class="text-end">
                            <td class="text-end">
                              <div class="dropdown">
                                <button class="btn btn-secondary dropdown-toggle" type="button"
                                  id="statusDropdown{{ $orderItem->id }}" data-bs-toggle="dropdown"
                                  aria-expanded="false">
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
                            </td>
                            </td>
                          </tr>
                        @endforeach
                        @php
                          $subTotal = $order->orderItems->sum(function ($item) {
                              return $item->quantity * $item->item->price;
                          });
                        @endphp
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
                            ₹{{ $subTotal - ($order->discount + $order->coupon_discount) }}</td>
                        </tr>
                      </tbody>
                    </table>
                    <!--end::Table-->
                  </div>
                </div>
                <!--end::Card body-->
              </div>
              <!--end::Product List-->
            </div>
            <!--end::Orders-->
          </div>
          <!--end::Tab pane-->
        </div>
        <!--end::Tab content-->
      </div>
      <!--end::Order details page-->
    </div>
    <!--end::Content container-->
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
