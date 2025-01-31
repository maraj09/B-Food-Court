<form id="kt_modal_update_billing_category_form"
  action="/dashboard/finance/billing-categories/{{ $billingCategory->id }}/update" method="POST"
  enctype="multipart/form-data">
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
              data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Update Category"></span>
            <span class="fs-7 fw-semibold text-muted">Update Category</span>
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
        <div class="btn btn-sm btn-icon btn-active-color-primary" id="kt_drawer_billing_category_close">
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
          <label class="fs-6 fw-semibold mb-2 required ">Name</label>
          <!--end::Label-->
          <!--begin::Input-->
          <input class="form-control form-control-solid @error('name') is-invalid @enderror" placeholder="Category Name"
            name="name" value="{{ old('name', $billingCategory->name) }}">
          <!--end::Input-->
          @error('name')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-md-6 fv-row">
          <!--begin::Label-->
          <label class="fs-6 fw-semibold mb-2">GST No</label>
          <!--end::Label-->
          <!--begin::Input-->
          <input type="text" class="form-control form-control-solid @error('gst_no') is-invalid @enderror"
            placeholder="GST No" name="gst_no" value="{{ old('gst_no', $billingCategory->gst_no) }}">
          <!--end::Input-->
          @error('gst_no')
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
          <label class="fs-6 fw-semibold mb-2">Address</label>
          <!--end::Label-->
          <!--begin::Input-->
          <input type="text" class="form-control form-control-solid @error('address') is-invalid @enderror"
            placeholder="Address" name="address" value="{{ old('address', $billingCategory->address) }}">
          <!--end::Input-->
          @error('address')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-md-6 fv-row">
          <!--begin::Label-->
          <label class="fs-6 fw-semibold mb-2 required">Color</label>
          <!--end::Label-->
          <select class="form-select form-select-solid select2ads @error('color_class') is-invalid @enderror"
            data-control="select2" data-placeholder="Select Color" name="color_class">
            <option></option>
            <option value="badge-light"
              {{ old('color_class', $billingCategory->color_class) == 'badge-light' ? 'selected' : '' }}>
              Light</option>
            <option value="badge-primary"
              {{ old('color_class', $billingCategory->color_class) == 'badge-primary' ? 'selected' : '' }}>
              Blue</option>
            <option value="badge-secondary"
              {{ old('color_class', $billingCategory->color_class) == 'badge-secondary' ? 'selected' : '' }}>
              Gray</option>
            <option value="badge-success"
              {{ old('color_class', $billingCategory->color_class) == 'badge-success' ? 'selected' : '' }}>
              Green</option>
            <option value="badge-info"
              {{ old('color_class', $billingCategory->color_class) == 'badge-info' ? 'selected' : '' }}>
              Violet</option>
            <option value="badge-warning"
              {{ old('color_class', $billingCategory->color_class) == 'badge-warning' ? 'selected' : '' }}>
              Yellow</option>
            <option value="badge-danger"
              {{ old('color_class', $billingCategory->color_class) == 'badge-danger' ? 'selected' : '' }}>
              Red</option>
            <option value="badge-dark"
              {{ old('color_class', $billingCategory->color_class) == 'badge-dark' ? 'selected' : '' }}>
              Black</option>
            <option value="badge-white"
              {{ old('color_class', $billingCategory->color_class) == 'badge-white' ? 'selected' : '' }}>
              White</option>
            <option value="badge-light-primary"
              {{ old('color_class', $billingCategory->color_class) == 'badge-light-primary' ? 'selected' : '' }}>
              Light Blue</option>
            <option value="badge-light-secondary"
              {{ old('color_class', $billingCategory->color_class) == 'badge-light-secondary' ? 'selected' : '' }}>
              Light Gray</option>
            <option value="badge-light-success"
              {{ old('color_class', $billingCategory->color_class) == 'badge-light-success' ? 'selected' : '' }}>
              Light Green</option>
            <option value="badge-light-info"
              {{ old('color_class', $billingCategory->color_class) == 'badge-light-info' ? 'selected' : '' }}>
              Light Violet</option>
            <option value="badge-light-warning"
              {{ old('color_class', $billingCategory->color_class) == 'badge-light-warning' ? 'selected' : '' }}>
              Light Yellow</option>
            <option value="badge-light-danger"
              {{ old('color_class', $billingCategory->color_class) == 'badge-light-danger' ? 'selected' : '' }}>
              Light Red</option>
            <option value="badge-light-dark"
              {{ old('color_class', $billingCategory->color_class) == 'badge-light-dark' ? 'selected' : '' }}>
              Light Black</option>
          </select>
          <!--end::Input-->
          @error('color_class')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <!--end::Col-->
      </div>
      <!--end::row-->
      <div class="row g-9 mb-7">
        <!--begin::Col-->
        <div class="col fv-row">
          <div class="mb-7 bg-active-light">
            <!--begin::Label-->
            <label class="fs-6 fw-semibold mb-2">
              <span>Brand Logo</span>
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
                  style="background-image: url({{ $billingCategory->logo ? asset($billingCategory->logo) : asset('assets/media/svg/files/blank-image-dark.svg') }})">
                </div>
                <!--end::Preview existing avatar-->

                <!--begin::Edit-->
                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px shadow"
                  data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                  data-bs-placement="top" aria-label="Change avatar" data-bs-original-title="Change avatar"
                  data-kt-initialized="1">
                  <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
                  <!--begin::Inputs-->
                  <input type="file" name="logo" accept=".png, .jpg, .jpeg">
                  <input type="hidden" name="avatar_remove">
                  <!--end::Inputs-->
                </label>
                <!--end::Edit-->
                <!--begin::Cancel-->
                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px shadow"
                  data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel avatar"
                  data-bs-custom-class="tooltip-inverse" data-bs-original-title="Cancel avatar"
                  data-kt-initialized="1">
                  <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                </span>
                <!--end::Cancel-->
                <!--begin::Remove-->
                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px shadow"
                  data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove avatar"
                  data-bs-custom-class="tooltip-inverse" data-bs-original-title="Remove avatar"
                  data-kt-initialized="1">
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
        <button type="submit" class="btn btn-primary" id="kt_modal_update_billing_category_submit">
          <span class="indicator-label">
            Update Category
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
<script>
  $(".select2ads").select2({})
  var KTImageInput = function(e, t) {
    var n = this;
    if (null != e) {
      var i = {},
        r = function() {
          (n.options = KTUtil.deepExtend({}, i, t)),
          (n.uid = KTUtil.getUniqueId("image-input")),
          (n.element = e),
          (n.inputElement = KTUtil.find(e, 'input[type="file"]')),
          (n.wrapperElement = KTUtil.find(e, ".image-input-wrapper")),
          (n.cancelElement = KTUtil.find(
            e,
            '[data-kt-image-input-action="cancel"]'
          )),
          (n.removeElement = KTUtil.find(
            e,
            '[data-kt-image-input-action="remove"]'
          )),
          (n.hiddenElement = KTUtil.find(e, 'input[type="hidden"]')),
          (n.src = KTUtil.css(n.wrapperElement, "backgroundImage")),
          n.element.setAttribute("data-kt-image-input", "true"),
            o(),
            KTUtil.data(n.element).set("image-input", n);
        },
        o = function() {
          KTUtil.addEvent(n.inputElement, "change", a),
            KTUtil.addEvent(n.cancelElement, "click", l),
            KTUtil.addEvent(n.removeElement, "click", s);
        },
        a = function(e) {
          if (
            (e.preventDefault(),
              null !== n.inputElement &&
              n.inputElement.files &&
              n.inputElement.files[0])
          ) {
            if (
              !1 ===
              KTEventHandler.trigger(
                n.element,
                "kt.imageinput.change",
                n
              )
            )
              return;
            var t = new FileReader();
            (t.onload = function(e) {
              KTUtil.css(
                n.wrapperElement,
                "background-image",
                "url(" + e.target.result + ")"
              );
            }),
            t.readAsDataURL(n.inputElement.files[0]),
              n.element.classList.add("image-input-changed"),
              n.element.classList.remove("image-input-empty"),
              KTEventHandler.trigger(
                n.element,
                "kt.imageinput.changed",
                n
              );
          }
        },
        l = function(e) {
          e.preventDefault(),
            !1 !==
            KTEventHandler.trigger(
              n.element,
              "kt.imageinput.cancel",
              n
            ) &&
            (n.element.classList.remove("image-input-changed"),
              n.element.classList.remove("image-input-empty"),
              "none" === n.src ?
              (KTUtil.css(
                  n.wrapperElement,
                  "background-image",
                  ""
                ),
                n.element.classList.add("image-input-empty")) :
              KTUtil.css(
                n.wrapperElement,
                "background-image",
                n.src
              ),
              (n.inputElement.value = ""),
              null !== n.hiddenElement &&
              (n.hiddenElement.value = "0"),
              KTEventHandler.trigger(
                n.element,
                "kt.imageinput.canceled",
                n
              ));
        },
        s = function(e) {
          e.preventDefault(),
            !1 !==
            KTEventHandler.trigger(
              n.element,
              "kt.imageinput.remove",
              n
            ) &&
            (n.element.classList.remove("image-input-changed"),
              n.element.classList.add("image-input-empty"),
              KTUtil.css(
                n.wrapperElement,
                "background-image",
                "none"
              ),
              (n.inputElement.value = ""),
              null !== n.hiddenElement &&
              (n.hiddenElement.value = "1"),
              KTEventHandler.trigger(
                n.element,
                "kt.imageinput.removed",
                n
              ));
        };
      !0 === KTUtil.data(e).has("image-input") ?
        (n = KTUtil.data(e).get("image-input")) :
        r(),
        (n.getInputElement = function() {
          return n.inputElement;
        }),
        (n.getElement = function() {
          return n.element;
        }),
        (n.destroy = function() {
          KTUtil.data(n.element).remove("image-input");
        }),
        (n.on = function(e, t) {
          return KTEventHandler.on(n.element, e, t);
        }),
        (n.one = function(e, t) {
          return KTEventHandler.one(n.element, e, t);
        }),
        (n.off = function(e, t) {
          return KTEventHandler.off(n.element, e, t);
        }),
        (n.trigger = function(e, t) {
          return KTEventHandler.trigger(n.element, e, t, n, t);
        });
    }
  };
  (KTImageInput.getInstance = function(e) {
    return null !== e && KTUtil.data(e).has("image-input") ?
      KTUtil.data(e).get("image-input") :
      null;
  }),
  (KTImageInput.createInstances = function(e = "[data-kt-image-input]") {
    var t = document.querySelectorAll(e);
    if (t && t.length > 0)
      for (var n = 0, i = t.length; n < i; n++) new KTImageInput(t[n]);
  }),
  (KTImageInput.init = function() {
    KTImageInput.createInstances();
  }),
  "undefined" != typeof module &&
    void 0 !== module.exports &&
    (module.exports = KTImageInput);
  var KTUpdateCustomer = (function() {
    var form, submitButton;

    return {
      init: function() {
        form = document.querySelector("#kt_modal_update_billing_category_form");
        submitButton = document.querySelector("#kt_modal_update_billing_category_submit");

        // Initialize FormValidation
        var fv = FormValidation.formValidation(form, {
          fields: {
            name: {
              validators: {
                notEmpty: {
                  message: "Category name is required",
                },
              },
            },
            gst_no: {
              validators: {
                notEmpty: {
                  message: "GST No is required",
                },
              },
            },
            address: {
              validators: {
                notEmpty: {
                  message: "Address is required",
                },
              },
            },
            color_class: {
              validators: {
                notEmpty: {
                  message: "Color class is required",
                },
              },
            },
            logo: {
              validators: {
                file: {
                  extension: 'png,jpg,jpeg',
                  type: 'image/png,image/jpg,image/jpeg',
                  message: 'Please select a valid image file (PNG, JPG, JPEG)',
                },
                maxSize: {
                  max: 2048 * 1024, // Max file size in bytes (2MB)
                  message: 'The file size must not exceed 2MB',
                },
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

        // Submit form with AJAX
        submitButton.addEventListener("click", function(e) {
          e.preventDefault();
          submitButton.setAttribute("data-kt-indicator", "on");
          submitButton.disabled = true;

          fv.validate().then(function(status) {
            if (status === "Valid") {
              // Form is valid, prepare data for AJAX request
              var formData = new FormData(form);

              // Append CSRF token to the form data
              formData.append(
                "_token",
                document.querySelector('meta[name="csrf-token"]').content
              );
              // Perform AJAX request to update customer
              $.ajax({
                url: form.getAttribute("action"), // Update URL with your endpoint
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                  // Show success message and handle UI updates
                  Swal.fire({
                    text: "Category updated successfully!",
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok",
                    customClass: {
                      confirmButton: "btn btn-primary",
                    },
                  }).then(function(result) {
                    if (result.isConfirmed) {
                      // Optionally handle UI updates or close modal/drawer
                      location.reload(); // Reload the page or update UI as needed
                    }
                  });
                },
                error: function(error) {
                  // Handle AJAX request error and show error message
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
                  submitButton.removeAttribute(
                    "data-kt-indicator"
                  );
                  submitButton.disabled = false;
                },
              });
            } else {
              // Form validation failed, show error message
              Swal.fire({
                text: "Please fill in all required fields correctly.",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok",
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
  KTUtil.onDOMContentLoaded(function() {
    KTUpdateCustomer.init();
    KTImageInput.init();
  });
</script>
