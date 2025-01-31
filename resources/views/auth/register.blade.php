@extends('layouts.guest')
@section('contents')
  @php
    use App\Models\Setting;
    $settings = Setting::first();
  @endphp
  <!--begin::Aside-->
  <div class="d-flex flex-lg-row-fluid">
    <!--begin::Content-->
    <div class="d-flex flex-column flex-center pb-0 pb-lg-10 p-10 w-100">
      <!--begin::Image-->
      <img class="theme-light-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
        src="{{ asset('assets/media/auth/agency.png') }}" alt="" />
      <img class="theme-dark-show mx-auto mw-100 w-150px w-lg-300px mb-10 mb-lg-20"
        src="{{ asset('assets/media/auth/agency-dark.png') }}" alt="" />
      <!--end::Image-->

      <!--begin::Title-->
      <h1 class="text-gray-800 fs-2qx fw-bold text-center mb-7">
        Bhopal Food Court
      </h1>
      <!--end::Title-->

      <!--begin::Text-->
      <!--end::Text-->
    </div>
    <!--end::Content-->
  </div>
  <!--begin::Aside-->

  <!--begin::Body-->
  <div class="d-flex flex-column-fluid flex-lg-row-auto justify-content-center justify-content-lg-end p-12">
    <!--begin::Wrapper-->
    <div class="bg-body d-flex flex-column flex-center rounded-4 w-md-600px p-10">
      <!--begin::Content-->
      <div class="d-flex flex-center flex-column align-items-stretch h-lg-100 w-md-400px">
        <!--begin::Wrapper-->
        <div class="d-flex flex-center flex-column flex-column-fluid pb-15 pb-lg-20">

          <!--begin::Form-->
          <!--begin::Form-->
          @if ($settings->sign_in_method == 'firebase')
            @include('auth.components.register.customerRegisterForm')
            <!--end::Form-->
            @include('auth.components.register.otpVerificationForm')
          @elseif ($settings->sign_in_method == 'otpless')
            <div id="otpless-login-page"></div>
          @endif
          <!-- OTPLESS Login UI -->
        </div>
        <!--end::Wrapper-->

        <!--begin::Footer-->
        <div class=" d-flex flex-stack  justify-content-end">

          <!--begin::Links-->
          <div class="d-flex fw-semibold text-primary fs-base gap-5">
            <a href="/" target="_blank">Terms &
              Conditions</a>
          </div>
          <!--end::Links-->
        </div>
        <!--end::Footer-->
      </div>
      <!--end::Content-->
    </div>
    <!--end::Wrapper-->
  </div>
  <!--end::Body-->
