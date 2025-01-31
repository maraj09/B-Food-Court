<!-- Modal-->
<div class="modal fade" id="shareBirthdayModal" data-keyboard="false" tabindex="-1" role="dialog"
  aria-labelledby="shareDetailsLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form class="form w-100" action="/settings-birthday" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="shareDetailsLabel">Unlock Special Offers
          </h5>
          <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
          </div>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <!--begin::Name-->
            <label for="name" class="form-label">Date of birth</label>
            <div class="position-relative d-flex align-items-center">
              <!--begin::Icon-->
              <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4"><span class="path1"></span><span
                  class="path2"></span><span class="path3"></span><span class="path4"></span><span
                  class="path5"></span><span class="path6"></span></i> <!--end::Icon-->

              <!--begin::Datepicker-->
              <input
                class="form-control form-control-solid ps-12 flatpickr-input @error('date_of_birth') is-invalid @enderror"
                placeholder="Select Birthday" name="date_of_birth" type="date" readonly="readonly"
                id="kt_datepicker_dob_custom_today">
              <!--end::Datepicker-->
            </div>
            @error('date_of_birth')
              <div class="text-danger">{{ $message }}</div>
            @enderror
            <label class="fs-8 fw-semibold mt-3 text-info" for="">For Special Offer & Gift</label>
            <!--end::Name-->
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary font-weight-bold w-100">Save changes</button>
        </div>
      </div>
    </form>
  </div>
</div>
