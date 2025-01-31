<script>
  var gst = `{{ $settings?->gst }}`
  var sgt = `{{ $settings?->sgt }}`
  var serviceTax = `{{ $settings?->service_tax }}`
  var userPoints = `{{ auth()->user()->point->points }}`
  var pointSliderValue = 0;

  function breakupContents(response, couponDiscount = 0, pointDiscount = 0) {
    $(`#billbreakup_item`).html(``);
    $(`#billbreakup_item`).append(`<li class="d-flex align-items-center py-2">
            <span class="bullet bullet-vertical fw-bold me-5"></span>Total : <span
              class="ms-2">₹${parseFloat(response.data.cartData.totalProductPrice)
              .toFixed(2)}</span>
          </li>`);
    if (gst > 0) {
      $('#billbreakup_item').append(` 
        <li class="d-flex align-items-center py-2">
            <span class="bullet bullet-vertical fw-bold bg-danger me-5"></span>GST ${parseFloat(
                gst
            ).toFixed(1)}%: <span class="ms-2">₹${parseFloat(response.chargesWithNetTotal.gstCharge).toFixed(
            2
        )}</span>
        </li>`)
    }
    if (sgt > 0) {
      $('#billbreakup_item').append(` 
        <li class="d-flex align-items-center py-2">
            <span class="bullet bullet-vertical fw-bold bg-danger me-5"></span>SGT ${parseFloat(
                sgt
            ).toFixed(1)}%: <span class="ms-2">₹${parseFloat(response.chargesWithNetTotal.sgtCharge).toFixed(
            2
        )}</span>
        </li>`);
    }
    if (serviceTax > 0) {
      $('#billbreakup_item').append(` 
        <li class="d-flex align-items-center py-2">
            <span class="bullet bullet-vertical fw-bold bg-danger me-5"></span>Service Tax ${parseFloat(
                serviceTax
            ).toFixed(1)}%: <span class="ms-2">₹${parseFloat(
              response.chargesWithNetTotal.serviceTaxCharge
        ).toFixed(2)}</span>
        </li>`);
    }

    if (pointDiscount) {
      var newLiContent =
        `<li id="point_discount" class="d-flex align-items-center py-2"><span class="bullet bullet-vertical fw-bold me-5 bg-success"></span>Points Discount : <span class="ms-2">₹${pointDiscount}</span></li>`;
      $('#billbreakup_item').append(newLiContent);
    }

    if (couponDiscount) {
      var newLiContent =
        `<li id="coupons_discount" class="d-flex align-items-center py-2"><span class="bullet bullet-vertical fw-bold me-5 bg-success"></span>Coupon Discount : <span class="ms-2" id="coupons_discount_span">₹${parseFloat(couponDiscount).toFixed(2)}</span></li>`;
      $('#billbreakup_item').append(newLiContent);
    }
  }

  $(document).on('click', '.couponButton', function(event) {
    event.preventDefault();

    if ($('#coupon_used').length) {
      return false;
    }

    var couponCode = $(this).data('code');
    var discount = $(this).data('discount');
    var discountType = $(this).data('discount-type');
    var formData = {
      code: couponCode,
      points: pointSliderValue,
      _token: document.querySelector('meta[name="csrf-token"]').content,
    };

    $.ajax({
      url: '/apply-coupon',
      type: 'POST',
      data: formData,
      success: function(response) {
        if (response.success) {
          $('#couponFormContainer').html(
            `<span class="btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning ms-0 ms-md-3 px-3 py-1"
          data-bs-toggle="collapse" data-bs-target="#kt_docs_card_applied_coupon">${response.data.cartData.coupon.code} : ${response.data.cartData.coupon.discount_type == 'fixed' ? '₹' + parseFloat(response.data.cartData.coupon.discount).toFixed(2) : response.data.cartData.coupon.discount + '%'}<i
            class="fas fa-info-circle text-primary ms-2 collapsible cursor-pointer" data-bs-toggle="tooltip"
            data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Applied Coupon"></i></span><a href="#" id="removeCouponButton" class="btn btn-danger btn-sm ms-2 p-2">Remove</a><input type="hidden" id="coupon_used" value="${response.data.cartData.coupon.id}" /><input type="hidden" id="coupon_code" value="${response.data.cartData.coupon.code}" />`
          );
          $(`.coupon_span_${response.data.cartData.coupon.id}`).removeClass(
            "btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning"
          );

          $(`.coupon_span_${response.data.cartData.coupon.id}`).addClass(
            "btn-warning"
          );
          $('#coupon_text_button').css('display', 'none');
          $('#total-price').html('Pay- ₹' + parseFloat(response.chargesWithNetTotal.netTotal).toFixed(2) + `<span
          class="badge badge-dark ms-2">${response.data.cartData.totalProductCount} Items</span>`);
          breakupContents(response, response.data.cartData.couponDiscount, response.data.cartData
            .pointDiscount);
        } else {
          Swal.fire({
            text: response.data.cartData.message,
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
      }
    });
  });

  $(document).on("click", "#removeCouponButton", function(event) {
    event.preventDefault();
    $.ajax({
      url: "/remove-coupon",
      type: "POST",
      data: {
        _token: document.querySelector('meta[name="csrf-token"]').content,
        points: pointSliderValue,
      },
      success: function(response) {
        if (response.success) {
          removeCouponElements(response)
        }
      },
      error: function(xhr, status, error) {
        console.error(xhr);
      }
    });
  });

  function bookProduct(button) {
    // Assuming button contains data-play-area-id attribute
    let url = '';
    var itemId = $(button).data('item-id');
    var playAreaId = $(button).data('play-area-id');
    var eventId = $(button).data('event-id');
    var eventBookedSeatCount = Math.round($(`#event_book_seat_${eventId}`).val());
    const cartDrawer = KTDrawer.getInstance(document.getElementById('kt_shopping_cart'));

    if (itemId) {
      url = '/item/add-to-cart'
    } else if (playAreaId) {
      url = '/play-area/add-to-cart'
    } else if (eventId) {
      url = '/event/add-to-cart'
    }
    // Send AJAX request to book play area
    $.ajax({
      url: url, // Define the appropriate route
      type: 'POST',
      data: {
        itemId: itemId,
        playAreaId: playAreaId,
        eventId: eventId,
        eventBookedSeatCount: eventBookedSeatCount,
        code: $('#coupon_code').val(),
        points: pointSliderValue,
      },
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        console.log(response);
        if (response.success) {
          if (itemId) {
            displayCartItems(response.data.cartData.cartItems);
          } else if (playAreaId) {
            displayCartPlayAreas(response.data.cartData.cartPlayAreas);
          } else if (eventId) {
            displayCartEvents(response.data.cartData.cartEvents);
          }
          $('.view-cart-span').html(`${response.data.cartData.totalProductCount} Items`)
          $('#total-price').html('Pay- ₹' + parseFloat(response.chargesWithNetTotal.netTotal).toFixed(2) + `<span
          class="badge badge-dark ms-2">${response.data.cartData.totalProductCount} Items</span>`);
          breakupContents(response, response.data.cartData.couponDiscount, response.data.cartData.pointDiscount);
          showToast('success', 'Product added to cart!');
          if (!response.coupon_success) {
            removeCouponElements(response)
          }
          if (!response.point_success) {
            removePointContents(response)
          }
        } else {
          showToast('error', response.message);
        }
        if (eventId) {
          cartDrawer.show();
        }

      },
      error: function(xhr, status, error) {
        // Handle error
        console.error('Error:', xhr);
        showToast('error', 'An error occurred while booking play area.');
      }
    });
  }

  function confirmClearCart() {
    Swal.fire({
      text: "Are you sure you would like to clear the cart?",
      icon: "warning",
      showCancelButton: true,
      buttonsStyling: false,
      confirmButtonText: "Yes, clear it!",
      cancelButtonText: "No, keep items",
      customClass: {
        confirmButton: "btn btn-danger",
        cancelButton: "btn btn-active-light",
      },
    }).then((result) => {
      if (result.isConfirmed) {
        window.location.href = "/clear-cart";
      }
    });
  }

  $(document).on('click', '.event-increase-quantity, .event-decrease-quantity', function() {
    // Get the event ID and determine if it's an increase or decrease action
    var productCartId = $(this).data('product-cart-id');
    var isEventIncrease = $(this).hasClass('event-increase-quantity');
    var quantityInput = $(this).closest('.input-group').find('[data-kt-dialer-control="input"]');

    // Update the quantity based on the button clicked
    var newQuantity = parseInt(quantityInput.val()) + (isEventIncrease ? 1 : -1);
    if (newQuantity < 1) {
      newQuantity = 1; // Prevent quantity from going below 1
    }
    quantityInput.val(newQuantity);

    // Make an AJAX request to update the session data
    $.ajax({
      type: 'POST',
      url: '/cart/update-event-quantity',
      data: {
        productCartId: productCartId,
        quantity: newQuantity,
        code: $('#coupon_code').val(),
        points: pointSliderValue,
        _token: document.querySelector('meta[name="csrf-token"]').content, // Ensure CSRF token is included
      },
      success: function(response) {
        if (response.success) {
          $(`.cart-per-product-total-${productCartId}`).text('₹' + Math.round(response.productTotal));
          breakupContents(response, response.data.cartData.couponDiscount, response.data.cartData
            .pointDiscount);
          $('#total-price').html('Pay- ₹' + parseFloat(response.chargesWithNetTotal.netTotal).toFixed(2) + `<span
          class="badge badge-dark ms-2">${response.data.cartData.totalProductCount} Items</span>`);
          $(`.cart-per-play-area-player-${productCartId}`).text(newQuantity + 'P');
          if (!response.coupon_success) {
            removeCouponElements(response)
          }
          if (!response.point_success) {
            removePointContents(response)
          }
        } else {
          quantityInput.val(parseInt(quantityInput.val()) - (isEventIncrease ? 1 : -1));
          $(`.cart-per-play-area-player-${productCartId}`).text(newQuantity + 'P');
          showToast('error', response.message);
        }

      },
      error: function(error) {
        // If the AJAX request failed, revert the input to its previous value
        quantityInput.val(parseInt(quantityInput.val()) - (isEventIncrease ? 1 : -1));
        console.error('Error updating quantity:', error);
      }
    });
  });

  $(document).on('click', '.delete-cart-item', function(e) {
    e.preventDefault();

    var productCartId = $(this).data('product-cart-id');

    $.ajax({
      url: '/cart/remove-item',
      type: 'POST',
      data: {
        productCartId: productCartId,
        code: $('#coupon_code').val(),
        points: pointSliderValue,
      },
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        if (response.success) {
          displayCartItems(response.data.cartData.cartItems);
          displayCartPlayAreas(response.data.cartData.cartPlayAreas)
          displayCartEvents(response.data.cartData.cartEvents)
          $('.view-cart-span').html(`${response.data.cartData.totalProductCount} Items`)
          $('#total-price').html('Pay- ₹' + parseFloat(response.chargesWithNetTotal.netTotal).toFixed(2) + `<span
          class="badge badge-dark ms-2">${response.data.cartData.totalProductCount} Items</span>`);
          breakupContents(response, response.data.cartData.couponDiscount, response.data.cartData
            .pointDiscount);
          if (!response.coupon_success) {
            removeCouponElements(response)
          }
          if (!response.point_success) {
            removePointContents(response)
          }
        }

      },
      error: function(error) {
        var errors = error.responseJSON.errors;
        console.log(errors);
        var errorMessage = Object.values(errors)
          .flat()
          .join("<br>");

        Swal.fire({
          html: errorMessage,
          icon: "error",
          buttonsStyling: !1,
          confirmButtonText: "Ok, got it!",
          customClass: {
            confirmButton: "btn btn-primary",
          },
        });
      }
    });
  });

  function displayCartItems(cartItems) {
    var cartItemsContainer = $('#cartItemsContainer');
    cartItemsContainer.empty(); // Clear previous items

    // Iterate over cart items and generate HTML for each item
    cartItems.forEach(function(cartItem) {
      var itemHtml = `
          <div class="d-block align-items-center border-dashed border-gray-900 bg-light rounded p-3 mb-7">
              <div class="d-flex align-items-sm-center mb-2">
                  <!--begin::Symbol-->
                  <div class="symbol symbol-50px symbol-circle me-2">
                      <img src="${cartItem.item.item_image}" class="align-self-center" alt="">
                  </div>
                  <!--end::Symbol-->
                  <!--begin::Section-->
                  <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                      <div class="flex-grow-1 me-5">
                          <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">${cartItem.item.item_name}</a>
                          <div class="d-flex flex-wrap flex-grow-1">
                              <div class="me-2">
                                  <span class="text-success fw-bold">Price</span>
                                  <span class="fw-bold text-gray-800 d-block fs-6">₹${Math.round(cartItem.item.price)}</span>
                              </div>
                              <div class="me-5s">
                                  <span class="text-danger fw-bold">Total</span>
                                  <span class="fw-bold text-gray-800 d-block fs-6 cart-per-product-total-${cartItem.id}">₹${Math.round(cartItem.item.price * cartItem.quantity)}</span>
                              </div>
                          </div>
                      </div>
                      <span>
                          <a href="#" class="symbol symbol-35px delete-cart-item" data-product-cart-id="${cartItem.id}">
                              <div class="symbol-label bg-light-danger">
                                  <i class="fas fa-trash-alt text-danger"></i>
                              </div>
                          </a>
                      </span>
                  </div>
                  <!--end::Section-->
              </div>
              <div class="d-flex flex-stack flex-wrap flex-grow-1">
                  <div class="fw-bold fs-3 text-info">
                      <div class="input-group w-md-300px w-125px" data-kt-dialer="true" data-kt-dialer-min="1" data-kt-dialer-max="5" data-kt-dialer-step="1">
                          <button class="btn btn-icon btn-outline btn-active-color-primary event-decrease-quantity" type="button" data-product-cart-id="${cartItem.id}">
                              <i class="bi bi-dash fs-1"></i>
                          </button>
                          <input type="text" class="form-control" readonly placeholder="Amount" value="${cartItem.quantity}" data-kt-dialer-control="input" />
                          <button class="btn btn-icon btn-outline btn-active-color-primary event-increase-quantity" type="button" data-product-cart-id="${cartItem.id}">
                              <i class="bi bi-plus fs-1"></i>
                          </button>
                      </div>
                  </div>
                  <div class="me-2 text-end">
                      <span class="text-info fw-bold">Vendor</span>
                      <span class="fw-bold text-gray-800 d-block fs-6">${cartItem.item.vendor.brand_name}<span class="badge badge-primary badge-sm ms-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Stall No.">${cartItem.item.vendor.stall_no}</span></span>
                  </div>
              </div>
          </div>
      `;

      // Append generated HTML to the container
      cartItemsContainer.append(itemHtml);
    });
  }

  function displayCartPlayAreas(cartItems) {
    var cartItemsContainer = $('#cartFoodAreasContainer');
    cartItemsContainer.empty(); // Clear previous items

    // Iterate over cart items and generate HTML for each item
    cartItems.forEach(function(playArea) {
      var playAreaId = playArea.play_area.id;
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

        var pricePerHour = playArea.play_area.price;

        cartPerPlayAreaTotalPrice = Math.round(pricePerHour * durationInHours * playersCount);
      }

      var itemHtml = `
        <div class="d-block align-items-center border-dashed border-gray-900 bg-light rounded p-3 mb-7" data-play-area-cart-id="${playArea.id}">
            <div class="d-flex align-items-sm-center mb-2">
                <div class="symbol symbol-50px symbol-2by3 me-2">
                    <img src="${playArea.play_area.image}" class="align-self-center" alt="">
                </div>
                <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                    <div class="flex-grow-1 me-1">
                        <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">${playArea.play_area.title}</a>
                        <div class="d-flex flex-wrap flex-grow-1">
                            <div class="me-2 col-12 col-md-3 mb-1 mb-md-0">
                                <span class="text-success fw-bold">Price</span>
                                <span class="fw-bold text-gray-800 d-block fs-6">₹${Math.round(playArea.play_area.price)}/Hour/Player</span>
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
                        <div class="symbol symbol-35px cursor-pointer delete-cart-item" data-product-cart-id="${playArea.id}">
                            <div class="symbol-label bg-light-danger">
                                <i class="fas fa-trash-alt text-danger"></i>
                            </div>
                        </div>
                    </span>
                </div>
            </div>
            <div class="d-flex flex-stack flex-wrap flex-grow-1 py-2 align-items-start">
                <div class="fs-3 text-info form-group w-125px w-md-200px">
                    <div class="position-relative d-flex align-items-center DatePickerContainer">
                        <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span></i>
                        <input class="form-control form-control-sm kt_datepicker_dob_custom ps-12 datePicker" placeholder="Pick date" id="${datePickerId}" value="${playArea.play_area_date || ''}" />
                        </div>
                        <div class="invalid-feedback"></div>
                </div>
                <div class="w-60px w-md-175px form-group">
                    <div class="StartPickerContainer">
                    <input class="form-control form-control-sm flatpickr-input startTimePicker" placeholder="Start Time" id="${startTimePickerId}" type="text" readonly="readonly" value="${playArea.play_area_start_time || ''}">
                    <div class="invalid-feedback">asdasd</div>
                    </div>
                </div>
                <div class="w-60px w-md-175px form-group">
                  <div class="EndPickerContainer">
                    <input class="form-control form-control-sm flatpickr-input endTimePicker" placeholder="End Time" id="${endTimePickerId}" type="text" readonly="readonly" value="${playArea.play_area_end_time || ''}">
                    <div class="invalid-feedback"></div>
                  </div>
                </div>
            </div>
            <div class="d-flex flex-stack flex-wrap flex-grow-1">
                <div class="me-2 text-Start">
                  <span class="text-gray-900 fw-bold"> Add Players</span>
                </div>
                <div class="fw-bold fs-3 text-info">
                  <div class="input-group w-md-300px w-125px" data-kt-dialer="true" data-kt-dialer-min="1" data-kt-dialer-max="5" data-kt-dialer-step="1">
                    <button class="btn btn-icon btn-outline btn-active-color-primary event-decrease-quantity" data-product-cart-id="${playArea.id}" type="button">
                      <i class="bi bi-dash fs-1"></i>
                    </button>
                    <input type="text" class="form-control" readonly placeholder="Amount" value="${playArea.quantity || 1}" data-kt-dialer-control="input" />
                    <button class="btn btn-icon btn-outline btn-active-color-primary event-increase-quantity" data-product-cart-id="${playArea.id}" type="button">
                      <i class="bi bi-plus fs-1"></i>
                    </button>
                  </div>
                </div>
            </div>
        </div>
    `;

      // Append generated HTML to the container
      cartItemsContainer.append(itemHtml);

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
            var playAreaId = $(instance.element).closest('[data-play-area-cart-id]').data(
              'play-area-cart-id');
            submitDateTime(playAreaId, 'start_time', timeStr);
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
              var playAreaId = $(instance.element).closest('[data-play-area-cart-id]').data(
                'play-area-cart-id');
              submitDateTime(playAreaId, 'end_time', timeStr);
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
          submitDateTime(playAreaId, 'start_time', timeStr);
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
          submitDateTime(playAreaId, 'end_time', timeStr);
        }
      });
    });
  }

  function displayCartEvents(cartItems) {
    var cartItemsContainer = $('#cartEventsContainer');
    cartItemsContainer.empty(); // Clear previous items
    console.log(cartItems);
    // Iterate over cart items and generate HTML for each item
    cartItems.forEach(function(event) {
      var itemHtml = `
        <div class="d-block align-items-center border-dashed border-gray-900 bg-light rounded p-3 mb-7">
          <div class="d-flex align-items-sm-center mb-2">
            <!--begin::Symbol-->
            <div class="symbol symbol-50px symbol-circle me-2">
              <img src="${event.event.image}" class="align-self-center" alt="">
            </div>
            <!--end::Symbol-->
            <!--begin::Section-->
            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
              <div class="flex-grow-1 me-5">
                <a href="#"
                  class="text-gray-800 text-hover-primary fs-6 fw-bold">${event.event.title}</a>
                <div class="d-flex flex-wrap flex-grow-1">
                  <div class="me-2">
                    <span class="text-success fw-bold">Price</span>
                    <span class="fw-bold text-gray-800 d-block fs-6">₹${Math.round(event.event.price)}</span>
                  </div>
                  <div class="me-5s">
                    <span class="text-danger fw-bold">Total</span>
                    <span
                      class="fw-bold text-gray-800 d-block fs-6 cart-per-product-total-${event.id}">₹${Math.round(event.event.price * event.quantity)}</span>
                  </div>
                </div>
              </div>
              <span>
                <div class="symbol symbol-35px cursor-pointer delete-cart-item"
                  data-product-cart-id="${event.id}">
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
              <div class="input-group w-md-300px w-125px" data-kt-dialer="true" data-kt-dialer-min="1"
                data-kt-dialer-max="10" data-kt-dialer-step="1">
                <!--begin::Decrease control-->
                <button class="btn btn-icon btn-outline btn-active-color-primary event-decrease-quantity" type="button" data-product-cart-id="${event.id}">
                  <i class="bi bi-dash fs-1"></i>
                </button>
                <!--end::Decrease control-->
                <!--begin::Input control-->
                <input type="text" class="form-control" readonly placeholder="Amount"
                  value="${event.quantity}" data-kt-dialer-control="input" />
                <!--end::Input control-->
                <!--begin::Increase control-->
                <button class="btn btn-icon btn-outline btn-active-color-primary event-increase-quantity" type="button" data-product-cart-id="${event.id}">
                  <i class="bi bi-plus fs-1"></i>
                </button>
                <!--end::Increase control-->
              </div>
              <!--end::Dialer-->
            </div>
            <div class="me-2 text-end">
              <span
                class="text-info fw-bolder">${durationCalculation(event.event.start_date, event.event.end_date)}</span>
              <span
                class="fw-bold text-gray-800 d-block fs-6">${moment(event.start_date).format('DD-MM-YYYY')} | ${moment(event.start_date).format('h:mm A')}</span>
            </div>
          </div>
          <div class="d-flex align-items-sm-center my-3">
            <!--begin::Section-->
            <div class="d-flex align-items-center flex-row-fluid flex-wrap">
              ${event.event.details}
            </div>
            <!--end::Section-->
          </div>
        </div>
      `;

      // Append generated HTML to the container
      cartItemsContainer.append(itemHtml);
    });
  }

  function removeCouponElements(response) {
    var coupon_id = $('#coupon_used').val();
    $('#couponFormContainer').html("");
    $(`.coupon_span_${coupon_id}`).addClass(
      "btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning"
    );
    $(`.coupon_span_${coupon_id}`).removeClass("btn-warning");
    $('#coupon_text_button').css('display', 'block')
    $('#coupons_discount').remove();
    $('#total-price').html('Pay- ₹' + parseFloat(response.chargesWithNetTotal.netTotal).toFixed(2) + `<span
          class="badge badge-dark ms-2">${response.data.cartData.totalProductCount} Items</span>`);
    breakupContents(response, 0, response.data.cartData.pointDiscount);
    Swal.fire({
      title: "Coupon has been removed!",
      text: response.data.cartData.message,
      icon: "warning",
      buttonsStyling: false,
      confirmButtonText: "Ok, got it!",
      customClass: {
        confirmButton: "btn btn-primary",
      },
    });
  }

  let selectedDate = null;
  let startTimePicker = null;

  $(".datePicker").flatpickr({
    dateFormat: "Y-m-d",
    minDate: "today",
    onChange: function(selectedDates, dateStr, instance) {
      selectedDate = new Date(selectedDates[0]);
      var playAreaCartId = $(instance.element).data('play-area-cart-id');
      submitDateTime(playAreaCartId, 'date', dateStr);
      updateStartTimePicker(selectedDate);
    }
  });

  function updateStartTimePicker(selectedDate) {
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

    startTimePicker = $(".startTimePicker").flatpickr({
      enableTime: true,
      noCalendar: true,
      dateFormat: "H:i",
      time_24hr: true,
      minTime: minTime,
      onChange: function(selectedDates, timeStr, instance) {
        var playAreaCartId = $(instance.element).data('play-area-cart-id');
        submitDateTime(playAreaCartId, 'start_time', timeStr);
        updateEndTimePicker(timeStr);
      }
    });
  }

  function updateEndTimePicker(startTimeStr) {
    // Convert startTimeStr to a Date object and add 5 minutes
    const [hours, minutes] = startTimeStr.split(":").map(Number);
    const minEndTime = new Date();
    minEndTime.setHours(hours);
    minEndTime.setMinutes(minutes + 5);
    minEndTime.setSeconds(0);
    minEndTime.setMilliseconds(0);

    const formattedMinEndTime = minEndTime.toTimeString().slice(0, 5);

    if (minEndTime.getDate() !== new Date().getDate()) {
      // If adding 5 minutes crosses midnight, disable the end time picker
      $(".endTimePicker").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        clickOpens: false, // Disable the end time picker
        onChange: null
      });
    } else {
      $(".endTimePicker").flatpickr({
        enableTime: true,
        noCalendar: true,
        dateFormat: "H:i",
        time_24hr: true,
        minTime: formattedMinEndTime,
        onChange: function(selectedDates, timeStr, instance) {
          var playAreaCartId = $(instance.element).data('play-area-cart-id');
          submitDateTime(playAreaCartId, 'end_time', timeStr);
        }
      });
    }
  }

  // Initialize the startTimePicker and endTimePicker with default settings
  startTimePicker = $(".startTimePicker").flatpickr({
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
    onChange: function(selectedDates, timeStr, instance) {
      var playAreaCartId = $(instance.element).data('play-area-cart-id');
      submitDateTime(playAreaCartId, 'start_time', timeStr);
      updateEndTimePicker(timeStr);
    }
  });

  $(".endTimePicker").flatpickr({
    enableTime: true,
    noCalendar: true,
    dateFormat: "H:i",
    time_24hr: true,
    onChange: function(selectedDates, timeStr, instance) {
      var playAreaCartId = $(instance.element).data('play-area-cart-id');
      submitDateTime(playAreaCartId, 'end_time', timeStr);
    }
  });


  function submitDateTime(playAreaCartId, type, value) {
    $.ajax({
      url: '/update-play-area-cart',
      method: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        type: type,
        value: value,
        playAreaCartId: playAreaCartId,
        code: $('#coupon_code').val(),
        points: pointSliderValue,
      },
      success: function(response) {
        if (response.success) {
          $(`.cart-per-play-area-duration-${playAreaCartId}`).text(response.durationFormatted);
          $(`.cart-per-product-total-${playAreaCartId}`).text('₹' + Math.round(response
            .cartPerPlayAreaTotalPrice));
          breakupContents(response, response.data.cartData.couponDiscount, response.data.cartData.pointDiscount);
          $('#total-price').html('Pay- ₹' + parseFloat(response.chargesWithNetTotal.netTotal).toFixed(2) + `<span
          class="badge badge-dark ms-2">${response.data.cartData.totalProductCount} Items</span>`);
          if (!response.coupon_success) {
            removeCouponElements(response)
          }
          if (!response.point_success) {
            removePointContents(response)
          }
        } else {
          console.log('Failed to update date/time');
        }

      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error('Error updating date/time:', textStatus, errorThrown);
      }
    });
  }

  document.querySelector("#kt_app_toolbar") &&
    (function() {
      var kt_customer_cart_slider = document.querySelector("#kt_customer_cart_slider"),
        t = document.querySelector("#kt_customer_cart_slider_value");
      var previousSliderValue = 0;
      if (kt_customer_cart_slider) {
        noUiSlider.create(kt_customer_cart_slider, {
            start: [0],
            connect: [true, false],
            step: 1,
            format: wNumb({
              decimals: 0
            }),
            range: {
              min: [0],
              max: [parseInt(userPoints)]
            },
          }),
          kt_customer_cart_slider.noUiSlider.on("update", function(values, handle) {
            t.innerHTML = values[handle];
          });
        kt_customer_cart_slider.noUiSlider.on("change", function(values, handle) {
          pointSliderValue = values[handle];
          $.ajax({
            url: '/update-cart-point',
            method: 'POST',
            data: {
              _token: '{{ csrf_token() }}',
              points: pointSliderValue,
              code: $('#coupon_code').val(),
            },
            success: function(response) {
              if (response.success) {
                $('#total-price').html('Pay- ₹' + parseFloat(response.chargesWithNetTotal.netTotal).toFixed(
                  2) + `<span
                class="badge badge-dark ms-2">${response.data.cartData.totalProductCount} Items</span>`);
                breakupContents(response, response.data.cartData.couponDiscount, response.data.cartData
                  .pointDiscount);
                previousSliderValue = pointSliderValue;
              } else {
                showToast('error', response.data.cartData.message);
                kt_customer_cart_slider.noUiSlider.set(previousSliderValue);
                pointSliderValue = previousSliderValue;
              }
            },
            error: function(jqXHR, textStatus, errorThrown) {
              console.error('Error updating points', textStatus, errorThrown);
              kt_customer_cart_slider.noUiSlider.set(previousSliderValue);
              pointSliderValue = previousSliderValue;
            }
          });
        });
        var n = kt_customer_cart_slider.querySelector(".noUi-handle");
        n.setAttribute("tabindex", 0),
          n.addEventListener("click", function() {
            this.focus();
          }),
          n.addEventListener("keydown", function(t) {
            var currentValue = Number(kt_customer_cart_slider.noUiSlider.get());
            switch (t.which) {
              case 37:
                kt_customer_cart_slider.noUiSlider.set(currentValue - 1);
                break;
              case 39:
                kt_customer_cart_slider.noUiSlider.set(currentValue + 1);
            }
          });
      }
    })();

  function removePointContents(response) {
    showToast('error', 'Applied Points Removed!');
    var slider = document.querySelector("#kt_customer_cart_slider");
    if (slider && slider.noUiSlider) {
      slider.noUiSlider.set(0);
    }
    pointSliderValue = 0;
    breakupContents(response, response.data.cartData.pointDiscount, 0);
  }
