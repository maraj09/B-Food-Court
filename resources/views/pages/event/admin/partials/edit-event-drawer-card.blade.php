<div class="card card-flush w-100 rounded-0">
  <!--begin::Card header-->
  <div class="card-header bg-dark">
    <!--begin::Title-->
    <h3 class="card-title text-gray-900 fw-bold">Edit Event</h3>
    <!--end::Title-->
    <!--begin::Card toolbar-->
    <div class="card-toolbar">
      <!--begin::Close-->
      <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_edit_event_close">
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
    <form class="form" id="kt_modal_edit_event_form">
      <!--begin::User form-->
      <!--begin::Input group-->
      <div class="mb-7 bg-active-light">
        <!--begin::Label-->
        <label class="fs-6 fw-semibold mb-2">
          <span>Event Image</span>
          <span class="ms-1" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
            title="Allowed file types: png, jpg, jpeg. Maximum image size 2MB."
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
              background-image: url("{{ asset('assets/media/svg/files/blank-image-dark.svg') }}");
              background-size: contain;
            }

            [data-bs-theme="dark"] .image-input-placeholder {
              background-image: url("{{ asset('assets/media/svg/files/blank-image-dark.svg') }}");
              background-size: contain;
            }
          </style>
          <!--end::Image placeholder-->
          <!--begin::Image input-->
          <div class="image-input image-input-outline image-input-placeholder fv-row" data-kt-image-input="true">
            <!--begin::Preview existing avatar-->
            <div class="image-input-wrapper w-350px h-150px"
              style="background-image: url({{ asset($event->image) }}); background-size: contain;">
            </div>
            <!--end::Preview existing avatar-->

            <!--begin::Edit-->
            <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px shadow"
              data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
              data-bs-placement="top" title="Change avatar">
              <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
              <!--begin::Inputs-->
              <input type="file" name="image" accept=".png, .jpg, .jpeg">
              <input type="hidden" name="avatar_remove">
              <!--end::Inputs-->
            </label>
            <!--end::Edit-->
            <!--begin::Cancel-->
            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px shadow"
              data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel avatar"
              data-bs-custom-class="tooltip-inverse" title="Cancel avatar">
              <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
            </span>
            <!--end::Cancel-->
            <!--begin::Remove-->
            <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px shadow"
              data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove avatar"
              data-bs-custom-class="tooltip-inverse" title="Remove avatar">
              <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
            </span>
            <!--end::Remove-->
          </div>
          <!--end::Image input-->
        </div>
        <span class="form-text">*Maximum image size 2MB</span>
        <!--end::Image input wrapper-->
      </div>
      <!--end::Input group-->
      <!--begin::Input group-->
      <div class="row g-9 mb-7">
        <!--begin::Col-->
        <div class="col fv-row">
          <!--begin::Label-->
          <label class="fs-6 fw-semibold mb-2">Event Name</label>
          <!--end::Label-->
          <!--begin::Input-->
          <input class="form-control form-control-solid w-100" placeholder="Event Name" name="title"
            value="{{ $event->title }}">
          <!--end::Input-->
        </div>
        <!--end::Col-->
      </div>
      <!--end::Input group-->
      <!--begin::Input group-->
      <div class="row g-9 mb-7">
        <!--begin::Col-->
        <div class="col-md-6 fv-row">
          <!--begin::Label-->
          <label class="fs-6 fw-semibold mb-2">Price <i class="fa-solid fa-circle-info ms-2" data-bs-toggle="tooltip"
              data-bs-custom-class="tooltip-inverse" data-bs-placement="top" data-bs-html="true"
              title="Price can be 0 also"></i></label>
          <!--end::Label-->

          <!--begin::Input-->
          <input class="form-control form-control-solid" placeholder="Ticket Price" name="price"
            value="{{ round($event->price) }}">
          <!--end::Input-->
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-md-6 fv-row">
          <!--begin::Label-->
          <label class="fs-6 fw-semibold mb-2">Start Date and Time<i class="fa-solid fa-circle-info ms-2"
              data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
              data-bs-html="true" title="Select Start Date and Time"></i></label>
          <!--end::Label-->
          <!--begin::Input-->
          <input class="form-control form-control-solid kt_datepicker_with_time" name="start_date"
            placeholder="Pick date & time" value="{{ $event->start_date }}" />
          <!--end::Input-->
        </div>
        <!--end::Col-->
      </div>
      <!--end::Input group-->
      <!--begin::Input group-->
      <div class="row g-9 mb-7">
        <!--begin::Col-->
        <div class="col-md-6 fv-row">
          <!--begin::Label-->
          <label class="fs-6 fw-semibold mb-2">End Date and Time</label>
          <!--end::Label-->
          <!--begin::Input-->
          <input class="form-control form-control-solid kt_datepicker_with_time" name="end_date"
            placeholder="Pick date & time" value="{{ $event->end_date }}" />
          <!--end::Input-->
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-md-6 fv-row">
          <!--begin::Label-->
          <label class="fs-6 fw-semibold mb-2">Total Seats</label>
          <!--end::Label-->
          <!--begin::Input-->
          <input type="number" class="form-control form-control-solid" placeholder="Total Seats for Booking"
            name="seats" value="{{ $event->seats }}">
          <!--end::Input-->
        </div>
        <!--end::Col-->
      </div>
      <!--end::Input group-->
      <!--begin::Input group-->
      <div class="fv-row mb-7">
        <!--begin::Label-->
        <label class="fs-6 fw-semibold mb-2">Event Details</label>
        <!--end::Label-->
        <!--begin::Input-->
        <textarea class="form-control" data-kt-autosize="true" name="details">{{ $event->details }}</textarea>
        <!--end::Input-->
      </div>
      <!--end::Input group-->

      <!--end::User form-->
    </form>
  </div>
  <!--end::Card body-->
  <!--begin::Card footer-->
  <div class="card-footer bg-dark p-2">
    <!--end::Action-->
    <div class="d-flex justify-content-end">
      <button type="submit" id="kt_edit_event_submit" class="btn btn-primary">
        <span class="indicator-label">
          Update Event
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
<script>
  $(".kt_datepicker_with_time").flatpickr({
    enableTime: true,
    dateFormat: "Y-m-d H:i",
  });
