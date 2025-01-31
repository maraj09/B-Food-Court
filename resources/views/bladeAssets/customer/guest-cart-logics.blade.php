<script>
  var gst = `{{ $settings?->gst }}`
  var sgt = `{{ $settings?->sgt }}`
  var serviceTax = `{{ $settings?->service_tax }}`

  function breakupContents(response) {
    $(`#billbreakup_item`).html(``);
    $(`#billbreakup_item`).append(`<li class="d-flex align-items-center py-2">
          <span class="bullet bullet-vertical fw-bold me-5"></span>Total : <span
            class="ms-2">₹${parseFloat(response.totalProductPrice)
            .toFixed(2)}</span>
        </li>`);
    if (gst > 0) {
      $('#billbreakup_item').append(` 
      <li class="d-flex align-items-center py-2">
          <span class="bullet bullet-vertical fw-bold bg-danger me-5"></span>GST ${parseFloat(
              gst
          ).toFixed(1)}%: <span class="ms-2">₹${parseFloat(response.gstCharge).toFixed(
          2
      )}</span>
      </li>`)
    }
    if (sgt > 0) {
      $('#billbreakup_item').append(` 
      <li class="d-flex align-items-center py-2">
          <span class="bullet bullet-vertical fw-bold bg-danger me-5"></span>SGT ${parseFloat(
              sgt
          ).toFixed(1)}%: <span class="ms-2">₹${parseFloat(response.sgtCharge).toFixed(
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
            response.serviceTaxCharge
      ).toFixed(2)}</span>
      </li>`);
    }

  }

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
                                  <span class="fw-bold text-gray-800 d-block fs-6 cart-per-item-total-${cartItem.item.id}">₹${Math.round(cartItem.item.price * cartItem.quantity)}</span>
                              </div>
                          </div>
                      </div>
                      <span>
                          <a href="#" class="symbol symbol-35px delete-cart-item" data-cart-item-id="${cartItem.item.id}">
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
                          <button class="btn btn-icon btn-outline btn-active-color-primary event-decrease-quantity" type="button" data-cart-item="${cartItem.item.id}">
                              <i class="bi bi-dash fs-1"></i>
                          </button>
                          <input type="text" class="form-control" readonly placeholder="Amount" value="${cartItem.quantity}" data-kt-dialer-control="input" />
                          <button class="btn btn-icon btn-outline btn-active-color-primary event-increase-quantity" type="button" data-cart-item="${cartItem.item.id}">
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
      var playAreaId = playArea.playArea.id;
      var datePickerId = `datePicker-${playAreaId}`;
      var startTimePickerId = `startTimePicker-${playAreaId}`;
      var endTimePickerId = `endTimePicker-${playAreaId}`;

      var start = playArea.data.start_time ? moment(playArea.data.start_time, "H:i") : null;
      var end = playArea.data.end_time ? moment(playArea.data.end_time, "H:i") : null;

      var durationFormatted = '0H';
      var cartPerPlayAreaTotalPrice = 0;
      var playersCount = playArea.data.playersCount || 1;

      if (start && end) {
        console.log(start);
        var duration = moment.duration(end.diff(start));
        var durationHours = Math.floor(duration.asHours());
        var durationMinutes = duration.minutes();


        durationFormatted = `${durationHours}H`;
        if (durationMinutes > 0) {
          durationFormatted += ` ${durationMinutes}Min`;
        }


        var durationInMinutes = duration.asMinutes();
        var durationInHours = durationInMinutes / 60;

        var pricePerHour = playArea.playArea.price;

        cartPerPlayAreaTotalPrice = Math.round(pricePerHour * durationInHours * playersCount);
      }

      var itemHtml = `
        <div class="d-block align-items-center border-dashed border-gray-900 bg-light rounded p-3 mb-7" data-play-area-id="${playAreaId}">
            <div class="d-flex align-items-sm-center mb-2">
                <div class="symbol symbol-50px symbol-2by3 me-2">
                    <img src="${playArea.playArea.image}" class="align-self-center" alt="">
                </div>
                <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                    <div class="flex-grow-1 me-1">
                        <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">${playArea.playArea.title}</a>
                        <div class="d-flex flex-wrap flex-grow-1">
                            <div class="me-2 col-12 col-md-3 mb-1 mb-md-0">
                                <span class="text-success fw-bold">Price</span>
                                <span class="fw-bold text-gray-800 d-block fs-6">₹${Math.round(playArea.playArea.price)}/Hour/Player</span>
                            </div>
                            <div class="me-3">
                                <span class="text-warning fw-bold">Hours</span>
                                <span class="fw-bold text-gray-800 text-center d-block fs-6 cart-per-play-area-duration-${playArea.playArea.id}">${durationFormatted}</span>
                            </div>
                            <div class="mx-2">
                                <span class="text-info fw-bold">Players</span>
                                <span class="fw-bold text-gray-800 text-center d-block fs-6 cart-per-play-area-player-${playArea.playArea.id}">${playersCount}P</span>
                            </div>
                            <div class="mx-3">
                                <span class="text-danger fw-bold">Total</span>
                                <span class="fw-bold text-gray-800 text-center d-block fs-6 cart-per-play-area-total-${playArea.playArea.id}">₹${cartPerPlayAreaTotalPrice}</span>
                            </div>
                        </div>
                    </div>
                    <span class="ms-auto mt-2 mt-md-0">
                        <div class="symbol symbol-35px cursor-pointer delete-cart-item" data-play-area-id="${playAreaId}">
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
                        <input class="form-control form-control-sm kt_datepicker_dob_custom ps-12 datePicker" placeholder="Pick date" id="${datePickerId}" value="${playArea.data.date || ''}" />
                        </div>
                        <div class="invalid-feedback"></div>
                </div>
                <div class="w-60px w-md-175px form-group">
                    <div class="StartPickerContainer">
                    <input class="form-control form-control-sm flatpickr-input startTimePicker" placeholder="Start Time" id="${startTimePickerId}" type="text" readonly="readonly" value="${playArea.data.start_time || ''}">
                    <div class="invalid-feedback">asdasd</div>
                    </div>
                </div>
                <div class="w-60px w-md-175px form-group">
                  <div class="EndPickerContainer">
                    <input class="form-control form-control-sm flatpickr-input endTimePicker" placeholder="End Time" id="${endTimePickerId}" type="text" readonly="readonly" value="${playArea.data.end_time || ''}">
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
                    <button class="btn btn-icon btn-outline btn-active-color-primary event-decrease-quantity" data-play-area-id="${playArea.playArea.id}" type="button">
                      <i class="bi bi-dash fs-1"></i>
                    </button>
                    <input type="text" class="form-control" readonly placeholder="Amount" value="${playArea.data.playersCount || 1}" data-kt-dialer-control="input" />
                    <button class="btn btn-icon btn-outline btn-active-color-primary event-increase-quantity" data-play-area-id="${playArea.playArea.id}" type="button">
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
          var playAreaId = $(instance.element).closest('[data-play-area-id]').data('play-area-id');
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
            var playAreaId = $(instance.element).closest('[data-play-area-id]').data('play-area-id');
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
              var playAreaId = $(instance.element).closest('[data-play-area-id]').data('play-area-id');
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
          var playAreaId = $(instance.element).closest('[data-play-area-id]').data('play-area-id');
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
          var playAreaId = $(instance.element).closest('[data-play-area-id]').data('play-area-id');
          submitDateTime(playAreaId, 'end_time', timeStr);
        }
      });
    });

  }

  function displayCartEvents(cartItems) {
    var cartItemsContainer = $('#cartEventsContainer');
    cartItemsContainer.empty(); // Clear previous items

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
                      class="fw-bold text-gray-800 d-block fs-6 cart-per-event-total-${event.event.id}">₹${Math.round(event.event.price * event.bookedSeatCount)}</span>
                  </div>
                </div>
              </div>
              <span>
                <div class="symbol symbol-35px cursor-pointer delete-cart-item"
                  data-event-id="${event.event.id}">
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
                <button class="btn btn-icon btn-outline btn-active-color-primary event-decrease-quantity" type="button" data-event-id="${event.event.id}">
                  <i class="bi bi-dash fs-1"></i>
                </button>
                <!--end::Decrease control-->
                <!--begin::Input control-->
                <input type="text" class="form-control" readonly placeholder="Amount"
                  value="${event.bookedSeatCount}" data-kt-dialer-control="input" />
                <!--end::Input control-->
                <!--begin::Increase control-->
                <button class="btn btn-icon btn-outline btn-active-color-primary event-increase-quantity" type="button" data-event-id="${event.event.id}">
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

  $(document).on('click', '.delete-cart-item', function(e) {
    e.preventDefault();

    var cartItemId = $(this).data('cart-item-id');
    var playAreaId = $(this).data('play-area-id');
    var eventId = $(this).data('event-id');

    $.ajax({
      url: '/cart/session-remove-item',
      type: 'POST',
      data: {
        cartItemId: cartItemId,
        playAreaId: playAreaId,
        eventId: eventId,
        _token: document.querySelector('meta[name="csrf-token"]').content
      },
      headers: {
        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
      },
      success: function(response) {
        console.log(response);
        if (response.success) {
          displayCartItems(response.data.sessionData.items);
          displayCartPlayAreas(response.data.sessionData.playAreas)
          displayCartEvents(response.data.sessionData.events)
          $('.view-cart-span').html(`${response.data.sessionData.totalProductCount} Items`)
          $('#total-price').html('Pay- ₹' + parseFloat(response.chargesWithNetTotal.netTotal).toFixed(2) + `<span
          class="badge badge-dark ms-2">${response.data.sessionData.totalProductCount} Items</span>`);
          breakupContents(response.chargesWithNetTotal);
          // breakupContents(response)
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

  function bookProduct(button) {
    // Assuming button contains data-play-area-id attribute
    let url = '';
    var itemId = $(button).data('item-id');
    var playAreaId = $(button).data('play-area-id');
    var eventId = $(button).data('event-id');
    var eventBookedSeatCount = Math.round($(`#event_book_seat_${eventId}`).val());
    const cartDrawer = KTDrawer.getInstance(document.getElementById('kt_shopping_cart'));
    if (itemId) {
      url = '/item/book'
    } else if (playAreaId) {
      url = '/play-area/book'
    } else if (eventId) {
      url = '/event/book'
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
        _token: $('meta[name="csrf-token"]').attr('content') // CSRF token for security
      },
      success: function(response) {
        if (response.success) {
          if (itemId) {
            displayCartItems(response.data.sessionData.items);
          } else if (playAreaId) {
            displayCartPlayAreas(response.data.sessionData.playAreas);
          } else if (eventId) {
            displayCartEvents(response.data.sessionData.events);
          }
          $('.view-cart-span').html(`${response.data.sessionData.totalProductCount} Items`)
          $('#total-price').html('Pay- ₹' + parseFloat(response.chargesWithNetTotal.netTotal).toFixed(2) + `<span
          class="badge badge-dark ms-2">${response.data.sessionData.totalProductCount} Items</span>`);
          breakupContents(response.chargesWithNetTotal);
          showToast('success', 'Product added to cart!');
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

  $(document).on('click', '.event-increase-quantity, .event-decrease-quantity', function() {
    // Get the event ID and determine if it's an increase or decrease action
    var itemId = $(this).data('cart-item');
    var eventId = $(this).data('event-id');
    var playAreaId = $(this).data('play-area-id');
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
      url: '/session/update-event-quantity',
      data: {
        itemId: itemId,
        eventId: eventId,
        playAreaId: playAreaId,
        quantity: newQuantity,
        _token: document.querySelector('meta[name="csrf-token"]').content // Ensure CSRF token is included
      },
      success: function(response) {
        console.log(response);
        if (response.success) {
          if (itemId) {
            $(`.cart-per-item-total-${itemId}`).text('₹' + Math.round(response.itemTotal));
          } else if (eventId) {
            $(`.cart-per-event-total-${eventId}`).text('₹' + Math.round(response.eventTotal));
          } else if (playAreaId) {
            $(`.cart-per-play-area-total-${playAreaId}`).text('₹' + Math.round(response
              .cartPerPlayAreaTotalPrice));
            console.log(response
              .cartPerPlayAreaTotalPrice);
            $(`.cart-per-play-area-player-${playAreaId}`).text(newQuantity + 'P');
          }
          breakupContents(response.chargesWithNetTotal);
          $('#total-price').html('Pay- ₹' + parseFloat(response.chargesWithNetTotal.netTotal).toFixed(2) + `<span
          class="badge badge-dark ms-2">${response.data.sessionData.totalProductCount} Items</span>`);
        } else {
          quantityInput.val(parseInt(quantityInput.val()) - (isEventIncrease ? 1 : -1));
          if (playAreaId) {
            $(`.cart-per-play-area-player-${playAreaId}`).text(newQuantity + 'P');
          }
          console.error('Error updating quantity:', response.error);
        }
      },
      error: function(error) {
        // If the AJAX request failed, revert the input to its previous value
        quantityInput.val(parseInt(quantityInput.val()) - (isEventIncrease ? 1 : -1));
        console.error('Error updating quantity:', error);
      }
    });
  });


  let selectedDate = null;
  let startTimePicker = null;

  $(".datePicker").flatpickr({
    dateFormat: "Y-m-d",
    minDate: "today",
    onChange: function(selectedDates, dateStr, instance) {
      selectedDate = new Date(selectedDates[0]);
      var playAreaCartId = $(instance.element).data('play-area-id');
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
        var playAreaCartId = $(instance.element).data('play-area-id');
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
          var playAreaCartId = $(instance.element).data('play-area-id');
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
      var playAreaCartId = $(instance.element).data('play-area-id');
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
      var playAreaCartId = $(instance.element).data('play-area-id');
      submitDateTime(playAreaCartId, 'end_time', timeStr);
    }
  });

  function submitDateTime(playAreaId, type, value) {
    $.ajax({
      url: '/update-play-area-session',
      method: 'POST',
      data: {
        _token: '{{ csrf_token() }}',
        type: type,
        value: value,
        playAreaId: playAreaId,
      },
      success: function(response) {
        if (response.success) {
          $(`.cart-per-play-area-duration-${playAreaId}`).text(response.durationFormatted);
          $(`.cart-per-play-area-total-${playAreaId}`).text('₹' + Math.round(response
            .cartPerPlayAreaTotalPrice));
          breakupContents(response.chargesWithNetTotal);
          $('#total-price').html('Pay- ₹' + parseFloat(response.chargesWithNetTotal.netTotal).toFixed(2) + `<span
          class="badge badge-dark ms-2">${response.data.sessionData.totalProductCount} Items</span>`);
        } else {
          console.log('Failed to update date/time');
        }
      },
      error: function(jqXHR, textStatus, errorThrown) {
        console.error('Error updating date/time:', textStatus, errorThrown);
      }
    });
  }
