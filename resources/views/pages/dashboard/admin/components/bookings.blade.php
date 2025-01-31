<div class="col-xl-4 mb-5 mb-xl-10 d-flex flex-row flex-column-fluid draggable">
  <!--begin::Timeline widget 3-->
  <div class="card card-flush h-md-100 d-flex flex-row-fluid">
    <!--begin::Header-->
    <div class="card-header ribbon ribbon-top border-0 pt-5 px-0">
      <div class="ribbon-label bg-primary draggable-handle">Upcoming Bookings</div>
      <!--begin::Nav-->
      <div class="d-flex w-100 overflow-auto">
        <ul
          class="nav nav-stretch  nav nav-custom nav-active-custom d-flex flex-nowrap justify-content-between my-4 px-2"
          role="tablist">
          <!--begin::Nav item-->
          <li class="nav-item p-0 mx-2" role="presentation">
            <!--begin::Date-->
            <a class="nav-link btn d-flex flex-column flex-center min-w-45px py-0 px-2 btn-active-danger active"
              data-bs-toggle="tab" href="#kt_timeline_widget_3_tab_content_1" aria-selected="true" role="tab">
              <span class="fs-7 fw-semibold">Orders</span>
              <span class="fs-6 fw-bold">Inprogress</span>
            </a>
            <!--end::Date-->
          </li>
          <!--end::Nav item-->
          <!--begin::Nav item-->
          <li class="nav-item p-0 mx-2" role="presentation">
            <!--begin::Date-->
            <a class="nav-link btn d-flex flex-column flex-center min-w-45px py-0 px-2 btn-active-danger"
              data-bs-toggle="tab" href="#kt_timeline_widget_3_tab_content_2" aria-selected="false" tabindex="-1"
              role="tab">
              <span class="fs-7 fw-semibold">Most</span>
              <span class="fs-6 fw-bold">Liked</span>
            </a>
            <!--end::Date-->
          </li>
          <!--end::Nav item-->
          <!--begin::Nav item-->
          <li class="nav-item p-0 mx-2" role="presentation">
            <!--begin::Date-->
            <a class="nav-link btn d-flex flex-column flex-center min-w-45px py-0 px-2 btn-active-danger"
              data-bs-toggle="tab" href="#kt_timeline_widget_3_tab_content_3" aria-selected="false" tabindex="-1"
              role="tab">
              <span class="fs-7 fw-semibold">Biggest</span>
              <span class="fs-6 fw-bold">Order</span>
            </a>
            <!--end::Date-->
          </li>
          <!--end::Nav item-->
          <!--begin::Nav item-->
          <li class="nav-item p-0 mx-2" role="presentation">
            <!--begin::Date-->
            <a class="nav-link btn d-flex flex-column flex-center min-w-45px py-0 px-2 btn-active-danger"
              data-bs-toggle="tab" href="#kt_timeline_widget_3_tab_content_4" aria-selected="false" tabindex="-1"
              role="tab">
              <span class="fs-7 fw-semibold">Repeat</span>
              <span class="fs-6 fw-bold">Customers</span>
            </a>
            <!--end::Date-->
          </li>
          <!--end::Nav item-->
        </ul>
      </div>
      <!--end::Nav-->
    </div>
    <!--end::Header-->
    <!--begin::Body-->
    <div class="card-body pt-7 px-0 card-scroll h-350px">
      <!--begin::Tab Content (ishlamayabdi)-->
      <div class="tab-content mb-2 px-5">
        <!--begin::Tap pane-->
        <div class="tab-pane fade show active" id="kt_timeline_widget_3_tab_content_1" role="tabpanel">
          <!--begin::Wrapper-->
          @foreach ($ordersInProgress as $item)
            <div class="d-flex align-items-center pb-2 rounded border-gray-300 border-bottom-dashed my-5">
              <!--begin::Item-->
              <!--begin::Avatar-->
              <div class="symbol symbol-50px me-2">
                <img src="{{ asset($item->item->item_image) }}" class="" alt="" />
                <span class="position-absolute top-0 start-0 translate-middle badge badge-circle badge-primary badge-sm"
                  data-bs-custom-class="tooltip-inverse" data-bs-toggle="tooltip" data-bs-placement="top"
                  title="Quantity">{{ $item->quantity }}</span>
              </div>
              <!--end::Avatar-->
              <!--begin::Text-->
              <div class="flex-grow-1">
                <a href="/vendor/items/{{ $item->item->id }}"
                  class="text-gray-900 fw-bold text-hover-primary fs-6">{{ $item->item->item_name }}<span
                    class="badge badge badge-outline badge-danger badge-sm ms-2"data-bs-custom-class="tooltip-inverse"
                    data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Order ID">#{{ $item->order->custom_id }}</span><span
                    class="spinner-border ms-3 spinner-border-sm d-none spinner_custom_{{ $item->id }}"
                    role="status" aria-hidden="true"></span></a>
                <a href="#" class="text-muted d-block me-2 text-hover-primary"
                  data-bs-custom-class="tooltip-inverse" data-bs-toggle="tooltip" data-bs-placement="top"
                  title="Order Time">{{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y, g:iA') }}</a>
                <select
                  class="form-select form-select-sm rounded-start-0 fs-6 kt_select2_status order_item_{{ $item->id }}"
                  name="status" data-placeholder="Select Status"
                  onchange="changeStatus('{{ $item->id }}', this.value, '{{$item->status}}')">
                  <option value="pending" data-kt-status-class="badge-warning"
                    {{ $item->status == 'pending' ? 'selected' : '' }}>Pending</option>
                  <option value="accepted" data-kt-status-class="badge-info"
                    {{ $item->status == 'accepted' ? 'selected' : '' }}>Accepted</option>
                  <option value="completed" data-kt-status-class="badge-primary"
                    {{ $item->status == 'completed' ? 'selected' : '' }}>Completed</option>
                  <option value="delivered" data-kt-status-class="badge-success"
                    {{ $item->status == 'delivered' ? 'selected' : '' }}>Delivered</option>
                  <option value="rejected" data-kt-status-class="badge-danger"
                    {{ $item->status == 'rejected' ? 'selected' : '' }}>Rejected</option>
                </select>
              </div>

              <!--end::Text-->
              <!--end::Item-->
            </div>
          @endforeach
          <!--end::Wrapper-->
        </div>
        <!--end::Tap pane-->
        <!--begin::Tap pane-->
        <div class="tab-pane fade" id="kt_timeline_widget_3_tab_content_2" role="tabpanel">
          <!--begin::Item-->
          @foreach ($likedItems as $item)
            <div class="d-flex mb-7">
              <!--begin::Symbol-->
              <div class="symbol symbol-50px flex-shrink-0 me-4">
                <img src="{{ asset($item->item->item_image) }}" class="mw-100" alt="">
              </div>
              <!--end::Symbol-->
              <!--begin::Section-->
              <div class="d-flex align-items-center flex-wrap flex-grow-1 mt-n2 mt-lg-n1">
                <!--begin::Title-->
                <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pe-3">
                  <a href="/vendor/items/{{ $item->item->id }}"
                    class="fs-5 text-gray-800 text-hover-primary fw-bold">{{ $item->item->item_name }}</a>
                  <span>
                    <i
                      class="fa-regular fa-star-half-stroke text-warning me-2"></i>{{ $item->item->itemRating->rating ?? 0 }}
                  </span>
                </div>
                <!--end::Title-->
                <!--begin::Info-->
                <div class="text-end py-lg-0 py-2">
                  <span class="badge badge-success fs-8 fw-bold my-2 d-block pt-1"
                    data-bs-custom-class="tooltip-inverse" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Total Earnings">₹{{ $item->item->price }}</span>
                  <span class="badge badge-light-info fs-8 fw-bold my-2 d-block pt-1"
                    data-bs-custom-class="tooltip-inverse" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Total Orders">{{ $item->item->orderItems()->whereNot('status', 'unpaid')->sum('quantity') }}
                    Times</span>
                </div>
                <!--end::Info-->
              </div>
              <!--end::Section-->
            </div>
          @endforeach
          <!--end::Item-->
        </div>
        <!--end::Tap pane-->
        <!--begin::Tap pane-->
        <div class="tab-pane fade" id="kt_timeline_widget_3_tab_content_3" role="tabpanel">
          <!--begin::Wrapper-->
          @foreach ($biggestOrders as $item)
            <div class="d-flex align-items-center pb-2 rounded border-gray-300 border-bottom-dashed my-5">

              <div class="flex-grow-1">
                <a href="/dashboard/orders/{{ $item->id }}" class="text-gray-900 fw-bold text-hover-primary fs-6">
                  <span class="badge badge badge-outline badge-danger badge-sm"data-bs-custom-class="tooltip-inverse"
                    data-bs-toggle="tooltip" data-bs-placement="top" title="Order ID">#{{ $item->custom_id }}</span>
                </a>
                <a href="#" class="text-muted d-block me-2 text-hover-primary"
                  data-bs-custom-class="tooltip-inverse" data-bs-toggle="tooltip" data-bs-placement="top"
                  title="Order Time">
                  {{ \Carbon\Carbon::parse($item->created_at)->format('d-m-Y, g:iA') }}
                </a>
                <a href="/dashboard/users/{{ $item->user_id }}" class="text-primary opacity-75-hover fw-semibold"
                  data-bs-custom-class="tooltip-inverse" data-bs-toggle="tooltip" data-bs-placement="top"
                  title="Ordered By">{{ $item->user->name }}</a>
              </div>
              <div class="d-flex flex-column">
                <span class="badge badge-success fs-8 fw-bold my-2 d-block pt-1"
                  data-bs-custom-class="tooltip-inverse" data-bs-toggle="tooltip" data-bs-placement="top"
                  title="Order Price">₹{{ $item->order_amount }}</span>
                <!--begin::Action-->
                <a href="/dashboard/orders/{{ $item->id }}" class="btn btn-sm btn-light">View</a>
                <!--end::Action-->
              </div>
            </div>
          @endforeach
          <!--end::Wrapper-->
        </div>
        <!--end::Tap pane-->
        <!--begin::Tap pane-->
        <div class="tab-pane fade" id="kt_timeline_widget_3_tab_content_4" role="tabpanel">
          <!--begin::Item-->
          @foreach ($repeatCustomers as $item)
            <div class="d-flex mb-7">
              <!--begin::Symbol-->
              <div class="symbol symbol-50px flex-shrink-0 me-4">
                {{-- <img src="{{ asset($item->user_image) }}" class="mw-100" alt=""> --}}
                <img src="{{ asset($item->user->customer->avatar ?? 'assets/media/avatars/blank.png') }}"
                  class="" alt="" />
              </div>
              <!--end::Symbol-->
              <!--begin::Section-->
              <div class="d-flex align-items-center flex-wrap flex-grow-1 mt-n2 mt-lg-n1">
                <!--begin::Title-->
                <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pe-3">
                  <a href="/dashboard/customers/{{ $item->user_id }}"
                    class="fs-5 text-gray-800 text-hover-primary fw-bold">{{ $item->user->name }}</a>
                </div>
                <!--end::Title-->
                <!--begin::Info-->
                <div class="text-end py-lg-0 py-2">
                  <span class="badge badge-success fs-8 fw-bold my-2 d-block pt-1"
                    data-bs-custom-class="tooltip-inverse" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Total Spending">₹{{ $item->total_amount }}</span>
                  <span class="badge badge-light-info fs-8 fw-bold my-2 d-block pt-1"
                    data-bs-custom-class="tooltip-inverse" data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Total Orders">{{ $item->total }} Times</span>
                </div>
                <!--end::Info-->
              </div>
              <!--end::Section-->
            </div>
          @endforeach
          <!--end::Item-->
        </div>
        <!--end::Tap pane-->

      </div>
      <!--end::Tab Content-->
    </div>
    <!--end: Card Body-->
  </div>
  <!--end::Timeline widget 3-->
</div>
