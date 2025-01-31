<div class="card">
  <!--begin::Form-->
  <form id="kt_project_settings_form" action="/dashboard/settings/store" enctype="multipart/form-data" method="POST"
    class="form fv-plugins-bootstrap5 fv-plugins-framework" novalidate="novalidate">
    @csrf
    <!--begin::Card body-->
    <div class="card-body p-9">
      <!-- Website Info -->
      <div class="row mb-7">
        <div class="col-xl-3">
          <div class="fs-6 fw-semibold mt-2 mb-3">Tax Information</div>
        </div>
        <div class="col-xl-3 fv-row fv-plugins-icon-container">
          <div class="d-flex">
            <input type="number" class="form-control form-control-solid me-3" name="gst"
              value="{{ $settings->gst ?? 0 }}">
            <div class="form-check form-check-custom form-check-solid form-check-sm w-250px">
              <input class="form-check-input" {{ $settings->gst_admin ? 'checked' : '' }} type="checkbox"
                name="gst_admin" value="1" id="gstAdmin" />
              <label class="form-check-label" for="gstAdmin">
                Add to admin
              </label>
            </div>
          </div>
          <div class="fv-plugins-message-container invalid-feedback">{{ $errors->first('gst') }}</div>
          <div class="form-text">GST</div>
        </div>
        <div class="col-xl-3 fv-row fv-plugins-icon-container">
          <div class="d-flex">
            <input type="number" class="form-control form-control-solid me-3" name="sgt"
              value="{{ $settings->sgt ?? 0 }}">
            <div class="form-check form-check-custom form-check-solid form-check-sm w-250px">
              <input class="form-check-input" {{ $settings->sgt_admin ? 'checked' : '' }} type="checkbox"
                name="sgt_admin" value="1" id="sgtAdmin" />
              <label class="form-check-label" for="sgtAdmin">
                Add to admin
              </label>
            </div>
          </div>
          <div class="fv-plugins-message-container invalid-feedback">{{ $errors->first('sgt') }}</div>
          <div class="form-text">SGT</div>
        </div>
        <div class="col-xl-3 fv-row fv-plugins-icon-container">
          <input type="number" class="form-control form-control-solid" name="service_tax"
            value="{{ $settings->service_tax ?? 0 }}">
          <div class="fv-plugins-message-container invalid-feedback">{{ $errors->first('service_tax') }}</div>
          <div class="form-text">SERVICE TAX</div>
        </div>
      </div>

      <!-- Status -->
      <div class="row">
        <div class="col-xl-3">
          <div class="fs-6 fw-semibold mt-2 mb-3">Payment Mode</div>
        </div>
        <div class="col-xl-3">
          <div class="form-check form-switch form-check-custom form-check-solid">
            <label class="form-check-label fw-semibold text-gray-400 me-3"
              for="payment_mode_upi_status">Inactive</label>
            <input class="form-check-input" type="checkbox" value="1" id="payment_mode_upi_status"
              name="payment_mode_upi_status"
              {{ isset($settings->payment_mode_upi_status) && $settings->payment_mode_upi_status ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold text-gray-400 ms-3" for="payment_mode_upi_status">Active</label>
          </div>
          <div class="form-text">UPI</div>
          <div class="fv-plugins-message-container invalid-feedback">
            {{ $errors->first('payment_mode_upi_status') }}
          </div>
        </div>
        <div class="col-xl-3">
          <div class="form-check form-switch form-check-custom form-check-solid">
            <label class="form-check-label fw-semibold text-gray-400 me-3"
              for="payment_mode_cash_status">Inactive</label>
            <input class="form-check-input" type="checkbox" value="1" id="payment_mode_cash_status"
              name="payment_mode_cash_status"
              {{ isset($settings->payment_mode_cash_status) && $settings->payment_mode_cash_status ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold text-gray-400 ms-3" for="payment_mode_cash_status">Active</label>
          </div>
          <div class="form-text">Cash</div>
          <div class="fv-plugins-message-container invalid-feedback">
            {{ $errors->first('payment_mode_cash_status') }}
          </div>
        </div>
        <div class="col-xl-3">
          <div class="form-check form-switch form-check-custom form-check-solid">
            <label class="form-check-label fw-semibold text-gray-400 me-3"
              for="payment_mode_card_status">Inactive</label>
            <input class="form-check-input" type="checkbox" value="1" id="payment_mode_card_status"
              name="payment_mode_card_status"
              {{ isset($settings->payment_mode_card_status) && $settings->payment_mode_card_status ? 'checked' : '' }}>
            <label class="form-check-label fw-semibold text-gray-400 ms-3" for="payment_mode_card_status">Active</label>
          </div>
          <div class="form-text">Card</div>
          <div class="fv-plugins-message-container invalid-feedback">
            {{ $errors->first('payment_mode_card_status') }}
          </div>
        </div>
      </div>
    </div>
    <!--end::Card body-->
    <!--begin::Card footer-->
    <div class="card-footer d-flex justify-content-end py-6 px-9">
      <button type="reset" class="btn btn-light btn-active-light-primary me-2">Discard</button>
      <button type="submit" class="btn btn-primary" id="kt_project_settings_submit">Save Changes</button>
    </div>
    <!--end::Card footer-->
  </form>
  <!--end:Form-->
</div>