</script>

<script src="https://checkout.razorpay.com/v1/checkout.js"></script>
<script>
  function otpless(otplessUser) {
    const otpless_user_token = otplessUser.token;
    var formData = new FormData();
    formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
    formData.set("otpless_user_token", otpless_user_token);
    $.ajax({
      url: "/otp-less-sign-in",
      type: "POST",
      data: formData,
      processData: false,
      contentType: false,
      success: function(response) {
        if (response.success) {
          placeOrder();
        }
      },
      error: function(error) {
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
    })
  }
</script>
<script type="text/javascript">
  document.getElementById('total-price').addEventListener('click', function(event) {
    event.preventDefault(); // Prevent the default action (navigation)
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

    // Show modal if all fields are filled
    if (!isEmpty) {
      var myModal = new bootstrap.Modal(document.getElementById('loginSignupModal'), {});
      myModal.show();
    }
  });

  const loginSignupModal = new bootstrap.Modal(document.getElementById('loginSignupModal'))

  const updateCsrfToken = (newToken) => {
    $('meta[name="csrf-token"]').attr('content', newToken);
  };
  // Set CSRF token in AJAX headers globally
  $.ajaxSetup({
    headers: {
      'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
    }
  });

  let loginOrSignup = null;
  const phoneLoginSignupInput = document.querySelector("#phoneLoginSignup");
  var phoneLoginSignupInputIti = window.intlTelInput(phoneLoginSignupInput, {
    utilsScript: "{{ asset('custom/assets/js/intlTelInput/utils.js') }}",
    separateDialCode: true,
    initialCountry: "auto",
    onlyCountries: ['bd', 'in'],
    initialCountry: 'bd',
  });
  window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container-custom');
  window.recaptchaVerifier.render();
  const appVerifier = window.recaptchaVerifier;

  var form, submitButton;
  var phoneNumber;
  var KTAddCustomer = (function() {
    return {
      init: function() {
        form = document.querySelector("#customer_login_signup_form");
        submitButton = document.querySelector("#customer_login_signup_submit");

        var fv = FormValidation.formValidation(form, {
          fields: {
            phone: {
              validators: {
                callback: {
                  message: "Invalid phone number",
                  callback: function(input) {
                    phoneNumber = input.value;
                    var isValidPhoneNumber = phoneLoginSignupInputIti.isValidNumber();
                    return isValidPhoneNumber;
                  }
                }
              },
            },
          },
          plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
              rowSelector: ".fv-row",
              eleInvalidClass: "",
              eleValidClass: "",
            }),
          },
        });

        submitButton.addEventListener("click", function(e) {
          e.preventDefault();

          fv.validate().then(function(isValid) {
            if (isValid === "Valid") {
              if (grecaptcha.getResponse().length !== 0) {
                submitButton.setAttribute("data-kt-indicator", "on");
                submitButton.disabled = true;

                var formData = new FormData(form);
                formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
                formData.set("phone", phoneLoginSignupInputIti.getNumber());

                $.ajax({
                  url: "/login-signup/verify",
                  type: "POST",
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function(response) {
                    loginOrSignup = response.data;
                    $('#masked-number').html('*'.repeat(String(form.phone.value).length - 4) +
                      String(form.phone.value).slice(-4));
                    firebase.auth().signInWithPhoneNumber(phoneLoginSignupInputIti
                        .getNumber(), appVerifier)
                      .then(function(confirmationResult) {
                        window.confirmationResult = confirmationResult;
                        coderesult = confirmationResult;
                        $("#customer_login_signup_form").hide();
                        $("#otp_verification_form").show();
                      }).catch(function(error) {
                        console.log(error);
                        var errorMessage = Object.values(error).flat().join("<br>");
                        Swal.fire({
                          html: errorMessage,
                          icon: "error",
                          buttonsStyling: false,
                          confirmButtonText: "Ok, got it!",
                          customClass: {
                            confirmButton: "btn btn-primary",
                          },
                        });
                      }).finally(() => {
                        submitButton.removeAttribute("data-kt-indicator");
                        submitButton.disabled = false;
                      });
                  },
                  error: function(error) {
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
                    submitButton.removeAttribute("data-kt-indicator");
                    submitButton.disabled = false;
                  },
                });
              } else {
                Swal.fire({
                  text: "Please verify Captcha!",
                  icon: "error",
                  buttonsStyling: false,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                    confirmButton: "btn btn-primary",
                  },
                });
              }
            } else {
              Swal.fire({
                text: "Sorry, looks like there are some errors detected, please try again.",
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
      },
    };
  })();

  var KTSigninTwoFactor = (function() {
    var t, e;
    return {
      init: function() {
        t = document.querySelector("#otp_verification_form");
        e = document.querySelector("#otp_verification_form_submit");

        e.addEventListener("click", function(n) {
          n.preventDefault();

          const inputElements = document.querySelectorAll('input[name^="code_"]');
          let otpDigits = [];

          inputElements.forEach(function(input) {
            const value = input.value.trim();
            if (/^\d$/.test(value)) {
              otpDigits.push(value);
            }
          });

          const otpCode = otpDigits.join('');
          if (otpCode) {
            e.setAttribute("data-kt-indicator", "on");
            e.disabled = true;

            coderesult.confirm(otpCode).then((res) => {
              const firebaseIdToken = res.user.ra;
              var formData = new FormData(t);
              formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
              formData.set("phone", phoneLoginSignupInputIti.getNumber());
              formData.append("firebaseIdToken", firebaseIdToken);

              // Update CSRF token after login/signup
              if (loginOrSignup == 'login') {
                $.ajax({
                  url: "{{ route('login') }}",
                  type: "POST",
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function(response) {
                    e.disabled = false;
                    e.removeAttribute("data-kt-indicator");
                    placeOrder();
                  },
                  error: function(error) {
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
                    e.removeAttribute("data-kt-indicator");
                    e.disabled = false;
                  },
                });
              } else {
                formData.append("name",
                  `customer${String(phoneLoginSignupInputIti.getNumber()).slice(-4)}`);
                $.ajax({
                  url: "/register-as-guest",
                  type: "POST",
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function(response) {
                    if (response.success) {
                      e.disabled = false;
                      e.removeAttribute("data-kt-indicator");
                      placeOrder();
                    } else {
                      Swal.fire({
                        html: response.message,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                          confirmButton: "btn btn-primary",
                        },
                      });
                      e.removeAttribute("data-kt-indicator");
                      e.disabled = false;
                    }
                  },
                  error: function(error) {
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
                    e.removeAttribute("data-kt-indicator");
                    e.disabled = false;
                  },
                });
              };
            }).catch(error => {
              Swal.fire({
                text: 'The SMS verification code is invalid.',
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              });
              e.removeAttribute("data-kt-indicator");
              e.disabled = false;
            });
          } else {
            Swal.fire({
              text: "Please enter valid security code and try again.",
              icon: "error",
              buttonsStyling: false,
              confirmButtonText: "Ok, got it!",
              customClass: {
                confirmButton: "btn fw-bold btn-light-primary",
              },
            }).then(function() {
              window.scrollTo(0, 0);
            });
          }
        });

        const inputs = document.querySelectorAll("#otp_verification_form input[type='text']");
        inputs.forEach((input, index) => {
          input.addEventListener("keydown", function(e) {
            if (e.key === "Backspace" && this.value.length === 0 && index > 0) {
              inputs[index - 1].focus();
            } else if ((e.key >= "0" && e.key <= "9") || e.key === "ArrowRight" || e.key ===
              "ArrowLeft") {
              if (this.value.length === 1 && index < inputs.length - 1) {
                inputs[index + 1].focus();
              }
            } else if (e.key === "ArrowRight" && index < inputs.length - 1) {
              inputs[index + 1].focus();
            } else if (e.key === "ArrowLeft" && index > 0) {
              inputs[index - 1].focus();
            }
          });

          input.addEventListener("input", function() {
            if (this.value.length === 1 && index < inputs.length - 1) {
              inputs[index + 1].focus();
            }
          });
        });

        inputs[0].focus();
      },
    };
  })();

  KTUtil.onDOMContentLoaded(function() {
    KTAddCustomer.init();
    KTSigninTwoFactor.init();
  });

  var otpBackButton = document.querySelector("#otp_verification_form_back")
  otpBackButton.addEventListener("click", function(e) {
    $("#otp_verification_form").hide();
    $("#customer_login_signup_form").show();
  })
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
<script>
  const placeOrder = () => {
    loginSignupModal.hide();
    var point = 'inactive';
    var coupon = $('#coupon_used').val();
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
    $.get('/refresh-csrf', function(data) {
      updateCsrfToken(data.csrfToken);
    })
    $.ajax({
      url: '/checkout',
      method: 'GET',
      data: {
        point: point,
        coupon: coupon
      },
      success: function(response) {
        if (response.success) {
          var options = {
            key: `{{ env('RAZORPAY_KEY') }}`,
            amount: response.total * 100,
            currency: 'INR',
            name: `{{ $setting->website_name ?? 'Bhopal Food Court' }}`,
            description: 'Payment for Order',
            image: `{{ asset($setting->project_logo ?? 'assets/media/logos/default-small.svg') }}`,
            order_id: response.order,
            handler: function(res) {
              $.ajax({
                url: `/checkout/payment-callback`,
                method: 'POST',
                data: {
                  razorpay_order_id: res.razorpay_order_id,
                  razorpay_payment_id: res.razorpay_payment_id,
                  razorpay_signature: res.razorpay_signature,
                  "_token": $('meta[name="csrf-token"]').attr('content'),
                  point: point,
                  coupon: coupon
                },
                success: function(data) {
                  if (data.success) {
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
                    alert('Payment failed. Please try again.');
                  }
                },
                error: function(xhr, status, error) {
                  console.log(error);
                  console.error(JSON.stringify(error));
                  console.log(xhr);
                },
                complete: () => {
                  KTApp.hidePageLoading();
                  loadingEl.remove();
                }
              });
            },
            modal: {
              ondismiss: function() {
                location.reload();
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
          }).then(() => {
            location.reload();
          });

        }
      },
      error: function(error) {
        Swal.fire({
          html: error.responseJSON.message,
          icon: "error",
          buttonsStyling: false,
          confirmButtonText: "Ok, got it!",
          customClass: {
            confirmButton: "btn btn-primary",
          },
        }).then(() => {
          location.reload();
        });
        KTApp.hidePageLoading();
        loadingEl.remove();
      }
    });
  };
</script>
