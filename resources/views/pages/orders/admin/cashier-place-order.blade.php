@extends('layouts.admin.app')
@section('contents')
  @include('pages.orders.admin.toolbar.cashier-place-order-toolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl mt-10 mt-md-0">
      <!--begin::Products-->
      <!--begin::Row-->
      <div class="row row-cols-1 row-cols-sm-2 row-cols-xl-2 gy-5">
        <!--begin::Item-->
        <div class="col mb-9 d-flex flex-column flex-row-fluid">
          <div class="card-body px-2">
            <!--begin::Row-->
            <div class="row g-5 flex-wrap-reverse">
              <!--begin::Col-->
              <div class="col-12 col-md-7">
                <div class="card bg-light card-flush shadow-sm">
                  <div class="card-header bg-primary ribbon ribbon-start ribbon-clip">
                    <h3 class="card-title align-items-start flex-column fs-7">Menu Item
                      <input type="text" class="form-control form-control-solid w-100px w-md-250px mt-n1"
                        id="searchInput" name="search" placeholder="Search Product" autocomplete="on">
                    </h3>
                    <div class="card-toolbar">
                      <!--begin::Search-->
                      <select class="form-select mt-3" data-control="select2" id="categorySelect"
                        data-placeholder="Select an option">
                        <option value="all">All Category</option>
                        @foreach ($itemCategories as $categorie)
                          <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                        @endforeach
                      </select>
                      <!--en:d:Search-->
                    </div>
                  </div>
                  <!--begin::Body-->
                  <div class="card-body pt-2 card-scroll h-500px ">
                    <!--begin::Mobile View-->
                    <div class="d-md-none" id="mobile-view">
                      <!--begin::Item-->
                      @can('cashier-items-management')
                        @foreach ($items as $item)
                          <div class="separator separator-dashed border-primary my-6"></div>
                          <div class="d-flex my-3">
                            <!--begin::Section-->
                            <div class="d-flex align-items-center flex-wrap flex-grow-1 ms-3 mt-n2 mt-lg-n1"
                              data-bs-toggle="modal" data-bs-target="#kt_modal_3"
                              onclick="populateModal({{ $item->id }})">
                              <!--begin::Title-->
                              <div class="d-flex flex-column flex-grow-1 my-lg-0 my-1 pe-3">
                                <a href="#"
                                  class="fs-5 text-gray-800 text-hover-primary fw-bold">{{ $item->item_name }}
                                  @if ($item->item_type == 'nonVegetarian')
                                    <img src="{{ asset('custom/assets/images/item-types/nonveg.png') }}" alt=""
                                      class="h-15px ms-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                                      data-bs-placement="top" aria-label="Non Vegetarian"
                                      data-bs-original-title="Non Vegetarian">
                                  @elseif ($item->item_type == 'eggetarian')
                                    <img src="{{ asset('custom/assets/images/item-types/egg.png') }}" alt=""
                                      class="h-15px ms-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                                      data-bs-placement="top" aria-label="Eggetarian" data-bs-original-title="Eggetarian">
                                  @elseif ($item->item_type == 'vegetarian')
                                    <img src="{{ asset('custom/assets/images/item-types/veg.png') }}" alt=""
                                      class="h-15px ms-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                                      data-bs-placement="top" aria-label="Vegetarian" data-bs-original-title="Vegetarian">
                                  @endif
                                </a>
                                <span class="text-gray-500 fw-semibold fs-7">By: <a href="#"
                                    class="text-primary fw-bold">{{ $item->vendor->brand_name }}</a></span>
                                <div class="my-1">
                                  <span class="badge badge-outline badge-warning my-2 ms-1">Stall No.
                                    {{ $item->vendor->stall_no }}</span>
                                  <i class="fa fa-star-half-alt text-warning fs-5"></i>
                                  <span class="text-gray-800 fw-bold">{{ $item->itemRating->rating ?? 0 }}</span>
                                </div>
                              </div>
                              <!--end::Title-->
                            </div>
                            <!--end::Section-->
                            <!--begin::Symbol-->
                            <div class="symbol symbol-75px symbol-2by3 flex-shrink-0">
                              <img src="{{ $item->item_image }}" alt="" data-bs-toggle="modal"
                                data-bs-target="#kt_modal_3" onclick="populateModal({{ $item->id }})" />
                              <span
                                class="position-absolute top-0 start-75 translate-middle badge {{ $item->category->ribbon_color ? $item->category->ribbon_color : 'bg-primary' }}">
                                <span class="text-gray-900 fw-bold">{{ $item->category->name }}</span>
                              </span>
                              <div class="row">
                                <div class="col">
                                  <form class="itemForm" action="/cart/add/{{ $item->id }}" method="post"
                                    data-item-id="{{ $item->id }}">
                                    @csrf
                                    <a href="#" onclick="bookProduct({{ json_encode($item) }}, 'foodItem')"
                                      data-item-id="{{ $item->id }}"
                                      class="btn btn-danger w-100 rounded-top-0 py-2 mt-n4 hover-elevate-up"
                                      data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                                      data-bs-placement="top" title="Add Item"><i
                                        class="fa-solid fa-cart-plus"></i>₹{{ round($item->price) }}</a>
                                  </form>
                                </div>
                              </div>
                            </div>
                            <!--end::Symbol-->
                          </div>
                        @endforeach
                      @endcan
                      <div class="separator separator-dashed border-primary my-6"></div>
                    </div>
                    <!--end::Mobile View-->
                    <!--begin::Desktop View-->
                    <div class="d-none d-md-block">
                      <!--begin::Row-->
                      <div class="row row-cols-4 g-0" id="desktop-view">
                        <!--begin::Col-->
                        @can('cashier-items-management')
                          @foreach ($items as $item)
                            <div class="col-3 rounded-2 d-flex flex-column flex-row-fluid m-2">
                              <!--begin::Mixed Widget 17-->
                              <div class="card card-flush d-flex flex-column-auto p-0 m-1">
                                <!--begin::Body-->
                                <div class="card-body p-0 ribbon ribbon-top" data-bs-toggle="modal"
                                  data-bs-target="#kt_modal_3" onclick="populateModal({{ $item->id }})">
                                  <div
                                    class="ribbon-label {{ $item->category->ribbon_color ? $item->category->ribbon_color : 'bg-primary' }}">
                                    {{ $item->category->name }}
                                  </div>
                                  <!--begin::Image-->
                                  <div class="d-flex flex-center w-100 py-2">
                                    <div class="symbol symbol-150px symbol-circle me-2 my-2">
                                      @if ($item->item_image)
                                        <img src="{{ asset($item->item_image) }}" class="align-self-center"
                                          alt="">
                                      @else
                                        <img src="{{ asset('assets/media/svg/files/blank-image-dark.svg') }}"
                                          class="align-self-center" alt="">
                                      @endif
                                    </div>
                                  </div>
                                  <!--end::Image-->
                                  <!--begin::Name and Vendor-->
                                  <div class="text-center w-100 position-relative z-index-1 py-3">
                                    <a href="#"
                                      class="fs-4 text-gray-800 text-hover-primary fw-bold">{{ $item->item_name }}
                                      @if ($item->item_type == 'nonVegetarian')
                                        <img src="{{ asset('custom/assets/images/item-types/nonveg.png') }}"
                                          alt="" class="h-20px ms-1" data-bs-toggle="tooltip"
                                          data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                                          aria-label="Non Vegetarian" data-bs-original-title="Non Vegetarian">
                                      @elseif ($item->item_type == 'eggetarian')
                                        <img src="{{ asset('custom/assets/images/item-types/egg.png') }}" alt=""
                                          class="h-20px ms-1" data-bs-toggle="tooltip"
                                          data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                                          aria-label="Eggetarian" data-bs-original-title="Eggetarian">
                                      @elseif ($item->item_type == 'vegetarian')
                                        <img src="{{ asset('custom/assets/images/item-types/veg.png') }}" alt=""
                                          class="h-20px ms-1" data-bs-toggle="tooltip"
                                          data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                                          aria-label="Vegetarian" data-bs-original-title="Vegetarian">
                                      @endif
                                    </a>
                                    <br>
                                    <span class="text-gray-500 fw-semibold fs-7">By: <a href="#"
                                        class="text-primary fw-bold">{{ $item->vendor->brand_name }}</a></span>
                                  </div>
                                  <!--end::Name and Vendor-->
                                </div>
                                <!--end::Body-->
                                <!--begin::Footer-->
                                <div class="card-footer d-flex flex-center py-2 px-4">
                                  <!--begin::Rating-->
                                  <div class="d-flex flex-wrap flex-grow-1" data-bs-toggle="modal"
                                    data-bs-target="#kt_modal_3" onclick="populateModal({{ $item->id }})">
                                    <div class="me-5s">
                                      <span class="badge badge-outline badge-warning">Stall No.
                                        {{ $item->vendor->stall_no }}</span>
                                    </div>
                                    <div class="m-1">
                                      <i class="fa fa-star-half-alt me-1 text-warning fs-5"></i>
                                      <span class="text-gray-800 fw-bold">{{ $item->itemRating->rating ?? 0 }}</span>
                                    </div>
                                  </div>
                                  <!--ed::Rating-->
                                  <!--begin::Add Now-->
                                  <div class="d-flex align-items-center flex-shrink-0">
                                    <a href="#" onclick="bookProduct({{ json_encode($item) }}, 'foodItem')"
                                      data-item-id="{{ $item->id }}"
                                      class="btn btn-danger hover-scale w-100 px-4 py-2" data-bs-toggle="tooltip"
                                      data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Add Item"><i
                                        class="fa-solid fa-cart-plus"></i>₹{{ round($item->price) }}</a>
                                  </div>
                                  <!--ed::Add Now-->
                                </div>
                                <!--end::Footer-->
                              </div>
                              <!--end::Mixed Widget 17-->
                            </div>
                          @endforeach
                        @endcan
                        <!--end::Col-->
                      </div>
                      <!--end::Row-->
                    </div>
                    <div>
                      @can('cashier-events-management')
                        <div class="row g-2" id="events-div">
                          @foreach ($events as $event)
                            <div class="col-12 col-xl-6 rounded-2 d-flex flex-column flex-row-fluid">
                              <!--begin::Card-->
                              <div class="card card-flush shadow-sm">
                                <!--begin::Overlay-->
                                <a class="d-block overlay" data-fslightbox="lightbox-hot-sales"
                                  href="{{ asset($event->image) }}">
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
                                          title="Start Date">{{ \Carbon\Carbon::parse($event->start_date)->format('d-M-Y') }}
                                          |
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
                                        <button class="btn btn-icon btn-outline btn-outline-secondary" type="button"
                                          data-kt-dialer-control="decrease">
                                          <i class="bi bi-dash fs-1"></i>
                                        </button>
                                        <!--end::Decrease control-->

                                        <!--begin::Input control-->
                                        <input type="text" class="form-control" readonly placeholder="Seats"
                                          value="1" data-kt-dialer-control="input"
                                          id="event_book_seat_{{ $event->id }}" />
                                        <!--end::Input control-->

                                        <!--begin::Increase control-->
                                        <button class="btn btn-icon btn-outline btn-outline-secondary" type="button"
                                          data-kt-dialer-control="increase">
                                          <i class="bi bi-plus fs-1"></i>
                                        </button>
                                        <!--end::Increase control-->
                                      </div>
                                      <!--end::Dialer-->
                                    </div>
                                    <div class="col-lg-6">
                                      <a href="#" class="btn btn-sm btn-danger hover-elevate-up my-2 mx-2"
                                        style="width:100%" onclick="bookProduct({{ json_encode($event) }}, 'event')"
                                        data-event-id="{{ $event->id }}">
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
                      @endcan
                      @can('cashier-play-area-management')
                        <div class="row" id="play-area-div">
                          @foreach ($playAreas as $playArea)
                            <div class="col-6 rounded-2 d-flex flex-column flex-row-fluid m-2 flex-grow-1">
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
                                <div
                                  class="card-footer row justify-content-center align-items-center py-2 px-4 mt-auto mb-3">
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
                                    <a href="#" class="btn btn-sm btn-danger hover-scale w-100 w-md-250px px-2 py-2"
                                      data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                                      data-bs-placement="top" data-bs-original-title="Book Now" data-kt-initialized="1"
                                      onclick="bookProduct({{ json_encode($playArea) }}, 'playArea')"
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
                      @endcan
                    </div>
                    <!--end::Desktop View-->
                  </div>
                  <!--end::Body-->
                  <div class="card-footer d-flex justify-content-end p-0">
                    <button type="button" class="btn btn-danger w-100 fw-bold flex-1">Total
                      <span class="badge badge-primary badge ms-2 view-cart-span">0
                        Items</span></button>
                  </div>
                </div>
              </div>
              <!--end::Col-->
              <!--begin::Col-->
              <div class="col-12 col-md-5">
                <!--begin::Messenger-->
                <div class="card w-md-100 mt-0 border-0 rounded-0 app-aside flex-column" id="kt_app_aside"
                  data-kt-drawer-close="#kt_drawer_order_close" data-kt-drawer="true" data-kt-drawer-name="app-aside"
                  data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true"
                  data-kt-drawer-width="auto" data-kt-drawer-direction="end"
                  data-kt-drawer-toggle="#kt_app_aside_toggle" data-kt-drawer-width="400px">
                  <!--begin::Card header-->
                  <div class="card-header bg-primary pe-5">
                    <!--begin::Title-->
                    <div class="card-title">
                      <!--begin::User-->
                      <div class="d-flex justify-content-center flex-column me-3">
                        <a id="kt_drawer_order_toggle"
                          class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1">#{{ $newCustomId }}</a>
                        <!--begin::Info-->
                        <div class="mb-0 lh-1">
                          <span class="badge badge-success badge-circle w-10px h-10px me-1" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            title="Order Delivered"></span>
                          <span class="fs-7 fw-semibold text-gray-900">Order ID</span>
                        </div>
                        <!--end::Info-->
                      </div>
                      <!--end::User-->
                    </div>
                    <!--end::Title-->
                    <!--begin::Card toolbar-->
                    <div class="card-toolbar">
                      <select id="kt_docs_select2_badge"
                        class="form-select w-100px w-md-250px rounded fs-6 new_order_drawer_select_customer"
                        name="..." data-placeholder="Select Customer">
                        <option></option>
                        @foreach ($customerUsers as $customerUser)
                          <option value="{{ $customerUser->id }}"
                            data-kt-select2-badge="{{ $customerUser->point->points ?? 0 }} Points"
                            data-kt-customer-info="{{ $customerUser->phone }}">
                            {{ $customerUser->name }}
                          </option>
                        @endforeach
                      </select>

                    </div>
                    <!--end::Card toolbar-->
                  </div>
                  <!--end::Card header-->
                  <!--begin::Card body-->
                  <div class="card-body p-2 scroll h-450px">
                    <!--begin::row-->
                    <div class="row">
                      <div class="col px-5">
                        <div id="cartItemsContainer">
                        </div>
                        <div id="cartEventsContainer"></div>
                        <div id="cartPlayAreaContainer"></div>
                      </div>
                    </div>
                    <!--end::row-->
                  </div>
                  <!--end::Card body-->
                  <!--begin::Card footer-->
                  <div class="card-footer px-3 py-0">
                    <!--begin::Heading-->
                    <div
                      class="d-flex align-items-center justify-content-between flex-wrap py-2 breakup_coupon_container">
                      <!--begin::Label-->
                      <span class="fs-4 fw-bold pe-2" data-bs-toggle="collapse"
                        data-bs-target="#kt_docs_card_billbreakup">Bill
                        Breakup<i class="fas fa-info-circle text-primary ms-2 collapsible cursor-pointer"
                          data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                          title="Bill Details"></i></span>
                      <!--end::Label-->
                      <span
                        class="btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning ms-auto px-3 py-1 applied_coupon_head"
                        data-bs-toggle="collapse" data-bs-target="#kt_docs_card_applied_coupon">Coupons<i
                          class="fas fa-info-circle text-primary ms-2 collapsible cursor-pointer"
                          data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                          title="Applied Coupon"></i></span>

                    </div>
                    <!--end::Heading-->
                    <!--begin::Bill Breakup-->
                    <div id="kt_docs_card_billbreakup"
                      class="collapse hide border border-dashed border-danger my-2 rounded-1">
                      <div class="d-flex flex-column p-2" id="billbreakup">
                      </div>
                    </div>
                    <!--end::Bill Breakup-->
                    <!--begin::Coupons-->
                    <div id="kt_docs_card_applied_coupon"
                      class="collapse hide border border-dashed border-danger my-2 rounded-1">
                      <div class="d-flex flex-column p-2">
                        @foreach ($coupons as $coupon)
                          <li class="d-flex align-items-center py-2 couponButtonForCashier"
                            data-code="{{ $coupon->code }}" data-discount="{{ $coupon->discount }}"
                            data-discount-type="{{ $coupon->discount_type }}">
                            <span
                              class="btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning px-3 py-1 coupon_span_{{ $coupon->code }}">{{ $coupon->code }}
                              :
                              {{ $coupon->discount_type == 'fixed' ? '₹' . $coupon->discount : $coupon->discount . '%' }}</span>
                          </li>
                        @endforeach
                      </div>
                    </div>
                    <!--end::Coupons-->
                    <div class="row row-cols-2">
                      <div class="col p-0">
                        <a href="#" id="kt_drawer_example_basic_button"
                          class="btn btn-warning hover-elevate-up w-100 rounded-0 py-2" data-bs-toggle="tooltip"
                          data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Clear All"
                          onclick="adminCartDrawerClear()">Clear Now</a>
                      </div>
                      <div class="col p-0">
                        <a href="" class="btn btn-danger hover-elevate-up w-100 rounded-0 py-2 dropdown-toggle"
                          data-bs-toggle="dropdown" role="button" aria-expanded="false" id="total_price_in_pay_now">₹
                          0<span class="badge badge-dark ms-2">0 Items</span></a>
                        <ul class="dropdown-menu p-0">
                          @if ($settings->payment_mode_upi_status)
                            <li><a href="#" onclick="placeOrder('upi', event)"
                                class="dropdown-item btn btn-light-warning w-100 rounded-0 py-2">UPI</a></li>
                          @endif
                          @if ($settings->payment_mode_cash_status)
                            <li><a href="#" onclick="placeOrder('cash', event)"
                                class="dropdown-item btn btn-light-success w-100 rounded-0 py-2">Cash</a></li>
                          @endif
                          @if ($settings->payment_mode_card_status)
                            <li><a href="#" onclick="placeOrder('card', event)"
                                class="dropdown-item btn btn-light-danger w-100 rounded-0 py-2">Card</a></li>
                          @endif
                        </ul>
                      </div>
                    </div>
                    <!--end::View component-->
                  </div>
                  <!--end::Card footer-->
                </div>
                <!--end::Messenger-->
              </div>
              <!--end::Col-->
            </div>
            <!--end::Row-->
          </div>
        </div>
        <!--end::Item-->
      </div>
      <!--end::Row-->
      <!--end::Products-->
      <!--end::Content container-->
    </div>
    <!--end::Content-->
  </div>
@endsection
@section('modules')
  @include('pages.items.customer.modules.models.reviewModal')
@endsection
@section('scripts')
  <script>
    function populateModal(id) {
      const sortOption = $('.reviewSortSelect').val();
      $.ajax({
        url: '/get-items-data',
        type: 'GET',
        data: {
          id: id,
          sort: sortOption
        },
        success: function(response) {
          $('#reviewModalDialog').html(response.drawerContent);
        },
        error: function(xhr, status, error) {
          console.error('Error fetching customer data:', xhr);
        }
      });
    }
  </script>
  <script>
    var selectedItems = {};
    var selectedEvents = {};
    var selectedPlayAreas = {};

    function removeProduct(itemId, type) {
      if (type == 'foodItem') {
        $("#item_" + itemId).remove();
        delete selectedItems[itemId];
      } else if (type == 'event') {
        $("#event_" + itemId).remove();
        delete selectedEvents[itemId];
      } else if (type == 'playArea') {
        $("#playArea_" + itemId).remove();
        delete selectedPlayAreas[itemId];
      }
      if ($("#coupon_used").length) {
        removeCoupon();
      } else {
        updateTotalCost();
      }
    }

    function updateTotalCost(couponDiscount = null) {
      var total = 0;
      var totalProductsCount = 0;
      for (var itemID in selectedItems) {
        if (selectedItems.hasOwnProperty(itemID)) {
          total +=
            selectedItems[itemID].price * selectedItems[itemID].quantity;
          totalProductsCount += 1;
        }
      }
      for (var itemID in selectedEvents) {
        if (selectedEvents.hasOwnProperty(itemID)) {
          total +=
            selectedEvents[itemID].price * selectedEvents[itemID].quantity;
          totalProductsCount += 1;
        }
      }
      for (var itemID in selectedPlayAreas) {
        if (selectedPlayAreas.hasOwnProperty(itemID)) {
          total += Math.round(
            selectedPlayAreas[itemID].price *
            selectedPlayAreas[itemID].quantity *
            selectedPlayAreas[itemID].duration
          ) || 0;
          totalProductsCount += 1;
        }
      }
      $(".view-cart-span").html(`${totalProductsCount} Items`);

      $("#billbreakup").html("");
      $("#billbreakup").append(`
        <li class="d-flex align-items-center py-2">
            <span class="bullet bullet-vertical fw-bold me-5"></span>Total : <span class="ms-2">₹${parseFloat(
                total
            ).toFixed(2)}</span>
        </li>`);
      if ($("#coupon_used").length) {
        total -= couponDiscount;
      }
      var gstAmount = 0;
      var sgtAmount = 0;
      var serviceTaxAmount = 0;
      if (gst > 0) {
        gstAmount = total * (gst / 100);
        $("#billbreakup").append(` 
        <li class="d-flex align-items-center py-2">
            <span class="bullet bullet-vertical fw-bold bg-danger me-5"></span>GST ${parseFloat(
                gst
            ).toFixed(1)}%: <span class="ms-2">₹${parseFloat(gstAmount).toFixed(
            2
        )}</span>
        </li>`);
      }

      if (sgt > 0) {
        var sgtAmount = total * (sgt / 100);
        $("#billbreakup").append(` 
        <li class="d-flex align-items-center py-2">
            <span class="bullet bullet-vertical fw-bold bg-danger me-5"></span>SGT ${parseFloat(
                sgt
            ).toFixed(1)}%: <span class="ms-2">₹${parseFloat(sgtAmount).toFixed(
            2
        )}</span>
        </li>`);
      }

      if (serviceTax > 0) {
        var serviceTaxAmount = total * (serviceTax / 100);
        $("#billbreakup").append(` 
        <li class="d-flex align-items-center py-2">
            <span class="bullet bullet-vertical fw-bold bg-danger me-5"></span>Service Tax ${parseFloat(
                serviceTax
            ).toFixed(1)}%: <span class="ms-2">₹${parseFloat(
            serviceTaxAmount
        ).toFixed(2)}</span>
        </li>`);
      }

      if ($("#coupon_used").length) {
        $("#billbreakup").append(` 
        <li class="d-flex align-items-center py-2">
            <span class="bullet bullet-vertical fw-bold bg-success me-5"></span>Coupon Discount : <span class="ms-2">₹${parseFloat(
                couponDiscount
            ).toFixed(2)}</span>
        </li>`);
      }

      document.getElementById(
        "total_price_in_pay_now"
      ).innerHTML = `₹ ${parseFloat(
        total + serviceTaxAmount + sgtAmount + gstAmount
    ).toFixed(2)}<span
    class="badge badge-dark ms-2">${
        Object.keys(selectedItems).length
    } Items</span>`;
    }

    $(document).on("click", ".couponButtonForCashier", function(event) {
      event.preventDefault();

      if ($("#coupon_used").length) {
        Swal.fire({
          text: "Please remove the coupon first!",
          icon: "error",
          buttonsStyling: !1,
          confirmButtonText: "Ok, got it!",
          customClass: {
            confirmButton: "btn btn-primary",
          },
        });
        return false;
      }

      var couponCode = $(this).data("code");
      var discount = $(this).data("discount");
      var discountType = $(this).data("discount-type");
      var userId = $(`.new_order_drawer_select_customer`)
        .find("option:selected")
        .val();
      if (!userId) {
        Swal.fire({
          text: "Please select a customer first!",
          icon: "error",
          buttonsStyling: !1,
          confirmButtonText: "Ok, got it!",
          customClass: {
            confirmButton: "btn btn-primary",
          },
        });
        return;
      }
      var selectedItemsArray = [];
      var selectedEventsArray = [];
      var selectedPlayAreasArray = [];
      for (var itemID in selectedItems) {
        if (selectedItems.hasOwnProperty(itemID)) {
          selectedItemsArray.push({
            id: itemID,
            quantity: selectedItems[itemID].quantity,
          });
        }
      }
      for (var itemID in selectedEvents) {
        if (selectedEvents.hasOwnProperty(itemID)) {
          selectedEventsArray.push({
            id: itemID,
            quantity: selectedEvents[itemID].quantity,
          });
        }
      }
      for (var itemID in selectedPlayAreas) {
        if (selectedPlayAreas.hasOwnProperty(itemID)) {
          selectedPlayAreasArray.push({
            id: itemID,
            quantity: selectedPlayAreas[itemID].quantity,
            price: selectedPlayAreas[itemID].price,
            duration: selectedPlayAreas[itemID].durationInHours,
            date: selectedPlayAreas[itemID].date,
            start_time: selectedPlayAreas[itemID].start_time,
            end_time: selectedPlayAreas[itemID].end_time,
          });
        }
      }
      var formData = {
        code: couponCode,
        userId: userId,
        items: selectedItemsArray,
        events: selectedEventsArray,
        playAreas: selectedPlayAreasArray,
        _token: document.querySelector('meta[name="csrf-token"]').content,
      };

      $.ajax({
        url: "/dashboard/apply-coupon",
        type: "POST",
        data: formData,
        success: function(response) {
          if (response.success) {
            $(`.coupon_span_${response.data.coupon.code}`).removeClass(
              "btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning"
            );
            $(`.coupon_span_${response.data.coupon.code}`).addClass(
              "btn-warning"
            );
            $(`.applied_coupon_head`).html(`${
                    response.data.coupon.code
                } : ${
                    response.data.coupon.discount_type == "fixed"
                        ? "₹" + response.data.coupon.discount
                        : response.data.coupon.discount + "%"
                } <i
                class="fas fa-info-circle text-primary ms-2 collapsible cursor-pointer" data-bs-toggle="tooltip"
                data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Applied Coupon"></i><input type="hidden" id="coupon_used" value="${
                    response.data.coupon.code
                }" />`);
            $(`.breakup_coupon_container`).append(
              `<button onclick="removeCoupon()" id="removeCoupon" class="btn btn-danger btn-sm ms-2 p-2">Remove</button>`
            );
            updateTotalCost(response.data.coupon_discount);
          } else {
            Swal.fire({
              text: response.message,
              icon: "error",
              buttonsStyling: false,
              confirmButtonText: "Ok, got it!",
              customClass: {
                confirmButton: "btn btn-primary",
              },
            });
          }
        },
        error: function(error) {
          console.log(error);

          var errors = error.responseJSON.errors;
          var errorMessage = Object.values(errors).flat().join("<br>");

          Swal.fire({
            html: errorMessage,
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
              confirmButton: "btn btn-primary",
            },
          });
        },
      });
    });

    function removeCoupon() {
      var code = $("#coupon_used").val();
      $(`#removeCoupon`).remove();
      $(`.coupon_span_${code}`).addClass(
        "btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning"
      );
      $(`.coupon_span_${code}`).removeClass("btn-warning");
      $(`.applied_coupon_head`).html(`Coupons <i
    class="fas fa-info-circle text-primary ms-2 collapsible cursor-pointer" data-bs-toggle="tooltip"
    data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Applied Coupon"></i>`);
      updateTotalCost();
    }

    function adminCartDrawerClear() {
      selectedItems = {};
      selectedEvents = {};
      selectedPlayAreas = {};
      $("#cartItemsContainer").html("");
      $("#cartEventsContainer").html("");
      $("#cartPlayAreaContainer").html("");
      if ($("#coupon_used").length) {
        removeCoupon();
      } else {
        updateTotalCost();
      }
    }

    function updateQuantity(itemId, action, type) {
      if ($("#coupon_used").length) {
        removeCoupon();
      }

      if (type == 'foodItem') {
        var quantityElement = document.getElementById(`item_quantity_${itemId}`);
        var currentQuantity = parseInt(quantityElement.value);
        var newQuantity = currentQuantity;

        if (action === "increase") {
          newQuantity++;
        } else if (action === "decrease" && newQuantity > 1) {
          newQuantity--;
        }

        quantityElement.value = newQuantity;

        // Update the quantity in the selectedItems object
        selectedItems[itemId].quantity = newQuantity;
        $(`#item_total_price_${itemId}`).html(
          "₹" +
          Math.round(
            selectedItems[itemId].quantity * selectedItems[itemId].price
          )
        );
      } else if (type == 'event') {
        var quantityElement = document.getElementById(`event_quantity_${itemId}`);
        var currentQuantity = parseInt(quantityElement.value);
        var newQuantity = currentQuantity;

        if (action === "increase") {
          newQuantity++;
        } else if (action === "decrease" && newQuantity > 1) {
          newQuantity--;
        }

        quantityElement.value = newQuantity;

        // Update the quantity in the selectedItems object
        selectedEvents[itemId].quantity = newQuantity;
        $(`#event_total_price_${itemId}`).html(
          "₹" +
          Math.round(
            selectedEvents[itemId].quantity * selectedEvents[itemId].price
          )
        );
      } else if (type == 'playArea') {
        var quantityElement = document.getElementById(`playArea_quantity_${itemId}`);
        var currentQuantity = parseInt(quantityElement.value);
        var newQuantity = currentQuantity;

        if (action === "increase") {
          newQuantity++;
        } else if (action === "decrease" && newQuantity > 1) {
          newQuantity--;
        }

        quantityElement.value = newQuantity;

        // Update the quantity in the selectedItems object
        selectedPlayAreas[itemId].quantity = newQuantity;
        $(`.cart-per-play-area-player-${itemId}`).html(
          newQuantity + "P"
        );
        $(`.cart-per-product-total-${itemId}`).html(
          "₹" +
          (Math.round(
            selectedPlayAreas[itemId].quantity * selectedPlayAreas[itemId].price * selectedPlayAreas[itemId].duration
          ) || 0)
        );
      }

      // Update the total cost
      updateTotalCost();
    }

    function displayCartEvents(event) {
      var cartEventsContainer = $('#cartEventsContainer');
      var eventImage = "{{ asset('') }}" + event.image;
      var eventBookedSeatCount = Math.round($(`#event_book_seat_${event.id}`).val());
      var eventDetailsHtml = `
          <div id="event_${event.id}" class="d-block align-items-center border-dashed border-gray-900 bg-light rounded p-3 mb-7">
          <div class="d-flex align-items-sm-center mb-2">
            <!--begin::Symbol-->
            <div class="symbol symbol-50px symbol-circle me-2">
              <img src="${eventImage}" class="align-self-center" alt="">
            </div>
            <!--end::Symbol-->
            <!--begin::Section-->
            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
              <div class="flex-grow-1 me-5">
                <a href="#"
                  class="text-gray-800 text-hover-primary fs-6 fw-bold">${event.title}</a>
                <div class="d-flex flex-wrap flex-grow-1">
                  <div class="me-2">
                    <span class="text-success fw-bold">Price</span>
                    <span class="fw-bold text-gray-800 d-block fs-6">₹${Math.round(event.price)}</span>
                  </div>
                  <div class="me-5s">
                    <span class="text-danger fw-bold">Total</span>
                    <span
                      class="fw-bold text-gray-800 d-block fs-6" id="event_total_price_${event.id}">₹${Math.round(event.price * eventBookedSeatCount)}</span>
                  </div>
                </div>
              </div>
              <span>
                <div class="symbol symbol-35px cursor-pointer"
                  onclick="removeProduct(${event.id}, 'event')">
                  <div class="symbol-label bg-light-danger">
                    <i class="fas fa-trash-alt text-danger"></i>
                  </div>
                </div>
              </span>
            </div>
            <!--end::Section-->
          </div>
          <div class="d-flex flex-stack flex-wrap flex-grow-1">
            <div class="fw-bold fs-3 text-info">
              <!--begin::Dialer-->
              <div class="input-group w-200px" data-kt-dialer="true" data-kt-dialer-min="1"
                data-kt-dialer-max="10" data-kt-dialer-step="1">
                <!--begin::Decrease control-->
                <button class="btn btn-icon btn-outline btn-active-color-primary" type="button" onclick="updateQuantity(${event.id}, 'decrease', 'event')">
                  <i class="bi bi-dash fs-1"></i>
                </button>
                <!--end::Decrease control-->
                <!--begin::Input control-->
                <input type="text" class="form-control" readonly placeholder="Amount"
                  value="${eventBookedSeatCount}" data-kt-dialer-control="input" id="event_quantity_${event.id}" />
                <!--end::Input control-->
                <!--begin::Increase control-->
                <button class="btn btn-icon btn-outline btn-active-color-primary" type="button" onclick="updateQuantity(${event.id}, 'increase', 'event')">
                  <i class="bi bi-plus fs-1"></i>
                </button>
                <!--end::Increase control-->
              </div>
              <!--end::Dialer-->
            </div>
            <div class="me-2 text-end">
              <span
                class="text-info fw-bolder">${durationCalculation(event.start_date, event.end_date)}</span>
              <span
                class="fw-bold text-gray-800 d-block fs-6">${moment(event.start_date).format('DD-MM-YYYY')} | ${moment(event.start_date).format('h:mm A')}</span>
            </div>
          </div>
          <div class="d-flex align-items-sm-center my-3">
            <!--begin::Section-->
            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
              ${event.details}
            </div>
            <!--end::Section-->
          </div>
        </div>
        `;
      // Append generated HTML to the container
      cartEventsContainer.append(eventDetailsHtml);
      selectedEvents[event.id] = {
        price: parseFloat(event.price),
        quantity: eventBookedSeatCount,
      };
    }

    function displayCartItems(item) {
      var cartItemsContainer = $('#cartItemsContainer');
      var itemImage = "{{ asset('') }}" + item.item_image;
      var itemDetailsHtml = `
            <div id="item_${item.id}" class="d-block align-items-center border-dashed border-gray-900 bg-light rounded p-3 mb-7">
              <div class="d-flex align-items-sm-center mb-2">
                  <div class="symbol symbol-50px symbol-circle me-2">
                  <img src="${itemImage}" class="align-self-center" alt="${item.item_name}">
                  </div>
                  <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                      <div class="flex-grow-1 me-5">
                          <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">${item.item_name}</a>
                          <div class="d-flex flex-wrap flex-grow-1">
                            <div class="me-2">
                                <span class="text-success fw-bold">Price</span>
                                <span class="fw-bold text-gray-800 d-block fs-6">₹${Math.round(
                                  item.price
                                )}</span>
                            </div>
                            <div class="me-5s">
                                <span class="text-danger fw-bold">Total</span>
                                <span class="fw-bold text-gray-800 d-block fs-6" id="item_total_price_${item.id}">₹${Math.round(
                                    item.price
                                )}</span>
                            </div>
                          </div>
                      </div>
                      <span style="cursor: pointer" onclick="removeProduct(${item.id}, 'foodItem')">
                          <div class="symbol symbol-35px">
                              <div class="symbol-label bg-light-danger">
                              <i class="fas fa-trash-alt text-danger"></i>
                              </div>
                          </div>
                      </span>
                  </div>
              </div>
              <div class="d-flex flex-stack flex-wrap flex-grow-1">
                  <div class="fw-bold fs-3 text-info">
                      <div class="input-group w-200px" data-kt-dialer="true" data-kt-dialer-min="1"
                      data-kt-dialer-max="5" data-kt-dialer-step="1">
                      <!--begin::Decrease control-->
                      <button class="btn btn-icon btn-outline btn-active-color-primary" type="button"
                          data-kt-dialer-control="decrease" onclick="updateQuantity(${item.id}, 'decrease', 'foodItem')">
                          <i class="bi bi-dash fs-1"></i>
                      </button>
                      <!--end::Decrease control-->
                      <!--begin::Input control-->
                      <input type="text" class="form-control" readonly placeholder="Amount" id="item_quantity_${item.id}" value="1"
                          data-kt-dialer-control="input" />
                      <!--end::Input control-->
                      <!--begin::Increase control-->
                      <button class="btn btn-icon btn-outline btn-active-color-primary" type="button"
                          data-kt-dialer-control="increase" onclick="updateQuantity(${item.id}, 'increase', 'foodItem')" >
                          <i class="bi bi-plus fs-1"></i>
                      </button>
                      <!--end::Increase control-->
                      </div>
                  </div>
                  <div class="me-2 text-end">
                  <span class="text-info fw-bold">Vendor</span>
                  <span class="fw-bold text-gray-800 d-block fs-6">${item.vendor.brand_name}<span class="badge badge-primary badge-sm ms-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Stall No.">${item.vendor.stall_no}</span></span>
                  </div>
              </div>
            </div>
        `;

      // Append generated HTML to the container
      cartItemsContainer.append(itemDetailsHtml);
      selectedItems[item.id] = {
        price: parseFloat(item.price),
        quantity: 1,
      };
    }

    function displayCartPlayAreas(playArea) {
      var cartPlayAreaContainer = $('#cartPlayAreaContainer');
      var playAreaImage = "{{ asset('') }}" + playArea.image;
      var playAreaId = playArea.id;
      var datePickerId = `datePicker-${playAreaId}`;
      var startTimePickerId = `startTimePicker-${playAreaId}`;
      var endTimePickerId = `endTimePicker-${playAreaId}`;

      var start = playArea.play_area_start_time ? moment(playArea.play_area_start_time, "H:i") : null;
      var end = playArea.play_area_end_time ? moment(playArea.play_area_end_time, "H:i") : null;

      var durationFormatted = '0H';
      var cartPerPlayAreaTotalPrice = 0;
      var playersCount = playArea.quantity || 1;

      if (start && end) {
        var duration = moment.duration(end.diff(start));
        var durationHours = Math.floor(duration.asHours());
        var durationMinutes = duration.minutes();


        durationFormatted = `${durationHours}H`;
        if (durationMinutes > 0) {
          durationFormatted += ` ${durationMinutes}Min`;
        }


        var durationInMinutes = duration.asMinutes();
        var durationInHours = durationInMinutes / 60;

        var pricePerHour = playArea.price;

        cartPerPlayAreaTotalPrice = Math.round(pricePerHour * durationInHours * playersCount);
      }

      var itemDetailsHtml = `
        <div id="playArea_${playArea.id}" class="d-block align-items-center border-dashed border-gray-900 bg-light rounded p-3 mb-7" data-play-area-cart-id="${playArea.id}">
            <div class="d-flex align-items-sm-center mb-2">
                <div class="symbol symbol-50px symbol-2by3 me-2">
                    <img src="${playAreaImage}" class="align-self-center" alt="">
                </div>
                <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                    <div class="flex-grow-1 me-1">
                        <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">${playArea.title}</a>
                        <div class="d-flex flex-wrap flex-grow-1">
                            <div class="me-2 col-12 col-md-3 mb-1 mb-md-0">
                                <span class="text-success fw-bold">Price</span>
                                <span class="fw-bold text-gray-800 d-block fs-6">₹${Math.round(playArea.price)}/Hour/Player</span>
                            </div>
                            <div class="me-3">
                                <span class="text-warning fw-bold">Hours</span>
                                <span class="fw-bold text-gray-800 text-center d-block fs-6 cart-per-play-area-duration-${playArea.id}">${durationFormatted}</span>
                            </div>
                            <div class="mx-2">
                                <span class="text-info fw-bold">Players</span>
                                <span class="fw-bold text-gray-800 text-center d-block fs-6 cart-per-play-area-player-${playArea.id}">${playersCount}P</span>
                            </div>
                            <div class="mx-3">
                                <span class="text-danger fw-bold">Total</span>
                                <span class="fw-bold text-gray-800 text-center d-block fs-6 cart-per-product-total-${playArea.id}">₹${cartPerPlayAreaTotalPrice}</span>
                            </div>
                        </div>
                    </div>
                    <span class="ms-auto mt-2 mt-md-0">
                        <div class="symbol symbol-35px cursor-pointer delete-cart-item" onclick="removeProduct(${playArea.id}, 'playArea')">
                            <div class="symbol-label bg-light-danger">
                                <i class="fas fa-trash-alt text-danger"></i>
                            </div>
                        </div>
                    </span>
                </div>
            </div>
            <div class="d-flex flex-stack flex-wrap flex-grow-1 py-2 align-items-start">
                <div class="fs-3 text-info form-group w-125px">
                    <div class="position-relative d-flex align-items-center DatePickerContainer">
                        <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                        <input class="form-control form-control-sm kt_datepicker_dob_custom ps-12 datePicker" placeholder="Pick date" id="${datePickerId}" value="${playArea.play_area_date || ''}" />
                        </div>
                        <div class="invalid-feedback"></div>
                </div>
                <div class="w-100px form-group">
                    <div class="StartPickerContainer">
                    <input class="form-control form-control-sm flatpickr-input startTimePicker" placeholder="Start Time" id="${startTimePickerId}" type="text" readonly="readonly" value="${playArea.play_area_start_time || ''}">
                    <div class="invalid-feedback">asdasd</div>
                    </div>
                </div>
                <div class="w-100px form-group">
                  <div class="EndPickerContainer">
                    <input class="form-control form-control-sm flatpickr-input endTimePicker" placeholder="End Time" id="${endTimePickerId}" type="text" readonly="readonly" value="${playArea.play_area_end_time || ''}">
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
            </div>
            <div class="d-flex flex-stack flex-wrap flex-grow-1 mt-3">
                <div class="me-2 text-Start">
                  <span class="text-gray-900 fw-bold"> Add Players</span>
                </div>
                <div class="fw-bold fs-3 text-info">
                  <div class="input-group w-md-300px w-125px" data-kt-dialer="true" data-kt-dialer-min="1" data-kt-dialer-max="5" data-kt-dialer-step="1">
                    <button class="btn btn-icon btn-outline btn-active-color-primary" onclick="updateQuantity(${playArea.id}, 'decrease', 'playArea')" type="button">
                      <i class="bi bi-dash fs-1"></i>
                    </button>
                    <input type="text" class="form-control" readonly placeholder="Amount" id="playArea_quantity_${playArea.id}" value="${playArea.quantity || 1}" data-kt-dialer-control="input" />
                    <button class="btn btn-icon btn-outline btn-active-color-primary" onclick="updateQuantity(${playArea.id}, 'increase', 'playArea')"  type="button">
                      <i class="bi bi-plus fs-1"></i>
                    </button>
                  </div>
                </div>
            </div>
        </div>
    `;
      cartPlayAreaContainer.append(itemDetailsHtml);
      selectedPlayAreas[playArea.id] = {
        price: playArea.price,
        quantity: 1,
        duration: durationInHours,
        date: null,
        start_time: start,
        end_time: end,
      };

      let selectedDate = null;

      $(`#${datePickerId}`).flatpickr({
        dateFormat: "Y-m-d",
        minDate: "today",
        onChange: function(selectedDates, dateStr, instance) {
          selectedDate = new Date(selectedDates[0]);
          var playAreaId = $(instance.element).closest('[data-play-area-cart-id]').data('play-area-cart-id');
          submitDateTime(playAreaId, 'date', dateStr);
          updateStartTimePicker(selectedDate, startTimePickerId, endTimePickerId);
        }
      });

      function updateStartTimePicker(selectedDate, startTimePickerId, endTimePickerId) {
        let minTime = "00:00";
        const now = new Date();
        const oneHourFromNow = new Date(now.getTime() + 60 * 60 * 1000); // Add one hour to the current time
        const oneHourFromNowStr = oneHourFromNow.toTimeString().slice(0, 5);

        if (selectedDate.toDateString() === new Date().toDateString()) {
          if (oneHourFromNow.getDate() !== now.getDate()) {
            // If adding one hour crosses midnight, set minTime to 23:59
            minTime = "23:59";
          } else {
            minTime = oneHourFromNowStr;
          }
        }

        $(`#${startTimePickerId}`).flatpickr({
          enableTime: true,
          noCalendar: true,
          dateFormat: "H:i",
          time_24hr: true,
          minTime: minTime,
          onChange: function(selectedDates, timeStr, instance) {
            var playAreaId = $(instance.element).closest('[data-play-area-cart-id]').data('play-area-cart-id');
            const pricePerHour = playArea.price; // Fetch price per hour from your object
            const playersCount = selectedPlayAreas[playAreaId].quantity; // Fetch the quantity (number of players)
            submitDateTime(playAreaId, 'start_time', timeStr, pricePerHour, playersCount, timeStr,
              selectedPlayAreas[playAreaId].end_time);
            updateEndTimePicker(timeStr, endTimePickerId);
          }
        });
      }

      function updateEndTimePicker(startTimeStr, endTimePickerId) {
        const [hours, minutes] = startTimeStr.split(":").map(Number);
        const minEndTime = new Date();
        minEndTime.setHours(hours);
        minEndTime.setMinutes(minutes + 5);
        minEndTime.setSeconds(0);
        minEndTime.setMilliseconds(0);

        const formattedMinEndTime = minEndTime.toTimeString().slice(0, 5);

        if (minEndTime.getDate() !== new Date().getDate()) {
          // If adding 5 minutes crosses midnight, disable the end time picker
          $(`#${endTimePickerId}`).flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            clickOpens: false, // Disable the end time picker
            onChange: null
          });
        } else {
          $(`#${endTimePickerId}`).flatpickr({
            enableTime: true,
            noCalendar: true,
            dateFormat: "H:i",
            time_24hr: true,
            minTime: formattedMinEndTime,
            onChange: function(selectedDates, timeStr, instance) {
              var playAreaId = $(instance.element).closest('[data-play-area-cart-id]').data('play-area-cart-id');
              const pricePerHour = playArea.price; // Fetch price per hour from your object
              const playersCount = selectedPlayAreas[playAreaId].quantity; // Fetch the quantity (number of players)
              submitDateTime(playAreaId, 'end_time', timeStr, pricePerHour, playersCount, startTimeStr, timeStr);
            }
          });
        }
      }

      // Initialize the startTimePicker and endTimePicker with default settings
      $(`#${startTimePickerId}`).flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        onChange: function(selectedDates, timeStr, instance) {
          var playAreaId = $(instance.element).closest('[data-play-area-cart-id]').data('play-area-cart-id');
          const pricePerHour = playArea.price; // Fetch price per hour from your object
          const playersCount = selectedPlayAreas[playAreaId].quantity; // Fetch the quantity (number of players)

          submitDateTime(playAreaId, 'start_time', timeStr, pricePerHour, playersCount, timeStr, selectedPlayAreas[
            playAreaId].end_time);
          updateEndTimePicker(timeStr, endTimePickerId);
        }
      });

      $(`#${endTimePickerId}`).flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        onChange: function(selectedDates, timeStr, instance) {
          var playAreaId = $(instance.element).closest('[data-play-area-cart-id]').data('play-area-cart-id');
          const pricePerHour = playArea.price; // Fetch price per hour from your object
          const playersCount = selectedPlayAreas[playAreaId].quantity; // Fetch the quantity (number of players)

          submitDateTime(playAreaId, 'end_time', timeStr, pricePerHour, playersCount, selectedPlayAreas[playAreaId]
            .start_time, timeStr);
        }
      });
    }

    function submitDateTime(playAreaCartId, type, value, pricePerHour, playersCount, startTime, endTime) {
      if (type === 'date') {
        selectedPlayAreas[playAreaCartId].date = value;
        return;
      }
      if (type === 'end_time') {
        selectedPlayAreas[playAreaCartId].end_time = value;
      }
      if (type === 'start_time') {
        selectedPlayAreas[playAreaCartId].start_time = value;
      }

      var start = startTime ? moment(startTime, "HH:mm:ss") : null;
      var end = endTime ? moment(endTime, "HH:mm:ss") : null;

      var durationFormatted = '0H';
      var cartPerPlayAreaTotalPrice = 0;

      if (start && end) {
        var duration = moment.duration(end.diff(start));

        var durationHours = Math.floor(duration.asHours());
        var durationMinutes = duration.minutes();

        durationFormatted = `${durationHours}H`;
        if (durationMinutes > 0) {
          durationFormatted += ` ${durationMinutes}Min`;
        }

        var durationInHours = durationHours + (durationMinutes / 60);
        cartPerPlayAreaTotalPrice = Math.round(pricePerHour * durationInHours * playersCount);

        selectedPlayAreas[playAreaCartId].duration = durationInHours;
      }

      $(`.cart-per-play-area-duration-${playAreaCartId}`).text(durationFormatted);
      $(`.cart-per-product-total-${playAreaCartId}`).text('₹' + cartPerPlayAreaTotalPrice);
      updateTotalCost();
    }

    function bookProduct(product, type) {
      if ($("#coupon_used").length) {
        removeCoupon();
      }
      if (type == 'foodItem') {
        if ($("#item_" + product.id).length > 0) {
          updateQuantity(product.id, 'increase', 'foodItem')
        } else {
          displayCartItems(product);
        }
      } else if (type == 'event') {
        if ($("#event_" + product.id).length > 0) {
          updateQuantity(product.id, 'increase', 'event')
        } else {
          displayCartEvents(product);
        }
      } else if (type == 'playArea') {
        if ($("#playArea_" + product.id).length < 1) {
          displayCartPlayAreas(product);
        }
      }
      updateTotalCost();
    }

    function placeOrder(paymentMethod, e) {
      e.preventDefault();
      var selectedItemsArray = [];
      var selectedEventsArray = [];
      var selectedPlayAreasArray = [];
      for (var itemID in selectedItems) {
        if (selectedItems.hasOwnProperty(itemID)) {
          selectedItemsArray.push({
            id: itemID,
            quantity: selectedItems[itemID].quantity,
          });
        }
      }
      for (var itemID in selectedEvents) {
        if (selectedEvents.hasOwnProperty(itemID)) {
          selectedEventsArray.push({
            id: itemID,
            quantity: selectedEvents[itemID].quantity,
          });
        }
      }
      for (var itemID in selectedPlayAreas) {
        if (selectedPlayAreas.hasOwnProperty(itemID)) {
          selectedPlayAreasArray.push({
            id: itemID,
            quantity: selectedPlayAreas[itemID].quantity,
            price: selectedPlayAreas[itemID].price,
            duration: selectedPlayAreas[itemID].duration,
            date: selectedPlayAreas[itemID].date,
            start_time: selectedPlayAreas[itemID].start_time,
            end_time: selectedPlayAreas[itemID].end_time,
          });
        }
      }
      var userId = $(`.new_order_drawer_select_customer`)
        .find("option:selected")
        .val();
      if (!userId) {
        Swal.fire({
          text: "Please select a customer first!",
          icon: "error",
          buttonsStyling: !1,
          confirmButtonText: "Ok, got it!",
          customClass: {
            confirmButton: "btn btn-primary",
          },
        });
        return;
      }
      Swal.fire({
        text: "Are you sure you would like to place an order?",
        icon: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        confirmButtonText: "Yes, Place order!",
        cancelButtonText: "No, return",
        customClass: {
          confirmButton: "btn btn-success",
          cancelButton: "btn btn-active-light",
        },
      }).then(function(res) {
        if (res.isConfirmed) {
          var formData = {
            user_id: userId,
            payment_method: paymentMethod,
            status: "paid",
            ajax: "ajax",
            coupon_code: $("#coupon_used").val(),
            items: selectedItemsArray,
            events: selectedEventsArray,
            playAreas: selectedPlayAreasArray,
            _token: document.querySelector('meta[name="csrf-token"]').content,
          };

          $.ajax({
            url: "/dashboard/orders/store",
            type: "POST",
            data: formData,
            success: function(response) {
              if (response.success) {
                Swal.fire({
                  text: "Order successfully placed!",
                  icon: "success",
                  buttonsStyling: false,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                    confirmButton: "btn btn-primary",
                  },
                }).then((res) => {
                  location.reload();
                });
              } else {
                Swal.fire({
                  text: response.message,
                  icon: "error",
                  buttonsStyling: false,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                    confirmButton: "btn btn-primary",
                  },
                });
              }
            },
            error: function(error) {
              console.log(error);

              var errors = error.responseJSON.errors;
              var errorMessage = Object.values(errors)
                .flat()
                .join("<br>");

              Swal.fire({
                html: errorMessage,
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              });
            },
          });
        }
      });
    }
  </script>
  <script>
    function generateItemHtml(item) {

      let itemTypeHtml = '';
      if (item.item_type === 'nonVegetarian') {
        itemTypeHtml = `
            <img src="{{ asset('custom/assets/images/item-types/nonveg.png') }}" alt="" class="h-20px ms-1" 
                data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" 
                aria-label="Non Vegetarian" data-bs-original-title="Non Vegetarian">
        `;
      } else if (item.item_type === 'eggetarian') {
        itemTypeHtml = `
            <img src="{{ asset('custom/assets/images/item-types/egg.png') }}" alt="" class="h-20px ms-1" 
                data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" 
                aria-label="Eggetarian" data-bs-original-title="Eggetarian">
        `;
      } else if (item.item_type === 'vegetarian') {
        itemTypeHtml = `
            <img src="{{ asset('custom/assets/images/item-types/veg.png') }}" alt="" class="h-20px ms-1" 
                data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" 
                aria-label="Vegetarian" data-bs-original-title="Vegetarian">
        `;
      }
      return `
        <div class="col-3 rounded-2 d-flex flex-column flex-row-fluid m-2">
            <!--begin::Mixed Widget 17-->
            <div class="card card-flush d-flex flex-column-auto p-0 m-1">
                <!--begin::Body-->
                <div class="card-body p-0 ribbon ribbon-top" data-bs-toggle="modal" data-bs-target="#kt_modal_3" onclick="populateModal(${item.id})">
                    <div class="ribbon-label ${item.category.ribbon_color ? item.category.ribbon_color : 'bg-primary'}">${item.category.name}</div>
                    <!--begin::Image-->
                    <div class="d-flex flex-center w-100 py-2">
                        <div class="symbol symbol-150px symbol-circle me-2 my-2">
                            <img src="{{ asset('${item.item_image}') }}" class="align-self-center" alt="">
                        </div>
                    </div>
                    <!--end::Image-->
                    <!--begin::Name and Vendor-->
                    <div class="text-center w-100 position-relative z-index-1 py-3">
                        <a href="#" class="fs-4 text-gray-800 text-hover-primary fw-bold">${item.item_name}${itemTypeHtml}<br></a>
                        <span class="text-gray-500 fw-semibold fs-7">By: <a href="#" class="text-primary fw-bold">${item.vendor.brand_name}</a></span>
                    </div>
                    <!--end::Name and Vendor-->
                </div>
                <!--end::Body-->
                <!--begin::Footer-->
                <div class="card-footer d-flex flex-center py-2 px-4">
                    <!--begin::Rating-->
                    <div class="d-flex flex-wrap flex-grow-1" data-bs-toggle="modal" data-bs-target="#kt_modal_3" onclick="populateModal(${item.id})">
                        <div class="me-5">
                            <span class="badge badge-outline badge-warning">Stall No. ${item.vendor.stall_no}</span>
                        </div>
                        <div class="m-1">
                            <i class="fa fa-star-half-alt me-1 text-warning fs-5"></i>
                            <span class="text-gray-800 fw-bold">${item.item_rating?.rating ?? 0.0}</span>
                        </div>
                    </div>
                    <!--end::Rating-->
                    <!--begin::Add Now-->
                    <div class="d-flex align-items-center flex-shrink-0">
                        <form class="itemForm" action="/cart/add/${item.id}" method="post" data-item-id="${item.id}">
                            @csrf
                            <a href="#" onclick="bookProduct(${JSON.stringify(item).replace(/"/g, '&quot;')}, 'foodItem')" class="btn btn-danger hover-scale w-100 px-4 py-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Add Item"><i class="fa-solid fa-cart-plus"></i>₹${Math.round(item.price)}</a>
                        </form>
                    </div>
                    <!--end::Add Now-->
                </div>
                <!--end::Footer-->
            </div>
            <!--end::Mixed Widget 17-->
        </div>
    `;
    }

    function generateItemMobileHtml(item) {
      let itemTypeHtml = '';
      if (item.item_type === 'nonVegetarian') {
        itemTypeHtml = `
            <img src="{{ asset('custom/assets/images/item-types/nonveg.png') }}" alt="" class="h-15px ms-1" 
                data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" 
                aria-label="Non Vegetarian" data-bs-original-title="Non Vegetarian">
        `;
      } else if (item.item_type === 'eggetarian') {
        itemTypeHtml = `
            <img src="{{ asset('custom/assets/images/item-types/egg.png') }}" alt="" class="h-15px ms-1" 
                data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" 
                aria-label="Eggetarian" data-bs-original-title="Eggetarian">
        `;
      } else if (item.item_type === 'vegetarian') {
        itemTypeHtml = `
            <img src="{{ asset('custom/assets/images/item-types/veg.png') }}" alt="" class="h-15px ms-1" 
                data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" 
                aria-label="Vegetarian" data-bs-original-title="Vegetarian">
        `;
      }
      return `
      <div class="separator separator-dashed border-primary my-6"></div>
        <div class="d-flex my-3">
          <div class="d-flex align-items-center flex-wrap flex-grow-1 ms-3 mt-n2 mt-lg-n1" data-bs-toggle="modal" data-bs-target="#kt_modal_3" onclick="populateModal(${item.id})">
            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-1 pe-3">
              <a href="#" class="fs-5 text-gray-800 text-hover-primary fw-bold">${item.item_name}${itemTypeHtml}</a>
              <span class="text-gray-500 fw-semibold fs-7">By:
                <a href="#" class="text-primary fw-bold">${item.vendor.brand_name}</a></span>
              <div class="my-1">
                <span class="badge badge-outline badge-warning my-2 ms-1">Stall No.
                  ${item.vendor.stall_no}</span>
                <i class="fa fa-star-half-alt text-warning fs-5"></i>
                <span class="text-gray-800 fw-bold">${item.item_rating?.rating ?? 0.0}</span>
              </div>
            </div>
          </div>
          <div class="symbol symbol-75px symbol-2by3 flex-shrink-0">
            <img src="{{ asset('${item.item_image}') }}" alt="" data-bs-toggle="modal" data-bs-target="#kt_modal_3" onclick="populateModal(${item.id})"/>
            <span class="position-absolute top-0 start-75 translate-middle badge ${item.category.ribbon_color ? item.category.ribbon_color : 'bg-primary'}">
              <span class="text-gray-900 fw-bold">${item.category.name}</span>
            </span>
            <div class="row">
              <div class="col">
                <form class="itemForm" action="#" method="post"
                  data-item-id="${item.id}">
                  <a href="#" onclick="bookProduct(${JSON.stringify(item).replace(/"/g, '&quot;')}, 'foodItem')" data-item-id="${item.id}"
                    class="btn btn-danger w-100 rounded-top-0 py-2 mt-n4 hover-elevate-up"
                    data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                    title="Add Item"><i class="fa-solid fa-cart-plus"></i>₹${Math.round(item.price)}</a>
                </form>
              </div>
            </div>
          </div>
        </div>
    `;
    }

    function generateEventHtml(event) {
      var img = `{{ asset('${event.image}') }}`
      return `
      <div class="col-12 col-xl-6 rounded-2 d-flex flex-column flex-row-fluid">
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
                  style="width:100%" onclick="bookProduct(${JSON.stringify(event).replace(/"/g, '&quot;')}, 'event')">
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

    function generatePlayAreaHtml(playArea) {
      var img = `{{ asset('${playArea.image}') }}`
      return `
      <div class="col-6 rounded-2 d-flex flex-column flex-row-fluid m-2 flex-grow-1">
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
                  class="text-gray-800  fw-bolder fs-5 border border-warning border-dashed rounded py-2 px-2 me-2">₹${Math.round(playArea.price)}/Game</span>
              </div>
            </div>
            <!--ed::Rating-->
            <!--begin::Add Now-->
            <div
              class="col-12 col-sm-8 d-flex align-items-center flex-shrink-0 justify-content-center justify-content-sm-end mt-5 mt-sm-0">
              <a href="#" class="btn btn-sm btn-danger hover-scale w-100 w-md-250px px-2 py-2"
                  data-bs-toggle="tooltip"
                  data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                  data-bs-original-title="Book Now" data-kt-initialized="1"
                  onclick="bookProduct(${JSON.stringify(playArea).replace(/"/g, '&quot;')}, 'playArea')">
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

    function attachDialerListeners() {
      const dialerGroups = document.querySelectorAll('[data-kt-dialer="true"]');

      dialerGroups.forEach(group => {
        const min = parseInt(group.getAttribute('data-kt-dialer-min'), 10);
        const max = parseInt(group.getAttribute('data-kt-dialer-max'), 10);
        const step = 1;

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

    $(document).ready(function() {
      function fetchItems() {
        var searchQuery = $('#searchInput').val();
        var selectedCategory = $('#categorySelect').val();

        $.ajax({
          url: '/search-products',
          method: 'GET',
          data: {
            search: searchQuery,
            category: selectedCategory
          },
          success: function(response) {
            console.log(response);

            function updateCardBody(filteredItems) {
              var cardBody = $('#desktop-view');
              var mobileCardBody = $('#mobile-view');
              cardBody.empty();
              mobileCardBody.empty();
              filteredItems.forEach(function(item) {
                var itemHtml = generateItemHtml(item);
                var itemMobileHtml = generateItemMobileHtml(item);
                cardBody.append(itemHtml);
                mobileCardBody.append(itemMobileHtml);
              });
            }
            updateCardBody(response.items);

            function updateEventDiv(filteredItems) {
              var cardBody = $('#events-div');
              cardBody.empty();
              filteredItems.forEach(function(item) {
                var itemHtml = generateEventHtml(item);
                cardBody.append(itemHtml);
              });
            }
            updateEventDiv(response.events);

            function updatePlayAreaBody(filteredItems) {
              var cardBody = $('#play-area-div');
              cardBody.empty();
              filteredItems.forEach(function(item) {
                var itemHtml = generatePlayAreaHtml(item);
                cardBody.append(itemHtml);
              });
            }
            updatePlayAreaBody(response.playAreas);
            attachDialerListeners();
          },
          error: function(xhr, status, error) {
            console.error(xhr);
          }
        });
      }

      $('#searchInput').on('input', fetchItems);
      $('#categorySelect').on('change', fetchItems);
    });
  </script>
@endsection
