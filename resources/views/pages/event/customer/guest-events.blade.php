@extends('layouts.customer.app')
@section('contents')
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-fluid  mt-md-0 mx-0">
      <!--begin::Products-->
      <!--begin::Row-->
      <div class="row pt-5 pt-md-0">
        @if (Session::has('success'))
          <div class="alert alert-success">
            {{ Session::get('success') }}
          </div>
        @endif
        <!--begin::Col-->
        <div class="col">
          <div class="card bg-light card-flush shadow-sm">
            <div class="card-header bg-primary ribbon ribbon-start ribbon-clip">
              <h3 class="card-title align-items-start flex-column fs-7">Events
                <input type="text" class="form-control form-control-solid w-100px w-md-250px" id="searchInput"
                  name="search" placeholder="Search Product" autocomplete="on">
              </h3>
              <div class="card-toolbar">
                <div class="d-flex flex-wrap flex-grow-1">
                  <div class="me-5">
                    <input class="form-control w-100px w-md-250px kt_datepicker_event_search_week_number"
                      placeholder="Pick a date" id="kt_datepicker_1" />
                  </div>
                </div>
              </div>
            </div>
            <!--begin::Body-->
            <div class="card-body pt-2 card-scroll px-4" style="height: 65vh">
              <!--begin::Mobile View-->
              <div class="d-md-block">
                <div class="row row-cols-3 row-cols-md-3 row-cols-lg-4 g-0" id="desktop-view">
                  @foreach ($events as $event)
                    <div class="col-12 col-sm-6 rounded-2 d-flex flex-column flex-row-fluid m-2">
                      <!--begin::Card-->
                      <div class="card card-flush shadow-sm">
                        {{-- <div class="ribbon ribbon-top">
                          <div class="ribbon-label bg-success fw-bold">FREE</div>
                        </div> --}}
                        <!--begin::Overlay-->
                        <a class="d-block overlay" data-fslightbox="lightbox-hot-sales" href="{{ asset($event->image) }}">
                          <!--begin::Image-->
                          <div
                            class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px"
                            style="background-image:url('{{ asset($event->image) }}')">
                          </div>
                          <!--end::Image-->
                        </a>
                        <!--end::Overlay-->
                        <!--begin::Body-->
                        <div class="card-body pt-4" style="padding:15px;">
                          <!--begin::User-->
                          <div class="d-flex align-items-end mb-2">
                            <div class="d-flex align-items-center">
                              <!--begin::Title-->
                              <div class="d-flex flex-column">
                                <a href="#"
                                  class="fs-4 text-dark-800 text-hover-primary fw-bolder">{{ $event->title }}
                                </a>
                                <span class="fw-bold text-dark-400" data-bs-custom-class="tooltip-dark"
                                  data-bs-toggle="tooltip" data-bs-placement="top"
                                  title="Start Date">{{ \Carbon\Carbon::parse($event->start_date)->format('d-M-Y') }} |
                                  {{ \Carbon\Carbon::parse($event->start_date)->format('g:iA') }}</span>
                              </div>
                              <!--end::Title-->
                            </div>
                          </div>
                          <!--end::User-->
                          <!--begin::Desc-->
                          <p class="mb-2 text-center">
                            <span class="fw-bold text-dark-900 fs-4" data-bs-custom-class="tooltip-dark"
                              data-bs-toggle="tooltip" data-bs-placement="top" title="Capacity">
                              <span
                                class="svg-icon svg-icon-primary svg-icon-3x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Home/Armchair.svg--><svg
                                  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                  width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <path
                                      d="M20,8 L18.173913,8 C17.0693435,8 16.173913,8.8954305 16.173913,10 L16.173913,12 C16.173913,12.5522847 15.7261978,13 15.173913,13 L8.86956522,13 C8.31728047,13 7.86956522,12.5522847 7.86956522,12 L7.86956522,10 C7.86956522,8.8954305 6.97413472,8 5.86956522,8 L4,8 L4,6 C4,4.34314575 5.34314575,3 7,3 L17,3 C18.6568542,3 20,4.34314575 20,6 L20,8 Z"
                                      fill="currentColor" opacity="0.3" />
                                    <path
                                      d="M6.15999985,21.0604779 L8.15999985,17.5963763 C8.43614222,17.1180837 9.04773263,16.9542085 9.52602525,17.2303509 C10.0043179,17.5064933 10.168193,18.1180837 9.89205065,18.5963763 L7.89205065,22.0604779 C7.61590828,22.5387706 7.00431787,22.7026457 6.52602525,22.4265033 C6.04773263,22.150361 5.88385747,21.5387706 6.15999985,21.0604779 Z M17.8320512,21.0301278 C18.1081936,21.5084204 17.9443184,22.1200108 17.4660258,22.3961532 C16.9877332,22.6722956 16.3761428,22.5084204 16.1000004,22.0301278 L14.1000004,18.5660262 C13.823858,18.0877335 13.9877332,17.4761431 14.4660258,17.2000008 C14.9443184,16.9238584 15.5559088,17.0877335 15.8320512,17.5660262 L17.8320512,21.0301278 Z"
                                      fill="currentColor" opacity="0.3" />
                                    <path
                                      d="M20,10 L20,15 C20,16.6568542 18.6568542,18 17,18 L7,18 C5.34314575,18 4,16.6568542 4,15 L4,10 L5.86956522,10 L5.86956522,12 C5.86956522,13.6568542 7.21271097,15 8.86956522,15 L15.173913,15 C16.8307673,15 18.173913,13.6568542 18.173913,12 L18.173913,10 L20,10 Z"
                                      fill="currentColor" />
                                  </g>
                                </svg><!--end::Svg Icon-->
                              </span>
                              {{ App\Models\OrderItem::where('event_id', $event->id)->whereNot('status', 'unpaid')->sum('quantity') }}/{{ $event->seats }}
                            </span>
                            <span class="fw-bold text-dark-900  px-2 fs-4" data-bs-custom-class="tooltip-dark"
                              data-bs-toggle="tooltip" data-bs-placement="top" title="Duration">
                              <span
                                class="svg-icon svg-icon-warning svg-icon-3x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Home/Alarm-clock.svg--><svg
                                  xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                                  width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                  <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                    <rect x="0" y="0" width="24" height="24" />
                                    <path
                                      d="M7.14319965,19.3575259 C7.67122143,19.7615175 8.25104409,20.1012165 8.87097532,20.3649307 L7.89205065,22.0604779 C7.61590828,22.5387706 7.00431787,22.7026457 6.52602525,22.4265033 C6.04773263,22.150361 5.88385747,21.5387706 6.15999985,21.0604779 L7.14319965,19.3575259 Z M15.1367085,20.3616573 C15.756345,20.0972995 16.3358198,19.7569961 16.8634386,19.3524415 L17.8320512,21.0301278 C18.1081936,21.5084204 17.9443184,22.1200108 17.4660258,22.3961532 C16.9877332,22.6722956 16.3761428,22.5084204 16.1000004,22.0301278 L15.1367085,20.3616573 Z"
                                      fill="currentColor" />
                                    <path
                                      d="M12,21 C7.581722,21 4,17.418278 4,13 C4,8.581722 7.581722,5 12,5 C16.418278,5 20,8.581722 20,13 C20,17.418278 16.418278,21 12,21 Z M19.068812,3.25407593 L20.8181344,5.00339833 C21.4039208,5.58918477 21.4039208,6.53893224 20.8181344,7.12471868 C20.2323479,7.71050512 19.2826005,7.71050512 18.696814,7.12471868 L16.9474916,5.37539627 C16.3617052,4.78960984 16.3617052,3.83986237 16.9474916,3.25407593 C17.5332781,2.66828949 18.4830255,2.66828949 19.068812,3.25407593 Z M5.29862906,2.88207799 C5.8844155,2.29629155 6.83416297,2.29629155 7.41994941,2.88207799 C8.00573585,3.46786443 8.00573585,4.4176119 7.41994941,5.00339833 L5.29862906,7.12471868 C4.71284263,7.71050512 3.76309516,7.71050512 3.17730872,7.12471868 C2.59152228,6.53893224 2.59152228,5.58918477 3.17730872,5.00339833 L5.29862906,2.88207799 Z"
                                      fill="currentColor" opacity="0.3" />
                                    <path
                                      d="M11.9630156,7.5 L12.0475062,7.5 C12.3043819,7.5 12.5194647,7.69464724 12.5450248,7.95024814 L13,12.5 L16.2480695,14.3560397 C16.403857,14.4450611 16.5,14.6107328 16.5,14.7901613 L16.5,15 C16.5,15.2109164 16.3290185,15.3818979 16.1181021,15.3818979 C16.0841582,15.3818979 16.0503659,15.3773725 16.0176181,15.3684413 L11.3986612,14.1087258 C11.1672824,14.0456225 11.0132986,13.8271186 11.0316926,13.5879956 L11.4644883,7.96165175 C11.4845267,7.70115317 11.7017474,7.5 11.9630156,7.5 Z"
                                      fill="currentColor" />
                                  </g>
                                </svg><!--end::Svg Icon-->
                              </span>
                              {{ durationCalculation($event->start_date, $event->end_date) }}
                            </span>
                            <span class="fw-normal text-dark-900 fs-4" data-bs-custom-class="tooltip-dark"
                              data-bs-toggle="tooltip" data-bs-placement="top" title="Price">
                              <span class="svg-icon svg-icon-danger svg-icon-3x">
                                <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 512 512"
                                  viewBox="0 0 512 512">
                                  <path fill="currentColor" opacity="0.3"
                                    d="M21 394.1c-.1 0-.2 0-.4-.1C20.7 394 20.9 394.1 21 394.1zM491 117.9c.1 0 .2 0 .4.1C491.3 118 491.1 117.9 491 117.9zM20.6 118c.1 0 .2 0 .4-.1C20.9 117.9 20.7 118 20.6 118zM491.4 394c-.1 0-.2 0-.4.1C491.1 394.1 491.3 394 491.4 394zM12 127.9c0-2.1.6-4 1.7-5.6C12.6 123.9 12 125.8 12 127.9l0 256.3c0 2.1.6 4 1.7 5.6-1.1-1.6-1.7-3.5-1.7-5.6V127.9zM500 127.9c0-2.1-.6-4-1.7-5.6C499.4 123.9 500 125.8 500 127.9l0 256.3c0 2.1-.6 4-1.7 5.6 1.1-1.6 1.7-3.5 1.7-5.6V127.9zM256.1 344.6c48.7 0 88.3-39.6 88.3-88.3s-39.6-88.3-88.3-88.3c-48.7 0-88.3 39.6-88.3 88.3S207.4 344.6 256.1 344.6zM218.8 224H265c-4-8-11.9-13.8-21.4-14.8h-24.8v-15h78.7v15h-24.3c3.6 4.3 6.3 9.3 7.9 14.8h16.4v15h-14.8c-1.4 19.7-16.3 35.7-35.5 38.6l33.9 40.8-11.5 9.6-45.1-54.2c-2.1-2.6-2.3-6.2-.4-9 1.9-2.8 5.3-4 8.5-2.9 2.7.9 5.4 1.3 8.3 1.3 13.9 0 25.4-10.6 26.9-24.1h-48.8V224z" />
                                  <path fill="currentColor" opacity="0.3"
                                    d="M32,374.1h448V137.9H32V374.1z M428.6,148.3h30.6c5.5,0,10,4.5,10,10v30.6c0,5.5-4.5,10-10,10s-10-4.5-10-10
                                                                                                                                                v-20.6h-20.6c-5.5,0-10-4.5-10-10S423,148.3,428.6,148.3z M428.6,343.7h20.6v-20.6c0-5.5,4.5-10,10-10s10,4.5,10,10v30.6
                                                                                                                                                c0,5.5-4.5,10-10,10h-30.6c-5.5,0-10-4.5-10-10S423,343.7,428.6,343.7z M256.1,148.1c59.7,0,108.3,48.6,108.3,108.3
                                                                                                                                                s-48.6,108.3-108.3,108.3S147.8,316,147.8,256.3S196.4,148.1,256.1,148.1z M42.8,158.3c0-5.5,4.5-10,10-10h30.6c5.5,0,10,4.5,10,10
                                                                                                                                                s-4.5,10-10,10H62.8v20.6c0,5.5-4.5,10-10,10s-10-4.5-10-10V158.3z M42.8,323.1c0-5.5,4.5-10,10-10s10,4.5,10,10v20.6h20.6
                                                                                                                                                c5.5,0,10,4.5,10,10s-4.5,10-10,10H52.8c-5.5,0-10-4.5-10-10V323.1z" />
                                  <path fill="currentColor"
                                    d="M500,127.9c0-2.1-0.6-4-1.7-5.6c-1.6-2.3-4-3.9-6.9-4.3c-0.1,0-0.2,0-0.4-0.1c-0.3,0-0.7-0.1-1-0.1H22
                                                                                                                                                c-0.3,0-0.7,0-1,0.1c-0.1,0-0.2,0-0.4,0.1c-2.9,0.4-5.4,2-6.9,4.3c-1.1,1.6-1.7,3.5-1.7,5.6v256.3c0,2.1,0.6,4,1.7,5.6
                                                                                                                                                c1.6,2.3,4,3.9,6.9,4.3c0.1,0,0.2,0,0.4,0.1c0.3,0,0.7,0.1,1,0.1h468c0.3,0,0.7,0,1-0.1c0.1,0,0.2,0,0.4-0.1c2.9-0.4,5.3-2,6.9-4.3
                                                                                                                                                c1.1-1.6,1.7-3.5,1.7-5.6V127.9z M480,374.1H32V137.9h448V374.1z" />
                                  <path fill="currentColor"
                                    d="M256.1 364.6c59.7 0 108.3-48.6 108.3-108.3s-48.6-108.3-108.3-108.3-108.3 48.6-108.3 108.3S196.4 364.6 256.1 364.6zM256.1 168.1c48.7 0 88.3 39.6 88.3 88.3s-39.6 88.3-88.3 88.3c-48.7 0-88.3-39.6-88.3-88.3S207.4 168.1 256.1 168.1zM52.8 198.9c5.5 0 10-4.5 10-10v-20.6h20.6c5.5 0 10-4.5 10-10s-4.5-10-10-10H52.8c-5.5 0-10 4.5-10 10v30.6C42.8 194.4 47.3 198.9 52.8 198.9zM428.6 168.3h20.6v20.6c0 5.5 4.5 10 10 10s10-4.5 10-10v-30.6c0-5.5-4.5-10-10-10h-30.6c-5.5 0-10 4.5-10 10S423 168.3 428.6 168.3zM428.6 363.7h30.6c5.5 0 10-4.5 10-10v-30.6c0-5.5-4.5-10-10-10s-10 4.5-10 10v20.6h-20.6c-5.5 0-10 4.5-10 10S423 363.7 428.6 363.7zM52.8 363.7h30.6c5.5 0 10-4.5 10-10s-4.5-10-10-10H62.8v-20.6c0-5.5-4.5-10-10-10s-10 4.5-10 10v30.6C42.8 359.3 47.3 363.7 52.8 363.7z" />
                                  <path fill="currentColor"
                                    d="M240.7,263.1c-2.8,0-5.6-0.4-8.3-1.3c-3.2-1-6.6,0.2-8.5,2.9c-1.9,2.8-1.7,6.4,0.4,9l45.1,54.2l11.5-9.6
                                                                                                                                                l-33.9-40.8c19.2-2.9,34.2-18.9,35.5-38.6h14.8v-15H281c-1.6-5.5-4.3-10.5-7.9-14.8h24.3v-15h-78.7v15h24.8
                                                                                                                                                c9.5,1,17.4,6.8,21.4,14.8h-46.2v15h48.8C266.1,252.5,254.6,263.1,240.7,263.1z" />
                                </svg>
                              </span>
                              ₹{{ round($event->price) }}
                            </span>
                          </p>
                          <!--end::Desc-->
                          <div class="form-group row">
                            <div class="col-lg-6">
                              <!--begin::Dialer-->
                              <div class="input-group w-md-150px" data-kt-dialer="true" data-kt-dialer-min="1"
                                data-kt-dialer-max="10" data-kt-dialer-step="1">

                                <!--begin::Decrease control-->
                                <button
                                  class="btn btn-icon btn-outline btn-outline-secondary event-decrease-quantity-temp"
                                  type="button">
                                  <i class="bi bi-dash fs-1"></i>
                                </button>
                                <!--end::Decrease control-->

                                <!--begin::Input control-->
                                <input type="text" class="form-control" id="event_book_seat_{{ $event->id }}"
                                  readonly placeholder="Seats" value="1" data-kt-dialer-control="input" />
                                <!--end::Input control-->

                                <!--begin::Increase control-->
                                <button
                                  class="btn btn-icon btn-outline btn-outline-secondary event-increase-quantity-temp"
                                  type="button">
                                  <i class="bi bi-plus fs-1"></i>
                                </button>
                                <!--end::Increase control-->
                              </div>
                              <!--end::Dialer-->
                            </div>
                            <div class="col-lg-6">
                              <a href="#" class="btn btn-sm btn-danger hover-elevate-up my-2 mx-2"
                                style="width:100%" onclick="bookProduct(this)" data-event-id="{{ $event->id }}">
                                <i class="fa-solid fa-passport fs-2"></i>
                                Book Now
                              </a>
                            </div>
                          </div>
                        </div>
                        <!--end::Body-->
                      </div>
                      <!--end::Card-->
                    </div>
                  @endforeach
                </div>
              </div>
              <!--end::Desktop View-->
            </div>
            <!--end::Body-->
            <div class="card-footer d-flex justify-content-end p-0">
              <a href="#" id="kt_drawer_shopping_cart_toggle" class="btn btn-danger w-100 fw-bold ">View Cart
                <span
                  class="badge badge-primary badge ms-2 view-cart-span">{{ count($sessionData['items']) + count($sessionData['playAreas']) + count($sessionData['events']) }}
                  Items</span></a>
            </div>
          </div>
        </div>
        <!--end::Col-->
      </div>
    </div>
    <!--end::Content-->
  </div>
