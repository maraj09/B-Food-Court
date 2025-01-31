<div id="kt_add_item" class="bg-body drawer drawer-end" data-kt-drawer="true" data-kt-drawer-name="cart"
  data-kt-drawer-activate="true" data-kt-drawer-overlay="true" data-kt-drawer-width="{default:'300px', 'md': '500px'}"
  data-kt-drawer-direction="end" data-kt-drawer-toggle="#kt_add_food_toggle" data-kt-drawer-close="#kt_add_food_close"
  style="width: 500px !important;">
  <!--begin::Messenger-->
  <div class="card card-flush w-100 rounded-0">
    <!--begin::Card header-->
    <div class="card-header bg-dark">
      <!--begin::Title-->
      <h3 class="card-title text-gray-900 fw-bold">Add Menu Item</h3>
      <!--end::Title-->
      <!--begin::Card toolbar-->
      <div class="card-toolbar">
        <!--begin::Close-->
        <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_add_food_close">
          <i class="ki-duotone ki-cross fs-2">
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
    <div class="card-body bg-active-light hover-scroll-overlay-y h-400px pt-5">
      <form class="form" action="#" id="kt_modal_add_item_form">
        <!--begin::User form-->
        <div id="kt_modal_update_user_user_info" class="collapse show">
          <!--begin::Input group-->
          <div class="mb-7 bg-active-light">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2">
              <span>Dish Image</span>
              <span class="ms-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                data-bs-placement="top" title="Allowed file types: png, jpg, jpeg. Maximum image size 2MB."
                aria-label="Allowed file types: png, jpg, jpeg. Maximum image size 2MB.">
                <i class="ki-duotone ki-information fs-7"><span class="path1"></span><span class="path2"></span><span
                    class="path3"></span></i> </span>
            </label>
            <!--end::Label-->
            <!--begin::Image input wrapper-->
            <div class="mt-1">
              <!--begin::Image placeholder-->
              <style>
                .image-input-placeholder {
                  background-image: url({{ asset('assets/media/svg/avatars/blank.svg') }});
                }

                [data-bs-theme="dark"] .image-input-placeholder {
                  background-image: url({{ asset('assets/media/svg/avatars/blank-dark.svg') }});
                }
              </style>
              <!--end::Image placeholder-->
              <!--begin::Image input-->
              <div class="image-input image-input-outline image-input-placeholder" data-kt-image-input="true">
                <!--begin::Preview existing avatar-->
                <div class="image-input-wrapper w-150px h-150px"
                  style="background-image: url({{ asset('assets/media/svg/files/blank-image-dark.svg') }}"></div>
                <!--end::Preview existing avatar-->

                <!--begin::Edit-->
                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px shadow"
                  data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                  data-bs-placement="top" aria-label="Change avatar" data-bs-original-title="Change avatar"
                  data-kt-initialized="1">
                  <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
                  <!--begin::Inputs-->
                  <input type="file" name="item_image" accept=".png, .jpg, .jpeg">
                  <input type="hidden" name="avatar_remove">
                  <!--end::Inputs-->
                </label>
                <!--end::Edit-->
                <!--begin::Cancel-->
                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px shadow"
                  data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel avatar"
                  data-bs-custom-class="tooltip-inverse" data-bs-original-title="Cancel avatar" data-kt-initialized="1">
                  <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                </span>
                <!--end::Cancel-->
                <!--begin::Remove-->
                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px shadow"
                  data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove avatar"
                  data-bs-custom-class="tooltip-inverse" data-bs-original-title="Remove avatar" data-kt-initialized="1">
                  <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                </span>
                <!--end::Remove-->
              </div>
              <!--end::Image input-->
            </div>
            <span class="form-text">*Maximum image size 2MB</span>
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
            <!--end::Image input wrapper-->
          </div>
          <!--end::Input group-->

          <!--begin::Input group-->
          <div class="row g-9 mb-7">
            <!--begin::Col-->
            <div class="col-md-6 fv-row">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">Dish Name</label>
              <!--end::Label-->

              <!--begin::Input-->
              <input class="form-control form-control-solid" placeholder="Enter Dish Name" name="item_name">
              <!--end::Input-->
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-md-6 fv-row">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">Dish Price</label>
              <!--end::Label-->
              <!--begin::Input-->
              <input class="form-control form-control-solid" placeholder="Dish Price" name="price">
              <!--end::Input-->
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
            </div>
            <!--end::Col-->
          </div>
          <!--end::Input group-->
          <!--begin::Input group-->
          <div class="row g-9 mb-7">
            <!--begin::Col-->
            <div class="col-md-6 fv-row">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">Dish Category</label>
              <!--end::Label-->
              <!--begin::Input-->
              <select class="form-select" name="category_id" data-control="select2"
                data-placeholder="Select an option">
                <option></option>
                @foreach (App\Models\ItemCategory::all() as $category)
                  <option value="{{ $category->id }}">
                    {{ $category->name }}
                  </option>
                @endforeach
              </select>
              <!--end::Input-->
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-md-6 fv-row">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">Type</label>
              <!--end::Label-->
              <!--begin::Input-->
              <select class="form-select" name="item_type" data-control="select2"
                data-placeholder="Select an option">
                <option></option>
                <option value="vegetarian">Vegetarian</option>
                <option value="nonVegetarian">Non-Vegetarian</option>
                <option value="eggetarian">Eggetarian</option>
              </select>
              <!--end::Input-->
              <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
            </div>
            <!--end::Col-->
          </div>

          <div class="row g-9 mb-7">
            <!--begin::Col-->
            <div class="col-md-6 fv-row">
              <!--begin::Label-->
              <label class="fs-6 fw-semibold mb-2">Points</label>
              <!--end::Label-->
              <!--begin::Input-->
              <div class="form-check form-switch form-check-custom form-check-solid me-10">
                <label class="form-check-label me-2" for="flexSwitch20x30">
                  Disable
                </label>
                <input class="form-check-input h-20px w-30px" type="checkbox" checked="" value="1"
                  name="points_status" id="flexSwitch20x30">
                <label class="form-check-label" for="flexSwitch20x30">
                  Enable
                </label>
              </div>
              <!--end::Input-->
            </div>
          </div>
          <!--end::Input group-->
          <!--begin::Input group-->
          <div class="fv-row mb-7">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2">About Dish</label>
            <!--end::Label-->
            <!--begin::Input-->
            <textarea class="form-control" data-kt-autosize="true" data-kt-initialized="1"
              style="overflow-x: hidden; overflow-wrap: break-word;" name="description"></textarea>
            <!--end::Input-->
            <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
          </div>
          <!--end::Input group-->
        </div>
        <!--end::User form-->
      </form>
    </div>
    <!--end::Card body-->
    <!--begin::Card footer-->
    <div class="card-footer bg-dark p-2 d-flex justify-content-end">
      <!--end::Action-->
      <button type="submit" id="kt_add_item_submit" class="btn btn-primary">
        <span class="indicator-label">
          Add Dish
        </span>
        <span class="indicator-progress">
          Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
        </span>
      </button>
      <!--end::Action-->
    </div>
    <!--end::Card footer-->
  </div>
  <!--end::Messenger-->
</div>
