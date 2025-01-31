@extends('layouts.admin.app')
@section('contents')
  @include('pages.promotions.toolbars.promotion-toolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      @if (Session::has('success'))
        <div class="alert alert-success">
          {{ Session::get('success') }}
        </div>
      @endif
      <!--begin::Stepper-->
      <div class="stepper stepper-pills" id="kt_stepper_example_basic">
        <!--begin::Nav-->
        <div class="stepper-nav flex-center flex-wrap mb-10">
          <!--begin::Step 1-->
          <div class="stepper-item mx-8 my-4 current" data-kt-stepper-element="nav" data-kt-stepper-action="step">
            <!--begin::Wrapper-->
            <div class="stepper-wrapper d-flex align-items-center">
              <!--begin::Icon-->
              <div class="stepper-icon w-40px h-40px">
                <i class="stepper-check fas fa-check"></i>
                <span class="stepper-number">1</span>
              </div>
              <!--end::Icon-->

              <!--begin::Label-->
              <div class="stepper-label">
                <h3 class="stepper-title">
                  Communication On
                </h3>

                <div class="stepper-desc">
                  Date and Type
                </div>
              </div>
              <!--end::Label-->
            </div>
            <!--end::Wrapper-->

            <!--begin::Line-->
            <div class="stepper-line h-40px"></div>
            <!--end::Line-->
          </div>
          <!--end::Step 1-->
          <!--begin::Step 2-->
          <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav" data-kt-stepper-action="step">
            <!--begin::Wrapper-->
            <div class="stepper-wrapper d-flex align-items-center">
              <!--begin::Icon-->
              <div class="stepper-icon w-40px h-40px">
                <i class="stepper-check fas fa-check"></i>
                <span class="stepper-number">2</span>
              </div>
              <!--begin::Icon-->

              <!--begin::Label-->
              <div class="stepper-label">
                <h3 class="stepper-title">
                  Content
                </h3>

                <div class="stepper-desc">
                  Message
                </div>
              </div>
              <!--end::Label-->
            </div>
            <!--end::Wrapper-->

            <!--begin::Line-->
            <div class="stepper-line h-40px"></div>
            <!--end::Line-->
          </div>
          <!--end::Step 2-->
          <!--begin::Step 3-->
          <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav" data-kt-stepper-action="step">
            <!--begin::Wrapper-->
            <div class="stepper-wrapper d-flex align-items-center">
              <!--begin::Icon-->
              <div class="stepper-icon w-40px h-40px">
                <i class="stepper-check fas fa-check"></i>
                <span class="stepper-number">3</span>
              </div>
              <!--begin::Icon-->

              <!--begin::Label-->
              <div class="stepper-label">
                <h3 class="stepper-title">
                  Audience
                </h3>

                <div class="stepper-desc">
                  Select Group
                </div>
              </div>
              <!--end::Label-->
            </div>
            <!--end::Wrapper-->

            <!--begin::Line-->
            <div class="stepper-line h-40px"></div>
            <!--end::Line-->
          </div>
          <!--end::Step 3-->
          {{-- <!--begin::Step 4-->
          <div class="stepper-item mx-8 my-4" data-kt-stepper-element="nav" data-kt-stepper-action="step">
            <!--begin::Wrapper-->
            <div class="stepper-wrapper d-flex align-items-center">
              <!--begin::Icon-->
              <div class="stepper-icon w-40px h-40px">
                <i class="stepper-check fas fa-check"></i>
                <span class="stepper-number">4</span>
              </div>
              <!--begin::Icon-->

              <!--begin::Label-->
              <div class="stepper-label">
                <h3 class="stepper-title">
                  Final
                </h3>

                <div class="stepper-desc">
                  Preview
                </div>
              </div>
              <!--end::Label-->
            </div>
            <!--end::Wrapper-->

            <!--begin::Line-->
            <div class="stepper-line h-40px"></div>
            <!--end::Line-->
          </div>
          <!--end::Step 4--> --}}
        </div>
        <!--end::Nav-->
        <!--begin::Form-->
        <form class="form w-lg-900px mx-auto" novalidate="novalidate" id="kt_stepper_example_basic_form"
          enctype="multipart/form-data" method="POST" action="/dashboard/notifications/promotions/store">
          @csrf
          <!--begin::Group-->
          <div class="mb-5">
            <!--begin::Communication On Step 1-->
            <div class="flex-column current" data-kt-stepper-element="content">
              <!--begin::Input group-->
              <div class="fv-row mb-10">
                <!--begin::Label-->
                <label class="form-label">Title</label>
                <!--end::Label-->
                <!--begin::Input-->
                <input type="text" required class="form-control form-control-solid" name="promotion_title"
                  placeholder="Give a title" value="" />
                <!--end::Input-->
              </div>
              <!--end::Input group-->

              <!--begin::Input group-->
              <div class="fv-row mb-10">
                <!--begin::Label-->
                <label class="form-label">Select Date and Time</label>
                <!--end::Label-->

                <!--begin::Input-->
                <input class="form-control form-control-solid datepicker_date_time" placeholder="Pick date & time"
                  id="" name="promotion_date_time" />
                <!--end::Input-->
              </div>
              <!--end::Input group-->

              <!--begin::Input group-->
              <div class="fv-row mb-10">
                <!--begin::Label-->
                <label class="form-label">Communication Type</label>
                <!--end::Label-->
                <!--begin::Switch-->
                <label class="form-check form-switch form-check-custom form-check-solid">
                  <input class="form-check-input h-20px w-30px" name="email_status" type="checkbox" checked="checked"
                    value="" />
                  <span class="form-check-label">
                    Email
                  </span>
                </label>
                <!--end::Switch-->
                <!--begin::Switch-->
                <label class="form-check form-switch form-check-custom form-check-solid py-3">
                  <input class="form-check-input  h-20px w-30px" name="push_status" type="checkbox" checked="checked"
                    value="" />
                  <span class="form-check-label">
                    Push
                  </span>
                </label>
                <!--end::Switch-->
              </div>
              <!--end::Input group-->
            </div>
            <!--end::Communication On Step 1-->
            <!--begin::Content Step 2-->
            <div class="flex-column" data-kt-stepper-element="content">
              <div class="form-group row">
                <div class="col-md-8" id="email_details_form">
                  <label class="form-label">Email</label>
                  <!--begin::Email-->
                  <!--begin::Input group-->
                  <div class="d-flex flex-column mb-3 fv-row">
                    <!--begin::Label-->
                    <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                      <span class="required">Title</span>
                      <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                        data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Title Of Email"></i>
                    </label>
                    <!--end::Label-->
                    <input type="text" class="form-control mb-3" id="kt_docs_maxlength_custom_text"
                      name="email_title" />
                  </div>
                  <!--end::Input group-->
                  <div id="promotion_quill_basic" class="h-400px">

                  </div>
                  <input id="email_description" type="hidden" name="email_description">
                  <!--end::Email-->
                </div>
                <div class="col-md-4" id="push_details_form">
                  <label class="form-label">Push</label>
                  <!--begin::Push group-->
                  <div class="row gx-10">
                    <!--begin::Col-->
                    <div class="col-12 px-3 rounded-2 me-3 mb-3">
                      <!--begin::Input group-->
                      <div class="d-flex flex-column mb-3 fv-row">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Title</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Title Of Push"></i>
                        </label>
                        <!--end::Label-->
                        <input type="text" class="form-control mb-3" id="kt_docs_maxlength_custom_text"
                          name="push_title" />
                      </div>
                      <!--end::Input group-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-12 px-3 rounded-2 me-3 mb-3">
                      <!--begin::Input group-->
                      <div class="d-flex flex-column mb-3 fv-row">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Body</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Body of PUSH"></i>
                        </label>
                        <!--end::Label-->
                        <textarea class="form-control mb-3" id="kt_docs_maxlength_textarea" placeholder="" rows="3"
                          name="push_description"></textarea>
                      </div>
                      <!--end::Input group-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-12 px-3 rounded-2 me-3 mb-3">
                      <!--begin::Input group-->
                      <div class="d-flex flex-column mb-3 fv-row">
                        <!--begin::Label-->
                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                          <span class="required">Link</span>
                          <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                            data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                            title="Where user should go"></i>
                        </label>
                        <!--end::Label-->
                        <input type="text" class="form-control mb-3" id="kt_docs_maxlength_custom_text"
                          name="push_link" />
                      </div>
                      <!--end::Input group-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-6 px-3 rounded-2 me-3 mb-3 fv-row">
                      <!--begin::Input group-->
                      <!--begin::Image input-->
                      <div class="image-input image-input-empty border border-primary rounded border-dashed w-150px"
                        data-kt-image-input="true"
                        style="background-image: url({{ asset('custom/assets/images/blank/blank-image-dark.svg') }})">
                        <!--begin::Image preview wrapper-->
                        <div class="image-input-wrapper"></div>
                        <!--end::Image preview wrapper-->
                        <!--begin::Edit button-->
                        <label
                          class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                          data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="click"
                          title="Change Banner">
                          <i class="bi bi-pencil-fill fs-7"></i>
                          <!--begin::Inputs-->
                          <input type="file" name="push_banner" accept=".png, .jpg, .jpeg" />
                          <input type="hidden" name="push_banner_remove" />
                          <!--end::Inputs-->
                        </label>
                        <!--end::Edit button-->
                        <!--begin::Cancel button-->
                        <span
                          class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                          data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-bs-dismiss="click"
                          title="Cancel Banner">
                          <i class="bi bi-x fs-2"></i>
                        </span>
                        <!--end::Cancel button-->
                        <!--begin::Remove button-->
                        <span
                          class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                          data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-bs-dismiss="click"
                          title="Remove Banner">
                          <i class="bi bi-x fs-2"></i>
                        </span>
                        <!--end::Remove button-->
                      </div>
                      <!--end::Image input-->
                      <!--end::Input group-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-5 px-3 rounded-2 me-3 mb-3 fv-row">
                      <!--begin::Input group-->
                      <!--begin::Image input-->
                      <div class="image-input image-input-empty" data-kt-image-input="true"
                        style="background-image: url({{ asset('custom/assets/images/blank/blank-avatar-dark.svg') }})">
                        <!--begin::Image preview wrapper-->
                        <div class="image-input-wrapper"></div>
                        <!--end::Image preview wrapper-->
                        <!--begin::Edit button-->
                        <label
                          class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                          data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="click"
                          title="Change avatar">
                          <i class="bi bi-pencil-fill fs-7"></i>
                          <!--begin::Inputs-->
                          <input type="file" name="push_avatar" accept=".png, .jpg, .jpeg" />
                          <input type="hidden" name="push_avatar_remove" />
                          <!--end::Inputs-->
                        </label>
                        <!--end::Edit button-->
                        <!--begin::Cancel button-->
                        <span
                          class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                          data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-bs-dismiss="click"
                          title="Cancel avatar">
                          <i class="bi bi-x fs-2"></i>
                        </span>
                        <!--end::Cancel button-->
                        <!--begin::Remove button-->
                        <span
                          class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
                          data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-bs-dismiss="click"
                          title="Remove avatar">
                          <i class="bi bi-x fs-2"></i>
                        </span>
                        <!--end::Remove button-->
                      </div>
                      <!--end::Image input-->
                      <!--end::Input group-->
                    </div>
                    <!--end::Col-->
                    <!--begin::Col-->
                    <div class="col-12 px-3 rounded-2 me-3 mb-3">
                      <!--begin::Input group-->
                      <div class="row g-0">
                        <!--begin::Input-->
                        {{-- <input id="kt_clipboard_1" type="text" class="form-control"
                          placeholder="Show Users Dropdown with multiple user select" value="" /> --}}
                        <div class="col-7">
                          <select id="send_push_test_user" class="form-control" data-control="select2"
                            data-placeholder="Select user to test">
                            <option value=""></option>
                            @foreach ($acceptedOneSignalUsers as $user)
                              <option value="{{ $user->id }}">{{ $user->name }} - {{ $user->phone }}</option>
                            @endforeach
                          </select>
                        </div>
                        <!--end::Input-->
                        <div class="col-5">
                          {{-- <button class="btn btn-light-primary" type="button" id="send_push_test_button">Send
                            Test</button> --}}
                          <button type="button" class="btn btn-light-primary" id="send_push_test_button">
                            <span class="indicator-label">Send Test</span>
                            <span class="indicator-progress">Sending Test
                              <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                          </button>
                        </div>
                        <!--begin::Button-->
                        <!--end::Button-->
                      </div>
                      <!--end::Input group-->
                    </div>
                    <!--end::Col-->
                  </div>
                  <!--end::Push group-->
                </div>
              </div>
            </div>
            <!--end::Content Step 2-->
            <!--begin::Audience Step 3-->
            <div class="flex-column" data-kt-stepper-element="content">
              <!--begin::Input group-->
              <div class="row gx-10">
                <!--begin::Col-->
                <div class="col-12 px-3 rounded-2 me-3 mb-3 fv-row">
                  <!--begin::Label-->
                  <label class="d-flex align-items-center fs-6 fw-semibold mb-2">
                    <span class="required">Select User Type</span>
                    <i class="fas fa-exclamation-circle ms-2 fs-7" data-bs-toggle="tooltip"
                      data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="When it will send"></i>
                  </label>
                  <!--end::Label-->
                  <!--begin::Select2-->
                  <select class="form-select form-select-solid" data-control="select2" data-hide-search="true"
                    data-placeholder="Select a Minimum Amount" id="user_type" name="user_type">
                    <option value="all">All</option>
                    <option value="customer">Customer</option>
                    <option value="vendor">Vendors</option>
                  </select>
                  <!--end::Select2-->
                </div>
                <!--end::Col-->
                <!--begin::Col-->
                <div class="col-12 px-3 rounded-2 me-3 mb-3">
                  <!--begin::Repeater-->
                  <div id="kt_docs_repeater_basic">
                    <!--begin::Form group-->
                    <div class="form-group">
                      <div data-repeater-list="kt_docs_repeater_basic">
                        <div data-repeater-item id="form-group-repeater">
                          <div class="form-group row">
                            <div class="col-md-2 fv-row">
                              <label class="form-label">Filter</label>
                              <!--begin::Select2-->
                              <select class="form-select form-select-sm form-select-solid" data-control="select2"
                                data-hide-search="true" data-kt-repeater="filter" data-placeholder="Select a option"
                                name="filter[]">
                                <option value="total_orders">Total Orders</option>
                                <option value="total_orders_value">Total Orders Value</option>
                                <option value="total_points_earn">Total Points Earn</option>
                                <option value="total_points_redeem">Total Points Redeem</option>
                                <option value="total_review">Total Review</option>
                              </select>
                              <!--end::Select2-->
                            </div>
                            <div class="col-md-2 fv-row">
                              <label class="form-label">Is</label>
                              <!--begin::Select2-->
                              <select class="form-select form-select-sm form-select-solid" data-control="select2"
                                data-hide-search="true" data-kt-repeater="comparison"
                                data-placeholder="Select a option" name="comparison[]">
                                <option value="is_less_then">Is Less Then</option>
                                <option value="is_more_then">Is More Then</option>
                              </select>
                              <!--end::Select2-->
                            </div>
                            <div class="col-md-2 fv-row">
                              <label class="form-label">Value</label>
                              <input data-kt-repeater="value" type="number"
                                class="form-control form-control-sm mb-2 mb-md-0" placeholder="Number"
                                name="value[]" />
                            </div>
                            <div class="col-md-2 fv-row">
                              <label class="form-label">And/Or</label>
                              <!--begin::Select2-->
                              <select data-kt-repeater="operator" class="form-select form-select-sm form-select-solid"
                                data-control="select2" data-hide-search="true" data-placeholder="Select a option"
                                name="operator[]">
                                <option value="and">And</option>
                                <option value="or">Or</option>
                              </select>
                              <!--end::Select2-->
                            </div>
                            <div class="col-md-2">
                              <span class="badge badge-outline badge-primary" data-user-count="0">0 Users</span>
                              <button type="button" class="btn btn-primary py-1 px-2 fs-4 my-2"
                                data-calculate="true">Calculate</button>
                            </div>
                            <div class="col-md-2">
                              <a href="javascript:;" data-repeater-delete
                                class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                                <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span
                                    class="path2"></span><span class="path3"></span><span class="path4"></span><span
                                    class="path5"></span></i>
                              </a>
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <!--end::Form group-->

                    <!--begin::Form group-->
                    <div class="form-group mt-5">
                      <a href="javascript:;" data-repeater-create class="btn btn-light-primary">
                        <i class="ki-duotone ki-plus fs-3"></i>
                        Add
                      </a>
                    </div>
                    <!--end::Form group-->
                  </div>
                  <!--end::Repeater-->
                </div>
                <!--end::Col-->
              </div>
              <!--end::Input group-->
            </div>
            <!--end::Audience Step 3-->
          </div>
          <!--end::Group-->
          <!--begin::Actions-->
          <div class="d-flex flex-stack">
            <!--begin::Wrapper-->
            <div class="me-2">
              <button type="button" class="btn btn-light btn-active-light-primary" data-kt-stepper-action="previous">
                Back
              </button>
            </div>
            <!--end::Wrapper-->
            <!--begin::Wrapper-->
            <div>
              <button type="submit" class="btn btn-primary" data-kt-stepper-action="submit">
                <span class="indicator-label">
                  Submit
                </span>
                <span class="indicator-progress">
                  Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                </span>
              </button>

              <button type="button" class="btn btn-primary" data-kt-stepper-action="next">
                Continue
              </button>
            </div>
            <!--end::Wrapper-->
          </div>
          <!--end::Actions-->
        </form>
        <!--end::Form-->
      </div>
      <!--end::Stepper-->
    </div>
    <!--end::Content container-->
  </div>
@endsection
@section('modules')
  @include('pages.coupons.admin.modules.toasts.status')
@endsection
@section('scripts')
  <script>
    var form = document.querySelector("#kt_stepper_example_basic_form");
    var element = document.querySelector("#kt_stepper_example_basic");
    var stepper = new KTStepper(element);

    // Initialize form validation only once
    var fv1 = FormValidation.formValidation(form, {
      fields: {
        promotion_title: {
          validators: {
            notEmpty: {
              message: "Title is required",
            },
          },
        },
        promotion_date_time: {
          validators: {
            notEmpty: {
              message: "Please Select a date",
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

    var fv2_email = FormValidation.formValidation(form, {
      fields: {
        email_title: {
          validators: {
            notEmpty: {
              message: "Title is required",
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

    var fv2_push = FormValidation.formValidation(form, {
      fields: {
        push_title: {
          validators: {
            notEmpty: {
              message: "Title is required",
            },
          },
        },
        push_description: {
          validators: {
            notEmpty: {
              message: "Description is required",
            },
          },
        },
        push_link: {
          validators: {
            notEmpty: {
              message: "Link is required",
            },
          },
        },
        'push_banner': {
          validators: {
            file: {
              extension: 'png,jpg,jpeg',
              type: 'image/png,image/jpeg',
              message: 'Please choose a valid image file',
            },
          },
        },
        'push_avatar': {
          validators: {
            file: {
              extension: 'png,jpg,jpeg',
              type: 'image/png,image/jpeg',
              message: 'Please choose a valid image file',
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

    // Initialize the form validation
    var fv3 = FormValidation.formValidation(
      form, {
        fields: {
          'filter[]': {
            validators: {
              notEmpty: {
                message: 'Filter is required'
              }
            }
          },
          'comparison[]': {
            validators: {
              notEmpty: {
                message: 'Comparison is required'
              }
            }
          },
          'value[]': {
            validators: {
              notEmpty: {
                message: 'Value is required'
              },
              numeric: {
                message: 'Value must be a number',
                thousandsSeparator: '',
                decimalSeparator: '.'
              }
            }
          },
          'operator[]': {
            validators: {
              notEmpty: {
                message: 'And/Or is required'
              }
            }
          }
        },
        plugins: {
          trigger: new FormValidation.plugins.Trigger(),
          bootstrap: new FormValidation.plugins.Bootstrap5({
            rowSelector: '.fv-row',
            eleInvalidClass: '',
            eleValidClass: '',
          }),
        },
      }
    );

    stepper.on("kt.stepper.next", function(stepper) {
      const emailChecked = document.querySelector('input[name="email_status"]').checked;
      const pushChecked = document.querySelector('input[name="push_status"]').checked;
      if (stepper.getCurrentStepIndex() === 1) {
        if (!emailChecked && !pushChecked) {
          Swal.fire({
            text: "At least one communication type must be selected.",
            icon: "error",
            buttonsStyling: false,
            confirmButtonText: "Ok, got it!",
            customClass: {
              confirmButton: "btn btn-primary",
            },
          });
          return; // Stop further execution
        }

        // Trigger validation
        fv1.validate().then(function(status) {
          if (status === "Valid") {
            if (emailChecked) {
              $('#email_details_form').show()
            } else {
              $('#email_details_form').hide()
            }

            if (pushChecked) {
              $('#push_details_form').show()
            } else {
              $('#push_details_form').hide()
            }

            stepper.goNext();
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
      } else if (stepper.getCurrentStepIndex() === 2) {
        var emailValidationPassed = !emailChecked; // Set to true if not checked
        var pushValidationPassed = !pushChecked; // Set to true if not checked

        var validationPromises = [];

        if (emailChecked) {
          validationPromises.push(
            fv2_email.validate().then(function(status) {
              if (status !== "Valid") {
                Swal.fire({
                  text: "Sorry, looks like there are some errors detected in the email validation, please try again.",
                  icon: "error",
                  buttonsStyling: false,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                    confirmButton: "btn btn-primary",
                  },
                });
                emailValidationPassed = false;
              } else {
                emailValidationPassed = true;
              }
            })
          );
        }

        if (pushChecked) {
          validationPromises.push(
            fv2_push.validate().then(function(status) {
              if (status !== "Valid") {
                Swal.fire({
                  text: "Sorry, looks like there are some errors detected in the push notification validation, please try again.",
                  icon: "error",
                  buttonsStyling: false,
                  confirmButtonText: "Ok, got it!",
                  customClass: {
                    confirmButton: "btn btn-primary",
                  },
                });
                pushValidationPassed = false;
              } else {
                pushValidationPassed = true;
              }
            })
          );
        }

        // After all validations are complete
        Promise.all(validationPromises).then(function() {
          if (emailValidationPassed && pushValidationPassed) {
            stepper.goNext(); // Proceed to the next step if both validations passed
          }
        });
      } else if (stepper.getCurrentStepIndex() === 3) {
        fv3.validate().then(function(status) {
          if (status === "Valid") {
            stepper.goNext();
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
        })
      }
    });

    stepper.on("kt.stepper.previous", function(stepper) {
      stepper.goPrevious(); // Go to previous step
    });

    $(document).on('click', '[data-calculate="true"]', function() {
      const calculateButton = $(this);
      const repeaterItem = calculateButton.closest('.form-group');

      // Disable the button and show loading text
      calculateButton.prop('disabled', true).text('Calculating...');

      // Gather the data from this repeater item
      const filter = repeaterItem.find('[data-kt-repeater="filter"]').val();
      const comparison = repeaterItem.find('[data-kt-repeater="comparison"]').val();
      const value = repeaterItem.find('[data-kt-repeater="value"]').val();
      const operator = repeaterItem.find('[data-kt-repeater="operator"]').val();
      const userType = $('#user_type').val(); // Assuming there's a select/input for user_type

      // Prepare data for the AJAX request
      const requestData = {
        filter: filter,
        comparison: comparison,
        value: value,
        operator: operator,
        user_type: userType
      };

      const csrfToken = $('meta[name="csrf-token"]').attr('content');

      $.ajax({
        url: '/dashboard/notifications/promotions/calculate-users',
        type: 'POST',
        data: requestData,
        headers: {
          'X-CSRF-TOKEN': csrfToken
        },
        success: function(response) {
          const userCount = response.userCount || 0;
          repeaterItem.find('[data-user-count]').text(`${userCount} Users`);
        },
        error: function(xhr, status, error) {
          console.error("Error during the AJAX request:", error);
        },
        complete: function() {
          // Re-enable the button and restore the original text
          calculateButton.prop('disabled', false).text('Calculate');
        }
      });
    });
  </script>
  <script>
    $('#send_push_test_button').click(function() {
      var user_id = $('#send_push_test_user').val();
      var push_title = $('input[name="push_title"]').val();
      var push_description = $('textarea[name="push_description"]').val();
      var push_link = $('input[name="push_link"]').val();
      var push_banner = $('input[name="push_banner"]')[0].files[0]; // Get the file object
      var push_avatar = $('input[name="push_avatar"]')[0].files[0]; // Get the file object

      if (user_id) {
        this.setAttribute("data-kt-indicator", "on");
        this.disabled = true;

        var formData = new FormData();
        formData.append('user_id', user_id);
        formData.append('push_title', push_title);
        formData.append('push_description', push_description);
        formData.append('push_link', push_link);
        formData.append('push_banner', push_banner); // Append file
        formData.append('push_avatar', push_avatar); // Append file
        formData.append('_token', '{{ csrf_token() }}');

        $.ajax({
          url: '/dashboard/notifications/promotions/send-push-notification',
          type: 'POST',
          data: formData,
          contentType: false,
          processData: false,
          success: function(response) {
            if (response.success) {
              showToast('success', response.message);
            } else {
              showToast('error', response.message);
            }
          },
          error: function(xhr, status, error) {
            showToast('error', 'An error occurred while sending the test push notification.');
          },
          complete: () => {
            this.removeAttribute(
              "data-kt-indicator"
            );
            this.disabled = false;
          }
        });
      } else {
        showToast('error', 'Please select a user!');
      }
    });
  </script>
  <script>
    // Function to create a new row
    function createNewRow() {
      let newRow = `
            <div class="form-group row">
                <div class="col-md-2 fv-row">
                    <label class="form-label">Filter</label>
                    <select class="form-select form-select-sm form-select-solid" data-control="select2" data-hide-search="true"
                        data-kt-repeater="filter" data-placeholder="Select a option" name="filter[]">
                        <option value="total_orders">Total Orders</option>
                        <option value="total_orders_value">Total Orders Value</option>
                        <option value="total_points_earn">Total Points Earn</option>
                        <option value="total_points_redeem">Total Points Redeem</option>
                        <option value="total_review">Total Review</option>
                    </select>
                </div>
                <div class="col-md-2 fv-row">
                    <label class="form-label">Is</label>
                    <select class="form-select form-select-sm form-select-solid" data-control="select2" data-hide-search="true"
                        data-kt-repeater="comparison" data-placeholder="Select a option" name="comparison[]">
                        <option value="is_less_then">Is Less Then</option>
                        <option value="is_more_then">Is More Then</option>
                    </select>
                </div>
                <div class="col-md-2 fv-row">
                    <label class="form-label">Value</label>
                    <input data-kt-repeater="value" type="number" class="form-control form-control-sm mb-2 mb-md-0"
                        placeholder="Number" name="value[]" />
                </div>
                <div class="col-md-2 fv-row">
                    <label class="form-label">And/Or</label>
                    <select data-kt-repeater="operator" class="form-select form-select-sm form-select-solid"
                        data-control="select2" data-hide-search="true" data-placeholder="Select a option"
                        name="operator[]">
                        <option value="and">And</option>
                        <option value="or">Or</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <span class="badge badge-outline badge-primary" data-user-count="0">0 Users</span>
                    <button type="button" class="btn btn-primary py-1 px-2 fs-4 my-2" data-calculate="true">Calculate</button>
                </div>
                <div class="col-md-2">
                    <a href="javascript:;" data-repeater-delete class="btn btn-sm btn-light-danger mt-3 mt-md-8">
                        <i class="ki-duotone ki-trash fs-5"><span class="path1"></span><span
                        class="path2"></span><span class="path3"></span><span class="path4"></span><span
                        class="path5"></span></i>
                    </a>
                </div>
            </div>`;
      return $(newRow);
    }

    // Handle "Add" button click
    $('[data-repeater-create]').on('click', function() {
      let newRow = createNewRow();

      // Append new row to the form container
      $('#form-group-repeater').append(newRow);

      // Re-initialize Select2 for the new row
      newRow.find('select').select2();
    });

    // Handle "Delete" button click
    $(document).on('click', '[data-repeater-delete]', function() {
      let $row = $(this).closest('.form-group.row');
      let $rows = $('#form-group-repeater .form-group.row');

      // Check if the row being deleted is the first one
      if ($row.is(':first-child')) {
        return; // Exit the function
      }

      // Proceed with deletion if it's not the first row
      $row.remove();
    });

    // Initialize Select2 for the initial row
    $('#kt_stepper_example_basic_form select').select2();
  </script>
  <script>
    var quill = new Quill('#promotion_quill_basic', {
      theme: 'snow',
      modules: {
        toolbar: [
          [{
            'header': []
          }], // Header level
          ['bold', 'italic', 'underline'], // Basic text formatting
          [{
            'list': 'ordered'
          }, {
            'list': 'bullet'
          }], // Lists
          ['link', 'image'], // Link and image
          [{
            'align': []
          }], // Text alignment
          ['clean'] // Clear formatting
        ]
      }
    });
    $("#kt_stepper_example_basic_form").on("submit", function(e) {
      e.preventDefault();
      var quillHtml = quill.root.innerHTML;
      $("#email_description").val(quillHtml);
      fv3.validate().then(function(status) {
        if (status === "Valid") {
          $("#kt_stepper_example_basic_form").off('submit').submit();
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
      })
    })
  </script>
@endsection
