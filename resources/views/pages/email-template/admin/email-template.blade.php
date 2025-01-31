@extends('layouts.admin.app')
@section('contents')
  @include('pages.email-template.admin.toolbars.email-template-toolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Inbox App - Compose -->
      @if ($errors->any())
        <div class="alert alert-danger">
          <ul>
            @foreach ($errors->all() as $error)
              <li>{{ $error }}</li>
            @endforeach
          </ul>
        </div>
      @endif
      @if (session('success'))
        <div class="alert alert-success">
          {{ session('success') }}
        </div>
      @endif
      <div class="d-flex flex-column flex-lg-row">
        <style>
          @media (max-width: 1200px) {
            .mobile-hide {
              display: none;
            }
          }

          @media (max-width: 1199px) {
            .pc-hide {
              position: fixed !important;
              z-index: 99;
              margin-top: 150px !important;
              margin-left: -70px;
              display: none;
            }
          }
        </style>
        <div class="pc-hide engage-toolbar d-flex position-fixed px-5 fw-bolder top-50 start-0 transform-90 mt-20 gap-2">
          <button id="kt_inbox_aside_toggle"
            class="engage-demos-toggle btn btn-flex h-35px btn-color-white btn btn-bg-primary shadow-sm fs-6 px-4 active"
            data-bs-toggle="tooltip" data-bs-placement="right" data-bs-dismiss="click" data-bs-trigger="hover"
            data-bs-original-title="Forum Filters">
            <span><i class="fas fa-filter text-light"></i>Emails</span>
          </button>
        </div>
        <!--begin::Sidebar-->
        <div class="d-none d-lg-flex flex-column flex-lg-row-auto w-100 w-lg-275px drawer drawer-start"
          data-kt-drawer="true" data-kt-drawer-name="inbox-aside" data-kt-drawer-activate="{default: true, lg: false}"
          data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start"
          data-kt-drawer-toggle="#kt_inbox_aside_toggle" style="width: 225px !important;">
          <!--begin::Sticky aside-->
          <div class="card card-flush mb-0" data-kt-sticky="true" data-kt-sticky-name="inbox-aside-sticky"
            data-kt-sticky-offset="{default: false, xl: '100px'}" data-kt-sticky-width="{lg: '275px'}"
            data-kt-sticky-left="auto" data-kt-sticky-top="100px" data-kt-sticky-animation="false"
            data-kt-sticky-zindex="95" style="">
            <!--begin::Aside content-->
            <div class="card-body card-scroll h-550px ">
              <!--begin::Menu-->
              <div class="menu menu-column menu-active-bg menu-hover-bg menu-title-gray-700 fs-6 menu-rounded w-100"
                id="#kt_aside_menu" data-kt-menu="true">
                <!--begin::Heading-->
                <div class="menu-item">
                  <div class="menu-content pb-2">
                    <span class="menu-section text-muted text-uppercase fs-7 fw-bolder">Email Templates</span>
                  </div>
                </div>
                <!--end::Heading-->
                <!--begin::Menu item-->

                <div class="menu-item">
                  @foreach ($templates as $template)
                    <a href="/dashboard/notifications/email-templates/{{ $template->id }}"
                      class="menu-link {{ $currentTemplate->id == $template->id ? 'active' : '' }}">
                      <span class="menu-title">{{ $template->name }}</span>
                    </a>
                  @endforeach
                </div>

                <!--end::Menu item-->
              </div>
              <!--end::Menu-->
            </div>
            <!--end::Aside content-->
          </div>
          <!--end::Sticky aside-->
        </div>
        <!--end::Sidebar-->
        <!--begin::Content-->
        <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
          <!--begin::Row-->
          <div class="row g-6 mb-6 g-xl-9 mb-xl-9">
            <!--begin::Col-->
            <div class="col-xl-9 col-md-9">
              <!--begin::Card-->
              <div class="card">
                <div class="card-header align-items-center">
                  <div class="card-title">
                    <h3>Customize Email For {{ $currentTemplate->name }}</h3>
                  </div>
                </div>
                <div class="card-body p-0">
                  <!--begin::Form-->
                  <form method="POST" action="/dashboard/notifications/email-templates/{{ request()->route('id') }}">
                    <!--begin::Body-->
                    @csrf
                    <div class="d-block">
                      <!--begin::Subject-->
                      <div class="border-bottom">
                        <input class="form-control border-0 px-8 min-h-45px" value="{{ $currentTemplate->subject }}"
                          name="subject" placeholder="Subject" />
                      </div>
                      <!--end::Subject-->
                      <!--begin::Message-->
                      <textarea id="kt_inbox_form_editor" name="body" class="border-0 form-control h-350px w-100 px-3">{!! $currentTemplate->body !!}</textarea>
                      <!--end::Message-->
                    </div>
                    <!--end::Body-->
                    <!--begin::Footer-->
                    <div class="d-flex flex-stack flex-wrap gap-2 py-5 ps-8 pe-5 border-top">
                      <!--begin::Actions-->
                      <div class="d-flex align-items-center me-3">
                        <!--begin::Send-->
                        <div class="btn-group me-4">
                          <!--begin::Submit-->
                          <button type="submit" class="btn btn-primary">Save</button>
                          <!--end::Submit-->
                        </div>
                        <!--end::Send-->
                      </div>
                      <!--end::Actions-->
                    </div>
                    <!--end::Footer-->
                  </form>
                  <!--end::Form-->
                </div>
              </div>
              <!--end::Card-->
            </div>
            <!--end::Col-->
            <!--begin::Col-->
            <div class="col-xl-3 col-md-3">
              <!--begin::Card-->
              <div class="card card-scroll h-550px">
                <div class="card-header align-items-center">
                  <div class="card-title">
                    <h3>Email Tags</h3>
                  </div>
                </div>
                <div class="card-body p-0">
                  <!--begin::Block-->
                  <div class="py-5">
                    <div class="rounded border">
                      <!--begin::Accordion-->
                      <div class="accordion" id="kt_accordion_1">
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="kt_accordion_1_header_1">
                            <button class="accordion-button fs-4 fw-bold" type="button" data-bs-toggle="collapse"
                              data-bs-target="#kt_accordion_1_body_1" aria-expanded="true"
                              aria-controls="kt_accordion_1_body_1">User</button>
                          </h2>
                          <div id="kt_accordion_1_body_1" class="accordion-collapse collapse show"
                            aria-labelledby="kt_accordion_1_header_1" data-bs-parent="#kt_accordion_1">
                            <div class="accordion-body text-danger text-center">
                              <div class="mb-2 bg-secondary">{user_id}</div>
                              <div class="mb-2 bg-secondary">{user_name}</div>
                              <div class="mb-2 bg-secondary">{user_email}</div>
                              <div class="mb-2 bg-secondary">{user_phone}</div>
                              <div class="mb-2 bg-secondary">{user_points}</div>
                              <div class="mb-2 bg-secondary">{user_points_added}</div>
                              <div class="mb-2 bg-secondary">{user_points_message}</div>
                              <div class="mb-2 bg-secondary">{user_birthday}</div>
                              <div class="mb-2 bg-secondary">{user_coupon_code}</div>
                              <div class="mb-2 bg-secondary">{user_coupon_discount}</div>
                            </div>
                          </div>
                        </div>
                        <div class="accordion-item">
                          <h2 class="accordion-header" id="kt_accordion_1_header_2">
                            <button class="accordion-button fs-4 fw-bold collapsed" type="button"
                              data-bs-toggle="collapse" data-bs-target="#kt_accordion_1_body_2" aria-expanded="false"
                              aria-controls="kt_accordion_1_body_2">Orders</button>
                          </h2>
                          <div id="kt_accordion_1_body_2" class="accordion-collapse collapse"
                            aria-labelledby="kt_accordion_1_header_2" data-bs-parent="#kt_accordion_1">
                            <div class="mb-2 bg-secondary">{order_id}</div>
                            <div class="mb-2 bg-secondary">{order_items}</div>
                            <div class="mb-2 bg-secondary">{order_date}</div>
                            <div class="mb-2 bg-secondary">{order_status}</div>
                            <div class="mb-2 bg-secondary">{order_booking}</div>
                            <div class="mb-2 bg-secondary">{order_play_area}</div>
                            <div class="mb-2 bg-secondary">{order_event}</div>
                            <div class="mb-2 bg-secondary">{order_review}</div>
                          </div>
                        </div>
                      </div>
                      <!--end::Accordion-->
                    </div>
                  </div>
                  <!--end::Block-->
                </div>
              </div>
              <!--end::Card-->
            </div>
            <!--end::Col-->
          </div>
          <!--end::Row-->
        </div>
        <!--end::Content-->
      </div>
      <!--end::Inbox App - Compose -->
    </div>
    <!--end::Content container-->
  </div>
@endsection
@section('scripts')
@endsection
