<div class="modal fade" tabindex="-1" id="kt_modal_stacked_1">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h3 class="modal-title">Create a log</h3>
        <!--begin::Close-->
        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
          <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
        </div>
        <!--end::Close-->
      </div>

      <form method="POST" action="{{ route('admin.customer-point-logs.store') }}">
        @csrf

        <div class="modal-body">
          <div class="fv-row fv-plugins-icon-container mb-8">
            <label class="required fs-6 fw-semibold mb-2">Select Customer</label>
            <select id="kt_docs_select2_badge" class="form-select w-100px w-md-250px rounded-start-0 fs-6"
              name="user_id" data-placeholder="Select Customer">
              <option></option>
              @foreach ($customers as $user)
                <option value="{{ $user->id }}" data-kt-select2-badge="{{ $user->point->points ?? 0 }} Points"
                  data-kt-customer-info="{{ $user->phone }}">{{ $user->name }}
                </option>
              @endforeach
            </select>
            @error('user_id')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="fv-row fv-plugins-icon-container mb-8">
            <label class="required fs-6 fw-semibold mb-2">Enter Points</label>
            <input type="text" class="form-control form-control-solid" name="points"
              placeholder="+ to Credit or - to Debit Points" />
            @error('points')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>

          <div class="fv-row fv-plugins-icon-container mb-8">
            <label class="required fs-6 fw-semibold mb-2">Enter Massage Details</label>
            <input type="text" name="details" class="form-control form-control-solid" placeholder="Message Detail" />
            @error('details')
              <div class="text-danger">{{ $message }}</div>
            @enderror
          </div>
        </div>

        <div class="modal-footer">
          <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
          <button type="submit" class="btn btn-primary">Save changes</button>
        </div>
      </form>
    </div>
  </div>
</div>
