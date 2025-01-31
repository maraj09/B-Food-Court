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
              <div class="mt-2">
                <h3 class="card-title align-items-start flex-column fs-7">Search Item</h3>
                <input type="text" class="form-control form-control-solid w-125px w-sm-150px w-lg-250px mt-n1"
                  id="searchInput" name="search" placeholder="Search Product" autocomplete="on">
              </div>

              <div class="card-toolbar">
                <!--begin::Search-->
                <div class="">
                  <h3 class="card-title align-items-start flex-column fs-7">Vendors</h3>
                  <select class="form-select mt-n1 w-100px w-md-150px w-lg-250px" data-control="select2" id="vendorSelect"
                    data-placeholder="Select an option">
                    <option value="all">All Vendors</option>
                    @foreach ($vendors as $vendor)
                      <option value="{{ $vendor->id }}">{{ $vendor->brand_name }}</option>
                    @endforeach
                  </select>
                </div>
                <!--en:d:Search-->
                <div class="ms-1 ms-md-5">
                  <h3 class="card-title align-items-start flex-column fs-7">Category</h3>
                  <select class="form-select mt-n1 w-100px w-md-150px w-lg-250px" data-control="select2"
                    id="categorySelect" data-placeholder="Select an option">
                    <option value="all">All Category</option>
                    @foreach ($categories as $categorie)
                      <option value="{{ $categorie->id }}">{{ $categorie->name }}</option>
                    @endforeach
                  </select>
                </div>
              </div>
            </div>
            <!--begin::Body-->
            <div class="card-body pt-2 card-scroll px-4" style="height: 65vh">
              <!--begin::Mobile View-->
              <div class="d-md-none" id="mobile-view">
                <!--begin::Item-->
                @foreach ($items as $item)
                  <div class="separator separator-dashed border-primary my-6"></div>
                  <div class="d-flex my-3">
                    <!--begin::Section-->
                    <div class="d-flex align-items-center flex-wrap flex-grow-1 ms-3 mt-n2 mt-lg-n1"
                      data-bs-toggle="modal" data-bs-target="#kt_modal_3" onclick="populateModal({{ $item->id }})">
                      <!--begin::Title-->
                      <div class="d-flex flex-column flex-grow-1 my-lg-0 my-1 pe-3">
                        <a href="#" class="fs-5 text-gray-800 text-hover-primary fw-bold">{{ $item->item_name }}
                          @if ($item->item_type == 'nonVegetarian')
                            <img src="{{ asset('custom/assets/images/item-types/nonveg.png') }}" alt=""
                              class="h-15px ms-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                              data-bs-placement="top" aria-label="Non Vegetarian" data-bs-original-title="Non Vegetarian">
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
                            <a href="javascript:void(0)" onclick="bookProduct(this)" data-item-id="{{ $item->id }}"
                              class="btn btn-danger w-100 rounded-top-0 py-2 mt-n4 hover-elevate-up"
                              data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                              title="Add Item"><i class="fa-solid fa-cart-plus"></i>₹{{ round($item->price) }}</a>
                          </form>
                        </div>
                      </div>
                    </div>
                    <!--end::Symbol-->
                  </div>
                @endforeach

                <!--end::Item-->
                <div class="separator separator-dashed border-primary my-6"></div>
              </div>
              <!--end::Mobile View-->
              <!--begin::Desktop View-->
              <div class="d-none d-md-block">
                <!--begin::Row-->
                <div id="desktop-view" style="height: 65vh" class="row row-cols-3 row-cols-md-4 row-cols-lg-5 g-0">
                  <!--begin::Col-->
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
                                <img src="{{ asset($item->item_image) }}" class="align-self-center" alt="">
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
                                <img src="{{ asset('custom/assets/images/item-types/nonveg.png') }}" alt=""
                                  class="h-20px ms-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                                  data-bs-placement="top" aria-label="Non Vegetarian"
                                  data-bs-original-title="Non Vegetarian">
                              @elseif ($item->item_type == 'eggetarian')
                                <img src="{{ asset('custom/assets/images/item-types/egg.png') }}" alt=""
                                  class="h-20px ms-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                                  data-bs-placement="top" aria-label="Eggetarian" data-bs-original-title="Eggetarian">
                              @elseif ($item->item_type == 'vegetarian')
                                <img src="{{ asset('custom/assets/images/item-types/veg.png') }}" alt=""
                                  class="h-20px ms-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                                  data-bs-placement="top" aria-label="Vegetarian" data-bs-original-title="Vegetarian">
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
                          <div class="d-flex flex-wrap flex-grow-1" data-bs-toggle="modal" data-bs-target="#kt_modal_3"
                            onclick="populateModal({{ $item->id }})">
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
                            <a href="javascript:void(0)" onclick="bookProduct(this)"
                              data-item-id="{{ $item->id }}" class="btn btn-danger hover-scale w-100 px-4 py-2"
                              data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                              title="Add Item"><i class="fa-solid fa-cart-plus"></i>₹{{ round($item->price) }}</a>
                          </div>
                          <!--ed::Add Now-->
                        </div>
                        <!--end::Footer-->
                      </div>
                      <!--end::Mixed Widget 17-->
                    </div>
                  @endforeach
                  <!--end::Col-->
                </div>
                <!--end::Row-->
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
@section('footer')
  @include('layouts.customer.components.bottom-navigation')
  @php
    $settings = \App\Models\Setting::first();
  @endphp
@endsection

@section('modules')
  @include('pages.items.customer.modules.drawers.cart')
  @include('pages.items.customer.modules.toasts.status')
  @include('pages.items.customer.modules.models.reviewModal')
@endsection
@section('scripts')
  @include('bladeAssets.customer.cart-logics')
  <script>
    @if (auth()->user()->cartItems->count() > 0)
      document.addEventListener('DOMContentLoaded', function() {
        // Trigger the drawer to open on page load
        const cartDrawer = document.getElementById('kt_shopping_cart');
        if (cartDrawer) {
          // Check if the cart drawer element exists
          const drawer = KTDrawer.getInstance(cartDrawer);

          drawer.show(); // Open the drawer
        }
      });
    @endif
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
                            <a href="javascript:void(0)" onclick="submitFormWithAjax(this)" class="btn btn-danger hover-scale w-100 px-4 py-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Add Item"><i class="fa-solid fa-cart-plus"></i>₹${Math.round(item.price)}</a>
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
                <form class="itemForm" action="/cart/add/${item.id}" method="post"
                  data-item-id="${item.id}">
                  @csrf
                  <a href="javascript:void(0)" onclick="bookProduct(this)" data-item-id="${item.id}"
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

    $(document).ready(function() {
      function fetchItems() {
        var searchQuery = $('#searchInput').val();
        var selectedVendor = $('#vendorSelect').val();
        var selectedCategory = $('#categorySelect').val();

        $.ajax({
          url: '/search-items',
          method: 'GET',
          data: {
            search: searchQuery,
            vendor: selectedVendor,
            category: selectedCategory
          },
          success: function(response) {
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
            updateCardBody(response);
          },
          error: function(xhr, status, error) {
            console.error(xhr);
          }
        });
      }

      $('#searchInput').on('input', fetchItems);
      $('#vendorSelect').on('change', fetchItems);
      $('#categorySelect').on('change', fetchItems);
    });
  </script>
@endsection
