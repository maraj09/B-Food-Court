<style>
  .dropzone .dz-preview .dz-image {
    width: 100%;
    height: auto;
    border-radius: 0;
    overflow: hidden;
  }

  .dropzone .dz-preview .dz-image img {
    width: 100%;
    height: auto;
    object-fit: cover;
  }
</style>
<form id="kt_modal_update_expense_form" class="expense-form" action="/dashboard/expenses/{{ $expense->id }}/update"
  method="POST" enctype="multipart/form-data">
  @csrf
  <div class="card w-100 border-0 rounded-0 h-100" id="kt_drawer_chat_messenger">
    <!--begin::Card header-->
    <div class="card-header pe-5" id="kt_drawer_chat_messenger_header">
      <!--begin::Title-->
      <div class="card-title">
        <!--begin::User-->
        <div class="d-flex justify-content-center flex-column me-3">
          <a id="kt_drawer_order_toggle"
            class="fs-4 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1">#{{ $expense->id }}</a>
          <!--begin::Info-->
          <div class="mb-0 lh-1">
            <span class="badge badge-success badge-circle w-10px h-10px me-1" data-bs-toggle="tooltip"
              data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Edit Expense"></span>
            <span class="fs-7 fw-semibold text-muted">Expense ID</span>
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
        <div class="btn btn-sm btn-icon btn-active-color-primary" id="kt_drawer_expence_edit_close">
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
            placeholder="Expense Title" name="title" value="{{ $expense->title }}">
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
            placeholder="Expense Amount" name="amount" value="{{ $expense->amount }}">
          <!--end::Input-->
          @error('amount')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <!--end::Col-->
      </div>
      <!--end::row--><!--begin::row-->
      <div class="row g-9 mb-7">
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
              class="form-control form-control-solid ps-12 flatpickr-input @error('created_at') is-invalid @enderror kt_datepicker_dob_custom"
              placeholder="Select a date" name="created_at" type="date" readonly="readonly"
              value="{{ $expense->created_at->format('Y-m-d') }}">
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
          <select class="form-select form-select-solid select34344 @error('payment_mode') is-invalid @enderror"
            data-control="select2" data-placeholder="Payment Mode" name="payment_mode">
            <option></option>
            <option value="Cash" {{ $expense->payment_mode == 'Cash' ? 'selected' : '' }}>Cash</option>
            <option value="UPI" {{ $expense->payment_mode == 'UPI' ? 'selected' : '' }}>UPI</option>
            <option value="Net Banking" {{ $expense->payment_mode == 'Net Banking' ? 'selected' : '' }}>Net Banking
            </option>
            <option value="Cheque" {{ $expense->payment_mode == 'Cheque' ? 'selected' : '' }}>Cheque</option>
          </select>
          <!--end::Input-->
          @error('payment_mode')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>
        <!--end::Col-->
      </div>

      <div class="row g-9 mb-7">
        <!--begin::Col-->


        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-md-6 fv-row">
          <!--begin::Label-->
          <label class="fs-6 fw-semibold mb-2 required">Expense Category</label>
          <select
            class="form-select form-select-solid select34344 min-w-150px @error('expense_category_id') is-invalid @enderror"
            data-control="select2" data-placeholder="Select Category" name="expense_category_id">
            <option></option>
            @foreach ($expenseCategories as $category)
              <option value="{{ $category->id }}" @if ($expense->expense_category_id == $category->id) selected @endif>
                {{ $category->name }}</option>
            @endforeach
          </select>
          @error('expense_category_id')
            <div class="invalid-feedback">{{ $message }}</div>
          @enderror
        </div>

        <div class="col-md-6 fv-row">
          <!--begin::Label-->
          <label class="fs-6 fw-semibold mb-2">Expense Tag</label>
          <!--end::Label-->
          <!--begin::Input-->
          <input class="form-control form-control-sm form-control-solid" placeholder="Enter tags"
            value="@if ($expense->tags) {{ implode(', ', array_column(json_decode($expense->tags, true), 'value')) }} @endif"
            id="expenses_tagify" name="tags" />
          <!--end::Input-->
        </div>
        <!--end::Col-->
      </div>
      <!--end::row-->
      <!--end::row--><!--begin::row-->
      <div class="row g-9 mb-7">
        <!--begin::Col-->
        <div class="col fv-row">
          <!--begin::Label-->
          <label class="fs-6 fw-semibold mb-2">Expense Details</label>
          <!--end::Label-->
          <!--begin::Input-->
          <textarea class="form-control @error('details') is-invalid @enderror" data-kt-autosize="true" name="details"
            style="overflow: hidden; overflow-wrap: break-word; resize: none; text-align: start; height: 64.6px;">{{ $expense->details }}</textarea>
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
              <div class="dropzone" id="expense-dropzone-{{ $expense->id }}">
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
            Update Expense
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
  Dropzone.autoDiscover = false;
  Dropzone.options.uploadForm = new Dropzone("#expense-dropzone-{{ $expense->id }}", {
    url: "/dashboard/expenses/{{ $expense->id }}/update", // Temporary URL for initial upload
    paramName: "images",
    addRemoveLinks: true,
    autoProcessQueue: false,
    uploadMultiple: false,
    parallelUploads: 10,
    uploadMultiple: true,
    maxFiles: null, // Unlimited files
    maxFilesize: 2, // Max file size in MB\
    resizeWidth: null,
    resizeHeight: null,
    resizeMimeType: null,
    resizeQuality: 1.0,
    acceptedFiles: "image/jpeg,image/png,image/jpg,image/gif,image/svg",
    init: function() {
      var submitButton = document.querySelector(".expense-form button[type=submit]");
      var myDropzone = this;

      @if (isset($imageData))
        @foreach ($imageData as $image)
          var mockFile = {
            name: "{{ $image['name'] }}",
            size: {{ $image['size'] }},
            url: "{{ asset($image['path']) }}"
          };

          myDropzone.emit("addedfile", mockFile);
          myDropzone.emit("thumbnail", mockFile, mockFile.url);
          myDropzone.emit("complete", mockFile);
          // myDropzone.displayExistingFile(mockFile, "{{ asset($image['path']) }}");
          var hiddenInput = document.createElement('input');
          hiddenInput.type = 'hidden';
          hiddenInput.name = 'existing_images[]';
          hiddenInput.value = "{{ $image['path'] }}";
          hiddenInput.className = 'existing-image-input';
          document.querySelector('.expense-form').appendChild(hiddenInput);
          // Add an event listener for removing existing images
          mockFile.previewElement.querySelector(".dz-remove").addEventListener("click", function() {
            // Remove the hidden input field for this image
            document.querySelectorAll('.existing-image-input').forEach(function(input) {
              if (input.value === "{{ $image['path'] }}") {
                input.remove();
              }
            });

            // Optionally, mark the image for deletion on the server
            var removeInput = document.createElement('input');
            removeInput.type = 'hidden';
            removeInput.name = 'remove_images[]';
            removeInput.value = "{{ $image['path'] }}";
            document.querySelector('.expense-form').appendChild(removeInput);
          });
        @endforeach
      @endif
      submitButton.addEventListener("click", function(e) {
        e.preventDefault();
        e.stopPropagation();
        submitButton.setAttribute('data-kt-indicator', 'on');
        submitButton.disabled = true;

        if (myDropzone.getQueuedFiles().length > 0) {
          myDropzone.processQueue();
        } else {
          document.querySelector(".expense-form").submit();
        }
      });

      this.on("sendingmultiple", function(data, xhr, formData) {
        var formElements = document.querySelector(".expense-form").elements;
        for (var i = 0; i < formElements.length; i++) {
          if (formElements[i].name) {
            formData.append(formElements[i].name, formElements[i].value);
          }
        }
      });

      this.on("successmultiple", function(files, response) {
        window.location.href = "/dashboard/expenses"
      });

      this.on("errormultiple", function(files, response) {
        var errorMessage = Object.values(response.errors)
          .flat()
          .join("<br>");
        myDropzone.removeAllFiles(true);
        Swal.fire({
          html: errorMessage,
          icon: "error",
          buttonsStyling: !1,
          confirmButtonText: "Ok, got it!",
          customClass: {
            confirmButton: "btn btn-primary",
          },
        });
        submitButton.setAttribute('data-kt-indicator', 'off');
        submitButton.disabled = false;
      });
    }
  });
