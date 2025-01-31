<div class="card card-flush w-100 rounded-0">
  <!--begin::Card header-->
  <div class="card-header bg-dark">
    <!--begin::Title-->
    <div class="card-title">
      <!--begin::User-->
      <div class="d-flex justify-content-center flex-column me-3">
        <a href="#" class="fs-5 fw-bold text-gray-900 text-hover-primary me-1 mb-2 lh-1">{{ $event->title }}</a>
        <!--begin::Info-->
        <div class="mb-3 lh-1">
          <div class="d-flex flex-wrap flex-grow-1 flex-center text-center">
            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative my-2">
              <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
              <input type="text" data-kt-customer-table-filter="search"
                class="form-control form-control-solid w-200px ps-13" placeholder="Search Attendees" />
            </div>
            <!--end::Search-->
          </div>
        </div>
        <!--end::Info-->
      </div>
      <!--end::User-->
    </div>
    <!--end::Title-->
    <!--begin::Card toolbar-->
    <div class="card-toolbar">
      <h3 class="card-title align-items-center flex-column">
        <span class="card-label fw-bold fs-3 mb-1">{{ $orderEvents->count() }}</span>
        <span class="text-muted fw-semibold fs-7">Total Attendees </span>
      </h3>
      <!--begin::Close-->
      <div class="btn btn-sm btn-icon btn-active-light-primary" id="kt_event_close">
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
  <div class="card-body p-3">
    <div class="table-responsive">
      <!--begin::Table-->
      <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_table">
        <thead>
          <tr class="text-center text-gray-500 fw-bold fs-7 text-uppercase gs-0">
            <th class="min-w-50px text-center">ID</th>
            <th class="min-w-125px text-center">Attendees Name</th>
            <th class="min-w-75px text-center">Seats</th>
            <th class="min-w-125px text-center">Paid</th>
            <th class="min-w-75px text-center">Arrived</th>
          </tr>
        </thead>
        <!--begain::Table body-->
        <tbody class="fw-semibold text-gray-600">
          @foreach ($orderEvents as $orderEvent)
            <tr class="text-center">
              <td>#{{ $loop->iteration }}</td>
              <td>
                <div class="d-flex align-items-center">
                  <div class="flex-grow-1">
                    <a href="tel:{{ $orderEvent->order->user->phone }}"
                      class="text-gray-900 fw-bold text-hover-primary fs-7">{{ $orderEvent->order->user->name }}</a>
                    <a href="tel:{{ $orderEvent->order->user->phone }}"
                      class="text-gray-900 text-hover-primary d-block fs-8">+{{ $orderEvent->order->user->phone }}</a>
                  </div>
                </div>
              </td>
              <td>{{ $orderEvent->quantity }}</td>
              <td>
                <div class="d-flex align-items-center">
                  <div class="flex-grow-1">
                    <a href="" class="fw-bold text-success fs-5">₹{{ round($orderEvent->price) }}</a>
                    <a href="" class="badge badge-light-success fw-bold d-block fs-8" data-bs-toggle="tooltip"
                      data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                      title="Payment Mode">{{ $orderEvent->order->payment_method }}</a>
                  </div>
                </div>
              </td>
              <td class="text-center">
                <div class="form-check form-switch form-check-custom form-check-solid me-10">
                  <input class="form-check-input h-20px w-30px booked-event-attendee-arrived-checkbox" type="checkbox"
                    {{ $orderEvent->event_attendee_arrived ? 'checked' : '' }}
                    data-order-event-id="{{ $orderEvent->id }}" id="flexSwitch20x30" />
                </div>
              </td>
            </tr>
          @endforeach
        </tbody>
        <!--end::Table body-->
      </table>
      <!--end::Table-->
    </div>
  </div>
  <!--end::Card body-->
  <!--begin::Card footer-->
  <div class="card-footer bg-dark p-2">
    <!--end::Action-->
    <div class="d-flex justify-content-end">
    </div>
    <!--end::Action-->
  </div>
  <!--end::Card footer-->
</div>
<script>
  $(".booked-event-attendee-arrived-checkbox").change(function() {
    // Get the item ID from the data attribute
    var eventId = $(this).data("order-event-id");

    // Save the reference to $(this) in a variable for later use
    var $checkbox = $(this);

    // Make an AJAX request to update the status
    $.ajax({
      url: "/dashboard/update-order-event-attendee-status/" + eventId, // Replace with your actual route
      type: "POST", // You can use 'PUT' or 'PATCH' depending on your setup
      data: {
        // Additional data to send, if any
      },
      headers: {
        "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
      },
      success: function(response) {
        showToast(response);
      },
      error: function(error) {
        console.error(error);
        showToast("error");
      },
    });
  });
</script>
<script>
  "use strict";
  var KTAppEcommerceSalesListing = (function() {
    var e,
      t,
      n,
      r,
      o,
      a = (e, n, a) => {
        (r = e[0] ? new Date(e[0]) : null),
        (o = e[1] ? new Date(e[1]) : null),
        $.fn.dataTable.ext.search.push(function(e, t, n) {
            var a = r,
              c = o,
              l = new Date(moment($(t[2]).text(), "DD-MM-YYYY")),
              u = new Date(moment($(t[2]).text(), "DD-MM-YYYY"));
            return (
              (null === a && null === c) ||
              (null === a && c >= u) ||
              (a <= l && null === c) ||
              (a <= l && c >= u)
            );
          }),
          t.draw();
      };
    return {
      init: function() {
        (e = document.querySelector("#kt_customers_table")) &&
        ((t = $(e).DataTable({
            info: !1,
            order: [],
            pageLength: 10,
            columnDefs: [{
              orderable: !1,
              targets: 4
            }, ],
          })).on("draw", function() {

          }),
          document
          .querySelector('[data-kt-customer-table-filter="search"]')
          .addEventListener("keyup", function(e) {
            t.search(e.target.value).draw();
          }));
      },
    };
  })();
  KTUtil.onDOMContentLoaded(function() {
    KTAppEcommerceSalesListing.init();
  });
</script>