</script>
<script>
  var KTEditEvent = (function() {
    var form, submitButton, cancelButton, drawer;
    return {
      init: function() {
        drawer = KTDrawer.getInstance(document.querySelector("#kt_edit_event"));
        form = document.querySelector("#kt_modal_edit_event_form");
        submitButton = document.querySelector("#kt_edit_event_submit");
        cancelButton = document.querySelector("#kt_edit_event_close");

        // Initialize FormValidation
        var fv = FormValidation.formValidation(form, {
          fields: {
            'image': {
              validators: {
                file: {
                  extension: 'png,jpg,jpeg',
                  type: 'image/png,image/jpeg',
                  message: 'Please choose a valid image file',
                },
              },
            },
            'title': {
              validators: {
                notEmpty: {
                  message: 'Event name is required',
                },
              },
            },
            'price': {
              validators: {
                notEmpty: {
                  message: 'Price is required',
                },
                integer: {
                  message: 'Price must be an integer',
                  thousandsSeparator: '',
                  decimalSeparator: '.',
                },
              },
            },
            'start_date': {
              validators: {
                notEmpty: {
                  message: 'Start Date is required',
                },
                date: {
                  format: 'YYYY-MM-DD HH:mm',
                  message: 'The date format should be YYYY-MM-DD HH:mm',
                },
              },
            },
            'end_date': {
              validators: {
                notEmpty: {
                  message: 'End Date is required',
                },
                date: {
                  format: 'YYYY-MM-DD HH:mm',
                  message: 'The date format should be YYYY-MM-DD HH:mm',
                },
                callback: {
                  message: 'End Date cannot be before Start Date',
                  callback: function(input) {
                    var startDate = form.querySelector('[name="start_date"]').value;
                    var endDate = input.value;
                    if (startDate && endDate) {
                      return new Date(endDate) >= new Date(startDate);
                    }
                    return true;
                  }
                }
              },
            },
            'seats': {
              validators: {
                notEmpty: {
                  message: 'Seats are required',
                },
                integer: {
                  message: 'Seats must be an integer',
                },
                callback: {
                  message: 'Seats must be at least 1',
                  callback: function(input) {
                    var value = parseInt(input.value, 10);
                    if (input.value) {
                      return value >= 1;
                    }
                    return true
                  }
                },
              },
            },
            'details': {
              validators: {
                notEmpty: {
                  message: 'Details are required',
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
          fv.validate().then(function(isValid) {
            if (isValid === "Valid") {
              submitButton.setAttribute("data-kt-indicator", "on");
              submitButton.disabled = true;
              var formData = new FormData(form);

              // Append CSRF token to the headers
              formData.append(
                "_token",
                document.querySelector('meta[name="csrf-token"]').content
              );


              $.ajax({
                url: "/dashboard/events/" + '{{ $event->id }}' + "/update",
                type: "POST",
                data: formData,
                headers: {
                  "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
                },
                processData: false,
                contentType: false,
                success: function(response) {
                  Swal.fire({
                    text: response.message,
                    icon: "success",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                      confirmButton: "btn btn-primary",
                    },
                  }).then(function(result) {
                    location.reload();
                  });
                },
                error: function(xhr, status, error) {
                  var errorMessage =
                    "Oops! Something went wrong. Please try again."; // Default error message
                  // Check if the error response contains validation errors
                  if (xhr.responseJSON && xhr.responseJSON.errors) {
                    // If there are validation errors, construct the error message
                    var errors = xhr.responseJSON.errors;
                    errorMessage = Object.values(errors).flat().join("<br>");
                  }
                  // Display the error message using SweetAlert
                  Swal.fire({
                    html: errorMessage,
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                      confirmButton: "btn btn-primary",
                    },
                  });
                },
                complete: function() {
                  submitButton.removeAttribute("data-kt-indicator");
                  submitButton.disabled = false;
                },
              });
            } else {
              Swal.fire({
                text: "Sorry, looks like there are some errors detected, please try again.",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              });
            }
          });
        });

        // Reset form on cancel
        cancelButton.addEventListener("click", function(e) {
          e.preventDefault();
          form.reset();
          drawer.hide();
          var blankImageUrl = '{{ asset('assets/media/svg/files/blank-image-dark.svg') }}';
          $('.image-input-wrapper').css('background-image', 'url(' + blankImageUrl + ')');
        });
      },
    };
  })();


  $('[data-bs-toggle="tooltip"]').tooltip();

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

  // Initialize the script on page load
  KTUtil.onDOMContentLoaded(function() {
    KTEditEvent.init();
    KTImageInput.init();
  });
</script>
