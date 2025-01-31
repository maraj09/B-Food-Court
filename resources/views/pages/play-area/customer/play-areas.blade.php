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
              <h3 class="card-title align-items-start flex-column fs-7">Play Area
                <input type="text" class="form-control form-control-solid w-100px w-md-250px" id="searchInputForPlayArea"
                  name="search" placeholder="Search Product" autocomplete="on">
              </h3>
              <div class="d-flex align-items-center">
                <div>
                  <h3 class="card-title align-items-start flex-column fs-7 mb-0">Select Date & Time Range</h3>
                  <input type="text" class="form-control" id="dateTimeRangePicker" placeholder="Select Time Range"
                    value="" />
                </div>
              </div>
            </div>
            <!--begin::Body-->
            <div class="card-body pt-2 card-scroll px-4" style="height: 65vh">
              <!--begin::Mobile View-->
              <div class="d-md-block">
                <div class="row row-cols-3 row-cols-md-3 row-cols-lg-4 g-0" id="desktop-view">
                  @foreach ($playAreas as $playArea)
                    <div class="col-4 rounded-2 d-flex flex-column flex-row-fluid m-2 flex-grow-1">
                      <!--begin::Card-->
                      <div
                        class="card card-flush shadow-sm hover-scale border-dashed border-gray-600 d-flex flex-col h-100">
                        <div class="ribbon ribbon-top">
                          <div class="ribbon-label bg-info fw-bold">{{ $playArea->max_player }} Players</div>
                        </div>
                        <!--begin::Overlay-->
                        <a class="d-block overlay" data-fslightbox="lightbox-hot-sales"
                          href="{{ asset($playArea->image) }}">
                          <!--begin::Image-->
                          <div
                            class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px"
                            style="background-image:url('{{ asset($playArea->image) }}')">
                          </div>
                          <!--end::Image-->
                        </a>
                        <!--end::Overlay-->
                        <!--begin::Body-->
                        <div class="card-body pt-4 flex-grow-1" style="padding:15px;">
                          <!--begin::User-->
                          <div class="d-flex align-items-end mb-2">
                            <div class="d-flex align-items-center">
                              <!--begin::Title-->
                              <div class="d-flex flex-column">
                                <a href="#"
                                  class="fs-4 text-gray-900 text-hover-primary fw-bolder">{{ $playArea->title }}
                                </a>
                                <span class="text-gray-500 fw-semibold fs-7 my-1">{{ $playArea->details }}</span>
                              </div>
                              <!--end::Title-->
                            </div>
                          </div>
                          <!--end::User-->
                        </div>
                        <!--end::Body-->
                        <div class="card-footer row justify-content-center align-items-center py-2 px-4 mt-auto mb-3">
                          <!--begin::Rating-->
                          <div
                            class="col-12 col-sm-4 d-flex flex-wrap flex-grow-1 justify-content-center justify-content-sm-start">
                            <div class="text-center ">
                              <span
                                class="text-gray-800  fw-bolder fs-5 border border-warning border-dashed rounded py-2 px-2 me-2">₹{{ round($playArea->price) }}/Hour</span>
                            </div>
                          </div>
                          <!--ed::Rating-->
                          <!--begin::Add Now-->
                          <div
                            class="col-12 col-sm-8 d-flex align-items-center flex-shrink-0 justify-content-center justify-content-sm-end mt-5 mt-sm-0">
                            <a href="javascript:void(0)"
                              class="btn btn-sm btn-danger hover-scale w-100 w-md-250px px-2 py-2"
                              data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                              data-bs-original-title="Book Now" data-kt-initialized="1" onclick="bookProduct(this)"
                              data-play-area-id="{{ $playArea->id }}">
                              <i class="fa-solid fa-baseball-bat-ball fs-2"></i>Book Now
                            </a>
                          </div>
                          <!--ed::Add Now-->
                        </div>
                      </div>
                      <!--end::Card-->
                    </div>
                  @endforeach
                </div>
              </div>
              <!--end::Desktop View-->
            </div>
            <!--end::Body-->
            @include('layouts.customer.components.place-order-footer-with-latest-order-progress-curasol')
          </div>
        </div>
        <!--end::Col-->
      </div>
    </div>
    <!--end::Content-->
  </div>
@endsection
@section('modules')
  @include('pages.items.customer.modules.drawers.cart')
  @include('pages.items.customer.modules.toasts.status')
@endsection
@section('footer')
  @include('layouts.customer.components.bottom-navigation')
