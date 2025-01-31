@extends('layouts.admin.app')
@section('contents')
  @include('pages.points.admin.toolbar.pointToolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Products-->
      <div class="card card-flush mt-sm-5 mt-20">
        <!--begin::Card header-->
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
          <!--begin::Card title-->
          <div class="card-title">
            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative my-1">
              {{-- <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
              <input type="text" data-kt-ecommerce-order-filter="search"
              class="form-control form-control-solid w-175px w-md-350px ps-12" placeholder="Search Order" /> --}}
              <h2 class="ms-4">
                Customer Points Management
              </h2>
            </div>
            <!--end::Search-->
          </div>
          <!--end::Card title-->
          <!--begin::Card toolbar-->
          <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
            <h3 class="card-title align-items-center flex-column">
              <span class="card-label fw-bold fs-3 mb-1">{{ $points->value }}</span>
              <span class="text-muted fw-semibold fs-7">Value of 1 Point</span>
            </h3>
          </div>
          <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
          <!--begin::Input group-->
          <div class="d-flex gap-5 me-md-5">
            <div class="form-floating mb-7 col-md-6">
              <input type="number" class="form-control" id="floatingInput" placeholder="1"
                value="{{ $points->value }}" />
              <label for="floatingInput">Define Value of 1 Point</label>
              <p id="saveText" class="text-muted" style="display: none;">Click outside to save value</p>
            </div>
            <!--end::Input group-->
            <div class="form-floating mb-7 col-md-3 ">
              <input type="number" class="form-control" id="floatingInputForMinimunAmount" placeholder="0"
                value="{{ $points->minimum_amount }}" />
              <label for="floatingInput">Minimum Amount</label>
              <p id="saveTextFormAmount" class="text-muted" style="display: none;">Click outside to save value</p>
            </div>

            <div class="form-floating mb-7 col-md-3 ">
              <input type="number" class="form-control" id="floatingInputForMaxPoint" placeholder="0"
                value="{{ $points->max_points }}" />
              <label for="floatingInput">Maximun Allowed Points(%) for order value</label>
              <p id="saveTextForMaxPoint" class="text-muted" style="display: none;">Click outside to save value</p>
            </div>
          </div>
          <!--begin::Accordion-->
          <div class="accordion" id="kt_accordion_1">

            <div class="accordion-item border border-dashed border-gray-500 border-active active mb-2">
              <h2 class="accordion-header" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_1">
                <div
                  class="accordion-button fs-4 fw-semibold collapsed d-flex align-items-center flex-row-fluid flex-wrap py-2">
                  <!--begin::Section-->
                  <div class="flex-grow-1 me-2" id="kt_accordion_1_header_1" aria-expanded="false"
                    aria-controls="kt_accordion_1_body_1">
                    Point for user signup
                  </div>
                  <div class="form-check-success form-check form-switch form-check-custom form-check-solid me-5 me-sm-10">
                    <input class="form-check-input h-15px w-30px"
                      {{ $points->signup_points && $points->signup_points['status'] == 'active' ? 'checked' : '' }}
                      type="checkbox" value="" id="signupStatusInput" />
                  </div>
                  <button type="button" class="btn btn-primary fs-8 me-2 p-2" id="saveSignupPointsBtn">
                    Points <span
                      class="badge badge-circle badge-sm badge-dark ms-1">{{ $points->signup_points ? $points->signup_points['points'] : '' }}</span>
                  </button>
                  <!--end::Section-->
                </div>
              </h2>
              <div id="kt_accordion_1_body_1" class="accordion-collapse collapse"
                aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                <div class="accordion-body">
                  <form id="signupPointsForm" action="{{ route('points.updateSignupPoints') }}" method="POST">
                    @csrf
                    <!--begin:Input group-->
                    <div class="row gx-10">
                      <!--begin::Col-->
                      <div class="col px-3 py-3 rounded-2 me-3 mb-3">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Points</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Points per event"></i>
                        </label>
                        <!--end::Label-->
                        <!--begin::Title-->
                        <input type="text" value="{{ $points->signup_points ? $points->signup_points['points'] : '' }}"
                          class="form-control form-control-solid" placeholder="Points Credit" name="points" />
                        <!--end::Title-->
                      </div>
                      <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin:Input group-->
                    <div class="row gx-10">
                      <!--begin::Col-->
                      <div class="col px-3 py-3 rounded-2 me-3 mb-3">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Alert Message</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Points per event"></i>
                        </label>
                        <!--end::Label-->
                        <!--begin::Title-->
                        <input type="text"
                          value="{{ $points->signup_points ? $points->signup_points['alert_message'] : '' }}"
                          class="form-control form-control-solid" placeholder="Enter alert message that user will see"
                          name="alert_message" />
                        <!--end::Title-->
                      </div>
                      <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="text-end">
                      <button type="reset" class="btn btn-sm btn-light me-3"
                        data-kt-modal-action-type="cancel">Cancel
                      </button>
                      <button type="submit" class="btn btn-sm btn-primary" data-kt-modal-action-type="submit"
                        id="signupSubmitBtn">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                          <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                      </button>
                    </div>
                    <!--end::Actions-->
                  </form>
                </div>
              </div>
            </div>

            <div class="accordion-item border border-dashed border-gray-500 border-active active mb-2">
              <h2 class="accordion-header" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_2">
                <div
                  class="accordion-button fs-4 fw-semibold collapsed d-flex align-items-center flex-row-fluid flex-wrap py-2">
                  <!--begin::Section-->
                  <div class="flex-grow-1 me-2" id="kt_accordion_1_header_2" type="button" aria-expanded="false"
                    aria-controls="kt_accordion_1_body_2">
                    Point for user login
                  </div>
                  <div class="form-check-success form-check form-switch form-check-custom form-check-solid me-10">
                    <input class="form-check-input h-15px w-30px"
                      {{ $points->login_points['status'] == 'active' ? 'checked' : '' }} type="checkbox" value=""
                      id="loginStatusInput" />
                  </div>
                  <button class="btn btn-primary fs-8 me-2 p-2">
                    Points <span
                      class="badge badge-circle badge-sm badge-dark ms-1">{{ $points->login_points['points'] }}</span>
                  </button>
                  <!--end::Section-->
                </div>
              </h2>
              <div id="kt_accordion_1_body_2" class="accordion-collapse collapse"
                aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#kt_accordion_2">
                <div class="accordion-body">
                  <!--begin:Form-->
                  <form id="loginPointsForm" action="{{ route('points.updateLoginPoints') }}" method="POST">
                    @csrf
                    <!--begin::Input group-->
                    <div class="row gx-10">
                      <!--begin::Col-->
                      <div class="col px-3 py-3 rounded-2 me-3 mb-3">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Points</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            title="Points per login"></i>
                        </label>
                        <!--end::Label-->
                        <!--begin::Title-->
                        <input type="text" value="{{ $points->login_points['points'] }}"
                          class="form-control form-control-solid" placeholder="Expence Amount" name="points" />
                        <!--end::Title-->
                      </div>
                      <!--end::Col-->
                      <!--begin::Col-->
                      <div class="col px-3 py-3 rounded-2 me-3 mb-3">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Logins</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            title="No. of time user can login"></i>
                        </label>
                        <!--end::Label-->
                        <!--begin::Title-->
                        <input type="text" class="form-control form-control-solid"
                          value="{{ $points->login_points['logins'] }}" placeholder="Login Count" name="logins" />
                        <!--end::Title-->
                      </div>
                      <!--end::Col-->
                      <!--begin::Col-->
                      <div class="col px-3 py-3 rounded-2 me-3 mb-3">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Limit</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            title="Maximum points user will get"></i>
                        </label>
                        <!--end::Label-->
                        <!--begin::Select2-->
                        <select class="form-select" aria-label="Select example" name="limit">
                          <option value="day" {{ $points->login_points['limit'] === 'day' ? 'selected' : '' }}>/Day
                          </option>
                          <option value="week" {{ $points->login_points['limit'] === 'week' ? 'selected' : '' }}>/Week
                          </option>
                          <option value="month" {{ $points->login_points['limit'] === 'month' ? 'selected' : '' }}>
                            /Month</option>
                          <option value="total" {{ $points->login_points['limit'] === 'total' ? 'selected' : '' }}>/In
                            Total</option>
                        </select>
                        <!--end::Select2-->
                      </div>
                      <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row gx-10">
                      <!--begin::Col-->
                      <div class="col px-3 py-3 rounded-2 me-3 mb-3">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Alert Message</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            title="Points per event"></i>
                        </label>
                        <!--end::Label-->
                        <!--begin::Title-->
                        <input type="text" value="{{ $points->login_points['alert_message'] }}"
                          class="form-control form-control-solid" placeholder="Enter alert message that user will see"
                          name="alert_message" />
                        <!--end::Title-->
                      </div>
                      <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="text-end">
                      <button type="reset" class="btn btn-sm btn-light me-3"
                        data-kt-modal-action-type="cancel">Cancel</button>
                      <button type="submit" class="btn btn-sm btn-primary" data-kt-modal-action-type="submit"
                        id="loginSubmitBtn">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                          <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                      </button>
                    </div>
                    <!--end::Actions-->
                    <!--end::Modal body-->
                  </form>
                  <!--end:Form-->
                </div>
              </div>
            </div>

            <div class="accordion-item border border-dashed border-gray-500 border-active active mb-2">
              <h2 class="accordion-header" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_3">
                <div
                  class="accordion-button fs-4 fw-semibold collapsed d-flex align-items-center flex-row-fluid flex-wrap py-2">
                  <!--begin::Section-->
                  <div class="flex-grow-1 me-2" id="kt_accordion_1_header_3" type="button" aria-expanded="false"
                    aria-controls="kt_accordion_1_body_3">
                    Points for Orders
                  </div>
                  <div class="form-check-success form-check form-switch form-check-custom form-check-solid me-10">
                    <input class="form-check-input h-15px w-30px"
                      {{ $points->order_points['status'] == 'active' ? 'checked' : '' }} type="checkbox" value=""
                      id="ordersStatusInput" />
                  </div>
                  <button class="btn btn-primary fs-8 me-2 p-2">
                    Order <span
                      class="badge badge-circle badge-sm badge-dark ms-1">{{ $points->order_points['points'] }}</span>
                  </button>
                  <!--end::Section-->
                </div>
              </h2>
              <div id="kt_accordion_1_body_3" class="accordion-collapse collapse"
                aria-labelledby="kt_accordion_1_header_3" data-bs-parent="#kt_accordion_3">
                <div class="accordion-body">
                  <!--begin:Form-->
                  <form id="ordersPointsForm" action="{{ route('points.updateOrdersPoints') }}" method="POST">
                    @csrf
                    <!--begin::Input group-->
                    <div class="row gx-10">
                      <!--begin::Col-->
                      <div class="col px-3 py-3 rounded-2 me-3 mb-3">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Orders</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            title="Points for Order"></i>
                        </label>
                        <!--end::Label-->
                        <!--begin::Title-->
                        <input type="text" value="{{ $points->order_points['points'] }}"
                          class="form-control form-control-solid" placeholder="Point Earn" name="points" />
                        <!--end::Title-->
                      </div>
                      <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row gx-10">
                      <!--begin::Col-->
                      <div class="col px-3 py-3 rounded-2 me-3 mb-3">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Alert Message</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            title="Points per event"></i>
                        </label>
                        <!--end::Label-->
                        <!--begin::Title-->
                        <input type="text" value="{{ $points->order_points['alert_message'] }}"
                          class="form-control form-control-solid" placeholder="Enter alert message that user will see"
                          name="alert_message" />
                        <!--end::Title-->
                      </div>
                      <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="text-end">
                      <button type="reset" class="btn btn-sm btn-light me-3"
                        data-kt-modal-action-type="cancel">Cancel</button>
                      <button type="submit" class="btn btn-sm btn-primary" data-kt-modal-action-type="submit"
                        id="ordersSubmitBtn">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                          <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                      </button>
                    </div>
                    <!--end::Actions-->
                    <!--end::Modal body-->
                  </form>
                  <!--end:Form-->
                </div>
              </div>
            </div>

            <div class="accordion-item border border-dashed border-gray-500 border-active active mb-2">
              <h2 class="accordion-header" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_4">
                <div
                  class="accordion-button fs-4 fw-semibold collapsed d-flex align-items-center flex-row-fluid flex-wrap py-2">
                  <!--begin::Section-->
                  <div class="flex-grow-1 me-2" id="kt_accordion_1_header_4" type="button" aria-expanded="false"
                    aria-controls="kt_accordion_1_body_4">
                    Point for Review & Ratings
                  </div>
                  <div class="form-check-success form-check form-switch form-check-custom form-check-solid me-10">
                    <input class="form-check-input h-15px w-30px"
                      {{ $points->review_points['status'] == 'active' ? 'checked' : '' }} type="checkbox"
                      value="" id="reviewRatingsStatusInput" />
                  </div>
                  <button class="btn btn-primary fs-8 me-2 p-2">
                    Review <span
                      class="badge badge-circle badge-sm badge-dark ms-1">{{ $points->review_points['review_points'] }}</span>
                  </button>
                  <button class="btn btn-primary fs-8 me-2 p-2">
                    Ratings <span
                      class="badge badge-circle badge-sm badge-dark ms-1">{{ $points->review_points['ratings_points'] }}</span>
                  </button>
                  <!--end::Section-->
                </div>
              </h2>
              <div id="kt_accordion_1_body_4" class="accordion-collapse collapse"
                aria-labelledby="kt_accordion_1_header_4" data-bs-parent="#kt_accordion_4">
                <div class="accordion-body">
                  <!--begin:Form-->
                  <form id="reviewRatingsPointsForm">
                    <!--begin::Input group-->
                    <div class="row gx-10">
                      <!--begin::Col-->
                      <div class="col px-3 py-3 rounded-2 me-3 mb-3">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Review Points</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            title="Points per review"></i>
                        </label>
                        <!--end::Label-->
                        <!--begin::Title-->
                        <input type="text" value="{{ $points->review_points['review_points'] }}"
                          class="form-control form-control-solid" placeholder="Review Points" name="review_points" />
                        <!--end::Title-->
                      </div>
                      <!--end::Col-->
                      <!--begin::Col-->
                      <div class="col px-3 py-3 rounded-2 me-3 mb-3">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Ratings Points</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            title="Points per rating"></i>
                        </label>
                        <!--end::Label-->
                        <!--begin::Title-->
                        <input type="text" value="{{ $points->review_points['ratings_points'] }}"
                          class="form-control form-control-solid" placeholder="Ratings Points" name="ratings_points" />
                        <!--end::Title-->
                      </div>
                      <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row gx-10">
                      <!--begin::Col-->
                      <div class="col px-3 py-3 rounded-2 me-3 mb-3">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Alert Message</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            title="Alert message for users"></i>
                        </label>
                        <!--end::Label-->
                        <!--begin::Title-->
                        <input type="text" value="{{ $points->review_points['alert_message'] }}"
                          class="form-control form-control-solid" placeholder="Enter alert message for users"
                          name="alert_message" />
                        <!--end::Title-->
                      </div>
                      <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="text-end">
                      <button type="reset" class="btn btn-sm btn-light me-3"
                        data-kt-modal-action-type="cancel">Cancel
                      </button>
                      <button type="submit" id="reviewRatingsSubmitBtn" class="btn btn-sm btn-primary"
                        data-kt-modal-action-type="submit">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                          <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                      </button>
                    </div>
                    <!--end::Actions-->
                    <!--end::Modal body-->
                  </form>
                  <!--end:Form-->
                </div>
              </div>
            </div>

            <div class="accordion-item border border-dashed border-gray-500 border-active active mb-2">
              <h2 class="accordion-header" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_5">
                <div
                  class="accordion-button fs-4 fw-semibold collapsed d-flex align-items-center flex-row-fluid flex-wrap py-2">
                  <!--begin::Section-->
                  <div class="flex-grow-1 me-2" id="kt_accordion_1_header_5" type="button" aria-expanded="false"
                    aria-controls="kt_accordion_1_body_5">
                    Points on Birthday
                  </div>
                  <div class="form-check-success form-check form-switch form-check-custom form-check-solid me-10">
                    <input class="form-check-input h-15px w-30px"
                      {{ $points->birthday_points['status'] == 'active' ? 'checked' : '' }} type="checkbox"
                      value="" id="birthdayStatusInput" />
                  </div>
                  <button class="btn btn-primary fs-8 me-2 p-2">
                    Order <span
                      class="badge badge-circle badge-sm badge-dark ms-1">{{ $points->birthday_points['points'] }}</span>
                  </button>
                  <!--end::Section-->
                </div>
              </h2>
              <div id="kt_accordion_1_body_5" class="accordion-collapse collapse"
                aria-labelledby="kt_accordion_1_header_5" data-bs-parent="#kt_accordion_5">
                <div class="accordion-body">
                  <!--begin:Form-->
                  <form id="birthdayPointsForm" action="{{ route('points.updateBirthdayPoints') }}" method="POST">
                    @csrf
                    <!--begin::Input group-->
                    <div class="row gx-10">
                      <!--begin::Col-->
                      <div class="col px-3 py-3 rounded-2 me-3 mb-3">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Birthday</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            title="Points for Order"></i>

                        </label>
                        <!--end::Label-->
                        <!--begin::Title-->
                        <input type="text" value="{{ $points->birthday_points['points'] }}"
                          class="form-control form-control-solid" placeholder="Point Earn" name="points" />
                        <!--end::Title-->
                      </div>
                      <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row gx-10">
                      <!--begin::Col-->
                      <div class="col px-3 py-3 rounded-2 me-3 mb-3">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Alert Message</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            title="Points per event"></i>
                        </label>
                        <!--end::Label-->
                        <!--begin::Title-->
                        <input type="text" value="{{ $points->birthday_points['alert_message'] }}"
                          class="form-control form-control-solid" placeholder="Enter alert message that user will see"
                          name="alert_message" />
                        <!--end::Title-->
                      </div>
                      <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="text-end">
                      <button type="reset" class="btn btn-sm btn-light me-3"
                        data-kt-modal-action-type="cancel">Cancel</button>
                      <button type="submit" class="btn btn-sm btn-primary" data-kt-modal-action-type="submit"
                        id="birthdaySubmitBtn">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                          <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                      </button>
                    </div>
                    <!--end::Actions-->
                    <!--end::Modal body-->
                  </form>
                  <!--end:Form-->
                </div>
              </div>
            </div>

            <div class="accordion-item border border-dashed border-gray-500 border-active active mb-2">
              <h2 class="accordion-header" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_play_area">
                <div
                  class="accordion-button fs-4 fw-semibold collapsed d-flex align-items-center flex-row-fluid flex-wrap py-2">
                  <!--begin::Section-->
                  <div class="flex-grow-1 me-2" id="kt_accordion_1_header_5" type="button" aria-expanded="false"
                    aria-controls="kt_accordion_1_body_5">
                    Points for Play area
                  </div>
                  <div class="form-check-success form-check form-switch form-check-custom form-check-solid me-10">
                    <input class="form-check-input h-15px w-30px"
                      {{ $points->play_area_points['status'] == 'active' ? 'checked' : '' }} type="checkbox"
                      value="" id="playAreaStatusInput" />
                  </div>
                  <button class="btn btn-primary fs-8 me-2 p-2">
                    Points <span
                      class="badge badge-circle badge-sm badge-dark ms-1">{{ $points->play_area_points['points'] }}</span>
                  </button>
                  <!--end::Section-->
                </div>
              </h2>
              <div id="kt_accordion_1_body_play_area" class="accordion-collapse collapse"
                aria-labelledby="kt_accordion_1_header_5" data-bs-parent="#kt_accordion_5">
                <div class="accordion-body">
                  <!--begin:Form-->
                  <form id="playAreaPointsForm" action="{{ route('points.updatePlayAreaPoints') }}" method="POST">
                    @csrf
                    <!--begin::Input group-->
                    <div class="row gx-10">
                      <!--begin::Col-->
                      <div class="col px-3 py-3 rounded-2 me-3 mb-3">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Play Area</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            title="Points for Order"></i>

                        </label>
                        <!--end::Label-->
                        <!--begin::Title-->
                        <input type="text" value="{{ $points->play_area_points['points'] }}"
                          class="form-control form-control-solid" placeholder="Point Earn" name="points" />
                        <!--end::Title-->
                      </div>
                      <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row gx-10">
                      <!--begin::Col-->
                      <div class="col px-3 py-3 rounded-2 me-3 mb-3">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Alert Message</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            title="Points per event"></i>
                        </label>
                        <!--end::Label-->
                        <!--begin::Title-->
                        <input type="text" value="{{ $points->play_area_points['alert_message'] }}"
                          class="form-control form-control-solid" placeholder="Enter alert message that user will see"
                          name="alert_message" />
                        <!--end::Title-->
                      </div>
                      <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="text-end">
                      <button type="reset" class="btn btn-sm btn-light me-3"
                        data-kt-modal-action-type="cancel">Cancel</button>
                      <button type="submit" class="btn btn-sm btn-primary" data-kt-modal-action-type="submit"
                        id="playAreaSubmitBtn">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                          <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                      </button>
                    </div>
                    <!--end::Actions-->
                    <!--end::Modal body-->
                  </form>
                  <!--end:Form-->
                </div>
              </div>
            </div>

            <div class="accordion-item border border-dashed border-gray-500 border-active active mb-2">
              <h2 class="accordion-header" data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_event">
                <div
                  class="accordion-button fs-4 fw-semibold collapsed d-flex align-items-center flex-row-fluid flex-wrap py-2">
                  <!--begin::Section-->
                  <div class="flex-grow-1 me-2" id="kt_accordion_1_header_5" type="button" aria-expanded="false"
                    aria-controls="kt_accordion_1_body_5">
                    Points for Events
                  </div>
                  <div class="form-check-success form-check form-switch form-check-custom form-check-solid me-10">
                    <input class="form-check-input h-15px w-30px"
                      {{ $points->event_points['status'] == 'active' ? 'checked' : '' }} type="checkbox" value=""
                      id="eventStatusInput" />
                  </div>
                  <button class="btn btn-primary fs-8 me-2 p-2">
                    Points <span
                      class="badge badge-circle badge-sm badge-dark ms-1">{{ $points->event_points['points'] }}</span>
                  </button>
                  <!--end::Section-->
                </div>
              </h2>
              <div id="kt_accordion_1_body_event" class="accordion-collapse collapse"
                aria-labelledby="kt_accordion_1_header_5" data-bs-parent="#kt_accordion_5">
                <div class="accordion-body">
                  <!--begin:Form-->
                  <form id="eventPointsForm" action="{{ route('points.updateEventPoints') }}" method="POST">
                    @csrf
                    <!--begin::Input group-->
                    <div class="row gx-10">
                      <!--begin::Col-->
                      <div class="col px-3 py-3 rounded-2 me-3 mb-3">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Event</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            title="Points for Order"></i>

                        </label>
                        <!--end::Label-->
                        <!--begin::Title-->
                        <input type="text" value="{{ $points->event_points['points'] }}"
                          class="form-control form-control-solid" placeholder="Point Earn" name="points" />
                        <!--end::Title-->
                      </div>
                      <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Input group-->
                    <div class="row gx-10">
                      <!--begin::Col-->
                      <div class="col px-3 py-3 rounded-2 me-3 mb-3">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Alert Message</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            title="Points per event"></i>
                        </label>
                        <!--end::Label-->
                        <!--begin::Title-->
                        <input type="text" value="{{ $points->event_points['alert_message'] }}"
                          class="form-control form-control-solid" placeholder="Enter alert message that user will see"
                          name="alert_message" />
                        <!--end::Title-->
                      </div>
                      <!--end::Col-->
                    </div>
                    <!--end::Input group-->
                    <!--begin::Actions-->
                    <div class="text-end">
                      <button type="reset" class="btn btn-sm btn-light me-3"
                        data-kt-modal-action-type="cancel">Cancel</button>
                      <button type="submit" class="btn btn-sm btn-primary" data-kt-modal-action-type="submit"
                        id="eventSubmitBtn">
                        <span class="indicator-label">Submit</span>
                        <span class="indicator-progress">Please wait...
                          <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                      </button>
                    </div>
                    <!--end::Actions-->
                    <!--end::Modal body-->
                  </form>
                  <!--end:Form-->
                </div>
              </div>
            </div>

          </div>
          <!--end::Accordion-->
        </div>
        <!--end::Card body-->
      </div>
      <!--end::Products-->
    </div>
    <!--end::Content container-->
  </div>
@endsection
@section('scripts')
  <script>
    $(document).ready(function() {
      $("#floatingInput").click(function() {
        $('#saveText').show();
      })
      $("#floatingInput").change(function() {
        var newValue = $(this).val();
        var csrfToken = $('meta[name="csrf-token"]').attr("content"); // Fetch CSRF token value
        $.ajax({
          url: "{{ route('points.updateValue') }}",
          type: "PUT",
          data: {
            value: newValue,
          },
          headers: {
            "X-CSRF-TOKEN": csrfToken, // Set CSRF token as header
          },
          success: function(response) {
            if (response.success) {
              // console.log("Value updated successfully");
              Swal.fire({
                text: "Value updated successfully!",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              })
            }
          },
          error: function(xhr, status, error) {
            console.error("Error:", error);
          },
        });
      });

      $('#signupPointsForm').submit(function(event) {
        event.preventDefault(); // Prevent form submission

        // Serialize form data including status
        var formData = $(this).serializeArray();
        var status = $('#signupStatusInput').prop('checked') ? 'active' : 'inactive';
        formData.push({
          name: 'status',
          value: status
        });

        // Disable submit button and show loading indicator
        $('#signupSubmitBtn').prop('disabled', true);
        $('#signupSubmitBtn').attr("data-kt-indicator", "on");

        // Convert serialized array to object
        var data = {};
        formData.forEach(function(item) {
          data[item.name] = item.value;
        });

        // Send AJAX request to update points
        $.ajax({
          url: "{{ route('points.updateSignupPoints') }}",
          type: 'POST',
          data: data,
          success: function(response) {
            console.log(response);
            if (response.success) {
              Swal.fire({
                text: "Form has been successfully submitted!",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              }).then(function(result) {
                if (result.isConfirmed) {
                  location.reload();
                }
              });
            }
          },
          error: function(error) {
            var errors = error.responseJSON.errors;
            console.log(error);
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
          },
          complete: function() {
            // Re-enable submit button and hide loading indicator
            $('#signupSubmitBtn').prop('disabled', false);
            $('#signupSubmitBtn').removeAttr("data-kt-indicator");
          }
        });
      });

      $('#signupStatusInput').change(function() {
        // Get the new status value
        var newStatus = $(this).prop('checked') ? 'active' : 'inactive';
        var formData = {
          status: newStatus,
        };
        $.ajax({
          url: "{{ route('points.updateSignupStatus') }}", // Replace with your route
          type: 'POST',
          data: formData,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.success) {
              Swal.fire({
                text: "Status changed successfully!",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              })
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

    });
  </script>
  <script>
    $(document).ready(function() {
      $("#floatingInputForMinimunAmount").click(function() {
        $('#saveTextFormAmount').show();
      })
      $("#floatingInputForMinimunAmount").change(function() {
        var newValue = $(this).val();
        var csrfToken = $('meta[name="csrf-token"]').attr("content"); // Fetch CSRF token value
        $.ajax({
          url: "{{ route('points.updateMinimumAmount') }}",
          type: "PUT",
          data: {
            value: newValue,
          },
          headers: {
            "X-CSRF-TOKEN": csrfToken, // Set CSRF token as header
          },
          success: function(response) {
            if (response.success) {
              // console.log("Value updated successfully");
              Swal.fire({
                text: "Minimum Amount changed successfully!",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              })
            }
          },
          error: function(xhr, status, error) {
            console.error("Error:", error);
          },
        });
      });

    });
  </script>
  <script>
    $(document).ready(function() {
      $("#floatingInputForMaxPoint").click(function() {
        $('#saveTextForMaxPoint').show();
      })
      $("#floatingInputForMaxPoint").change(function() {
        var newValue = $(this).val();
        var csrfToken = $('meta[name="csrf-token"]').attr("content"); // Fetch CSRF token value
        $.ajax({
          url: "{{ route('points.updateMaxPoint') }}",
          type: "PUT",
          data: {
            value: newValue,
          },
          headers: {
            "X-CSRF-TOKEN": csrfToken, // Set CSRF token as header
          },
          success: function(response) {
            if (response.success) {
              // console.log("Value updated successfully");
              Swal.fire({
                text: "Max points updated successfully!",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              })
            }
          },
          error: function(xhr, status, error) {
            console.error("Error:", error);
          },
        });
      });

    });
  </script>
  <script>
    $(document).ready(function() {
      function sendAjaxRequest(url, data, successMessage) {
        $.ajax({
          url: url,
          type: 'POST',
          data: data,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.success) {
              Swal.fire({
                text: successMessage,
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              }).then(function(result) {
                if (result.isConfirmed) {
                  location.reload();
                }
              });
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
      }

      $('#loginPointsForm').submit(function(event) {
        event.preventDefault(); // Prevent form submission

        // Serialize form data including status
        var formData = $(this).serializeArray();
        var status = $('#loginStatusInput').prop('checked') ? 'active' : 'inactive';
        formData.push({
          name: 'status',
          value: status
        });

        // Disable submit button and show loading indicator
        $('#loginSubmitBtn').prop('disabled', true);
        $('#loginSubmitBtn').attr("data-kt-indicator", "on");

        // Convert serialized array to object
        var data = {};
        formData.forEach(function(item) {
          data[item.name] = item.value;
        });

        // Send AJAX request to update points
        sendAjaxRequest("{{ route('points.updateLoginPoints') }}", data, "Login Points updated successfully!");

        // Re-enable submit button and hide loading indicator
        $('#loginSubmitBtn').prop('disabled', false);
        $('#loginSubmitBtn').removeAttr("data-kt-indicator");
      });

      $('#loginStatusInput').change(function() {
        // Get the new status value
        var newStatus = $(this).prop('checked') ? 'active' : 'inactive';
        var formData = {
          status: newStatus,
        };

        // Send AJAX request to update status
        sendAjaxRequest("{{ route('points.updateLoginStatus') }}", formData,
          "Login Status changed successfully!");
      });


      // Handle form submission for orders points
      $('#ordersPointsForm').submit(function(event) {
        event.preventDefault(); // Prevent form submission

        // Serialize form data including status
        var formData = $(this).serializeArray();
        var status = $('#ordersStatusInput').prop('checked') ? 'active' : 'inactive';
        formData.push({
          name: 'status',
          value: status
        });

        // Disable submit button and show loading indicator
        $('#ordersSubmitBtn').prop('disabled', true);
        $('#ordersSubmitBtn').attr("data-kt-indicator", "on");

        // Convert serialized array to object
        var data = {};
        formData.forEach(function(item) {
          data[item.name] = item.value;
        });

        // Send AJAX request to update points
        $.ajax({
          url: "{{ route('points.updateOrdersPoints') }}",
          type: 'POST',
          data: data,
          success: function(response) {
            if (response.success) {
              Swal.fire({
                text: "Form has been successfully submitted!",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              }).then(function(result) {
                if (result.isConfirmed) {
                  location.reload();
                }
              });
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
          },
          complete: function() {
            // Re-enable submit button and hide loading indicator
            $('#ordersSubmitBtn').prop('disabled', false);
            $('#ordersSubmitBtn').removeAttr("data-kt-indicator");
          }
        });
      });

      // Handle status change for orders points
      $('#ordersStatusInput').change(function() {
        // Get the new status value
        var newStatus = $(this).prop('checked') ? 'active' : 'inactive';
        var formData = {
          status: newStatus,
        };
        $.ajax({
          url: "{{ route('points.updateOrdersStatus') }}", // Replace with your route
          type: 'POST',
          data: formData,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.success) {
              Swal.fire({
                text: "Status changed successfully!",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              })
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


      // Update Review & Ratings Points
      $('#reviewRatingsPointsForm').submit(function(event) {
        event.preventDefault(); // Prevent form submission

        // Serialize form data including status
        var formData = $(this).serializeArray();
        var status = $('#reviewRatingsStatusInput').prop('checked') ? 'active' : 'inactive';
        formData.push({
          name: 'status',
          value: status
        });

        // Disable submit button and show loading indicator
        $('#reviewRatingsSubmitBtn').prop('disabled', true);
        $('#reviewRatingsSubmitBtn').attr("data-kt-indicator", "on");

        // Convert serialized array to object
        var data = {};
        formData.forEach(function(item) {
          data[item.name] = item.value;
        });

        // Send AJAX request to update points
        $.ajax({
          url: "{{ route('points.updateReviewAndRatingsPoints') }}",
          type: 'POST',
          data: data,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.success) {
              Swal.fire({
                text: "Form has been successfully submitted!",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              }).then(function(result) {
                if (result.isConfirmed) {
                  location.reload();
                }
              });
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
          },
          complete: function() {
            // Re-enable submit button and hide loading indicator
            $('#reviewRatingsSubmitBtn').prop('disabled', false);
            $('#reviewRatingsSubmitBtn').removeAttr("data-kt-indicator");
          }
        });
      });

      // Update Review & Ratings Status
      $('#reviewRatingsStatusInput').change(function() {
        // Get the new status value
        var newStatus = $(this).prop('checked') ? 'active' : 'inactive';
        var formData = {
          status: newStatus,
        };
        $.ajax({
          url: "{{ route('points.updateReviewAndRatingsStatus') }}", // Assuming you have a route named 'points.updateReviewAndRatingsStatus'
          type: 'POST',
          data: formData,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.success) {
              Swal.fire({
                text: "Status updated successfully!",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              })
            }
          },
          error: function(error) {
            console.error(error);
            Swal.fire({
              text: "An error occurred while updating the status.",
              icon: "error",
              buttonsStyling: false,
              confirmButtonText: "Ok, got it!",
              customClass: {
                confirmButton: "btn btn-primary",
              },
            })
          }
        });
      });

      $('#playAreaStatusInput').change(function() {
        // Get the new status value
        var newStatus = $(this).prop('checked') ? 'active' : 'inactive';
        var formData = {
          status: newStatus,
        };
        $.ajax({
          url: "{{ route('points.updatePlayAreaStatus') }}", // Assuming you have a route named 'points.updateReviewAndRatingsStatus'
          type: 'POST',
          data: formData,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.success) {
              Swal.fire({
                text: "Status updated successfully!",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              })
            }
          },
          error: function(error) {
            console.error(error);
            Swal.fire({
              text: "An error occurred while updating the status.",
              icon: "error",
              buttonsStyling: false,
              confirmButtonText: "Ok, got it!",
              customClass: {
                confirmButton: "btn btn-primary",
              },
            })
          }
        });
      });

      $('#playAreaPointsForm').submit(function(event) {
        event.preventDefault(); // Prevent form submission

        // Serialize form data including status
        var formData = $(this).serializeArray();
        var status = $('#playAreaStatusInput').prop('checked') ? 'active' : 'inactive';
        formData.push({
          name: 'status',
          value: status
        });

        // Disable submit button and show loading indicator
        $('#playAreaSubmitBtn').prop('disabled', true);
        $('#playAreaSubmitBtn').attr("data-kt-indicator", "on");

        // Convert serialized array to object
        var data = {};
        formData.forEach(function(item) {
          data[item.name] = item.value;
        });

        // Send AJAX request to update points
        $.ajax({
          url: "{{ route('points.updatePlayAreaPoints') }}",
          type: 'POST',
          data: data,
          success: function(response) {
            if (response.success) {
              Swal.fire({
                text: "Form has been successfully submitted!",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              }).then(function(result) {
                if (result.isConfirmed) {
                  location.reload();
                }
              });
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
          },
          complete: function() {
            // Re-enable submit button and hide loading indicator
            $('#playAreaSubmitBtn').prop('disabled', false);
            $('#playAreaSubmitBtn').removeAttr("data-kt-indicator");
          }
        });
      });


      $('#eventStatusInput').change(function() {
        // Get the new status value
        var newStatus = $(this).prop('checked') ? 'active' : 'inactive';
        var formData = {
          status: newStatus,
        };
        $.ajax({
          url: "{{ route('points.updateEventStatus') }}", // Assuming you have a route named 'points.updateReviewAndRatingsStatus'
          type: 'POST',
          data: formData,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.success) {
              Swal.fire({
                text: "Status updated successfully!",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              })
            }
          },
          error: function(error) {
            console.error(error);
            Swal.fire({
              text: "An error occurred while updating the status.",
              icon: "error",
              buttonsStyling: false,
              confirmButtonText: "Ok, got it!",
              customClass: {
                confirmButton: "btn btn-primary",
              },
            })
          }
        });
      });

      $('#eventPointsForm').submit(function(event) {
        event.preventDefault(); // Prevent form submission

        // Serialize form data including status
        var formData = $(this).serializeArray();
        var status = $('#eventStatusInput').prop('checked') ? 'active' : 'inactive';
        formData.push({
          name: 'status',
          value: status
        });

        // Disable submit button and show loading indicator
        $('#eventSubmitBtn').prop('disabled', true);
        $('#eventSubmitBtn').attr("data-kt-indicator", "on");

        // Convert serialized array to object
        var data = {};
        formData.forEach(function(item) {
          data[item.name] = item.value;
        });

        // Send AJAX request to update points
        $.ajax({
          url: "{{ route('points.updateEventPoints') }}",
          type: 'POST',
          data: data,
          success: function(response) {
            if (response.success) {
              Swal.fire({
                text: "Form has been successfully submitted!",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              }).then(function(result) {
                if (result.isConfirmed) {
                  location.reload();
                }
              });
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
          },
          complete: function() {
            // Re-enable submit button and hide loading indicator
            $('#eventSubmitBtn').prop('disabled', false);
            $('#eventSubmitBtn').removeAttr("data-kt-indicator");
          }
        });
      });

      $('#birthdayPointsForm').submit(function(event) {
        event.preventDefault(); // Prevent form submission

        // Serialize form data including status
        var formData = $(this).serializeArray();
        var status = $('#birthdayStatusInput').prop('checked') ? 'active' : 'inactive';
        formData.push({
          name: 'status',
          value: status
        });

        // Disable submit button and show loading indicator
        $('#birthdaySubmitBtn').prop('disabled', true);
        $('#birthdaySubmitBtn').attr("data-kt-indicator", "on");

        // Convert serialized array to object
        var data = {};
        formData.forEach(function(item) {
          data[item.name] = item.value;
        });

        // Send AJAX request to update points
        $.ajax({
          url: "{{ route('points.updateBirthdayPoints') }}",
          type: 'POST',
          data: data,
          success: function(response) {
            if (response.success) {
              Swal.fire({
                text: "Form has been successfully submitted!",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              }).then(function(result) {
                if (result.isConfirmed) {
                  location.reload();
                }
              });
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
          },
          complete: function() {
            // Re-enable submit button and hide loading indicator
            $('#birthdaySubmitBtn').prop('disabled', false);
            $('#birthdaySubmitBtn').removeAttr("data-kt-indicator");
          }
        });
      });

      $('#birthdayStatusInput').change(function() {
        // Get the new status value
        var newStatus = $(this).prop('checked') ? 'active' : 'inactive';
        var formData = {
          status: newStatus,
        };
        $.ajax({
          url: "{{ route('points.updateBirthdayStatus') }}", // Replace with your route
          type: 'POST',
          data: formData,
          headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
          },
          success: function(response) {
            if (response.success) {
              Swal.fire({
                text: "Status changed successfully!",
                icon: "success",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              })
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


    });
  </script>
@endsection
