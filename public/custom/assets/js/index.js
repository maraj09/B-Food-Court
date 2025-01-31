/////////////////////////////////////////////////
/////// Handling DELETE FOR LIST TABLE ----- START
/////////////////////////////////////////////////
function submitParentForm(element, formId = null, isDelete = true) {
    // Find the closest form element to the clicked anchor
    var form;
    if (formId) {
        form = document.querySelector(formId);
    } else {
        form = element.closest("form");
    }

    if (isDelete) {
        // Check if a form is found before submitting
        Swal.fire({
            text: "Are you sure you would like to delete?",
            icon: "warning",
            showCancelButton: !0,
            buttonsStyling: !1,
            confirmButtonText: "Yes, delete it!",
            cancelButtonText: "No, return",
            customClass: {
                confirmButton: "btn btn-danger",
                cancelButton: "btn btn-active-light",
            },
        }).then(function (res) {
            // console.log(res.value);
            if (res.value && form) {
                form.submit();
            }
        });
    } else {
        form.submit();
    }
}
/////////////////////////////////////////////////
/////// Handling DELETE FOR LIST TABLE ----- END
/////////////////////////////////////////////////

// Function to show toast based on status response
function showToast(status, message = "Status changed successful!") {
    // Create new toast element
    const container = document.querySelector("#kt_docs_toast_stack_container");
    const targetElement = document.querySelector(
        '[data-kt-docs-toast="stack"]'
    );
    const newToast = targetElement.cloneNode(true);
    container.append(newToast);

    // Update toast content based on the status response
    newToast.querySelector(".toast-body").textContent =
        status === "error" && !message
            ? "An error occurred. Please try again."
            : message;

    // Add appropriate class for styling
    newToast.classList.add(status === "error" ? "bg-danger" : "bg-success");

    // Create new toast instance
    const toast = new bootstrap.Toast(newToast);

    // Toggle toast to show
    toast.show();

    setTimeout(() => {
        toast.hide(); // Hide the toast after 5 seconds
    }, 2000);
}

function showPointToast(
    status,
    message = "Status changed successful!",
    heading = "Points Credited"
) {
    // Create new toast element
    const container = document.querySelector(
        "#kt_docs_toast_stack_container_points"
    );
    const targetElement = document.querySelector(
        '[data-kt-docs-toast="points"]'
    );
    const newToast = targetElement.cloneNode(true);
    container.append(newToast);

    newToast.querySelector(".toast-head").textContent = heading;

    // Update toast content based on the status response
    newToast.querySelector(".toast-body").textContent =
        status === "error" && !message
            ? "An error occurred. Please try again."
            : message;

    // Add appropriate class for styling
    newToast.classList.add(status === "error" ? "bg-danger" : "bg-success");

    // Create new toast instance
    const toast = new bootstrap.Toast(newToast);

    // Toggle toast to show
    toast.show();

    setTimeout(() => {
        toast.hide(); // Hide the toast after 5 seconds
    }, 3000);
}

function durationCalculation(startDateString, endDateString) {
    // Parse the date strings
    const startDate = moment(startDateString);
    const endDate = moment(endDateString);

    // Calculate the difference in milliseconds
    const duration = moment.duration(endDate.diff(startDate));

    // Initialize formatted duration
    let formattedDuration = "";

    // Check for days
    if (duration.days() > 0) {
        formattedDuration += duration.days() + " day";
        if (duration.days() > 1) {
            formattedDuration += "s";
        }
    } else if (duration.hours() > 0) {
        // Check for hours
        formattedDuration += duration.hours() + " hour";
        if (duration.hours() > 1) {
            formattedDuration += "s";
        }
    } else if (duration.minutes() > 0) {
        // Check for minutes
        formattedDuration += duration.minutes() + " minute";
        if (duration.minutes() > 1) {
            formattedDuration += "s";
        }
    }

    // Return the formatted duration
    return formattedDuration;
}

$("#kt_datepicker_dob_custom").flatpickr({
    disableMobile: true,
});

$(".kt_time_picker_play_area").flatpickr({
    enableTime: true,
    dateFormat: "H:i",
    noCalendar: true,
});

$(".datepicker_date_time").flatpickr({
    enableTime: true,
    dateFormat: "Y-m-d H:i",
});