</script>
<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
  $(document).ready(function() {
    $('#total-price').on('click', function() {
      var datePickers = document.querySelectorAll('.datePicker');
      var startTimePickers = document.querySelectorAll('.startTimePicker');
      var endTimePickers = document.querySelectorAll('.endTimePicker');
      var isEmpty = false;
      var swalDisplayed = false;

      // Function to check and set invalid feedback for input fields
      function checkAndSetInvalid(inputField, fieldName) {
        var parentDiv = inputField.closest('.form-group').querySelector(`.${fieldName}PickerContainer`);
        var invalidFeedback = inputField.closest('.form-group').querySelector('.invalid-feedback');
        if (!inputField.value) {
          isEmpty = true;
          // Add Bootstrap's is-invalid class
          inputField.classList.add('is-invalid');
          parentDiv.classList.add('is-invalid');
          // Set the error message
          invalidFeedback.textContent = `${fieldName} is required`;
          if (!swalDisplayed) {
            Swal.fire({
              text: "Some fields are missing. Please fill out all required fields.",
              icon: "error",
              buttonsStyling: false,
              confirmButtonText: "Ok, got it!",
              customClass: {
                confirmButton: "btn btn-primary",
              },
            });
            swalDisplayed = true;
          }
        } else {
          // Remove Bootstrap's is-invalid class if field is not empty
          inputField.classList.remove('is-invalid');
          parentDiv.classList.remove('is-invalid');
          invalidFeedback.textContent = '';
        }
      }

      // Iterate over the date pickers and check if any are empty
      datePickers.forEach(function(datePicker) {
        checkAndSetInvalid(datePicker, 'Date');
      });

      // Iterate over the start time pickers and check if any are empty
      startTimePickers.forEach(function(startTimePicker) {
        checkAndSetInvalid(startTimePicker, 'Start');
      });

      // Iterate over the end time pickers and check if any are empty
      endTimePickers.forEach(function(endTimePicker) {
        checkAndSetInvalid(endTimePicker, 'End');
      });

      if (isEmpty) {
        return
      }

      const loadingEl = document.createElement("div");
      document.body.prepend(loadingEl);
      loadingEl.classList.add("page-loader");
      loadingEl.classList.add("flex-column");
      loadingEl.classList.add("bg-dark");
      loadingEl.classList.add("bg-opacity-25");
      loadingEl.innerHTML = `
      <span class="spinner-border text-primary" role="status"></span>
      <span class="text-gray-800 fs-6 fw-semibold mt-5">Loading...</span>
    `;

      KTApp.showPageLoading();
      var coupon = $('#coupon_used').val();
      $.ajax({
        url: '/checkout',
        method: 'GET',
        data: {
          coupon: coupon,
          points: pointSliderValue,
        },
        success: function(response) {
          if (response.success) {
            var options = {
              key: `{{ env('RAZORPAY_KEY') }}`,
              amount: response.total * 100, // Amount in paisa
              currency: 'INR',
              name: `{{ $setting->website_name ?? 'Bhopal Food Court' }}`,
              description: 'Payment for Order',
              image: `{{ asset($setting->project_logo ?? 'assets/media/logos/default-small.svg') }}`,
              order_id: response.order, // Use the order ID obtained from the server
              handler: function(res) {
                $.ajax({
                  url: `/checkout/payment-callback`,
                  method: 'POST',
                  data: {
                    razorpay_order_id: res.razorpay_order_id,
                    razorpay_payment_id: res.razorpay_payment_id,
                    razorpay_signature: res.razorpay_signature,
                    _token: document.querySelector('meta[name="csrf-token"]').content,
                    points: pointSliderValue,
                    coupon: coupon
                  },
                  success: function(data) {
                    // Check the server response and take appropriate action
                    if (data.success) {
                      // Redirect to a success page or show a success message
                      Swal.fire({
                        text: "Order has been successfully submitted!",
                        icon: "success",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                          confirmButton: "btn btn-primary",
                        },
                      }).then(function(result) {
                        location.reload();
                      });
                    } else {
                      // Show an error message or handle other scenarios
                      alert('Payment failed. Please try again.');
                    }
                  },
                  error: function(xhr, status, error) {
                    console.log(error);
                    console.error(JSON.stringify(error));
                    console.log(xhr);
                    // Handle error, show a message, or redirect accordingly
                    // alert('An error occurred. Please try again.');
                  },
                  complete: () => {
                    KTApp.hidePageLoading();
                    loadingEl.remove();
                  }
                });
              },
              modal: {
                ondismiss: function() {
                  KTApp.hidePageLoading();
                  loadingEl.remove();
                }
              }
            };

            var rzp = new Razorpay(options);
            rzp.open();
          } else {
            KTApp.hidePageLoading();
            loadingEl.remove();
            Swal.fire({
              html: response.message,
              icon: "error",
              buttonsStyling: false,
              confirmButtonText: "Ok, got it!",
              customClass: {
                confirmButton: "btn btn-primary",
              },
            })
          }
        },
        error: function(error) {
          Swal.fire({
            html: error.responseJSON.message,
            icon: "error",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            customClass: {
              confirmButton: "btn btn-primary",
            },
          });
          KTApp.hidePageLoading();
          loadingEl.remove();
        }
      });
    });
  });
</script>
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
