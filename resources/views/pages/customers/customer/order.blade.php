@extends('layouts.customer.app')
@section('contents')
  <div id="kt_app_content" class="app-content flex-column-fluid mt-10">
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
                          <th class="min-w-105px">Product</th>
                          <th class="min-w-70px text-end">Qty</th>
                          <th class="min-w-100px text-end">Status</th>
                          <th class="min-w-100px text-end">Review</th>
                          <th class="min-w-100px text-end">Unit Price</th>
                          <th class="min-w-100px text-end">Total</th>
                        </tr>
                      </thead>
                      <tbody class="fw-semibold text-gray-600">
                        @foreach ($order->orderItems()->whereNot('item_id', null)->get() as $orderItem)
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
                            <td class="text-end">
                              <div class="dropdown">
                                <span class="btn btn-secondary" type="button">
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
                                </span>
                              </div>
                            </td>
                            <td class="">
                              @php
                                $userRating = $orderItem->item->ratings
                                    ->where('order_id', $orderItem->order->id)
                                    ->where('user_id', auth()->id())
                                    ->first();
                              @endphp
                              @if ($orderItem->status == 'delivered' && is_null($userRating?->rating))
                                <div class="rating justify-content-end rating-active">
                                  <input type="hidden" name="item_id" value="{{ $orderItem->item->id }}">
                                  <input type="hidden" name="order_id" value="{{ $orderItem->order->id }}">
                                  @php
                                    $itemRating =
                                        $orderItem->item->ratings
                                            ->where('order_id', $orderItem->order->id)
                                            ->where('user_id', auth()->id())
                                            ->first()->rating ?? 0;
                                  @endphp
                                  @for ($i = 1; $i <= 5; $i++)
                                    @php
                                      $filledClass = $i <= $itemRating ? 'fas' : 'far';
                                    @endphp
                                    <i class="{{ $filledClass }} fa-star text-warning" style="font-size: 22px"
                                      data-rating="{{ $i }}"></i>
                                  @endfor
                                </div>
                              @elseif($orderItem->status !== 'delivered')
                                <div class="d-flex justify-content-end">
                                  <span>
                                    Sorry! you cant review if not delivered!
                                  </span>
                                </div>
                              @else
                                <div class="rating justify-content-end">
                                  @php
                                    $itemRating =
                                        $orderItem->item->ratings
                                            ->where('order_id', $orderItem->order->id)
                                            ->where('user_id', auth()->id())
                                            ->first()->rating ?? 0;
                                  @endphp
                                  @for ($i = 1; $i <= 5; $i++)
                                    @php
                                      $filledClass = $i <= $itemRating ? 'fas' : 'far';
                                    @endphp
                                    <i class="{{ $filledClass }} fa-star text-warning" style="font-size: 22px"></i>
                                  @endfor
                                </div>
                              @endif
                              @if ($orderItem->status == 'delivered' && is_null($userRating?->review))
                                <form class="d-flex align-items-baseline review-form justify-content-end">
                                  <input type="hidden" name="item_id" value="{{ $orderItem->item->id }}">
                                  <input type="hidden" name="order_id" value="{{ $orderItem->order->id }}">
                                  <input type="text" class="ms-auto form-control form-control-sm mt-3 w-250px"
                                    placeholder="Short Review" name="review">
                                  <button type="submit" class="btn btn-success btn-sm ms-2">Submit</button>
                                </form>
                              @else
                                <div class="text-end mt-3">
                                  {{ $userRating?->review }}
                                </div>
                              @endif
                            </td>
                            <td class="text-end">₹{{ round($orderItem->price) }}</td>
                            <td class="text-end">₹{{ round($orderItem->price * $orderItem->quantity) }}</td>
                          </tr>
                        @endforeach
                        @php
                          $subTotal = $order->orderItems->sum(function ($item) {
                              return $item->quantity * $item->item->price;
                          });
                          $settings = \App\Models\Setting::first();
                        @endphp
                        <tr>
                          <td colspan="5" class="text-end">Subtotal</td>
                          <td class="text-end">
                            ₹{{ $subTotal }}
                          </td>
                        </tr>
                        @if ($order->discount > 0)
                          <tr>
                            <td colspan="5" class="text-end">Point Discount</td>
                            <td class="text-end">₹{{ $order->discount }}</td>
                          </tr>
                        @endif
                        @if ($order->coupon_discount > 0)
                          <tr>
                            <td colspan="5" class="text-end">Coupon Discount</td>
                            <td class="text-end">₹{{ $order->coupon_discount }}</td>
                          </tr>
                        @endif
                        @if ($order->gst_amount > 0)
                          <tr>
                            <td colspan="5" class="text-end">GST:</td>
                            <td class="text-end">₹{{ $order->gst_amount }}</td>
                          </tr>
                        @endif
                        @if ($order->sgt_amount > 0)
                          <tr>
                            <td colspan="5" class="text-end">SGT:</td>
                            <td class="text-end">₹{{ $order->sgt_amount }}</td>
                          </tr>
                        @endif
                        @if ($order->service_tax > 0)
                          <tr>
                            <td colspan="5" class="text-end">Service Tax:</td>
                            <td class="text-end">₹{{ $order->service_tax }}</td>
                          </tr>
                        @endif
                        <tr>
                          <td colspan="5" class="fs-3 text-gray-900 text-end">Grand Total</td>
                          <td class="text-gray-900 fs-3 fw-bolder text-end">
                            ₹{{ $order->net_amount }}</td>
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
  @include('pages.customers.customer.modules.toasts.status')
@endsection
@section('scripts')
  <script>
    $(document).ready(function() {
      $('.rating-active').on('click', 'i', function() {
        console.log('asd');
        var rating = $(this).data('rating');
        var item_id = $(this).closest('.rating-active').find('input[name="item_id"]').val();
        var order_id = $(this).closest('.rating-active').find('input[name="order_id"]').val();
        var ratingIcons = $(this).closest('.rating-active').find('i');
        var ratingContainer = $(this).closest('.rating-active');
        $.ajax({
          type: 'POST',
          url: '{{ route('rate.item') }}',
          data: {
            item_id: item_id,
            order_id: order_id,
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
        var reviewText = form.find('input[name="review"]').val();

        $.ajax({
          type: 'POST',
          url: '{{ route('submit.review') }}',
          data: {
            order_id: orderId,
            item_id: itemId,
            review: reviewText,
            _token: '{{ csrf_token() }}'
          },
          success: function(response) {
            // Show success message
            showToast(response, response.message);
            form.html(`<div class="text-end mt-3">
                                  ${reviewText}
                                </div>`)
          },
          error: function(xhr, status, error) {
            var errorMessage = xhr.responseJSON.message;
            alert(errorMessage);
          }
        });
      })
    });
  </script>
@endsection