@endsection
@section('scripts')
  <script>
    function otpless(otplessUser) {
      const otpless_user_token = otplessUser.token;
      var formData = new FormData();
      formData.append("_token", $('meta[name="csrf-token"]').attr('content'));
      formData.set("otpless_user_token", otpless_user_token);
      $.ajax({
        url: "/otp-less-sign-in",
        type: "POST",
        data: formData,
        processData: false,
        contentType: false,
        success: function(response) {
          if (response.success) {
            window.location = "/"
          }
        },
        error: function(error) {
          var errors = error.responseJSON.errors;
          var errorMessage = Object.values(errors).flat().join("<br>");
          Swal.fire({
            html: errorMessage,
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
              confirmButton: "btn btn-primary",
            },
          });
        }
      })
    }
  </script>
  <script>
    $(document).ready(function() {
      // Initialize intl-tel-input
      const input = document.querySelector("#phone");
      var iti = window.intlTelInput(input, {
        utilsScript: "{{ asset('custom/assets/js/intlTelInput/utils.js') }}",
        separateDialCode: true,
        initialCountry: "auto",
        onlyCountries: ['bd', 'in'],
        initialCountry: 'bd',
      });
      $("#kt_datepicker_dob_custom").flatpickr({
        disableMobile: true
      });

      var signInMethod = '{{ $settings->sign_in_method }}'

      var coderesult;

      if (signInMethod == 'firebase') {
        var appVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container');
        appVerifier.render()
      }

      var form, submitButton;
      let phoneNumber = iti.getNumber();

      var KTAddCustomer = (function() {

        return {
          init: function() {
            form = document.querySelector("#customer_signup_form");
            submitButton = document.querySelector("#customer_form_submit_btn");

            // Initialize FormValidation
            var fv = FormValidation.formValidation(form, {
              fields: {
                // Define your validation rules here
                name: {
                  validators: {
                    notEmpty: {
                      message: "Name is required",
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
                        var isValidPhoneNumber = iti.isValidNumber();

                        return isValidPhoneNumber;
                      }
                    }
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
                date_of_birth: {
                  validators: {
                    notEmpty: {
                      message: "Date of birth is required",
                    },
                    date: {
                      format: 'YYYY-MM-DD',
                      message: 'The input is not a valid date',
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
              phoneNumber = iti.getNumber();
              fv.validate().then(function(isValid) {
                if (isValid === "Valid") {
                  if (signInMethod == 'firebase') {
                    if (grecaptcha.getResponse().length !== 0) {
                      // console.log(form.phone.value);
                      submitButton.setAttribute("data-kt-indicator", "on");
                      submitButton.disabled = true;

                      var formData = new FormData(form);
                      formData.append(
                        "_token",
                        document.querySelector('meta[name="csrf-token"]')
                        .content
                      );
                      formData.set("phone", phoneNumber);
                      $.ajax({
                        url: "/register/verify", // Update with your actual API endpoint
                        type: "POST",
                        data: formData,
                        processData: false,
                        contentType: false,
                        success: function(response) {
                          $('#masked-number').html('*'.repeat(String(form.phone.value).length -
                              4) +
                            String(form.phone.value).slice(-4))
                          firebase.auth().signInWithPhoneNumber(phoneNumber, appVerifier)
                            .then(function(confirmationResult) {
                              window.confirmationResult = confirmationResult;
                              coderesult = confirmationResult;
                              $("#customer_signup_form").css({
                                'display': 'none'
                              })
                              $("#otp_verification_form").css({
                                'display': 'block'
                              })
                            }).catch(function(error) {
                              console.log(error);
                              var errorMessage = Object.values(error)
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
                            }).finally(() => {
                              submitButton.removeAttribute(
                                "data-kt-indicator"
                              );
                              submitButton.disabled = false;
                            });
                          $("#customer_signup_form").css({
                            'display': 'none'
                          })
                          $("#otp_verification_form").css({
                            'display': 'block'
                          })
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
                          submitButton.removeAttribute(
                            "data-kt-indicator"
                          );
                          submitButton.disabled = false;
                        },
                      });
                    } else {
                      Swal.fire({
                        text: "Please verify Captcha!",
                        icon: "error",
                        buttonsStyling: !1,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                          confirmButton: "btn btn-primary",
                        },
                      });
                    }
                  }
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
          },
        };
      })();
      "use strict";
      var KTSigninTwoFactor = (function() {
        var t, e;

        return {
          init: function() {
            t = document.querySelector("#otp_verification_form");
            e = document.querySelector("#otp_verification_form_submit");

            e.addEventListener("click", function(n) {
              n.preventDefault();
              const inputElements = document.querySelectorAll('input[name^="code_"]');
              let otpDigits = [];

              // Loop through each input element to retrieve the entered value
              inputElements.forEach(function(input) {
                const value = input.value.trim(); // Get the trimmed value of the input

                // Check if the value is a valid single-digit number (0-9)
                if (/^\d$/.test(value)) {
                  otpDigits.push(value); // Add the valid digit to the OTP digits array
                }
              });

              const otpCode = otpDigits.join('');

              if (otpCode) {
                e.setAttribute("data-kt-indicator", "on");
                e.disabled = true;

                coderesult.confirm(otpCode).then((res) => {
                  const firebaseIdToken = res.user.ra;
                  var formData = new FormData(form);
                  formData.append("_token", document.querySelector('meta[name="csrf-token"]').content);
                  formData.set("phone", phoneNumber);
                  formData.append("firebaseIdToken", firebaseIdToken);

                  $.ajax({
                    url: "/register", // Update with your actual API endpoint
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                      if (response.success) {
                        var n = t.getAttribute("data-kt-redirect-url");
                        if (n) location.href = n;
                      } else {
                        Swal.fire({
                          html: response.message,
                          icon: "error",
                          buttonsStyling: false,
                          confirmButtonText: "Ok, got it!",
                          customClass: {
                            confirmButton: "btn btn-primary",
                          },
                        });
                        e.removeAttribute("data-kt-indicator");
                        e.disabled = false;
                      }
                    },
                    error: function(error) {
                      // Registration failed, show error messages
                      console.log(error);
                      var errors = error.responseJSON.errors;
                      var errorMessage = Object.values(errors).flat().join("<br>");

                      Swal.fire({
                        html: errorMessage,
                        icon: "error",
                        buttonsStyling: false,
                        confirmButtonText: "Ok, got it!",
                        customClass: {
                          confirmButton: "btn btn-primary",
                        },
                      });
                      e.removeAttribute("data-kt-indicator");
                      e.disabled = false;
                    },
                  });
                }).catch(error => {
                  Swal.fire({
                    text: error.message,
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                      confirmButton: "btn btn-primary",
                    },
                  });
                  e.removeAttribute("data-kt-indicator");
                  e.disabled = false;
                });
              } else {
                Swal.fire({
                  text: "Please enter a valid security code and try again.",
                  icon: "error",
                  buttonsStyling: false,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                    confirmButton: "btn fw-bold btn-light-primary",
                  },
                }).then(function() {
                  window.scrollTo(0, 0); // Scroll to the top
                });
              }
            });

            const inputs = document.querySelectorAll("#otp_verification_form input[type='text']");

            inputs.forEach((input, index) => {
              input.addEventListener("keydown", function(e) {
                if (e.key === "Backspace" && this.value.length === 0 && index > 0) {
                  inputs[index - 1].focus();
                } else if ((e.key >= "0" && e.key <= "9") || e.key === "ArrowRight" || e.key ===
                  "ArrowLeft") {
                  if (this.value.length === 1 && index < inputs.length - 1) {
                    inputs[index + 1].focus();
                  }
                } else if (e.key === "ArrowRight" && index < inputs.length - 1) {
                  inputs[index + 1].focus();
                } else if (e.key === "ArrowLeft" && index > 0) {
                  inputs[index - 1].focus();
                }
              });

              input.addEventListener("input", function() {
                if (this.value.length === 1 && index < inputs.length - 1) {
                  inputs[index + 1].focus();
                }
              });
            });

            inputs[0].focus();
          },
        };
      })();

      // Initialize the script on page load
      KTUtil.onDOMContentLoaded(function() {
        KTAddCustomer.init();
        KTSigninTwoFactor.init();

      });
      $("#kt_datepicker_dob").flatpickr();
    });
  </script>
@endsection