</script>
<script>
  var input3 = document.querySelector("#expenses_tagify");
  new Tagify(input3, {
    whitelist: {!! json_encode($tags) !!},
    maxTags: 10,
    dropdown: {
      maxItems: 20, // <- mixumum allowed rendered suggestions
      classname: "tagify__inline__suggestions", // <- custom classname for this dropdown, so it could be targeted
      enabled: 0, // <- show suggestions on focus
      closeOnSelect: false // <- do not hide the suggestions dropdown once an item has been selected
    }
  });
  $(".select34344").select2({})
</script>
<script>
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
        form = document.querySelector("#kt_modal_update_expense_form");
        submitButton = document.querySelector("#kt_modal_update_expense_submit");

        // Initialize FormValidation
        var fv = FormValidation.formValidation(form, {
          fields: {
            expense_category_id: {
              validators: {
                notEmpty: {
                  message: "Please Select a category!",
                },
              },
            },
            title: {
              validators: {
                notEmpty: {
                  message: "Title is required",
                },
              },
            },
            amount: {
              validators: {
                notEmpty: {
                  message: "Amount is required",
                },
              },
            },
            payment_mode: {
              validators: {
                notEmpty: {
                  message: "Payment Mode is required",
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
                    text: "Expense updated successfully!",
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

  $(".kt_datepicker_dob_custom").flatpickr({
    disableMobile: true,
  });

  // Initialize the script on page load
  KTUtil.onDOMContentLoaded(function() {
    KTUpdateCustomer.init();
    KTImageInput.init();
  });
</script>
