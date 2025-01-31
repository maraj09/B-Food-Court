"use strict";
/////////////////////////////////////////////////
/////// Handling Adding Vendors ----- Start
/////////////////////////////////////////////////

var KTAddVendor = (function () {
    var modal, form, submitButton, cancelButton, drawer;

    return {
        init: function () {
            modal = document.querySelector("#kt_add_vendor");
            drawer = KTDrawer.getInstance(modal);
            form = document.querySelector("#kt_modal_update_user_form");
            submitButton = document.querySelector("#kt_add_vendor_submit");
            cancelButton = document.querySelector("#kt_add_vendor_close");

            // Initialize FormValidation
            var fv = FormValidation.formValidation(form, {
                fields: {
                    // Define your validation rules here
                    brand_name: {
                        validators: {
                            notEmpty: {
                                message: "Brand Name is required",
                            },
                        },
                    },
                    commission: {
                        validators: {
                            notEmpty: {
                                message: "Commission is required",
                            },
                        },
                    },
                    name: {
                        validators: {
                            notEmpty: {
                                message: "Brand Owner Name is required",
                            },
                        },
                    },
                    email: {
                        validators: {
                            regexp: {
                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                message:
                                    "The value is not a valid email address",
                            },
                        },
                    },
                    phone: {
                        validators: {
                            callback: {
                                message: "Invalid phone number",
                                callback: function (input) {
                                    // Get the phone number value
                                    var phoneNumber = input.value;

                                    // Validate the phone number format using intl-tel-input
                                    var isValidPhoneNumber =
                                        iti.isValidNumber();

                                    return isValidPhoneNumber;
                                },
                            },
                        },
                    },
                    "documents[]": {
                        validators: {
                            file: {
                                maxSize: 10485760, // 2 MB
                                message: "The selected file is not valid",
                                type: "application/pdf,image/jpeg, image/jpg, image/png,image/gif,image/bmp,image/tiff",
                                // Validate file type
                                // Check if the file type is allowed (images and PDF)
                                callback: function (input) {
                                    var file = input.files[0];
                                    if (!file) {
                                        return false;
                                    }

                                    // Get the file MIME type
                                    var fileType = file.type;

                                    // Define allowed MIME types for images
                                    var allowedImageTypes = [
                                        "image/jpeg",
                                        "image/jpg",
                                        "image/png",
                                        "image/gif",
                                        "image/bmp",
                                        "image/tiff",
                                    ];

                                    // Check if the file MIME type is allowed (image or PDF)
                                    return (
                                        fileType === "application/pdf" ||
                                        allowedImageTypes.includes(fileType)
                                    );
                                },
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
            submitButton.addEventListener("click", function (e) {
                e.preventDefault();
                let phoneNumber = iti.getNumber();
                fv.validate().then(function (isValid) {
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
                            url: "/dashboard/vendor/register", // Update with your actual API endpoint
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                console.log(response);
                                Swal.fire({
                                    text: "Form has been successfully submitted!",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary",
                                    },
                                }).then(function (result) {
                                    if (result.isConfirmed) {
                                        drawer.hide();
                                        form.reset(); // Reset the form after successful submission
                                        location.reload();
                                    }
                                });
                            },
                            error: function (error) {
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
                            complete: function () {
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

            // Reset form on cancel
            cancelButton.addEventListener("click", function (e) {
                e.preventDefault();
                form.reset();
                drawer.hide();
            });
        },
    };
})();

// Initialize the script on page load
KTUtil.onDOMContentLoaded(function () {
    KTAddVendor.init();
});

/////////////////////////////////////////////////
/////// Handling Adding Vendors ----- End
/////////////////////////////////////////////////
/////////////////////////////////////////////////
/////// Handling Adding Customer ----- Start
/////////////////////////////////////////////////

var KTAddCustomer = (function () {
    var modal, form, submitButton, cancelButton, drawer;
    const input = document.querySelector("#phone_modal_add_customer");
    var itiC = window.intlTelInput(input, {
        utilsScript: utilsScript,
        separateDialCode: true,
        initialCountry: "auto",
        onlyCountries: ["bd", "in"],
        initialCountry: "bd",
    });
    return {
        init: function () {
            modal = new bootstrap.Modal(
                document.querySelector("#add_customer_modal")
            );
            form = document.querySelector("#kt_modal_add_customer_form");
            submitButton = document.querySelector("#kt_add_customer_save");
            cancelButton = document.querySelector("#kt_add_customer_close");

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
                    email: {
                        validators: {
                            regexp: {
                                regexp: /^[^\s@]+@[^\s@]+\.[^\s@]+$/,
                                message:
                                    "The value is not a valid email address",
                            },
                        },
                    },
                    date_of_birth: {
                        validators: {
                            notEmpty: {
                                message: "Date of birth is required",
                            },
                        },
                    },
                    phone: {
                        validators: {
                            callback: {
                                message: "Invalid phone number",
                                callback: function (input) {
                                    // Get the phone number value
                                    var phoneNumber = input.value;

                                    // Validate the phone number format using intl-tel-input
                                    var isValidPhoneNumber =
                                        itiC.isValidNumber();

                                    return isValidPhoneNumber;
                                },
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
            submitButton.addEventListener("click", function (e) {
                e.preventDefault();
                let phoneNumber = itiC.getNumber();
                fv.validate().then(function (isValid) {
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
                            url: "/dashboard/customers/register", // Update with your actual API endpoint
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                Swal.fire({
                                    text: "Form has been successfully submitted!",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary",
                                    },
                                }).then(function (result) {
                                    if (result.isConfirmed) {
                                        modal.hide();
                                        form.reset(); // Reset the form after successful submission
                                        location.reload();
                                    }
                                });
                            },
                            error: function (error) {
                                // Registration failed, show error messages
                                var errors = error.responseJSON.errors;
                                var errorMessage = Object.values(errors)
                                    .flat()
                                    .join("<br>");
                                console.log(errorMessage);

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
                            complete: function () {
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
            cancelButton.addEventListener("click", function (t) {
                t.preventDefault(), (form.reset(), modal.hide());
            });
        },
    };
})();
// Initialize the script on page load
$("#kt_datepicker_dob").flatpickr();
KTUtil.onDOMContentLoaded(function () {
    KTAddCustomer.init();
});
/////////////////////////////////////////////////
/////// Handling Adding Vendors ----- End
/////////////////////////////////////////////////

/////////////////////////////////////////////////
/////// Handling Adding Items ----- Start
/////////////////////////////////////////////////

var KTAddItem = (function () {
    var modal, form, submitButton, cancelButton, drawer;

    return {
        init: function () {
            modal = document.querySelector("#kt_add_item");
            drawer = KTDrawer.getInstance(modal);
            form = document.querySelector("#kt_modal_add_item_form");
            submitButton = document.querySelector("#kt_add_item_submit");
            cancelButton = document.querySelector("#kt_add_item_close");

            // Initialize FormValidation
            var fv = FormValidation.formValidation(form, {
                fields: {
                    // Define your validation rules here
                    item_name: {
                        validators: {
                            notEmpty: {
                                message: "Name is required",
                            },
                        },
                    },
                    price: {
                        validators: {
                            notEmpty: {
                                message: "Price is required",
                            },
                        },
                    },
                    vendor_id: {
                        validators: {
                            notEmpty: {
                                message: "Please select a vendor",
                            },
                        },
                    },
                    item_type: {
                        validators: {
                            notEmpty: {
                                message: "Please select a type",
                            },
                        },
                    },
                    category_id: {
                        validators: {
                            notEmpty: {
                                message: "Please select a category",
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
            submitButton.addEventListener("click", function (e) {
                e.preventDefault();
                fv.validate().then(function (isValid) {
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
                        $.ajax({
                            url: "/dashboard/items/store", // Update with your actual API endpoint
                            type: "POST",
                            data: formData,
                            processData: false,
                            contentType: false,
                            success: function (response) {
                                console.log(response);
                                Swal.fire({
                                    text: "Form has been successfully submitted!",
                                    icon: "success",
                                    buttonsStyling: false,
                                    confirmButtonText: "Ok, got it!",
                                    customClass: {
                                        confirmButton: "btn btn-primary",
                                    },
                                }).then(function (result) {
                                    if (result.isConfirmed) {
                                        drawer.hide();
                                        form.reset(); // Reset the form after successful submission
                                        location.reload();
                                    }
                                });
                            },
                            error: function (error) {
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
                            complete: function () {
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

            // Reset form on cancel
            cancelButton.addEventListener("click", function (e) {
                e.preventDefault();
                form.reset();
                drawer.hide();
            });
        },
    };
})();

// Initialize the script on page load
KTUtil.onDOMContentLoaded(function () {
    KTAddItem.init();
});

/////////////////////////////////////////////////
/////// Handling Adding Items ----- End
/////////////////////////////////////////////////

/////////////////////////////////////////////////
/////// Handling Item Status ----- Start
/////////////////////////////////////////////////

$(".status-checkbox").change(function () {
    // Get the item ID from the data attribute
    var itemId = $(this).data("item-id");

    // Save the reference to $(this) in a variable for later use
    var $checkbox = $(this);

    // Make an AJAX request to update the status
    $.ajax({
        url: "/dashboard/update-item-status/" + itemId, // Replace with your actual route
        type: "POST", // You can use 'PUT' or 'PATCH' depending on your setup
        data: {
            // Additional data to send, if any
        },
        headers: {
            "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
        },
        success: function (response) {
            showToast(response);
        },
        error: function (error) {
            console.error(error);
            showToast("error");
        },
    });
});

/////////////////////////////////////////////////
/////// Handling Item Status ----- END
/////////////////////////////////////////////////
var selectedItems = {};
function removeItem(itemId) {
    $("#item_" + itemId).remove();
    delete selectedItems[itemId];
    if ($("#coupon_used").length) {
        removeCoupon();
    } else {
        updateTotalCost();
    }
}
function updateTotalCost(couponDiscount = null) {
    var total = 0;
    for (var itemID in selectedItems) {
        if (selectedItems.hasOwnProperty(itemID)) {
            total +=
                selectedItems[itemID].price * selectedItems[itemID].quantity;
        }
    }
    $("#billbreakup").html("");
    $("#billbreakup").append(`
        <li class="d-flex align-items-center py-2">
            <span class="bullet bullet-vertical fw-bold me-5"></span>Total : <span class="ms-2">₹${parseFloat(
                total
            ).toFixed(2)}</span>
        </li>`);
    if ($("#coupon_used").length) {
        total -= couponDiscount;
    }
    var gstAmount = 0;
    var sgtAmount = 0;
    var serviceTaxAmount = 0;
    if (gst > 0) {
        gstAmount = total * (gst / 100);
        $("#billbreakup").append(` 
        <li class="d-flex align-items-center py-2">
            <span class="bullet bullet-vertical fw-bold bg-danger me-5"></span>GST ${parseFloat(
                gst
            ).toFixed(1)}%: <span class="ms-2">₹${parseFloat(gstAmount).toFixed(
            2
        )}</span>
        </li>`);
    }

    if (sgt > 0) {
        var sgtAmount = total * (sgt / 100);
        $("#billbreakup").append(` 
        <li class="d-flex align-items-center py-2">
            <span class="bullet bullet-vertical fw-bold bg-danger me-5"></span>SGT ${parseFloat(
                sgt
            ).toFixed(1)}%: <span class="ms-2">₹${parseFloat(sgtAmount).toFixed(
            2
        )}</span>
        </li>`);
    }

    if (serviceTax > 0) {
        var serviceTaxAmount = total * (serviceTax / 100);
        $("#billbreakup").append(` 
        <li class="d-flex align-items-center py-2">
            <span class="bullet bullet-vertical fw-bold bg-danger me-5"></span>Service Tax ${parseFloat(
                serviceTax
            ).toFixed(1)}%: <span class="ms-2">₹${parseFloat(
            serviceTaxAmount
        ).toFixed(2)}</span>
        </li>`);
    }

    if ($("#coupon_used").length) {
        $("#billbreakup").append(` 
        <li class="d-flex align-items-center py-2">
            <span class="bullet bullet-vertical fw-bold bg-success me-5"></span>Coupon Discount : <span class="ms-2">₹${parseFloat(
                couponDiscount
            ).toFixed(2)}</span>
        </li>`);
    }

    document.getElementById(
        "total_price_in_pay_now"
    ).innerHTML = `₹ ${parseFloat(
        total + serviceTaxAmount + sgtAmount + gstAmount
    ).toFixed(2)}<span
    class="badge badge-dark ms-2">${
        Object.keys(selectedItems).length
    } Items</span>`;
}

$(document).on("click", ".couponButton", function (event) {
    event.preventDefault();

    if ($("#coupon_used").length) {
        Swal.fire({
            text: "Please remove the coupon first!",
            icon: "error",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary",
            },
        });
        return false;
    }

    var couponCode = $(this).data("code");
    var discount = $(this).data("discount");
    var discountType = $(this).data("discount-type");
    var userId = $(`.new_order_drawer_select_customer`)
        .find("option:selected")
        .val();
    if (!userId) {
        Swal.fire({
            text: "Please select a customer first!",
            icon: "error",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary",
            },
        });
        return;
    }
    var selectedItemsArray = [];
    for (var itemID in selectedItems) {
        if (selectedItems.hasOwnProperty(itemID)) {
            selectedItemsArray.push({
                id: itemID,
                quantity: selectedItems[itemID].quantity,
            });
        }
    }
    var formData = {
        code: couponCode,
        userId: userId,
        items: selectedItemsArray,
        // status: $("#poitnStatus").prop("checked") ? "active" : "inactive",
        _token: document.querySelector('meta[name="csrf-token"]').content,
    };

    $.ajax({
        url: "/dashboard/apply-coupon",
        type: "POST",
        data: formData,
        success: function (response) {
            if (response.success) {
                $(`.coupon_span_${response.data.coupon.code}`).removeClass(
                    "btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning"
                );
                $(`.coupon_span_${response.data.coupon.code}`).addClass(
                    "btn-warning"
                );
                $(`.applied_coupon_head`).html(`${
                    response.data.coupon.code
                } : ${
                    response.data.coupon.discount_type == "fixed"
                        ? "₹" + response.data.coupon.discount
                        : response.data.coupon.discount + "%"
                } <i
                class="fas fa-info-circle text-primary ms-2 collapsible cursor-pointer" data-bs-toggle="tooltip"
                data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Applied Coupon"></i><input type="hidden" id="coupon_used" value="${
                    response.data.coupon.code
                }" />`);
                $(`.breakup_coupon_container`).append(
                    `<button onclick="removeCoupon()" id="removeCoupon" class="btn btn-danger btn-sm ms-2 p-2">Remove</button>`
                );
                updateTotalCost(response.data.coupon_discount);
            } else {
                Swal.fire({
                    text: response.message,
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok, got it!",
                    customClass: {
                        confirmButton: "btn btn-primary",
                    },
                });
            }
        },
        error: function (error) {
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
        },
    });
});

function removeCoupon() {
    var code = $("#coupon_used").val();
    $(`#removeCoupon`).remove();
    $(`.coupon_span_${code}`).addClass(
        "btn-outline btn-outline-dashed btn-outline-warning btn-active-light-warning"
    );
    $(`.coupon_span_${code}`).removeClass("btn-warning");
    $(`.applied_coupon_head`).html(`Coupons <i
    class="fas fa-info-circle text-primary ms-2 collapsible cursor-pointer" data-bs-toggle="tooltip"
    data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Applied Coupon"></i>`);
    updateTotalCost();
}

function adminCartDrawerClear() {
    selectedItems = {};
    $("#selectedItemsDetails").html("");
    if ($("#coupon_used").length) {
        removeCoupon();
    } else {
        updateTotalCost();
    }
}

function updateQuantity(itemId, action) {
    if ($("#coupon_used").length) {
        removeCoupon();
    }

    var quantityElement = document.getElementById(`quantity_${itemId}`);
    var currentQuantity = parseInt(quantityElement.value);
    var newQuantity = currentQuantity;

    if (action === "increase") {
        newQuantity++;
    } else if (action === "decrease" && newQuantity > 1) {
        newQuantity--;
    }

    quantityElement.value = newQuantity;

    // Update the quantity in the selectedItems object
    selectedItems[itemId].quantity = newQuantity;
    $(`#item_total_price_${itemId}`).html(
        "₹" +
            Math.round(
                selectedItems[itemId].quantity * selectedItems[itemId].price
            )
    );
    // Update the total cost
    updateTotalCost();
}
$(document).ready(function () {
    $("#kt_docs_select2_rich_content").change(function () {
        var selectedItem = $(this).find("option:selected");
        var itemId = selectedItem.val();
        $("#kt_docs_select2_rich_content").val(0).trigger("change.select2");
        // Check if the selected item is already displayed
        if ($("#coupon_used").length) {
            removeCoupon();
        }
        if ($("#item_" + itemId).length > 0) {
            // Item is already displayed, remove it
            $("#item_" + itemId).remove();
            delete selectedItems[itemId];
        } else {
            // Item is not displayed, append it
            var itemName = selectedItem.text();
            var itemPrice = selectedItem.data("item-price");
            var itemVendor = selectedItem.data("kt-rich-content-vendor");
            var itemIcon = selectedItem.data("kt-rich-content-icon");
            var itemStallNo = selectedItem.data("kt-rich-content-stall");
            var itemRating = selectedItem.data("kt-rich-content-rating");

            // Construct item details HTML
            var itemDetailsHtml = `
            <div id="item_${itemId}" class="d-block align-items-center border-dashed border-gray-900 bg-light rounded p-3 mb-7">
            <div class="d-flex align-items-sm-center mb-2">
                <div class="symbol symbol-50px symbol-circle me-2">
                <img src="${itemIcon}" class="align-self-center" alt="${itemName}">
                </div>
                <div class="d-flex align-items-center flex-row-fluid flex-wrap">
                    <div class="flex-grow-1 me-5">
                        <a href="#" class="text-gray-800 text-hover-primary fs-6 fw-bold">${itemName}</a>
                        <div class="d-flex flex-wrap flex-grow-1">
                        <div class="me-2">
                            <span class="text-success fw-bold">Price</span>
                            <span class="fw-bold text-gray-800 d-block fs-6">₹${Math.round(
                                itemPrice
                            )}</span>
                        </div>
                        <div class="me-5s">
                            <span class="text-danger fw-bold">Total</span>
                            <span class="fw-bold text-gray-800 d-block fs-6" id="item_total_price_${itemId}">₹${Math.round(
                itemPrice
            )}</span>
                            </div>
                        </div>
                    </div>
                    <span style="cursor: pointer" onclick="removeItem(${itemId})">
                        <div class="symbol symbol-35px">
                            <div class="symbol-label bg-light-danger">
                            <i class="fas fa-trash-alt text-danger"></i>
                            </div>
                        </div>
                    </span>
                </div>
            </div>
            <div class="d-flex flex-stack flex-wrap flex-grow-1">
                <div class="fw-bold fs-3 text-info">
                    <div class="input-group w-md-300px w-125px" data-kt-dialer="true" data-kt-dialer-min="1"
                    data-kt-dialer-max="5" data-kt-dialer-step="1">
                    <!--begin::Decrease control-->
                    <button class="btn btn-icon btn-outline btn-active-color-primary" type="button"
                        data-kt-dialer-control="decrease" onclick="updateQuantity(${itemId}, 'decrease')">
                        <i class="bi bi-dash fs-1"></i>
                    </button>
                    <!--end::Decrease control-->
                    <!--begin::Input control-->
                    <input type="text" class="form-control" readonly placeholder="Amount" id="quantity_${itemId}" value="1"
                        data-kt-dialer-control="input" />
                    <!--end::Input control-->
                    <!--begin::Increase control-->
                    <button class="btn btn-icon btn-outline btn-active-color-primary" type="button"
                        data-kt-dialer-control="increase" onclick="updateQuantity(${itemId}, 'increase')" >
                        <i class="bi bi-plus fs-1"></i>
                    </button>
                    <!--end::Increase control-->
                    </div>
                </div>
                <div class="me-2 text-end">
                <span class="text-info fw-bold">Vendor</span>
                <span class="fw-bold text-gray-800 d-block fs-6">${itemVendor}<span class="badge badge-primary badge-sm ms-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Stall No.">${itemStallNo}</span></span>
                </div>
            </div>
            </div>
        `;

            // Append item details HTML to container
            $("#selectedItemsDetails").append(itemDetailsHtml);

            selectedItems[itemId] = {
                price: parseFloat(itemPrice),
                quantity: 1,
            };
        }
        updateTotalCost();
    });
});

function placeOrder(paymentMethod, e) {
    e.preventDefault();
    var selectedItemsArray = [];
    for (var itemID in selectedItems) {
        if (selectedItems.hasOwnProperty(itemID)) {
            selectedItemsArray.push({
                id: itemID,
                quantity: selectedItems[itemID].quantity,
            });
        }
    }
    if (selectedItemsArray.length < 1) {
        Swal.fire({
            text: "Please select at least one item!",
            icon: "error",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary",
            },
        });
        return false;
    }

    var userId = $(`.new_order_drawer_select_customer`)
        .find("option:selected")
        .val();
    if (!userId) {
        Swal.fire({
            text: "Please select a customer first!",
            icon: "error",
            buttonsStyling: !1,
            confirmButtonText: "Ok, got it!",
            customClass: {
                confirmButton: "btn btn-primary",
            },
        });
        return;
    }
    Swal.fire({
        text: "Are you sure you would like to place an order?",
        icon: "warning",
        showCancelButton: !0,
        buttonsStyling: !1,
        confirmButtonText: "Yes, Place order!",
        cancelButtonText: "No, return",
        customClass: {
            confirmButton: "btn btn-success",
            cancelButton: "btn btn-active-light",
        },
    }).then(function (res) {
        if (res.isConfirmed) {
            var formData = {
                user_id: userId,
                payment_method: paymentMethod,
                status: "paid",
                ajax: "ajax",
                coupon_code: $("#coupon_used").val(),
                items: selectedItemsArray,
                // status: $("#poitnStatus").prop("checked") ? "active" : "inactive",
                _token: document.querySelector('meta[name="csrf-token"]')
                    .content,
            };

            $.ajax({
                url: "/dashboard/orders/store",
                type: "POST",
                data: formData,
                success: function (response) {
                    console.log(response);
                    if (response.success) {
                        Swal.fire({
                            text: "Order successfully placed!",
                            icon: "success",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        }).then((res) => {
                            location.reload();
                        });
                    } else {
                        Swal.fire({
                            text: response.message,
                            icon: "error",
                            buttonsStyling: false,
                            confirmButtonText: "Ok, got it!",
                            customClass: {
                                confirmButton: "btn btn-primary",
                            },
                        });
                    }
                },
                error: function (error) {
                    console.log(error);

                    var errors = error.responseJSON.errors;
                    var errorMessage = Object.values(errors)
                        .flat()
                        .join("<br>");

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
            });
        }
    });
}
