@php
  $settings = \App\Models\Setting::first();
  $totalPrice = $sessionData['totalProductPrice'];
  $gstCost = $totalPrice * ($settings->gst / 100);
  $sgtCost = $totalPrice * ($settings->sgt / 100);
  $serviceTaxAmount = $totalPrice * ($settings->service_tax / 100);

@endphp
<div id="kt_shopping_cart" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="cart" data-kt-drawer-activate="true"
  data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'340px', 'md': '700px'}" data-kt-drawer-direction="end"
  data-kt-drawer-toggle="#kt_drawer_shopping_cart_toggle" data-kt-drawer-close="#kt_drawer_shopping_cart_close">
  <!--begin::Messenger-->
  <div class="card shadow-none w-100 border-primary border-start-dashed">
    <div class="card-header bg-primary border-0 pt-2">
      <h3 class="card-title align-items-start flex-column">
        <span class="card-label fw-bold text-gray-900">Hi Guest</span>
        <span class="text-gray-900 mt-1 fw-semibold fs-7">Checkout Now</span>
      </h3>
      <div class="card-toolbar">
        <h3 class="card-title align-items-start flex-column">
          <div class="form-check form-switch form-check-custom form-check-warning form-check-solid">
            <div class="spinner-border spinner-border-sm mx-3" style="display: none" id="loadingIndicator"
              role="status">
              <span class="sr-only">Loading...</span>
            </div>

            <label class="form-check-label" for="flexSwitchDefault">
              <span class="badge badge-warning">0 Points</span>
            </label>
          </div>
          <span class="mt-1 fw-semibold fs-7">Login to redeem points</span>
        </h3>
      </div>
    </div>
    <!--begin::Card body-->
    <div class="card-body p-2 scroll h-auto">
      <!--begin::row-->
      <div class="row">
        <div class="col px-5">
          <!--begin::Item-->
          <div id="cartItemsContainer">
            @foreach ($sessionData['items'] as $cartItem)
              <div class="d-block align-items-center border-dashed border-gray-900 bg-light rounded p-3 mb-7">
                <div class="d-flex align-items-sm-center mb-2">
                  <!--begin::Symbol-->
                  <div class="symbol symbol-50px symbol-circle me-2">
                    <img src="{{ asset($cartItem['item']->item_image) }}" class="align-self-center" alt="">
                  </div>
                  <!--end::Symbol-->
                  <!--begin::Section-->
                  <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                    <div class="flex-grow-1 me-5">
                      <a href="#"
                        class="text-gray-800 text-hover-primary fs-6 fw-bold">{{ $cartItem['item']->item_name }}</a>
                      <div class="d-flex flex-wrap flex-grow-1">
                        <div class="me-2">
                          <span class="text-success fw-bold">Price</span>
                          <span class="fw-bold text-gray-800 d-block fs-6">₹{{ round($cartItem['item']->price) }}</span>
                        </div>
                        <div class="me-5s">
                          <span class="text-danger fw-bold">Total</span>
                          <span
                            class="fw-bold text-gray-800 d-block fs-6 cart-per-item-total-{{ $cartItem['item']->id }}">₹{{ round($cartItem['item']->price * $cartItem['quantity']) }}</span>
                        </div>
                      </div>
                    </div>
                    <span>
                      <a href="#" class="symbol symbol-35px delete-cart-item"
                        data-cart-item-id="{{ $cartItem['item']->id }}">
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
                    <!--begin::Dialer-->
                    <div class="input-group w-md-300px w-125px" data-kt-dialer="true" data-kt-dialer-min="1"
                      data-kt-dialer-step="1">
                      <!--begin::Decrease control-->
                      <button class="btn btn-icon btn-outline btn-active-color-primary event-decrease-quantity"
                        type="button" data-cart-item="{{ $cartItem['item']->id }}">
                        <i class="bi bi-dash fs-1"></i>
                      </button>
                      <!--end::Decrease control-->
                      <!--begin::Input control-->
                      <input type="text" class="form-control" readonly placeholder="Amount"
                        value="{{ $cartItem['quantity'] }}" data-kt-dialer-control="input" />
                      <!--end::Input control-->
                      <!--begin::Increase control-->
                      <button class="btn btn-icon btn-outline btn-active-color-primary event-increase-quantity"
                        type="button" data-cart-item="{{ $cartItem['item']->id }}">
                        <i class="bi bi-plus fs-1"></i>
                      </button>
                      <!--end::Increase control-->
                    </div>
                    <!--end::Dialer-->
                  </div>
                  <div class="me-2 text-end">
                    <span class="text-info fw-bold">Vendor</span>
                    <span class="fw-bold text-gray-800 d-block fs-6">{{ $cartItem['item']->vendor->brand_name }}<span
                        class="badge badge-primary badge-sm ms-2" data-bs-toggle="tooltip"
                        data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                        title="Stall No.">{{ $cartItem['item']->vendor->stall_no }}</span></span>
                  </div>
                </div>
              </div>
            @endforeach
          </div>
          <div id="cartFoodAreasContainer">
            @foreach ($sessionData['playAreas'] as $playArea)
              <div class="d-block align-items-center border-dashed border-gray-900 bg-light rounded p-3 mb-7"
                data-play-area-id="{{ $playArea['playArea']->id }}">
                <div class="d-flex align-items-sm-center mb-2">
                  <!--begin::Symbol-->
                  <div class="symbol symbol-50px symbol-2by3 me-2">
                    <img src="{{ asset($playArea['playArea']->image) }}" class="align-self-center" alt="">
                  </div>
                  <!--end::Symbol-->
                  <!--begin::Section-->
                  @php
                    $start = \Carbon\Carbon::parse($playArea['data']['start_time'] ?? 0);
                    $end = \Carbon\Carbon::parse($playArea['data']['end_time'] ?? 0);

                    $duration = $start->diff($end);
                    $durationFormatted = $duration->h . 'H';
                    if ($duration->i > 0) {
                        $durationFormatted .= ' ' . $duration->i . 'Min';
                    }
                    // Calculate the difference in minutes
                    $durationInMinutes = $start->diffInMinutes($end);

                    // Convert minutes to hours (including fractions)
                    $durationInHours = $durationInMinutes / 60;

                    // Get the price per hour from the play area
                    $pricePerHour = $playArea['playArea']->price;

                    // Get the number of players
                    $playersCount = $playArea['data']['playersCount'] ?? 1;

                    // Calculate the total price
                    $cartPerPlayAreaTotalPrice = round($pricePerHour * $durationInHours * $playersCount);
                  @endphp
                  <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                    <div class="flex-grow-1 me-1">
                      <a href="#"
                        class="text-gray-800 text-hover-primary fs-6 fw-bold">{{ $playArea['playArea']->title }}</a>
                      <div class="d-flex flex-wrap flex-grow-1">
                        <div class="me-2 col-12 col-md-3 mb-1 mb-md-0">
                          <span class="text-success fw-bold">Price</span>
                          <span
                            class="fw-bold text-gray-800 d-block fs-6">₹{{ round($playArea['playArea']->price) }}/Hour/Player</span>
                        </div>
                        <div class="me-3">
                          <span class="text-warning fw-bold">Hours</span>
                          <span
                            class="fw-bold text-gray-800 text-center d-block fs-6 cart-per-play-area-duration-{{ $playArea['playArea']->id }}">{{ $durationFormatted }}</span>
                        </div>
                        <div class="mx-2">
                          <span class="text-info fw-bold">Players</span>
                          <span
                            class="fw-bold text-gray-800 text-center d-block fs-6 cart-per-play-area-player-{{ $playArea['playArea']->id }}">{{ $playArea['data']['playersCount'] }}P</span>
                        </div>
                        <div class="mx-3">
                          <span class="text-danger fw-bold">Total</span>
                          <span
                            class="fw-bold text-gray-800 text-center d-block fs-6 cart-per-play-area-total-{{ $playArea['playArea']->id }}">₹{{ $cartPerPlayAreaTotalPrice }}</span>
                        </div>
                      </div>
                    </div>
                    <span class="ms-auto mt-2 mt-md-0">
                      <div class="symbol symbol-35px cursor-pointer delete-cart-item"
                        data-play-area-id="{{ $playArea['playArea']->id }}">
                        <div class="symbol-label bg-light-danger">
                          <i class="fas fa-trash-alt text-danger"></i>
                        </div>
                      </div>
                    </span>
                  </div>
                  <!--end::Section-->
                </div>
                <!--end::Info-->
                <div class="d-flex align-items-start flex-stack flex-wrap flex-grow-1 py-2">
                  <div class="fs-3 text-info form-group w-125px w-md-200px">
                    <div class="position-relative d-flex align-items-center DatePickerContainer">
                      <!--begin::Icon-->
                      <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4">
                        <span class="path1"></span>
                        <span class="path2"></span>
                        <span class="path3"></span>
                        <span class="path4"></span>
                        <span class="path5"></span>
                        <span class="path6"></span>
                      </i>
                      <!--end::Icon-->

                      <!--begin::Datepicker-->
                      <input class="form-control form-control-sm kt_datepicker_dob_custom ps-12 datePicker"
                        placeholder="Pick date" value="{{ $playArea['data']['date'] ?? '' }}"
                        data-play-area-id="{{ $playArea['playArea']->id }}" />
                      <!--end::Datepicker-->
                    </div>
                    <div class="invalid-feedback"></div>
                  </div>
                  <div class="w-60px w-md-175px form-group">
                    <div class="StartPickerContainer">
                      <input class="form-control form-control-sm flatpickr-input startTimePicker"
                        placeholder="Start Time" type="text" readonly="readonly"
                        value="{{ $playArea['data']['start_time'] ?? '' }}"
                        data-play-area-id="{{ $playArea['playArea']->id }}">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>
                  <div class="w-60px w-md-175px form-group">
                    <div class="EndPickerContainer">
                      <input class="form-control form-control-sm flatpickr-input endTimePicker" placeholder="End Time"
                        type="text" readonly="readonly" value="{{ $playArea['data']['end_time'] ?? '' }}"
                        data-play-area-id="{{ $playArea['playArea']->id }}">
                      <div class="invalid-feedback"></div>
                    </div>
                  </div>
                </div>
                <div class="d-flex flex-stack flex-wrap flex-grow-1">
                  <div class="me-2 text-Start">
                    <span class="text-gray-900 fw-bold"> Add Players</span>
                  </div>
                  <div class="fw-bold fs-3 text-info">
                    <!--begin::Dialer-->
                    <div class="input-group w-md-300px w-125px" data-kt-dialer="true" data-kt-dialer-min="1"
                      data-kt-dialer-max="5" data-kt-dialer-step="1">
                      <!--begin::Decrease control-->
                      <button class="btn btn-icon btn-outline btn-active-color-primary event-decrease-quantity"
                        type="button" data-play-area-id="{{ $playArea['playArea']->id }}">
                        <i class="bi bi-dash fs-1"></i>
                      </button>
                      <!--end::Decrease control-->
                      <!--begin::Input control-->
                      <input type="text" class="form-control" readonly placeholder="Amount"
                        value="{{ $playArea['data']['playersCount'] }}" data-kt-dialer-control="input" />
                      <!--end::Input control-->
                      <!--begin::Increase control-->
                      <button class="btn btn-icon btn-outline btn-active-color-primary event-increase-quantity"
                        type="button" data-play-area-id="{{ $playArea['playArea']->id }}">
                        <i class="bi bi-plus fs-1"></i>
                      </button>
                      <!--end::Increase control-->
                    </div>
                    <!--end::Dialer-->
                  </div>
                </div>
              </div>
            @endforeach
          </div>
          <div id="cartEventsContainer">
            @foreach ($sessionData['events'] as $event)
              <div class="d-block align-items-center border-dashed border-gray-900 bg-light rounded p-3 mb-7">
                <div class="d-flex align-items-sm-center mb-2">
                  <!--begin::Symbol-->
                  <div class="symbol symbol-50px symbol-circle me-2">
                    <img src="{{ $event['event']->image }}" class="align-self-center" alt="">
                  </div>
                  <!--end::Symbol-->
                  <!--begin::Section-->
                  <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                    <div class="flex-grow-1 me-5">
                      <a href="#"
                        class="text-gray-800 text-hover-primary fs-6 fw-bold">{{ $event['event']->title }}</a>
                      <div class="d-flex flex-wrap flex-grow-1">
                        <div class="me-2">
                          <span class="text-success fw-bold">Price</span>
                          <span class="fw-bold text-gray-800 d-block fs-6">₹{{ round($event['event']->price) }}</span>
                        </div>
                        <div class="me-5s">
                          <span class="text-danger fw-bold">Total</span>
                          <span
                            class="fw-bold text-gray-800 d-block fs-6 cart-per-event-total-{{ $event['event']->id }}">₹{{ round($event['event']->price * (int) $event['bookedSeatCount']) }}</span>
                        </div>
                      </div>
                    </div>
                    <span>
                      <div class="symbol symbol-35px cursor-pointer delete-cart-item"
                        data-event-id="{{ $event['event']->id }}">
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
                    <div class="input-group w-md-300px w-125px " data-kt-dialer="true" data-kt-dialer-min="1"
                      data-kt-dialer-max="10" data-kt-dialer-step="1">
                      <!--begin::Decrease control-->
                      <button class="btn btn-icon btn-outline btn-active-color-primary event-decrease-quantity"
                        type="button" data-event-id="{{ $event['event']->id }}">
                        <i class="bi bi-dash fs-1"></i>
                      </button>
                      <!--end::Decrease control-->
                      <!--begin::Input control-->
                      <input type="text" class="form-control" readonly placeholder="Amount"
                        value="{{ $event['bookedSeatCount'] }}" data-kt-dialer-control="input" />
                      <!--end::Input control-->
                      <!--begin::Increase control-->
                      <button class="btn btn-icon btn-outline btn-active-color-primary event-increase-quantity"
                        type="button" data-event-id="{{ $event['event']->id }}">
                        <i class="bi bi-plus fs-1"></i>
                      </button>
                      <!--end::Increase control-->
                    </div>
                    <!--end::Dialer-->
                  </div>
                  <div class="me-2 text-end">
                    <span
                      class="text-info fw-bolder">{{ durationCalculation($event['event']->start_date, $event['event']->end_date) }}</span>
                    <span
                      class="fw-bold text-gray-800 d-block fs-6">{{ \Carbon\Carbon::parse($event['event']->start_date)->format('d-m-Y') }}
                      |
                      {{ \Carbon\Carbon::parse($event['event']->start_date)->format('g:iA') }}</span>
                  </div>
                </div>
                <div class="d-flex align-items-sm-center my-3">
                  <!--begin::Section-->
                  <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                    {{ $event['event']->details }}
                  </div>
                  <!--end::Section-->
                </div>
              </div>
            @endforeach
          </div>
          <!--end::Item-->
        </div>
      </div>
      <!--end::row-->
    </div>
    <!--end::Card body-->
    <!--begin::Card footer-->
    <div class="card-footer px-3 py-0">
      <!--begin::Heading-->
      <div class="d-flex align-items-center flex-wrap py-2">
        <!--begin::Label-->
        <div id="billBreakupRightHead">
          <span class="fs-4 fw-bold pe-2 mr-auto" data-bs-toggle="collapse"
            data-bs-target="#kt_docs_card_billbreakup">Bill
            Breakup<i class="fas fa-info-circle text-primary ms-2 collapsible cursor-pointer" data-bs-toggle="tooltip"
              data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Bill Details"></i></span>
        </div>
        <div style="margin-left: auto">
          <span class="btn btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning px-3 py-1"
            data-bs-toggle="collapse" data-bs-target="#kt_docs_card_applied_coupon">Coupons<i
              class="fas fa-info-circle text-primary ms-2 collapsible cursor-pointer" data-bs-toggle="tooltip"
              data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Applied Coupon"></i></span>
        </div>
      </div>
      <!--end::Heading-->
      <!--begin::Bill Breakup-->
      <div id="kt_docs_card_billbreakup" class="collapse hide border border-dashed border-danger my-2 rounded-1">
        <div class="d-flex flex-column p-2" id="billbreakup_item">
          <li class="d-flex align-items-center py-2">
            <span class="bullet bullet-vertical fw-bold me-5"></span>Total : <span class="ms-2"
              id="billbreakup_total">₹{{ number_format(round($totalPrice, 2), 2) }}</span>
          </li>
          @if ($settings->gst > 0 && $sessionData['totalProductCount'] > 0)
            <li class="d-flex align-items-center py-2">
              <span class="bullet bullet-vertical fw-bold bg-danger me-5"></span>GST
              {{ round($settings->gst, 1) }}%:
              <span class="ms-2">₹{{ round($gstCost, 2) }}</span>
            </li>
          @endif

          @if ($settings->sgt > 0 && $sessionData['totalProductCount'] > 0)
            <li class="d-flex align-items-center py-2">
              <span class="bullet bullet-vertical fw-bold bg-danger me-5"></span>SGT
              {{ round($settings->sgt, 1) }}%:
              <span class="ms-2">₹{{ round($sgtCost, 2) }}</span>
            </li>
          @endif

          @if ($settings->service_tax > 0 && $sessionData['totalProductCount'] > 0)
            <li class="d-flex align-items-center py-2">
              <span class="bullet bullet-vertical fw-bold bg-danger me-5"></span>Service Tax
              {{ round($settings->service_tax, 1) }}%:
              <span class="ms-2">₹{{ round($serviceTaxAmount, 2) }}</span>
            </li>
          @endif
        </div>
      </div>
      <!--end::Bill Breakup-->
      <!--begin::Coupons-->
      <div id="kt_docs_card_applied_coupon" class="collapse hide border border-dashed border-danger my-2 rounded-1">
        <div class="d-flex flex-column p-2">
          Login to see available coupons
        </div>
      </div>
      <!--end::Coupons-->
      <div class="row row-cols-2">
        <div class="col p-0">
          <a href="/cart/session-clear" id="kt_drawer_example_basic_button"
            class="btn btn-warning hover-elevate-up w-100 rounded-0 rounded-start-1 py-2" data-bs-toggle="tooltip"
            data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Clear All">Clear Now</a>
        </div>
        <div class="col p-0">
          <a href="#" id="total-price" class="btn btn-danger hover-elevate-up w-100 rounded-0 py-2 px-2"
            data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
            title="Order Now">Pay-
            ₹{{ number_format(round($totalPrice + $serviceTaxAmount + $sgtCost + $gstCost, 2), 2) }}<span
              class="badge badge-dark ms-2" id="total-items">{{ $sessionData['totalProductCount'] }}
              Items</span></a>

        </div>
      </div>
      <!--end::View component-->
    </div>
    <!--end::Card footer-->
  </div>
  <!--end::Messenger-->
</div>
