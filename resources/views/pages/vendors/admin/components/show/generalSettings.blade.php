<div class="tab-pane fade {{ $lastSegment == 'edit' ? 'active show' : '' }}" id="kt_ecommerce_customer_general"
  role="tabpanel">
  <!--begin::Card-->
  <div class="card pt-4 mb-6 mb-xl-9">
    <!--begin::Card header-->
    <div class="card-header border-0">
      <!--begin::Card title-->
      <div class="card-title">
        <h2>Profile</h2>
      </div>
      <!--end::Card title-->
    </div>
    <!--end::Card header-->

    <!--begin::Card body-->
    <div class="card-body pt-0 pb-5">
      <!--begin::Form-->
      <form method="POST" class="form fv-plugins-bootstrap5 fv-plugins-framework"
        action="/dashboard/vendors/{{ $user->id }}" id="kt_ecommerce_vendor_profile" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        <!--begin::Input group-->
        <div class="mb-7">
          <!--begin::Label-->
          <label class="fs-6 fw-semibold mb-2">
            <span>Update Avatar</span>

            <span class="ms-1" data-bs-toggle="tooltip" aria-label="Allowed file types: png, jpg, jpeg."
              data-bs-original-title="Allowed file types: png, jpg, jpeg." data-kt-initialized="1">
              <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span
                  class="path3"></span></i> </span>
          </label>
          <!--end::Label-->

          <!--begin::Image input wrapper-->
          <div class="mt-1">
            <!--begin::Image input placeholder-->
            <style>
              .image-input-placeholder {
                background-image: url('/metronic8/demo1/assets/media/svg/files/blank-image.svg');
              }

              [data-bs-theme="dark"] .image-input-placeholder {
                background-image: url('/metronic8/demo1/assets/media/svg/files/blank-image-dark.svg');
              }
            </style>
            <!--end::Image input placeholder-->

            <!--begin::Image input-->
            <div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
              <!--begin::Preview existing avatar-->
              <div class="image-input-wrapper w-125px h-125px"
                style="background-image: url({{ $user->vendor->avatar ? asset($user->vendor->avatar) : asset('assets/media/svg/avatars/blank-dark.svg') }})">
              </div>
              <!--end::Preview existing avatar-->

              <!--begin::Edit-->
              <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change avatar"
                data-bs-original-title="Change avatar" data-kt-initialized="1">
                <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
                <!--begin::Inputs-->
                <input type="file" name="avatar" accept=".png, .jpg, .jpeg">
                <input type="hidden" name="avatar_remove">
                <!--end::Inputs-->
              </label>
              <!--end::Edit-->

              <!--begin::Cancel-->
              <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel avatar"
                data-bs-original-title="Cancel avatar" data-kt-initialized="1">
                <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i> </span>
              <!--end::Cancel-->

              <!--begin::Remove-->
              <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove avatar"
                data-bs-original-title="Remove avatar" data-kt-initialized="1">
                <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i> </span>
              <!--end::Remove-->
            </div>
            <!--end::Image input-->
          </div>
          @error('avatar')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror

          <!--end::Image input wrapper-->
        </div>
        <!--end::Input group-->

        <!--begin::Input group-->
        <div class="fv-row mb-7 fv-plugins-icon-container">
          <!--begin::Label-->
          <label class="fs-6 fw-semibold mb-2 required">Brand Name</label>
          <!--end::Label-->

          <!--begin::Input-->
          <input type="text" class="form-control form-control-solid @error('brand_name') is-invalid @enderror"
            placeholder="Enter Brand Name" name="brand_name" value="{{ $user->vendor->brand_name }}">
          @error('brand_name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
          <!--end::Input-->
          <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
          </div>
        </div>
        <!--end::Input group-->

        <!--begin::Row-->
        <div class="row row-cols-1 row-cols-md-2">
          <!--begin::Col-->
          <div class="col">
            <!--begin::Input group-->
            <div class="fv-row mb-7 fv-plugins-icon-container">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">
                <span class="required">Brand Owner Name</span>
              </label>
              <!--end::Label-->

              <!--begin::Input-->
              <input class="form-control form-control-solid @error('name') is-invalid @enderror" placeholder=""
                name="name" value="{{ $user->name }}">
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <!--end::Input-->

            </div>

            <!--end::Input group-->
          </div>
          <!--end::Col-->

          <!--begin::Col-->
          <div class="col">
            <!--begin::Input group-->
            <div class="fv-row mb-7">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">
                <span class="required">Commission</span>
              </label>
              <!--end::Label-->

              <!--begin::Input-->
              <input class="form-control form-control-solid @error('commission') is-invalid @enderror" placeholder=""
                name="commission" value="{{ $user->vendor->commission }}">
              @error('commission')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <!--end::Input-->
            </div>
            <!--end::Input group-->
          </div>
          <!--end::Col-->
        </div>
        <!--end::Row-->

        <!--begin::Row-->
        <div class="row row-cols-1 row-cols-md-2">
          <!--begin::Col-->
          <div class="col">
            <!--begin::Input group-->
            <div class="fv-row mb-7 fv-plugins-icon-container">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">
                <span class="">Email Address</span>
              </label>
              <!--end::Label-->

              <!--begin::Input-->
              <input type="email" class="form-control form-control-solid @error('email') is-invalid @enderror"
                placeholder="Enter email address" name="email" value="{{ $user->email }}">
              @error('email')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <!--end::Input-->

            </div>
            <!--end::Input group-->
          </div>
          <!--end::Col-->

          <!--begin::Col-->
          <div class="col">
            <!--begin::Input group-->
            <div class="fv-row mb-7">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">
                <span class="required">Contact No:</span>
              </label>
              <!--end::Label-->
              <!--begin::Input-->
              <input class="form-control form-control-solid @error('phone') is-invalid @enderror" id="phone"
                name="phone" value="{{ $user->phone }}">
              <!--end::Input-->
              @error('phone')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>
            <!--end::Input group-->
          </div>
          <!--end::Col-->
        </div>
        <!--end::Row-->

        <!--begin::Row-->
        <div class="row row-cols-1 row-cols-md-2">
          <!--begin::Col-->
          <div class="col">
            <!--begin::Input group-->
            <div class="fv-row mb-7 fv-plugins-icon-container">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">
                <span class="">Fassi no</span>
              </label>
              <!--end::Label-->

              <!--begin::Input-->
              <input class="form-control form-control-solid @error('fassi_no') is-invalid @enderror" placeholder=""
                name="fassi_no" value="{{ $user->vendor->fassi_no }}">
              @error('fassi_no')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <!--end::Input-->

            </div>

            <!--end::Input group-->
          </div>
          <!--end::Col-->

          <!--begin::Col-->
          <div class="col">
            <!--begin::Input group-->
            <div class="fv-row mb-7">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">
                <span>Stall no</span>
              </label>
              <!--end::Label-->

              <!--begin::Input-->
              <input class="form-control form-control-solid @error('stall_no') is-invalid @enderror" placeholder=""
                name="stall_no" value="{{ $user->vendor->stall_no }}">
              @error('stall_no')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <!--end::Input-->
            </div>
            <!--end::Input group-->
          </div>
          <!--end::Col-->
        </div>
        <!--end::Row-->
        <!--begin::Row-->
        <div class="row row-cols-1 row-cols-md-2">
          <!--begin::Col-->
          <div class="col">
            <!--begin::Input group-->
            <div class="fv-row mb-7 fv-plugins-icon-container">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">
                <span class="">Documents</span>
              </label>
              <!--end::Label-->

              @if (!empty($user->vendor->documents))
                <ul>
                  @foreach ($user->vendor->documents as $document)
                    <li>
                      <a href="{{ asset($document['filepath']) }}" target="_blank">
                        {{ $document['filename'] }}
                      </a>
                      <a href="#" class="text-danger ms-3 remove-document"
                        data-filepath="{{ $document['filepath'] }}" data-vendor-id="{{ $user->vendor->id }}">X</a>
                    </li>
                  @endforeach
                </ul>
              @else
                <p>No documents uploaded.</p>
              @endif

            </div>

            <!--end::Input group-->
          </div>
          <!--end::Col-->

          <!--begin::Col-->
          <div class="col">
            <!--begin::Input group-->
            <div class="fv-row mb-7">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">
                <span class="">Add More Documents</span>
              </label>
              <!--end::Label-->

              <!--begin::Input-->
              <input type="file" name="documents[]" class="form-control custom-file-input" id="customFile"
                multiple>
              @error('documents[]')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
              <!--end::Input-->
            </div>
            <!--end::Input group-->
          </div>

          <!--end::Col-->
        </div>
        <div class="row mb-20">
          <div class="col">
            <label class="fs-6 fw-semibold mb-2">Details</label>
            <input type="hidden" id="quill_html" name="details"></input>
            <div id="kt_docs_quill_basic" style="height: 150px" name="kt_docs_quill_basic">
              {!! $user->vendor->details !!}
            </div>
          </div>
        </div>
        <!--end::Row-->

        <div class="d-flex justify-content-end">
          <!--begin::Button-->
          <button type="submit" onclick="this.setAttribute('data-kt-indicator', 'on')"
            id="kt_ecommerce_customer_profile_submit" class="btn btn-success">
            <span class="indicator-label">
              Save
            </span>
            <span class="indicator-progress">
              Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
            </span>
          </button>
          <!--end::Button-->
        </div>
      </form>
      <!--end::Form-->
    </div>
    <!--end::Card body-->
  </div>
  <!--end::Card-->


</div>
