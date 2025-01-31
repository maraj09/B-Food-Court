<form method="POST" class="form"
  action="/dashboard/settings/taxes/{{ $invoiceTax->id }}/update">
  @csrf
  <input type="hidden" name="is_edit" value="{{ $invoiceTax->id }}">
  <!--begin::Scroll-->
  <div class="d-flex flex-column scroll-y px-5 px-lg-10" id="kt_modal_add_tax_scroll" data-kt-scroll="true"
    data-kt-scroll-activate="true" data-kt-scroll-max-height="auto"
    data-kt-scroll-dependencies="#kt_modal_add_tax_header" data-kt-scroll-wrappers="#kt_modal_add_tax_scroll"
    data-kt-scroll-offset="300px">

    <!--begin::Input group-->
    <div class="fv-row mb-7">
      <!--begin::Label-->
      <label class="required fw-semibold fs-6 mb-2">Title</label>
      <!--end::Label-->
      <!--begin::Input-->
      <input type="text" name="tax_title" class="form-control form-control-solid mb-3 mb-lg-0"
        placeholder="Tax title" value="{{ $invoiceTax->tax_title }}" />
      <!--end::Input-->
      @if ($errors->has('tax_title'))
        <div class="text-danger">{{ $errors->first('tax_title') }}</div>
      @endif
    </div>
    <!--end::Input group-->

    <!--begin::Input group-->
    <div class="fv-row mb-7">
      <!--begin::Label-->
      <label class="required fw-semibold fs-6 mb-2">Rate</label>
      <!--end::Label-->
      <!--begin::Input-->
      <input type="number" name="tax_rate" class="form-control form-control-solid mb-3 mb-lg-0"
        placeholder="Tax Rate (%)" value="{{ $invoiceTax->tax_rate }}" />
      <!--end::Input-->
      @if ($errors->has('tax_rate'))
        <div class="text-danger">{{ $errors->first('tax_rate') }}</div>
      @endif
    </div>
    <!--end::Input group-->
  </div>
  <!--end::Scroll-->

  <!--begin::Actions-->
  <div class="text-center pt-10">
    <button type="reset" class="btn btn-light me-3" data-bs-dismiss="modal" aria-label="Close">Discard</button>
    <button type="submit" class="btn btn-primary" data-kt-taxes-modal-action="submit">
      <span class="indicator-label">Submit</span>
      <span class="indicator-progress">Please wait...
        <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
    </button>
  </div>
  <!--end::Actions-->
</form>
