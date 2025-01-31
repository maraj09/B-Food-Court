<div id="kt_drawer_expence" class="bg-body" data-kt-drawer="true" data-kt-drawer-name="chat" data-kt-drawer-activate="true"
  data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}" data-kt-drawer-direction="end"
  data-kt-drawer-toggle="#kt_drawer_expence_button" data-kt-drawer-close="#kt_drawer_expence_close">
  <!--begin::Messenger-->
  <form id="expense-form" action="/dashboard/expenses/store" method="POST" enctype="multipart/form-data">
    @csrf
    <div class="card w-100 border-0 rounded-0 h-100" id="kt_drawer_chat_messenger">
      <!--begin::Card header-->
      <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
        <!--begin::Title-->
        <div class="card-title">
          <!--begin::User-->
          <div class="d-flex justify-content-center flex-column me-3">

            <!--begin::Info-->
            <div class="mb-0 lh-1">
              <span class="badge badge-success badge-circle w-10px h-10px me-1" data-bs-toggle="tooltip"
                data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Add Expense"></span>
              <span class="fs-7 fw-semibold text-muted">Add Expense</span>
            </div>
            <!--end::Info-->
          </div>
          <!--end::User-->
        </div>
        <!--end::Title-->
        <!--begin::Card toolbar-->
        <div class="card-toolbar">
          <!--begin::User Info-->

          <!--end::User Info-->
          <!--begin::Close-->
          <div class="btn btn-sm btn-icon btn-active-color-primary" id="kt_drawer_expence_close">
            <i class="ki-duotone ki-cross-square fs-2">
              <span class="path1"></span>
              <span class="path2"></span>
            </i>
          </div>
          <!--end::Close-->
        </div>
        <!--end::Card toolbar-->
      </div>
      <!--end::Card header-->
      <!--begin::Card body-->
      <div class="card-body px-10 scroll h-auto">
        <!--begin::row-->
        <div class="row g-9 mb-7">
          <!--begin::Col-->
          <div class="col-md-6 fv-row">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2 required ">Expense Title</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input class="form-control form-control-solid @error('title') is-invalid @enderror"
              placeholder="Expense Title" name="title" value="{{ old('title') }}">
            <!--end::Input-->
            @error('title')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <!--end::Col-->
          <!--begin::Col-->
          <div class="col-md-6 fv-row">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2 required ">Expense Amount</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input type="number" class="form-control form-control-solid @error('amount') is-invalid @enderror"
              placeholder="Expense Amount" name="amount" value="{{ old('amount') }}">
            <!--end::Input-->
            @error('amount')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <!--end::Col-->
        </div>
        <!--end::row--><!--begin::row-->
        <div class="row g-9 mb-7">
          <!--begin::Col-->

          <div class="col-md-6 fv-row">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2">Expense Date</label>
            <!--end::Label-->
            <!--begin::Input-->
            <div class="position-relative d-flex align-items-center">
              <!--begin::Icon-->
              <i class="ki-duotone ki-calendar-8 fs-2 position-absolute mx-4"><span class="path1"></span><span
                  class="path2"></span><span class="path3"></span><span class="path4"></span><span
                  class="path5"></span><span class="path6"></span></i> <!--end::Icon-->

              <!--begin::Datepicker-->
              <input
                class="form-control form-control-solid ps-12 flatpickr-input @error('created_at') is-invalid @enderror"
                placeholder="Select a date" name="created_at" type="date" readonly="readonly"
                id="kt_datepicker_dob_custom" value="{{ old('created_at') }}">
              <!--end::Datepicker-->
              @error('created_at')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <!--end::Input-->
          </div>

          <!--end::Col-->
          <!--begin::Col-->
          <div class="col-md-6 fv-row">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2 required">Payment Mode</label>
            <!--end::Label-->
            <select class="form-select form-select-solid @error('payment_mode') is-invalid @enderror"
              data-control="select2" data-placeholder="Payment Mode" name="payment_mode">
              <option></option>
              <option value="Cash" {{ old('payment_mode') == 'Cash' ? 'selected' : '' }}>Cash</option>
              <option value="UPI" {{ old('payment_mode') == 'UPI' ? 'selected' : '' }}>UPI</option>
              <option value="Net Banking" {{ old('payment_mode') == 'Net Banking' ? 'selected' : '' }}>Net Banking
              </option>
              <option value="Cheque" {{ old('payment_mode') == 'Cheque' ? 'selected' : '' }}>Cheque</option>
            </select>
            <!--end::Input-->
            @error('payment_mode')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>
          <!--end::Col-->
        </div>
        <!--end::row-->

        <div class="row g-9 mb-7">
          <!--begin::Col-->
          <div class="col-md-6 fv-row">
            <label class="fs-6 fw-semibold mb-2 required ">Expense Category</label>
            <select id="category-select"
              class="form-select form-select-solid @error('expense_category_id') is-invalid @enderror"
              data-control="select2" data-placeholder="Select Category" name="expense_category_id">
              <option></option>
              @foreach ($expenseCategories as $category)
                <option value="{{ $category->id }}" @if (old('expense_category_id') == $category->id) selected @endif>
                  {{ $category->name }}</option>
              @endforeach
            </select>
            @error('expense_category_id')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
          </div>

          <!--end::Col-->
          <!--begin::Col-->
          <div class="col-md-6 fv-row">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2">Expense Tag</label>
            <!--end::Label-->
            <!--begin::Input-->
            <input class="form-control form-control-solid" placeholder="Enter tags" value="" id="kt_tagify_3"
              name="tags" />
            <!--end::Input-->
          </div>
          <!--end::Col-->
        </div>

        <div class="row g-9 mb-7">
          <!--begin::Col-->
          <div class="col fv-row">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2">Expense Details</label>
            <!--end::Label-->
            <!--begin::Input-->
            <textarea class="form-control @error('details') is-invalid @enderror" data-kt-autosize="true" name="details"
              style="overflow: hidden; overflow-wrap: break-word; resize: none; text-align: start; height: 64.6px;">{{ old('details') }}</textarea>
            <!--end::Input-->
            @error('details')
              <div class="invalid-feedback">{{ $message }}</div>
              <!--end::Input-->
            @enderror
          </div>
          <!--end::Col-->
        </div>
        <!--end::row-->
        <!--end::row--><!--begin::row-->
        <div class="row g-9 mb-7">
          <!--begin::Col-->
          <div class="col fv-row">
            <div class="mb-7 bg-active-light">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">
                <span>Expense Image</span>
                <span class="ms-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                  data-bs-placement="top" title="Allowed file types: png, jpg, jpeg. Maximum image size 2MB."
                  aria-label="Allowed file types: png, jpg, jpeg. Maximum image size 2MB.">
                  <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span
                      class="path2"></span><span class="path3"></span></i> </span>
              </label>
              <!--end::Label-->
              <!--begin::Image input wrapper-->
              <div class="fv-row">
                <!--begin::Dropzone-->
                <div class="dropzone" id="expense-dropzone">
                  <!--begin::Message-->
                  <div class="dz-message needsclick">
                    <i class="ki-duotone ki-file-up fs-3x text-primary"><span class="path1"></span><span
                        class="path2"></span></i>

                    <!--begin::Info-->
                    <div class="ms-4">
                      <h3 class="fs-5 fw-bold text-gray-900 mb-1">Drop images here or click to upload.</h3>
                      <span class="fs-7 fw-semibold text-gray-500">*Maximum image size 2MB</span>
                    </div>
                    <!--end::Info-->
                  </div>
                </div>
                @if ($errors->has('images.*'))
                  @foreach ($errors->get('images.*') as $messages)
                    @foreach ($messages as $message)
                      <div class="invalid-feedback">{{ $message }}</div>
                    @endforeach
                  @endforeach
                @endif
                <!--end::Dropzone-->
              </div>
              <!--end::Image input wrapper-->
            </div>
          </div>
          <!--end::Col-->
        </div>
        <!--end::row-->
      </div>
      <!--end::Card body-->
      <!--begin::Card footer-->
      <div class="card-footer bg-dark p-2 mt-auto">
        <!--end::Action-->
        <div class="d-flex justify-content-end">
          <button type="submit" class="btn btn-primary">
            <span class="indicator-label">
              Add Expense
            </span>
            <span class="indicator-progress">
              Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
          </button>
        </div>
        <!--end::Action-->
      </div>
      <!--end::Card footer-->
    </div>
    <!--end::Messenger-->
  </form>
</div>
