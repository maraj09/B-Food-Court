@extends('layouts.guest')
@section('contents')
  @php
    use App\Models\Setting;
    $settings = Setting::first();
  @endphp
  <style>
    .iti {
      width: 100%;
      display: block;
    }

    .iti__country-name {
      color: #000;
    }

    .iti__search-input {
      background: white;
      color: #000;

    }
  </style>
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
      {{-- <div class="text-gray-600 fs-base text-center fw-semibold">
        In this kind of post, <a href="#" class="opacity-75-hover text-primary me-1">the blogger</a>

        introduces a person they’ve interviewed <br /> and provides some background information about

        <a href="#" class="opacity-75-hover text-primary me-1">the interviewee</a>
        and their <br /> work following this is a transcript of the interview.
      </div> --}}
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
          @if ($settings->sign_in_method == 'firebase')
            <form class="form w-100" id="customer_signin_form" method="POST">
              @csrf
              <!--begin::Heading-->
              <div class="text-center mb-11">
                <!--begin::Title-->
                <h1 class="text-gray-900 fw-bolder mb-3">
                  Sign In
                </h1>
                <!--end::Title-->

                <!--begin::Subtitle-->
                <div class="text-gray-500 fw-semibold fs-6">
                  Best food court in your area
                </div>
                <!--end::Subtitle--->
              </div>
              <!--begin::Heading-->

              <!--begin::Login options-->
              <div class="row g-3 mb-9">
                <!--begin::Col-->

                <!--end::Col-->
              </div>
              <!--end::Login options-->

              <!--begin::Input group--->
              <div class="fv-row mb-8">
                <!--begin::Phone-->
                <input id="phone" type="text" name="phone" autocomplete="off"
                  class="form-control bg-transparent @error('phone') is-invalid @enderror" value="{{ old('phone') }}" />
                <!--end::Phone-->
                @error('phone')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <!--end::Input group--->

              <!--end::Input group--->

              <!--begin::Wrapper-->

              <!--end::Wrapper-->
              <div id="recaptcha-container-custom" class="mb-5"></div>

              <!--begin::Submit button-->
              <div class="d-grid mb-10">
                <button type="submit" id="kt_sign_in_submit" class="btn btn-primary">

                  <!--begin::Indicator label-->
                  <span class="indicator-label">
                    Sign In</span>
                  <!--end::Indicator label-->

                  <!--begin::Indicator progress-->
                  <span class="indicator-progress">
                    Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                  </span>
                  <!--end::Indicator progress--> </button>
              </div>
              <!--end::Submit button-->
              <!--begin::Sign up-->
              <div class="text-gray-500 text-center fw-semibold fs-6">
                Don't have an account?

                <a href="{{ route('register') }}" class="link-primary">
                  Sign up
                </a>
              </div>
              <!--end::Sign up-->
            </form>
            @include('auth.components.register.otpVerificationForm')
          @elseif ($settings->sign_in_method == 'otpless')
            <div id="otpless-login-page"></div>
          @endif

          <!--end::Form-->

        </div>
        <!--end::Wrapper-->

        <!--begin::Footer-->
        <div class=" d-flex flex-stack  justify-content-end">
          <!--begin::Languages-->

          <!--end::Languages-->

          <!--begin::Links-->
          <div class="d-flex fw-semibold text-primary fs-base gap-5">
            <a href="https://preview.keenthemes.com/metronic8/demo1/pages/team.html" target="_blank">Terms &
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
  <script src="{{ asset('assets/js/custom/authentication/sign-in/general.js') }}"></script>
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
    const input = document.querySelector("#phone");
    var iti = window.intlTelInput(input, {
      utilsScript: "{{ asset('custom/assets/js/intlTelInput/utils.js') }}",
      separateDialCode: true,
      initialCountry: "auto",
      onlyCountries: ['bd', 'in'],
      initialCountry: 'bd',
    });
  </script>
  <script type="text/javascript">
    window.recaptchaVerifier = new firebase.auth.RecaptchaVerifier('recaptcha-container-custom');
    window.recaptchaVerifier.render()
    const appVerifier = window.recaptchaVerifier;


    var form, submitButton;
    var phoneNumber;
    var KTAddCustomer = (function() {

      return {
        init: function() {
          form = document.querySelector("#customer_signin_form");
          submitButton = document.querySelector("#kt_sign_in_submit");

          // Initialize FormValidation
          var fv = FormValidation.formValidation(form, {
            fields: {
              phone: {
                validators: {
                  callback: {
                    message: "Invalid phone number",
                    callback: function(input) {
                      // Get the phone number value
                      phoneNumber = input.value;

                      // Validate the phone number format using intl-tel-input
                      var isValidPhoneNumber = iti.isValidNumber();

                      return isValidPhoneNumber;
                    }
                  }
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
                if (true) {
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
                    formData.set("phone", iti.getNumber());
                    $.ajax({
                      url: "/login/verify", // Update with your actual API endpoint
                      type: "POST",
                      data: formData,
                      processData: false,
                      contentType: false,
                      success: function(response) {
                        $('#masked-number').html('*'.repeat(String(form.phone.value).length - 4) +
                          String(form.phone.value).slice(-4))
                        firebase.auth().signInWithPhoneNumber(iti.getNumber(), appVerifier)
                          .then(function(confirmationResult) {
                            window.confirmationResult = confirmationResult;
                            coderesult = confirmationResult;
                            $("#customer_signin_form").css({
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
                        $("#customer_signin_form").css({
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
                } else {
                  const options = {
                    method: 'POST',
                    headers: {
                      clientId: 'BDLU6BNYNR8PGPQXSOESC29IVMGI59LH',
                      clientSecret: 'b30m1yyg0llzler8z6epxd9xp9vozz0n',
                      'Content-Type': 'application/json'
                    },
                    body: '{"phoneNumber":"+8801706055613","expiry":30,"otpLength":4,"channels":["WHATSAPP","SMS"],"metaData":{"key1":"Data1","key2":"Data2"}}'
                  };

                  fetch('https://auth.otpless.app/auth/v1/initiate/otp', options)
                    .then(response => response.json())
                    .then(response => console.log(response))
                    .catch(err => console.error(err));
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
          var form;

          t = document.querySelector("#otp_verification_form");
          e = document.querySelector("#otp_verification_form_submit");

          form = document.querySelector('#otp_verification_form'); // Add form reference

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
                formData.set("phone", iti.getNumber());
                formData.append("firebaseIdToken", firebaseIdToken);

                $.ajax({
                  url: "{{ route('login') }}", // Update with your actual API endpoint
                  type: "POST",
                  data: formData,
                  processData: false,
                  contentType: false,
                  success: function(response) {
                    location.href = '/';
                    e.removeAttribute("data-kt-indicator");
                    e.disabled = false;
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
                text: "Please enter valid security code and try again.",
                icon: "error",
                buttonsStyling: false,
                confirmButtonText: "Ok, got it!",
                customClass: {
                  confirmButton: "btn fw-bold btn-light-primary",
                },
              }).then(function() {
                window.scrollTo(0, 0); // Change KTUtil.scrollTop() to window.scrollTo()
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
  </script>
@endsection
