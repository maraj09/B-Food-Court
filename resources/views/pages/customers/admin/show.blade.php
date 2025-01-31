@extends('layouts.admin.app')
@section('contents')
  @php
    $lastSegment = request()->segment(count(request()->segments()));
  @endphp
  <div class="app-main flex-column flex-row-fluid " id="kt_app_main">
    <!--begin::Content wrapper-->
    <div class="d-flex flex-column flex-column-fluid">
      <!--begin::Toolbar-->
      @include('pages.customers.admin.toolbar.showToolbar')
      <!--end::Toolbar-->

      <!--begin::Content-->
      <div id="kt_app_content" class="app-content  flex-column-fluid ">


        <!--begin::Content container-->
        <div id="kt_app_content_container" class="app-container  container-xxl ">
          <!--begin::Layout-->
          <div class="d-flex flex-column flex-xl-row">
            <!--begin::Sidebar-->
            <div class="flex-column flex-lg-row-auto w-100 w-xl-350px mb-10">

              <!--begin::Card-->
              <div class="card mb-5 mb-xl-8">
                <!--begin::Card body-->
                <div class="card-body pt-15">
                  <!--begin::Summary-->
                  <div class="d-flex flex-center flex-column mb-5">
                    <!--begin::Avatar-->
                    <div class="symbol symbol-150px symbol-circle mb-7">
                      @if ($user->customer->avatar)
                        <img src="{{ asset($user->customer->avatar) }}" alt="image">
                      @else
                        <img src="{{ asset('assets/media/svg/avatars/blank-dark.svg') }}" alt="image">
                      @endif
                    </div>
                    <!--end::Avatar-->

                    <!--begin::Name-->
                    <p href="" class="fs-3 text-gray-800 text-hover-primary fw-bold mb-1">
                      {{ $user->name }} </p>
                    <!--end::Name-->

                    <!--begin::Email-->
                    <p href="#" class="fs-5 fw-semibold text-muted text-hover-primary mb-6">
                      {{ $user->email }} </p>
                    <!--end::Email-->
                  </div>
                  <!--end::Summary-->

                  <!--begin::Details toggle-->
                  <div class="d-flex flex-stack fs-4 py-3">
                    <div class="fw-bold">
                      Details
                    </div>

                    <!--begin::Badge-->
                    <div class="badge badge-light-info d-inline">Premium user</div>
                    <!--begin::Badge-->
                  </div>
                  <!--end::Details toggle-->

                  <div class="separator separator-dashed my-3"></div>

                  <!--begin::Details content-->
                  <div class="pb-5 fs-6">
                    <!--begin::Details item-->
                    <div class="fw-bold mt-5">Account ID</div>
                    <div class="text-gray-600">ID-{{ $user->id }}</div>
                    <!--begin::Details item-->
                    <!--begin::Details item-->
                    <div class="fw-bold mt-5">Email Address</div>
                    <div class="text-gray-600"><a href="#"
                        class="text-gray-600 text-hover-primary">{{ $user->email }}</a></div>
                    <!--begin::Details item-->
                    <!--begin::Details item-->
                    <div class="fw-bold mt-5">Contact No</div>
                    <div class="text-gray-600">{{ $user->customer->contact_no }}</div>
                    <!--begin::Details item-->
                    <div class="fw-bold mt-5">Date of birth</div>
                    <div class="text-gray-600">{{ $user->customer->date_of_birth }}</div>

                    <div class="fw-bold mt-5">Latest Order</div>
                    <div class="text-gray-600"><a href="/dashboard/orders/{{ $user->orders->last()->id ?? null }}"
                        class="text-gray-600 text-hover-primary">#{{ $user->orders->last()->id ?? null }}</a> </div>
                    <!--begin::Details item-->
                  </div>
                  <!--end::Details content-->
                </div>
                <!--end::Card body-->
              </div>
              <!--end::Card-->
            </div>
            <!--end::Sidebar-->

            <!--begin::Content-->
            <div class="flex-lg-row-fluid ms-lg-15">
              <!--begin:::Tabs-->
              <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-8"
                role="tablist">
                <!--begin:::Tab item-->
                <li class="nav-item" role="presentation">
                  <a class="nav-link text-active-primary pb-4 {{ $lastSegment == 'edit' ? '' : 'active' }}"
                    data-bs-toggle="tab" href="#kt_ecommerce_customer_overview" aria-selected="true"
                    role="tab">Overview</a>
                </li>
                <!--end:::Tab item-->

                <!--begin:::Tab item-->
                <li class="nav-item" role="presentation">
                  <a class="nav-link text-active-primary pb-4  {{ $lastSegment == 'edit' ? 'active' : '' }}"
                    data-bs-toggle="tab" href="#kt_ecommerce_customer_general" aria-selected="false"
                    role="tab">General Settings</a>
                </li>
                <!--end:::Tab item-->
              </ul>
              <!--end:::Tabs-->

              <!--begin:::Tab content-->
              <div class="tab-content" id="myTabContent">
                @if (Session::has('success'))
                  <div class="alert alert-success">
                    {{ Session::get('success') }}
                  </div>
                @endif
                <!--begin:::Tab pane-->
                @include('pages.customers.admin.components.show.overview')
                <!--end:::Tab pane-->

                <!--begin:::Tab pane-->
                @include('pages.customers.admin.components.show.generalSettings')
                <!--end:::Tab pane-->
              </div>
              <!--end:::Tab content-->
            </div>
            <!--end::Content-->
          </div>
          <!--end::Layout-->
        </div>
        <!--end::Content container-->
      </div>
      <!--end::Content-->
    </div>
    <!--end::Content wrapper-->
  </div>
@endsection
@section('modules')
  @include('pages.customers.admin.modules.modals.editPointModal')
@endsection
@section('scripts')
  <script src="{{ asset('assets/js/custom/apps/ecommerce/customers/details/transaction-history.js') }}"></script>
  <script>
    var KTAddItem = (function() {
      var modal;
      return {
        init: function() {
          modal = new bootstrap.Modal(
            document.querySelector("#kt_modal_update_address")
          );
          @if ($errors->has('points'))
            modal.show()
          @endif
        },
      };
    })();
  </script>
  <script>
    KTUtil.onDOMContentLoaded(function() {
      KTAddItem.init();
    });
  </script>
  <script>
    $(document).ready(function() {
      const input = document.querySelector("#phone");
      var iti = window.intlTelInput(input, {
        utilsScript: "{{ asset('custom/assets/js/intlTelInput/utils.js') }}",
        separateDialCode: true,
        initialCountry: "auto",
        onlyCountries: ["bd", "in"],
        initialCountry: "bd",
      });
      @if ($errors->has('phone'))
        $('.iti').addClass('is-invalid');
      @endif
      const form = document.querySelector('#kt_ecommerce_customer_profile');
      const phoneInput = document.querySelector('#phone');

      form.addEventListener('submit', function(event) {
        // Prevent the default form submission behavior
        event.preventDefault();

        // Update the value of the hidden input field with the phone number
        phoneInput.value = iti.getNumber();

        // Now, you can submit the form programmatically
        form.submit();
      });
    })
  </script>
@endsection