@endsection
@section('modules')
  @include('pages.items.customer.modules.drawers.guestCartDrawer')
  @include('pages.items.customer.modules.toasts.status')
  @include('pages.items.customer.modules.models.loginSignupModal')
@endsection
@section('scripts')
  @include('bladeAssets.customer.guest-cart-logics')
  <script>
    $(document).ready(function() {
      $('#searchInput, #kt_datepicker_1').on('input', function() {
        var searchInputValue = $('#searchInput').val();
        var dateValue = $('#kt_datepicker_1').val();
        $.ajax({
          url: '/search-events',
          method: 'GET',
          data: {
            search: searchInputValue,
            date: dateValue
          },
          success: function(response) {
            function updateCardBody(filteredItems) {
              var cardBody = $('#desktop-view');
              cardBody.empty();
              filteredItems.forEach(function(item) {
                var itemHtml = generateItemHtml(item);
                cardBody.append(itemHtml);
              });
              attachDialerListeners();
            }
            updateCardBody(response);
          },
          error: function(xhr, status, error) {
            console.error(xhr);
          }
        });
      });
    });

    function generateItemHtml(event) {
      var img = `{{ asset('${event.image}') }}`
      return `
      <div class="col-12 col-sm-6 rounded-2 d-flex flex-column flex-row-fluid m-2">
        <!--begin::Card-->
        <div class="card card-flush shadow-sm">
          <!--begin::Overlay-->
          <a class="d-block overlay" data-fslightbox="lightbox-hot-sales" href="${img}">
            <!--begin::Image-->
            <div
              class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px"
              style="background-image:url(${img})">
            </div>
            <!--end::Image-->
          </a>
          <!--end::Overlay-->
          <!--begin::Body-->
          <div class="card-body pt-4" style="padding:15px;">
            <!--begin::User-->
            <div class="d-flex align-items-end mb-2">
              <div class="d-flex align-items-center">
                <!--begin::Title-->
                <div class="d-flex flex-column">
                  <a href="#"
                    class="fs-4 text-dark-800 text-hover-primary fw-bolder">${event.title} 
                  </a>
                  <span class="fw-bold text-dark-400" data-bs-custom-class="tooltip-dark"
                    data-bs-toggle="tooltip" data-bs-placement="top"
                    title="Start Date">${moment(event.start_date).format('DD-MMM-YYYY')} | ${moment(event.start_date).format('h:mm A')}
                    </span>
                </div>
                <!--end::Title-->
              </div>
            </div>
            <!--end::User-->
            <!--begin::Desc-->
            <p class="mb-2 text-center">
              <span class="fw-bold text-dark-900 fs-4" data-bs-custom-class="tooltip-dark"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Capacity">
                <span
                  class="svg-icon svg-icon-primary svg-icon-3x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Home/Armchair.svg--><svg
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                      <path
                        d="M20,8 L18.173913,8 C17.0693435,8 16.173913,8.8954305 16.173913,10 L16.173913,12 C16.173913,12.5522847 15.7261978,13 15.173913,13 L8.86956522,13 C8.31728047,13 7.86956522,12.5522847 7.86956522,12 L7.86956522,10 C7.86956522,8.8954305 6.97413472,8 5.86956522,8 L4,8 L4,6 C4,4.34314575 5.34314575,3 7,3 L17,3 C18.6568542,3 20,4.34314575 20,6 L20,8 Z"
                        fill="currentColor" opacity="0.3" />
                      <path
                        d="M6.15999985,21.0604779 L8.15999985,17.5963763 C8.43614222,17.1180837 9.04773263,16.9542085 9.52602525,17.2303509 C10.0043179,17.5064933 10.168193,18.1180837 9.89205065,18.5963763 L7.89205065,22.0604779 C7.61590828,22.5387706 7.00431787,22.7026457 6.52602525,22.4265033 C6.04773263,22.150361 5.88385747,21.5387706 6.15999985,21.0604779 Z M17.8320512,21.0301278 C18.1081936,21.5084204 17.9443184,22.1200108 17.4660258,22.3961532 C16.9877332,22.6722956 16.3761428,22.5084204 16.1000004,22.0301278 L14.1000004,18.5660262 C13.823858,18.0877335 13.9877332,17.4761431 14.4660258,17.2000008 C14.9443184,16.9238584 15.5559088,17.0877335 15.8320512,17.5660262 L17.8320512,21.0301278 Z"
                        fill="currentColor" opacity="0.3" />
                      <path
                        d="M20,10 L20,15 C20,16.6568542 18.6568542,18 17,18 L7,18 C5.34314575,18 4,16.6568542 4,15 L4,10 L5.86956522,10 L5.86956522,12 C5.86956522,13.6568542 7.21271097,15 8.86956522,15 L15.173913,15 C16.8307673,15 18.173913,13.6568542 18.173913,12 L18.173913,10 L20,10 Z"
                        fill="currentColor" />
                    </g>
                  </svg><!--end::Svg Icon-->
                </span>
                ${event.totalBooked}/${event.seats}
              </span>
              <span class="fw-bold text-dark-900  px-2 fs-4" data-bs-custom-class="tooltip-dark"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Duration">
                <span
                  class="svg-icon svg-icon-warning svg-icon-3x"><!--begin::Svg Icon | path:/var/www/preview.keenthemes.com/metronic/releases/2021-05-14-112058/theme/html/demo1/dist/../src/media/svg/icons/Home/Alarm-clock.svg--><svg
                    xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink"
                    width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                      <rect x="0" y="0" width="24" height="24" />
                      <path
                        d="M7.14319965,19.3575259 C7.67122143,19.7615175 8.25104409,20.1012165 8.87097532,20.3649307 L7.89205065,22.0604779 C7.61590828,22.5387706 7.00431787,22.7026457 6.52602525,22.4265033 C6.04773263,22.150361 5.88385747,21.5387706 6.15999985,21.0604779 L7.14319965,19.3575259 Z M15.1367085,20.3616573 C15.756345,20.0972995 16.3358198,19.7569961 16.8634386,19.3524415 L17.8320512,21.0301278 C18.1081936,21.5084204 17.9443184,22.1200108 17.4660258,22.3961532 C16.9877332,22.6722956 16.3761428,22.5084204 16.1000004,22.0301278 L15.1367085,20.3616573 Z"
                        fill="currentColor" />
                      <path
                        d="M12,21 C7.581722,21 4,17.418278 4,13 C4,8.581722 7.581722,5 12,5 C16.418278,5 20,8.581722 20,13 C20,17.418278 16.418278,21 12,21 Z M19.068812,3.25407593 L20.8181344,5.00339833 C21.4039208,5.58918477 21.4039208,6.53893224 20.8181344,7.12471868 C20.2323479,7.71050512 19.2826005,7.71050512 18.696814,7.12471868 L16.9474916,5.37539627 C16.3617052,4.78960984 16.3617052,3.83986237 16.9474916,3.25407593 C17.5332781,2.66828949 18.4830255,2.66828949 19.068812,3.25407593 Z M5.29862906,2.88207799 C5.8844155,2.29629155 6.83416297,2.29629155 7.41994941,2.88207799 C8.00573585,3.46786443 8.00573585,4.4176119 7.41994941,5.00339833 L5.29862906,7.12471868 C4.71284263,7.71050512 3.76309516,7.71050512 3.17730872,7.12471868 C2.59152228,6.53893224 2.59152228,5.58918477 3.17730872,5.00339833 L5.29862906,2.88207799 Z"
                        fill="currentColor" opacity="0.3" />
                      <path
                        d="M11.9630156,7.5 L12.0475062,7.5 C12.3043819,7.5 12.5194647,7.69464724 12.5450248,7.95024814 L13,12.5 L16.2480695,14.3560397 C16.403857,14.4450611 16.5,14.6107328 16.5,14.7901613 L16.5,15 C16.5,15.2109164 16.3290185,15.3818979 16.1181021,15.3818979 C16.0841582,15.3818979 16.0503659,15.3773725 16.0176181,15.3684413 L11.3986612,14.1087258 C11.1672824,14.0456225 11.0132986,13.8271186 11.0316926,13.5879956 L11.4644883,7.96165175 C11.4845267,7.70115317 11.7017474,7.5 11.9630156,7.5 Z"
                        fill="currentColor" />
                    </g>
                  </svg><!--end::Svg Icon-->
                </span> 
                ${durationCalculation(event.start_date, event.end_date)}
              </span>
              <span class="fw-normal text-dark-900 fs-4" data-bs-custom-class="tooltip-dark"
                data-bs-toggle="tooltip" data-bs-placement="top" title="Price">
                <span class="svg-icon svg-icon-danger svg-icon-3x">
                  <svg xmlns="http://www.w3.org/2000/svg" enable-background="new 0 0 512 512"
                    viewBox="0 0 512 512">
                    <path fill="currentColor" opacity="0.3"
                      d="M21 394.1c-.1 0-.2 0-.4-.1C20.7 394 20.9 394.1 21 394.1zM491 117.9c.1 0 .2 0 .4.1C491.3 118 491.1 117.9 491 117.9zM20.6 118c.1 0 .2 0 .4-.1C20.9 117.9 20.7 118 20.6 118zM491.4 394c-.1 0-.2 0-.4.1C491.1 394.1 491.3 394 491.4 394zM12 127.9c0-2.1.6-4 1.7-5.6C12.6 123.9 12 125.8 12 127.9l0 256.3c0 2.1.6 4 1.7 5.6-1.1-1.6-1.7-3.5-1.7-5.6V127.9zM500 127.9c0-2.1-.6-4-1.7-5.6C499.4 123.9 500 125.8 500 127.9l0 256.3c0 2.1-.6 4-1.7 5.6 1.1-1.6 1.7-3.5 1.7-5.6V127.9zM256.1 344.6c48.7 0 88.3-39.6 88.3-88.3s-39.6-88.3-88.3-88.3c-48.7 0-88.3 39.6-88.3 88.3S207.4 344.6 256.1 344.6zM218.8 224H265c-4-8-11.9-13.8-21.4-14.8h-24.8v-15h78.7v15h-24.3c3.6 4.3 6.3 9.3 7.9 14.8h16.4v15h-14.8c-1.4 19.7-16.3 35.7-35.5 38.6l33.9 40.8-11.5 9.6-45.1-54.2c-2.1-2.6-2.3-6.2-.4-9 1.9-2.8 5.3-4 8.5-2.9 2.7.9 5.4 1.3 8.3 1.3 13.9 0 25.4-10.6 26.9-24.1h-48.8V224z" />
                    <path fill="currentColor" opacity="0.3"
                      d="M32,374.1h448V137.9H32V374.1z M428.6,148.3h30.6c5.5,0,10,4.5,10,10v30.6c0,5.5-4.5,10-10,10s-10-4.5-10-10
                      v-20.6h-20.6c-5.5,0-10-4.5-10-10S423,148.3,428.6,148.3z M428.6,343.7h20.6v-20.6c0-5.5,4.5-10,10-10s10,4.5,10,10v30.6
                      c0,5.5-4.5,10-10,10h-30.6c-5.5,0-10-4.5-10-10S423,343.7,428.6,343.7z M256.1,148.1c59.7,0,108.3,48.6,108.3,108.3
                      s-48.6,108.3-108.3,108.3S147.8,316,147.8,256.3S196.4,148.1,256.1,148.1z M42.8,158.3c0-5.5,4.5-10,10-10h30.6c5.5,0,10,4.5,10,10
                      s-4.5,10-10,10H62.8v20.6c0,5.5-4.5,10-10,10s-10-4.5-10-10V158.3z M42.8,323.1c0-5.5,4.5-10,10-10s10,4.5,10,10v20.6h20.6
                      c5.5,0,10,4.5,10,10s-4.5,10-10,10H52.8c-5.5,0-10-4.5-10-10V323.1z" />
                    <path fill="currentColor"
                      d="M500,127.9c0-2.1-0.6-4-1.7-5.6c-1.6-2.3-4-3.9-6.9-4.3c-0.1,0-0.2,0-0.4-0.1c-0.3,0-0.7-0.1-1-0.1H22
                      c-0.3,0-0.7,0-1,0.1c-0.1,0-0.2,0-0.4,0.1c-2.9,0.4-5.4,2-6.9,4.3c-1.1,1.6-1.7,3.5-1.7,5.6v256.3c0,2.1,0.6,4,1.7,5.6
                      c1.6,2.3,4,3.9,6.9,4.3c0.1,0,0.2,0,0.4,0.1c0.3,0,0.7,0.1,1,0.1h468c0.3,0,0.7,0,1-0.1c0.1,0,0.2,0,0.4-0.1c2.9-0.4,5.3-2,6.9-4.3
                      c1.1-1.6,1.7-3.5,1.7-5.6V127.9z M480,374.1H32V137.9h448V374.1z" />
                    <path fill="currentColor"
                      d="M256.1 364.6c59.7 0 108.3-48.6 108.3-108.3s-48.6-108.3-108.3-108.3-108.3 48.6-108.3 108.3S196.4 364.6 256.1 364.6zM256.1 168.1c48.7 0 88.3 39.6 88.3 88.3s-39.6 88.3-88.3 88.3c-48.7 0-88.3-39.6-88.3-88.3S207.4 168.1 256.1 168.1zM52.8 198.9c5.5 0 10-4.5 10-10v-20.6h20.6c5.5 0 10-4.5 10-10s-4.5-10-10-10H52.8c-5.5 0-10 4.5-10 10v30.6C42.8 194.4 47.3 198.9 52.8 198.9zM428.6 168.3h20.6v20.6c0 5.5 4.5 10 10 10s10-4.5 10-10v-30.6c0-5.5-4.5-10-10-10h-30.6c-5.5 0-10 4.5-10 10S423 168.3 428.6 168.3zM428.6 363.7h30.6c5.5 0 10-4.5 10-10v-30.6c0-5.5-4.5-10-10-10s-10 4.5-10 10v20.6h-20.6c-5.5 0-10 4.5-10 10S423 363.7 428.6 363.7zM52.8 363.7h30.6c5.5 0 10-4.5 10-10s-4.5-10-10-10H62.8v-20.6c0-5.5-4.5-10-10-10s-10 4.5-10 10v30.6C42.8 359.3 47.3 363.7 52.8 363.7z" />
                    <path fill="currentColor"
                      d="M240.7,263.1c-2.8,0-5.6-0.4-8.3-1.3c-3.2-1-6.6,0.2-8.5,2.9c-1.9,2.8-1.7,6.4,0.4,9l45.1,54.2l11.5-9.6
                      l-33.9-40.8c19.2-2.9,34.2-18.9,35.5-38.6h14.8v-15H281c-1.6-5.5-4.3-10.5-7.9-14.8h24.3v-15h-78.7v15h24.8
                      c9.5,1,17.4,6.8,21.4,14.8h-46.2v15h48.8C266.1,252.5,254.6,263.1,240.7,263.1z" />
                  </svg>
                </span>
                ₹${Math.round(event.price)}
              </span>
            </p>
            <!--end::Desc-->
            <div class="form-group row">
              <div class="col-lg-6">
                <!--begin::Dialer-->
                <div class="input-group w-md-150px" data-kt-dialer="true" data-kt-dialer-min="1"
                  data-kt-dialer-max="10" data-kt-dialer-step="1">

                  <!--begin::Decrease control-->
                  <button class="btn btn-icon btn-outline btn-outline-secondary event-decrease-quantity-temp" type="button">
                      <i class="bi bi-dash fs-1"></i>
                  </button>
                  <!--end::Decrease control-->

                  <!--begin::Input control-->
                  <input type="text" class="form-control" id="event_book_seat_${event.id}" readonly placeholder="Seats" value="1"
                      data-kt-dialer-control="input" />
                  <!--end::Input control-->

                  <!--begin::Increase control-->
                  <button class="btn btn-icon btn-outline btn-outline-secondary event-increase-quantity-temp" type="button">
                      <i class="bi bi-plus fs-1"></i>
                  </button>
                  <!--end::Increase control-->
                </div>
                <!--end::Dialer-->
              </div>
              <div class="col-lg-6">
                <a href="#" class="btn btn-sm btn-danger hover-elevate-up my-2 mx-2"
                  style="width:100%" onclick="bookProduct(this)" data-event-id="${event.id}">
                  <i class="fa-solid fa-passport fs-2"></i>
                  Book Now
                </a>
              </div>
            </div>
          </div>
          <!--end::Body-->
        </div>
        <!--end::Card-->
      </div>
    `;
    }

    function attachDialerListeners() {
      const dialerGroups = document.querySelectorAll('[data-kt-dialer="true"]');

      dialerGroups.forEach(group => {
        const min = parseInt(group.getAttribute('data-kt-dialer-min'), 10);
        const max = parseInt(group.getAttribute('data-kt-dialer-max'), 10);
        const step = parseInt(group.getAttribute('data-kt-dialer-step'), 10);

        const input = group.querySelector('[data-kt-dialer-control="input"]');
        const decreaseButton = group.querySelector('.event-decrease-quantity-temp');
        const increaseButton = group.querySelector('.event-increase-quantity-temp');

        decreaseButton.addEventListener('click', () => {
          let value = parseInt(input.value, 10);
          if (value > min) {
            value -= step;
            input.value = value;
          }
        });

        increaseButton.addEventListener('click', () => {
          let value = parseInt(input.value, 10);
          if (value < max) {
            value += step;
            input.value = value;
          }
        });
      });
    }

    document.addEventListener('DOMContentLoaded', function() {
      attachDialerListeners();
    });
  </script>

  <script>
    $(".kt_datepicker_event_search_week_number").flatpickr({
      weekNumbers: true
    });
  </script>
@endsection