@endsection
@section('scripts')
  @include('bladeAssets.customer.cart-logics')
  <script>
    $(document).ready(function() {
      function fetchItems() {
        var searchQuery = $('#searchInputForPlayArea').val();
        var range = $('#dateTimeRangePicker').val();
        $.ajax({
          url: '/search-play-area',
          method: 'GET',
          data: {
            search: searchQuery,
            range: range,
          },
          success: function(response) {
            function updateCardBody(filteredItems) {
              var cardBody = $('#desktop-view');
              cardBody.empty();
              filteredItems.forEach(function(item) {
                var itemHtml = generatePlayAreaHtml(item);
                cardBody.append(itemHtml);
              });
            }
            updateCardBody(response);
          },
          error: function(xhr, status, error) {
            console.error(xhr);
          }
        });
      }

      $('#searchInputForPlayArea').on('input', fetchItems);

      $("#dateTimeRangePicker").daterangepicker({
        timePicker: true, // Enable time selection
        autoUpdateInput: false, // Prevents auto-updating the input with a value
        locale: {
          format: 'M/D h:mm A',
          cancelLabel: 'Clear'
        }
      });

      // Event listener for applying the date-time range
      $('#dateTimeRangePicker').on('apply.daterangepicker', function(ev, picker) {
        // Set the selected date-time range as the input value
        $(this).val(picker.startDate.format('M/DD hh:mm A') + ' - ' + picker.endDate.format('M/DD hh:mm A'));
        fetchItems();
      });

      // Event listener for canceling the date-time selection
      $('#dateTimeRangePicker').on('cancel.daterangepicker', function(ev, picker) {
        // Clear the input value if the selection is canceled
        $(this).val('');
        fetchItems();
      });
    });

    function generatePlayAreaHtml(playArea) {
      var img = `{{ asset('${playArea.image}') }}`
      return `
      <div class="col-4 rounded-2 d-flex flex-column flex-row-fluid m-2 flex-grow-1">
      <!--begin::Card-->
        <div
          class="card card-flush shadow-sm hover-scale border-dashed border-gray-600 d-flex flex-col h-100">
          <div class="ribbon ribbon-top">
            <div class="ribbon-label bg-info fw-bold">${playArea.max_player} Players</div>
          </div>
          <!--begin::Overlay-->
          <a class="d-block overlay" data-fslightbox="lightbox-hot-sales"
            href="${img}">
            <!--begin::Image-->
            <div
              class="overlay-wrapper bgi-no-repeat bgi-position-center bgi-size-cover card-rounded min-h-175px"
              style="background-image:url(${img})">
            </div>
            <!--end::Image-->
          </a>
          <!--end::Overlay-->
          <!--begin::Body-->
          <div class="card-body pt-4 flex-grow-1" style="padding:15px;">
            <!--begin::User-->
            <div class="d-flex align-items-end mb-2">
              <div class="d-flex align-items-center">
                <!--begin::Title-->
                <div class="d-flex flex-column">
                  <a href="#"
                    class="fs-4 text-gray-900 text-hover-primary fw-bolder">${playArea.title}
                  </a>
                  <span class="text-gray-500 fw-semibold fs-7 my-1">${playArea.details}</span>
                </div>
                <!--end::Title-->
              </div>
            </div>
            <!--end::User-->
          </div>
          <!--end::Body-->
          <div class="card-footer row justify-content-center align-items-center py-2 px-4 mt-auto mb-3">
            <!--begin::Rating-->
            <div
              class="col-12 col-sm-4 d-flex flex-wrap flex-grow-1 justify-content-center justify-content-sm-start">
              <div class="text-center ">
                <span
                  class="text-gray-800  fw-bolder fs-5 border border-warning border-dashed rounded py-2 px-2 me-2">₹${Math.round(playArea.price)}/Hour</span>
              </div>
            </div>
            <!--ed::Rating-->
            <!--begin::Add Now-->
            <div
              class="col-12 col-sm-8 d-flex align-items-center flex-shrink-0 justify-content-center justify-content-sm-end mt-5 mt-sm-0">
              <a href="javascript:void(0)" class="btn btn-sm btn-danger hover-scale w-100 w-md-250px px-2 py-2"
                  data-bs-toggle="tooltip"
                  data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                  data-bs-original-title="Book Now" data-kt-initialized="1"
                  onclick="bookProduct(this)" data-play-area-id="${playArea.id}">
                  <i class="fa-solid fa-baseball-bat-ball fs-2"></i>Book Now
              </a>
            </div>
            <!--ed::Add Now-->
          </div>
        </div>
      <!--end::Card-->
      </div>
    `;
    }
  </script>
@endsection
