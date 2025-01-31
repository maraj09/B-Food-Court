<div class="modal-header">
  <h3 class="modal-title">Update Client</h3>

  <!--begin::Close-->
  <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
    <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
  </div>
  <!--end::Close-->
</div>
<form id="kt_modal_edit_client_form" class="form fv-plugins-bootstrap5 fv-plugins-framework" action=""
  method="POST">
  @csrf
  <div class="modal-body">
    <!-- Hidden input for client ID -->
    <input type="hidden" name="client_id" value="{{ $client->id }}">

    <!--begin::Input group-->
    <div class="row g-9 mb-8">
      <!--begin::Col-->
      <div class="col-md-6 fv-row fv-plugins-icon-container">
        <label class="required fs-6 fw-semibold mb-2">Name</label>

        <input type="text" name="name" class="form-control form-control-solid mb-3 mb-lg-0"
          placeholder="Enter Name" value="{{ $client->name }}" autocomplete="off" />

      </div>
      <!--end::Col-->

      <!--begin::Col-->
      <div class="col-md-6 fv-row fv-plugins-icon-container">
        <label class="required fs-6 fw-semibold mb-2">Company Name</label>

        <!--begin::Input-->
        <input type="text" name="company_name" class="form-control form-control-solid mb-3 mb-lg-0"
          placeholder="Enter company name" value="{{ $client->company_name }}" autocomplete="off" />
        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
        <!--end::Input-->
      </div>
      <!--end::Col-->
    </div>
    <!--end::Input group-->

    <!--begin::Input group-->
    <div class="row g-9 mb-8">
      <!--begin::Col-->
      <div class="col-md-6 fv-row fv-plugins-icon-container">
        <label class="fs-6 fw-semibold mb-2">Email Address</label>

        <input type="email" name="email" class="form-control form-control-solid mb-3 mb-lg-0"
          placeholder="Enter email address" value="{{ $client->email }}" />

        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
      </div>
      <!--end::Col-->

      <!--begin::Col-->
      <div class="col-md-6 fv-row">
        <label class="fs-6 fw-semibold mb-2">Contact No</label>

        <input type="text" name="phone"
          class="form-control form-control-solid mb-3 mb-lg-0 phone_modal_edit_client" value="{{ $client->phone }}" />

        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>

        <!--end::Input-->
      </div>
      <!--end::Col-->
    </div>
    <!--end::Input group-->
    <!--begin::Input group-->
    <div class="row g-9 mb-8">
      <!--begin::Col-->
      <div class="col-md-6 fv-row fv-plugins-icon-container">
        <label class="fs-6 fw-semibold mb-2">GST NO</label>

        <input type="text" name="gst_no" class="form-control form-control-solid mb-3 mb-lg-0"
          placeholder="Enter GST No" value="{{ $client->gst_no }}" />

        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>
      </div>
      <!--end::Col-->

      <!--begin::Col-->
      <div class="col-md-6 fv-row">
        <label class="fs-6 fw-semibold mb-2">Address</label>

        <input type="text" placeholder="Enter Address" name="address"
          class="form-control form-control-solid mb-3 mb-lg-0" value="{{ $client->address }}" />

        <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback"></div>

        <!--end::Input-->
      </div>
      <!--end::Col-->
    </div>
    <!--end::Input group-->
  </div>

  <div class="modal-footer">
    <button type="button" class="btn btn-light" id="kt_edit_client_close">Close</button>
    <button type="submit" id="kt_edit_client_save" class="btn btn-primary">
      <span class="indicator-label">
        Submit
      </span>
      <span class="indicator-progress">
        Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
      </span>
    </button>
  </div>
</form>

<script>
  var KTEditClient = (function() {
    var form, submitButton, cancelButton;
    var iti = window.intlTelInput(document.querySelector(".phone_modal_edit_client"), {
      utilsScript: utilsScript,
      separateDialCode: true,
      initialCountry: "auto",
      initialCountry: "in",
    });

    return {
      init: function() {
        form = document.querySelector("#kt_modal_edit_client_form");
        submitButton = document.querySelector("#kt_edit_client_save");
        cancelButton = document.querySelector("#kt_edit_client_close");

        // Initialize FormValidation
        var fv = FormValidation.formValidation(form, {
          fields: {
            name: {
              validators: {
                notEmpty: {
                  message: "Name is required",
                },
              },
            },
            company_name: {
              validators: {
                notEmpty: {
                  message: "Company name is required",
                },
              },
            },
            phone: {
              validators: {
                callback: {
                  message: "Invalid phone number",
                  callback: function(input) {
                    // Get the phone number value
                    var phoneNumber = input.value;

                    // Validate the phone number format using intl-tel-input
                    if (phoneNumber === '') {
                      return true;
                    }
                    var isValidPhoneNumber = iti.isValidNumber();

                    return isValidPhoneNumber;
                  },
                },
              },
            },
            email: {
              validators: {
                regexp: {
                  regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                  message: "The value is not a valid email address",
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
          let phoneNumber = iti.getNumber();
          fv.validate().then(function(isValid) {
            if (isValid === "Valid") {
              // You can customize the AJAX request here
              submitButton.setAttribute("data-kt-indicator", "on");
              submitButton.disabled = true;
              var formData = new FormData(form);

              // Append CSRF token to the headers
              formData.append(
                "_token",
                document.querySelector('meta[name="csrf-token"]')
                .content
              );
              formData.set("phone", phoneNumber);
              $.ajax({
                url: "/dashboard/finance/clients/{{ $client->id }}/update", // Update with your actual API endpoint
                type: "POST",
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                  if (response.success) {
                    Swal.fire({
                      text: "Form has been successfully submitted!",
                      icon: "success",
                      buttonsStyling: false,
                      confirmButtonText: "Ok, got it!",
                      customClass: {
                        confirmButton: "btn btn-primary",
                      },
                    }).then(function(result) {
                      if (result.isConfirmed) {
                        location.reload();
                        modal.hide();
                        form.reset(); // Reset the form after successful submission
                      }
                    });
                  }
                },
                error: function(error) {
                  // Registration failed, show error messages
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
                complete: function() {
                  submitButton.removeAttribute(
                    "data-kt-indicator"
                  );
                  submitButton.disabled = false;
                },
              });
            } else {
              Swal.fire({
                text: "Sorry, looks like there are some errors detected, please try again.",
                icon: "error",
                buttonsStyling: !1,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn btn-primary",
                },
              });
            }
          });
        });
        cancelButton.addEventListener("click", function(t) {
          t.preventDefault(), (form.reset(), modal.hide());
        });
      },
    };
  })();
  KTUtil.onDOMContentLoaded(function() {
    KTEditClient.init();
  });
</script>
