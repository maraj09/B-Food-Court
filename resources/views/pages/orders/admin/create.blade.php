@extends('layouts.admin.app')
@section('contents')
  @include('pages.orders.admin.toolbar.createToolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid mt-20 mt-lg-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul class="mb-0">
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      <!--begin::Form-->
      <form action="/dashboard/orders/store" method="POST" id="kt_ecommerce_edit_order_form"
        class="form d-flex flex-column flex-lg-row">
        @csrf
        <input type="hidden" name="selected_items" id="selected_items">
        <!--begin::Aside column-->
        <div class="w-100 flex-lg-row-auto w-lg-300px mb-7 me-7 me-lg-10">
          <!--begin::Order details-->
          <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
              <div class="card-title">
                <h2>Order Details</h2>
              </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
              <div class="d-flex flex-column gap-10">
                <!--begin::Input group-->
                <div class="fv-row">
                  <!--begin::Label-->
                  <label class="required form-label">Customer</label>
                  <!--end::Label-->
                  <!--begin::Select2-->
                  <select class="form-select mb-2 select_2" data-control="select2" data-hide-search="true"
                    data-placeholder="Select an option" name="user_id">
                    @foreach ($customers as $customer)
                      <option value="{{ $customer->user->id }}">{{ $customer->user->name }}</option>
                    @endforeach
                  </select>
                  <!--end::Select2-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="fv-row">
                  <!--begin::Label-->
                  <label class="form-label">Payment Method</label>
                  <!--end::Label-->
                  <!--begin::Select2-->
                  @php
                    $settings = \App\Models\Setting::first();
                  @endphp
                  <select class="form-select mb-2" data-control="select2" data-hide-search="true"
                    data-placeholder="Select an option" name="payment_method" id="kt_ecommerce_edit_order_payment">
                    @if ($settings->payment_mode_upi_status)
                      <option value="upi">Upi</option>
                    @endif
                    @if ($settings->payment_mode_cash_status)
                      <option value="cash">Cash</option>
                    @endif
                    @if ($settings->payment_mode_card_status)
                      <option value="card">Card</option>
                    @endif
                  </select>
                  <!--end::Select2-->
                </div>
                <!--end::Input group-->
                <!--begin::Input group-->
                <div class="fv-row">
                  <!--begin::Label-->
                  <label class="required form-label">Status</label>
                  <!--end::Label-->
                  <!--begin::Select2-->
                  <select class="form-select mb-2 select_2" data-control="select2" data-hide-search="true"
                    data-placeholder="Select an option" name="status">
                    <option value="paid">Paid</option>
                    <option value="completed">Completed</option>
                    <option value="delivered">Delivered</option>
                    <option value="rejected">Rejected</option>
                  </select>
                  <!--end::Select2-->
                </div>
                <!--end::Input group-->
              </div>
            </div>
            <!--end::Card header-->
          </div>
          <!--end::Order details-->
        </div>
        <!--end::Aside column-->
        <!--begin::Main column-->
        <div class="d-flex flex-column flex-lg-row-fluid gap-7 gap-lg-10">
          <!--begin::Order details-->
          <div class="card card-flush py-4 ">
            <!--begin::Card header-->
            <div class="card-header">
              <div class="card-title">
                <h2>Select Products</h2>
              </div>
            </div>
            <!--end::Card header-->
            <!--begin::Card body-->
            <div class="card-body pt-0">
              <div class="d-flex flex-column gap-10">
                <!--begin::Input group-->
                <div>
                  <!--begin::Label-->
                  <label class="form-label">Add products to this order</label>
                  <!--end::Label-->
                  <!--begin::Selected products-->
                  <div
                    class="row row-cols-1 row-cols-xl-3 row-cols-md-2 border border-dashed rounded pt-3 pb-1 px-2 mb-5 mh-300px overflow-scroll"
                    id="kt_ecommerce_edit_order_selected_products">
                    <!--begin::Empty message-->
                    <span id="muted-text" class="w-100 text-muted">Select one or more products from the list below by
                      ticking the
                      checkbox.</span>
                    <!--end::Empty message-->

                  </div>
                  <!--begin::Selected products-->
                  <!--begin::Total price-->
                  <div class="fw-bold fs-4">Total Cost: ₹
                    <span id="kt_ecommerce_edit_order_total_price">0.00</span>
                  </div>
                  <!--end::Total price-->
                </div>
                <!--end::Input group-->
                <!--begin::Separator-->
                <div class="separator"></div>
                <!--end::Separator-->
                <!--begin::Search products-->
                <div class="d-flex align-items-center position-relative mb-n7">
                  <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                    <span class="path1"></span>
                    <span class="path2"></span>
                  </i>
                  <input type="text" data-kt-ecommerce-edit-order-filter="search" id="searchInput"
                    class="form-control form-control-solid w-100 w-lg-50 ps-12" placeholder="Search Items" />
                </div>
                <!--end::Search products-->
                <!--begin::Table-->
                <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_edit_order_product_table">
                  <thead>
                    <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                      <th class="w-25px pe-2"></th>
                      <th class="min-w-200px">Food Items</th>
                    </tr>
                  </thead>
                  <tbody class="fw-semibold text-gray-600">
                    @foreach ($items as $item)
                      <tr>
                        <td>
                          <div class="form-check form-check-sm form-check-custom form-check-solid">
                            <input class="form-check-input" type="checkbox" value="{{ $item->id }}"
                              onclick="toggleSelectedProduct(this, '{{ asset($item->item_image) }}')" />
                          </div>
                        </td>
                        <td>
                          <div class="d-flex align-items-center">
                            <!--begin::Thumbnail-->
                            <a href="/dashboard/items/{{ $item->id }}" class="symbol symbol-50px">
                              <span class="symbol-label"
                                style="background-image:url({{ asset($item->item_image) }});"></span>
                            </a>
                            <!--end::Thumbnail-->
                            <div class="ms-5">
                              <!--begin::Title-->
                              <a href="/dashboard/items/{{ $item->id }}"
                                class="text-gray-800 text-hover-primary fs-5 fw-bold">{{ $item->item_name }}</a>
                              <!--end::Title-->
                              <!--begin::Price-->
                              <div class="fw-semibold fs-7">Price: ₹
                                <span data-kt-ecommerce-edit-order-filter="price">{{ $item->price }}</span>
                              </div>
                              <!--end::Price-->
                              <!--begin::SKU-->
                              <div class="text-muted fs-7">{{ $item->vendor->brand_name }}</div>
                              <!--end::SKU-->
                            </div>
                          </div>
                        </td>
                      </tr>
                    @endforeach
                  </tbody>
                </table>
                <!--end::Table-->
              </div>
            </div>
            <!--end::Card header-->
          </div>
          <!--end::Order details-->


          <!--end::Order details-->
          <div class="d-flex justify-content-end">
            <!--begin::Button-->
            <a href="apps/ecommerce/catalog/products.html" id="kt_ecommerce_edit_order_cancel"
              class="btn btn-light me-5">Cancel</a>
            <!--end::Button-->
            <!--begin::Button-->
            <button type="button" id="kt_ecommerce_edit_order_submit" class="btn btn-primary" onclick="placeOrder()">
              <span class="indicator-label">Place Order</span>
              <span class="indicator-progress d-none">Please wait... <span
                  class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
            </button>
            <!--end::Button-->
          </div>
        </div>
        <!--end::Main column-->
      </form>
      <!--end::Form-->
    </div>
    <!--end::Content container-->
  </div>
@endsection
@section('scripts')
  <script src="{{ asset('assets/js/custom/apps/ecommerce/sales/save-order.js') }}"></script>
  <script>
    function toggleSelectedProduct(checkbox, itemImg) {
      var itemID = checkbox.value;
      var selectedItem = checkbox.closest('tr');

      if (checkbox.checked) {
        // Get item details
        var itemName = selectedItem.querySelector('.fw-bold').textContent;
        var itemPrice = selectedItem.querySelector('[data-kt-ecommerce-edit-order-filter="price"]').textContent;
        var itemSKU = selectedItem.querySelector('.text-muted').textContent;

        // Add item to selected products with quantity 1
        updateSelectedProduct(itemID, itemName, itemPrice, itemSKU, itemImg, 1);
        document.querySelector('#muted-text').classList.add('d-none');
      } else {
        // Remove item from selected products
        var selectedProduct = document.querySelector(`[data-kt-ecommerce-edit-order-id="product_${itemID}"]`);
        selectedProduct.remove();

        // Remove item price from selectedItemsPrices
        delete selectedItems[itemID];

        // Update the total cost
        updateTotalCost();

      }
    }

    var selectedItems = {}; // Object to store the selected items with quantity

    function updateSelectedProduct(itemID, itemName, itemPrice, itemSKU, itemImg, quantity) {
      var selectedProductContainer = document.getElementById('kt_ecommerce_edit_order_selected_products');
      var selectedProductCard = document.createElement('div');
      selectedProductCard.classList.add('col', 'my-2');
      selectedProductCard.setAttribute('data-kt-ecommerce-edit-order-filter', 'product');
      selectedProductCard.setAttribute('data-kt-ecommerce-edit-order-id', 'product_' + itemID);
      selectedProductCard.innerHTML = `
        <div class="d-flex align-items-center border border-dashed p-3 rounded bg-light-dark">
            <!--begin::Thumbnail-->
            <a href="/dashboard/items/${itemID}" class="symbol symbol-50px">
                <span class="symbol-label" style="background-image:url(${itemImg});"></span>
            </a>
            <!--end::Thumbnail-->
            <div class="ms-5 me-4">
                <!--begin::Title-->
                <a href="/dashboard/items/${itemID}" class="text-gray-800 text-hover-primary fs-5 fw-bold">${itemName}</a>
                <!--end::Title-->
                <!--begin::Price-->
                <div class="fw-semibold fs-7">Price: ₹<span data-kt-ecommerce-edit-order-filter="price">${itemPrice}</span></div>
                <!--end::Price-->
                <!--begin::SKU-->
                <div class="text-muted fs-7">${itemSKU}</div>
                <!--end::SKU-->
                <!--begin::Quantity-->
                
                <!--end::Quantity-->
            </div>
            <div class="fs-7 text-center">
              <div>
                    <button type="button" class="btn btn-sm btn-light-success" onclick="updateQuantity('${itemID}', 'increase')">+</button>
                </div>
                <div class="my-2">
                    <span id="quantity_${itemID}">${quantity}</span>
                </div>
                <div>
                    <button type="button" class="btn btn-sm btn-light-danger" onclick="updateQuantity('${itemID}', 'decrease')">-</button>
                </div>
              </div>
        </div>
    `;
      selectedProductContainer.appendChild(selectedProductCard);

      // Store the selected item with its quantity
      selectedItems[itemID] = {
        price: parseFloat(itemPrice),
        quantity: quantity
      };

      // Update the total cost
      updateTotalCost();
    }

    function updateQuantity(itemID, action) {
      var quantityElement = document.getElementById(`quantity_${itemID}`);
      var currentQuantity = parseInt(quantityElement.textContent);
      var newQuantity = currentQuantity;

      if (action === 'increase') {
        newQuantity++;
      } else if (action === 'decrease' && newQuantity > 1) {
        newQuantity--;
      }

      quantityElement.textContent = newQuantity;

      // Update the quantity in the selectedItems object
      selectedItems[itemID].quantity = newQuantity;

      // Update the total cost
      updateTotalCost();
    }

    function updateTotalCost() {
      var total = 0;
      for (var itemID in selectedItems) {
        if (selectedItems.hasOwnProperty(itemID)) {
          total += selectedItems[itemID].price * selectedItems[itemID].quantity;
        }
      }
      document.getElementById('kt_ecommerce_edit_order_total_price').textContent = total.toFixed(2);
    }
  </script>
  <script>
    function placeOrder() {
      // Create an array to store the selected items' IDs and quantities
      var selectedItemsArray = [];

      // Loop through selectedItems object to collect data
      for (var itemID in selectedItems) {
        if (selectedItems.hasOwnProperty(itemID)) {
          selectedItemsArray.push({
            id: itemID,
            quantity: selectedItems[itemID].quantity
          });
        }
      }

      // Update the hidden input field with the JSON stringified selected items array
      document.getElementById('selected_items').value = JSON.stringify(selectedItemsArray);
      document.getElementById('kt_ecommerce_edit_order_form').submit();
    }
  </script>
  <script>
    // In your Javascript (external .js resource or <script> tag)
    $(document).ready(function() {
      $('.select_2').select2();
    });
  </script>
@endsection
