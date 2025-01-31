"use strict";
var KTModalNewTarget = (function () {
    var t, e, n, a, o, i;
    return {
        init: function () {
            (i = document.querySelector("#kt_modal_new_target")) &&
                ((o = new bootstrap.Modal(i)),
                (a = document.querySelector("#kt_modal_new_target_form")),
                (t = document.getElementById("kt_modal_new_target_submit")),
                (e = document.getElementById("kt_modal_new_target_cancel")),
                new Tagify(a.querySelector('[name="tags"]'), {
                    whitelist: ["Important", "Urgent", "High", "Medium", "Low"],
                    maxTags: 5,
                    dropdown: { maxItems: 10, enabled: 0, closeOnSelect: !1 },
                }).on("change", function () {
                    n.revalidateField("tags");
                }),
                $(a.querySelector('[name="due_date"]')).flatpickr({
                    enableTime: !0,
                    dateFormat: "d, M Y, H:i",
                }),
                $(a.querySelector('[name="team_assign"]')).on(
                    "change",
                    function () {
                        n.revalidateField("team_assign");
                    }
                ),
                (n = FormValidation.formValidation(a, {
                    fields: {
                        name: {
                            validators: {
                                notEmpty: {
                                    message: "Name is required",
                                },
                            },
                        },
                        email: {
                            validators: {
                                notEmpty: {
                                    message: "Email Address is required",
                                },
                            },
                        },
                        password: {
                            validators: {
                                notEmpty: {
                                    message: "Password is required",
                                },
                            },
                        },
                        password_confirmation: {
                            validators: {
                                notEmpty: {
                                    message: "Confirm Password is required",
                                },
                                identical: {
                                    compare: function () {
                                        return a.querySelector(
                                            '[name="password"]'
                                        ).value;
                                    },
                                    message:
                                        "The password and confirm password must match",
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
                })),
                t.addEventListener("click", function (e) {
                    e.preventDefault(),
                        n &&
                            n.validate().then(function (isValid) {
                                if (isValid === "Valid") {
                                    t.setAttribute("data-kt-indicator", "on");
                                    t.disabled = true;

                                    var formData = new FormData(a);

                                    // Get CSRF token from meta tag
                                    var csrfToken = document.head.querySelector(
                                        'meta[name="csrf-token"]'
                                    ).content;

                                    // Include CSRF token in the headers of the AJAX request
                                    $.ajax({
                                        url: "/dashboard/register",
                                        type: "POST",
                                        data: formData,
                                        headers: {
                                            "X-CSRF-TOKEN": csrfToken,
                                        },
                                        processData: false,
                                        contentType: false,
                                        success: function (response) {
                                            // Registration successful
                                            Swal.fire({
                                                text: "Admin created successfully!",
                                                icon: "success",
                                                buttonsStyling: !1,
                                                confirmButtonText:
                                                    "Ok, got it!",
                                                customClass: {
                                                    confirmButton:
                                                        "btn btn-primary",
                                                },
                                            }).then(function (t) {
                                                if (t.isConfirmed) {
                                                    // Hide the modal
                                                    o.hide();

                                                    // Clear the form fields
                                                    a.reset();
                                                }
                                            });
                                        },
                                        error: function (error) {
                                            // Registration failed, show error messages
                                            var errors =
                                                error.responseJSON.errors;

                                            var errorMessage = Object.values(
                                                errors
                                            )
                                                .flat()
                                                .join("<br>");

                                            Swal.fire({
                                                text: errorMessage,
                                                icon: "error",
                                                buttonsStyling: !1,
                                                confirmButtonText:
                                                    "Ok, got it!",
                                                customClass: {
                                                    confirmButton:
                                                        "btn btn-primary",
                                                },
                                            });
                                        },
                                        complete: function () {
                                            t.removeAttribute(
                                                "data-kt-indicator"
                                            );
                                            t.disabled = false;
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
                }),
                e.addEventListener("click", function (t) {
                    t.preventDefault(),
                        Swal.fire({
                            text: "Are you sure you would like to cancel?",
                            icon: "warning",
                            showCancelButton: !0,
                            buttonsStyling: !1,
                            confirmButtonText: "Yes, cancel it!",
                            cancelButtonText: "No, return",
                            customClass: {
                                confirmButton: "btn btn-primary",
                                cancelButton: "btn btn-active-light",
                            },
                        }).then(function (t) {
                            t.value
                                ? (a.reset(), o.hide())
                                : "cancel" === t.dismiss &&
                                  Swal.fire({
                                      text: "Your form has not been cancelled!.",
                                      icon: "error",
                                      buttonsStyling: !1,
                                      confirmButtonText: "Ok, got it!",
                                      customClass: {
                                          confirmButton: "btn btn-primary",
                                      },
                                  });
                        });
                }));
        },
    };
})();
KTUtil.onDOMContentLoaded(function () {
    KTModalNewTarget.init();
});
