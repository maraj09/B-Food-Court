@extends('layouts.customer.app')
@section('contents')
  <div class="bgi-no-repeat bgi-size-contain bgi-position-x-center bgi-position-y-bottom">
    <div class="d-flex flex-column flex-center w-100 mt-10 mb-n20">
      <div class="tns mt-n5" style="direction: ltr">
        <div data-tns="true" data-tns-nav="false" data-tns-mouse-drag="true" data-tns-controls="false" data-tns-dots="false">
          <!--begin::Item-->
          <div class="text-center">
            <img src="assets/media/stock/1600x800/5.png" class="card-rounded shadow w-100 h-75" alt="" />
          </div>
          <!--end::Item-->
          <!--begin::Item-->
          <div class="text-center">
            <img src="assets/media/stock/1600x800/2.png" class="card-rounded shadow w-100 h-75" alt="" />
          </div>
          <!--end::Item-->
          <!--begin::Item-->
          <div class="text-center">
            <img src="assets/media/stock/1600x800/3.png" class="card-rounded shadow w-100 h-75" alt="" />
          </div>
          <!--end::Item-->
          <!--begin::Item-->
          <div class="text-center">
            <img src="assets/media/stock/1600x800/4.png" class="card-rounded shadow w-100 h-75" alt="" />
          </div>
          <!--end::Item-->
          <!--begin::Item-->
          <div class="text-center">
            <img src="assets/media/stock/1600x800/1.png" class="card-rounded shadow w-100 h-75" alt="" />
          </div>
          <!--end::Item-->
        </div>
      </div>
    </div>
  </div>
  <div class="mt-sm-n20">
    <!--begin::Wrapper-->
    <div class="pb-5 pt-5 mt-n20">
      <!--begin::Container-->
      <div class="container">
        <!--begin::Heading-->
        <div class="text-center mt-15 mb-18" id="achievements" data-kt-scroll-offset="{default: 100, lg: 150}">
          <!--begin::Title-->
          <h3 class="fs-2hx text-white fw-bold mb-5">What Make Us Special</h3>
          <!--end::Title-->
          <!--begin::Description-->
          <div class="fs-5 text-gray-700 fw-bold">Serve thousands of customers every month</div>
          <!--end::Description-->
        </div>
        <!--end::Heading-->
        <!--begin::Statistics-->
        <div class="d-flex flex-center">
          <!--begin::Items-->
          <div class="d-flex flex-wrap flex-center justify-content-lg-between mb-15 mx-auto w-xl-900px">
            <!--begin::Item-->
            <div
              class="d-flex flex-column flex-center h-200px w-200px h-lg-250px w-lg-250px m-3 bgi-no-repeat bgi-position-center bgi-size-contain"
              style="background-image: url('assets/media/svg/misc/octagon.svg')">
              <!--begin::Symbol-->
              <i class="fa-solid fa-users-viewfinder text-danger fs-3x"></i>
              <!--end::Symbol-->
              <!--begin::Info-->
              <div class="mb-0">
                <!--begin::Value-->
                <div class="fs-lg-2hx fs-2x fw-bold text-white d-flex flex-center">
                  <div class="min-w-70px" data-kt-countup="true" data-kt-countup-value="{{ $totalCustomerCount }}"
                    data-kt-countup-suffix="+">0
                  </div>
                </div>
                <!--end::Value-->
                <!--begin::Label-->
                <span class="text-gray-600 fw-semibold fs-5 lh-0">Customers</span>
                <!--end::Label-->
              </div>
              <!--end::Info-->
            </div>
            <!--end::Item-->
            <!--begin::Item-->
            <div
              class="d-flex flex-column flex-center h-200px w-200px h-lg-250px w-lg-250px m-3 bgi-no-repeat bgi-position-center bgi-size-contain"
              style="background-image: url('assets/media/svg/misc/octagon.svg')">
              <!--begin::Symbol-->
              <i class="fa-regular fa-rectangle-list text-primary fs-3x"></i>
              <!--end::Symbol-->
              <!--begin::Info-->
              <div class="mb-0">
                <!--begin::Value-->
                <div class="fs-lg-2hx fs-2x fw-bold text-white d-flex flex-center">
                  <div class="min-w-70px" data-kt-countup="true" data-kt-countup-value="{{ $totalItemsCount }}"
                    data-kt-countup-suffix="+">0
                  </div>
                </div>
                <!--end::Value-->
                <!--begin::Label-->
                <span class="text-gray-600 fw-semibold fs-5 lh-0">Menu Items</span>
                <!--end::Label-->
              </div>
              <!--end::Info-->
            </div>
            <!--end::Item-->
            <!--begin::Item-->
            <div
              class="d-flex flex-column flex-center h-200px w-200px h-lg-250px w-lg-250px m-3 bgi-no-repeat bgi-position-center bgi-size-contain"
              style="background-image: url('assets/media/svg/misc/octagon.svg')">
              <!--begin::Symbol-->
              <i class="fa-solid fa-store text-success fs-3x"></i>
              <!--end::Symbol-->
              <!--begin::Info-->
              <div class="mb-0">
                <!--begin::Value-->
                <div class="fs-lg-2hx fs-2x fw-bold text-white d-flex flex-center">
                  <div class="min-w-70px" data-kt-countup="true" data-kt-countup-value="{{ $totalVendorsCount }}"
                    data-kt-countup-suffix="+">0
                  </div>
                </div>
                <!--end::Value-->
                <!--begin::Label-->
                <span class="text-gray-600 fw-semibold fs-5 lh-0">Vendors</span>
                <!--end::Label-->
              </div>
              <!--end::Info-->
            </div>
            <!--end::Item-->
          </div>
          <!--end::Items-->
        </div>
        <!--end::Statistics-->
      </div>
      <!--end::Container-->
    </div>
    <!--end::Wrapper-->
  </div>
  <div class="mt-sm-n20">
    <!--begin::Wrapper-->
    <div class="pb-5 pt-5 mt-15">
      <!--begin::Container-->
      <div class="container">
        <!--begin::Row-->
        <div class="row row-cols-1 row-cols-md-4 row-cols-lg-5 g-0">
          <!--begin::Col-->
          @foreach ($topFourItems as $item)
            <div class="col rounded-2 d-flex flex-column flex-row-fluid m-2">
              <!--begin::Mixed Widget 17-->
              <div class="card card-flush d-flex flex-column-auto p-0 m-1">
                <!--begin::Body-->
                <div class="card-body p-0 ribbon ribbon-top">
                  <div class="ribbon-label bg-primary">{{ $item->category->name }}
                  </div>
                  <!--begin::Image-->
                  <div class="d-flex flex-center w-100 py-2">
                    <div class="symbol symbol-150px symbol-circle me-2 my-2">
                      <img src="{{ asset($item->item_image) }}" class="align-self-center" alt="">
                    </div>
                  </div>
                  <!--end::Image-->
                  <!--begin::Name and Vendor-->
                  <div class="text-center w-100 position-relative z-index-1 py-3">
                    <a href="#"
                      class="fs-4 text-gray-800 text-hover-primary fw-bold">{{ $item->item_name }}</br></a>
                    <span class="text-gray-500 fw-semibold fs-7">By: <a href="#"
                        class="text-primary fw-bold">{{ $item->vendor->brand_name }}</a></span>
                  </div>
                  <!--end::Name and Vendor-->
                </div>
                <!--end::Body-->
                <!--begin::Footer-->
                <div class="card-footer d-flex flex-center py-2 px-4">
                  <!--begin::Rating-->
                  <div class="d-flex flex-wrap flex-grow-1">
                    <div class="me-5s">
                      <span class="badge badge-outline badge-warning">Stall No. {{ $item->vendor->stall_no }}</span>
                    </div>
                    <div class="m-1">
                      <i class="fa fa-star-half-alt me-1 text-warning fs-5"></i>
                      <span class="text-gray-800 fw-bold">{{ $item->itemRating->rating ?? 0 }}</span>
                    </div>
                  </div>
                  <!--ed::Rating-->
                  <!--begin::Add Now-->
                  <div class="d-flex align-items-center flex-shrink-0">
                    <a href="/food-items" class="btn btn-danger hover-scale w-100 px-4 py-2" data-bs-toggle="tooltip"
                      data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title="Add Item"><i
                        class="fa-solid fa-cart-plus"></i>₹{{ round($item->price) }}</a>
                  </div>
                  <!--ed::Add Now-->
                </div>
                <!--end::Footer-->
              </div>
              <!--end::Mixed Widget 17-->
            </div>
          @endforeach
          <!--end::Col-->
        </div>
        <!--end::Row-->
      </div>
      <!--end::Container-->
    </div>
    <!--end::Wrapper-->
  </div>
  <div class="py-10 py-lg-20">
    <!--begin::Container-->
    <div class="container">
      <!--begin::Heading-->
      <div class="text-center mb-12">
        <!--begin::Title-->
        <h3 class="fs-2hx text-gray-900 mb-5" id="team" data-kt-scroll-offset="{default: 100, lg: 150}">Our
          Partners</h3>
        <!--end::Title-->
        <!--begin::Sub-title-->
        <div class="fs-5 text-muted fw-bold">Partners Who Grate Taste</div>
        <!--end::Sub-title=-->
      </div>
      <!--end::Heading-->
      <!--begin::Slider-->
      <div class="tns tns-default" style="direction: ltr">
        <!--begin::Wrapper-->
        <div data-tns="true" data-tns-loop="true" data-tns-swipe-angle="false" data-tns-speed="2000"
          data-tns-autoplay="true" data-tns-autoplay-timeout="5000" data-tns-controls="true" data-tns-nav="false"
          data-tns-items="1" data-tns-center="false" data-tns-dots="false" data-tns-prev-button="#kt_team_slider_prev"
          data-tns-next-button="#kt_team_slider_next" data-tns-responsive="{1200: {items: 3}, 992: {items: 2}}">
          <!--begin::Item-->
          @foreach ($topTenVendors as $vendor)
            <div class="text-center">
              <!--begin::Photo-->
              <div class="mx-auto mb-5 d-flex w-200px h-200px bgi-no-repeat bgi-size-contain bgi-position-center"
                style="background-image:url('{{ $vendor->avatar ? asset($vendor->avatar) : asset('assets/media/svg/avatars/blank-dark.svg') }}')">
              </div>
              <!--end::Photo-->
              <!--begin::Person-->
              <div class="mb-0">
                <!--begin::Name-->
                <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-3">{{ $vendor->brand_name }}</a>
                <!--end::Name-->
                <!--begin::Position-->
                <div class="mt-1"><span class="badge badge-outline badge-warning">Stall No.
                    {{ $vendor->stall_no }}</span></div>
                <!--begin::Position-->
              </div>
              <!--end::Person-->
            </div>
          @endforeach
          <!--end::Item-->

        </div>
        <!--end::Wrapper-->
        <!--begin::Button-->
        <button class="btn btn-icon btn-active-color-primary" id="kt_team_slider_prev">
          <i class="ki-duotone ki-left fs-2x"></i>
        </button>
        <!--end::Button-->
        <!--begin::Button-->
        <button class="btn btn-icon btn-active-color-primary" id="kt_team_slider_next">
          <i class="ki-duotone ki-right fs-2x"></i>
        </button>
        <!--end::Button-->
      </div>
      <!--end::Slider-->
    </div>
    <!--end::Container-->
  </div>
  <div class="mt-20 mb-n20 position-relative z-index-2">
    <!--begin::Container-->
    <div class="container">
      <!--begin::Heading-->
      <div class="text-center mb-17">
        <!--begin::Title-->
        <h3 class="fs-2hx text-gray-900 mb-5" id="clients" data-kt-scroll-offset="{default: 125, lg: 150}">What Our
          Clients Say</h3>
        <!--end::Title-->
        <!--begin::Description-->
        <div class="fs-5 text-muted fw-bold">Save thousands to millions of bucks by using single tool
          <br />for different amazing and great useful admin
        </div>
        <!--end::Description-->
      </div>
      <!--end::Heading-->
      <!--begin::Row-->
      <div class="row g-lg-10 mb-10 mb-lg-20">
        <!--begin::Col-->
        <div class="col-lg-4">
          <!--begin::Testimonial-->
          <div class="d-flex flex-column justify-content-between h-lg-100 px-10 px-lg-0 pe-lg-10 mb-15 mb-lg-0">
            <!--begin::Wrapper-->
            <div class="mb-7">
              <!--begin::Rating-->
              <div class="rating mb-6">
                <div class="rating-label me-2 checked">
                  <i class="ki-duotone ki-star fs-5"></i>
                </div>
                <div class="rating-label me-2 checked">
                  <i class="ki-duotone ki-star fs-5"></i>
                </div>
                <div class="rating-label me-2 checked">
                  <i class="ki-duotone ki-star fs-5"></i>
                </div>
                <div class="rating-label me-2 checked">
                  <i class="ki-duotone ki-star fs-5"></i>
                </div>
                <div class="rating-label me-2 checked">
                  <i class="ki-duotone ki-star fs-5"></i>
                </div>
              </div>
              <!--end::Rating-->
              <!--begin::Title-->
              <div class="fs-2 fw-bold text-gray-900 mb-3">This is by far the cleanest template
                <br />and the most well structured
              </div>
              <!--end::Title-->
              <!--begin::Feedback-->
              <div class="text-gray-500 fw-semibold fs-4">The most well thought out design theme I have ever used. The
                codes are up to tandard. The css styles are very clean. In fact the cleanest and the most up to standard I
                have ever seen.</div>
              <!--end::Feedback-->
            </div>
            <!--end::Wrapper-->
            <!--begin::Author-->
            <div class="d-flex align-items-center">
              <!--begin::Avatar-->
              <div class="symbol symbol-circle symbol-50px me-5">
                <img src="assets/media/avatars/300-1.jpg" class="" alt="" />
              </div>
              <!--end::Avatar-->
              <!--begin::Name-->
              <div class="flex-grow-1">
                <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-6">Paul Miles</a>
                <span class="text-muted d-block fw-bold">Development Lead</span>
              </div>
              <!--end::Name-->
            </div>
            <!--end::Author-->
          </div>
          <!--end::Testimonial-->
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-lg-4">
          <!--begin::Testimonial-->
          <div class="d-flex flex-column justify-content-between h-lg-100 px-10 px-lg-0 pe-lg-10 mb-15 mb-lg-0">
            <!--begin::Wrapper-->
            <div class="mb-7">
              <!--begin::Rating-->
              <div class="rating mb-6">
                <div class="rating-label me-2 checked">
                  <i class="ki-duotone ki-star fs-5"></i>
                </div>
                <div class="rating-label me-2 checked">
                  <i class="ki-duotone ki-star fs-5"></i>
                </div>
                <div class="rating-label me-2 checked">
                  <i class="ki-duotone ki-star fs-5"></i>
                </div>
                <div class="rating-label me-2 checked">
                  <i class="ki-duotone ki-star fs-5"></i>
                </div>
                <div class="rating-label me-2 checked">
                  <i class="ki-duotone ki-star fs-5"></i>
                </div>
              </div>
              <!--end::Rating-->
              <!--begin::Title-->
              <div class="fs-2 fw-bold text-gray-900 mb-3">This is by far the cleanest template
                <br />and the most well structured
              </div>
              <!--end::Title-->
              <!--begin::Feedback-->
              <div class="text-gray-500 fw-semibold fs-4">The most well thought out design theme I have ever used. The
                codes are up to tandard. The css styles are very clean. In fact the cleanest and the most up to standard I
                have ever seen.</div>
              <!--end::Feedback-->
            </div>
            <!--end::Wrapper-->
            <!--begin::Author-->
            <div class="d-flex align-items-center">
              <!--begin::Avatar-->
              <div class="symbol symbol-circle symbol-50px me-5">
                <img src="assets/media/avatars/300-2.jpg" class="" alt="" />
              </div>
              <!--end::Avatar-->
              <!--begin::Name-->
              <div class="flex-grow-1">
                <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-6">Janya Clebert</a>
                <span class="text-muted d-block fw-bold">Development Lead</span>
              </div>
              <!--end::Name-->
            </div>
            <!--end::Author-->
          </div>
          <!--end::Testimonial-->
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-lg-4">
          <!--begin::Testimonial-->
          <div class="d-flex flex-column justify-content-between h-lg-100 px-10 px-lg-0 pe-lg-10 mb-15 mb-lg-0">
            <!--begin::Wrapper-->
            <div class="mb-7">
              <!--begin::Rating-->
              <div class="rating mb-6">
                <div class="rating-label me-2 checked">
                  <i class="ki-duotone ki-star fs-5"></i>
                </div>
                <div class="rating-label me-2 checked">
                  <i class="ki-duotone ki-star fs-5"></i>
                </div>
                <div class="rating-label me-2 checked">
                  <i class="ki-duotone ki-star fs-5"></i>
                </div>
                <div class="rating-label me-2 checked">
                  <i class="ki-duotone ki-star fs-5"></i>
                </div>
                <div class="rating-label me-2 checked">
                  <i class="ki-duotone ki-star fs-5"></i>
                </div>
              </div>
              <!--end::Rating-->
              <!--begin::Title-->
              <div class="fs-2 fw-bold text-gray-900 mb-3">This is by far the cleanest template
                <br />and the most well structured
              </div>
              <!--end::Title-->
              <!--begin::Feedback-->
              <div class="text-gray-500 fw-semibold fs-4">The most well thought out design theme I have ever used. The
                codes are up to tandard. The css styles are very clean. In fact the cleanest and the most up to standard I
                have ever seen.</div>
              <!--end::Feedback-->
            </div>
            <!--end::Wrapper-->
            <!--begin::Author-->
            <div class="d-flex align-items-center">
              <!--begin::Avatar-->
              <div class="symbol symbol-circle symbol-50px me-5">
                <img src="assets/media/avatars/300-16.jpg" class="" alt="" />
              </div>
              <!--end::Avatar-->
              <!--begin::Name-->
              <div class="flex-grow-1">
                <a href="#" class="text-gray-900 fw-bold text-hover-primary fs-6">Steave Brown</a>
                <span class="text-muted d-block fw-bold">Development Lead</span>
              </div>
              <!--end::Name-->
            </div>
            <!--end::Author-->
          </div>
          <!--end::Testimonial-->
        </div>
        <!--end::Col-->
      </div>
      <!--end::Row-->
      <!--begin::Highlight-->
      <div class="d-flex flex-stack flex-wrap flex-md-nowrap card-rounded shadow p-8 p-lg-12 mb-n5 mb-lg-n13"
        style="background: linear-gradient(90deg, #20AA3E 0%, #03A588 100%);">
        <!--begin::Content-->
        <div class="my-2 me-5">
          <!--begin::Title-->
          <div class="fs-1 fs-lg-2qx fw-bold text-white mb-2">Start With Metronic Today,
            <span class="fw-normal">Speed Up Development!</span>
          </div>
          <!--end::Title-->
          <!--begin::Description-->
          <div class="fs-6 fs-lg-5 text-white fw-semibold opacity-75">Join over 100,000 Professionals Community to Stay
            Ahead</div>
          <!--end::Description-->
        </div>
        <!--end::Content-->
        <!--begin::Link-->
        <a href="https://1.envato.market/EA4JP"
          class="btn btn-lg btn-outline border-2 btn-outline-white flex-shrink-0 my-2">Purchase on Themeforest</a>
        <!--end::Link-->
      </div>
      <!--end::Highlight-->
    </div>
    <!--end::Container-->
  </div>
  <!--end::Testimonials Section-->
  <!--begin::Footer Section-->
  <div class="mb-0">
    <!--begin::Curve top-->
    <div class="landing-curve landing-dark-color">
      <svg viewBox="15 -1 1470 48" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
          d="M1 48C4.93573 47.6644 8.85984 47.3311 12.7725 47H1489.16C1493.1 47.3311 1497.04 47.6644 1501 48V47H1489.16C914.668 -1.34764 587.282 -1.61174 12.7725 47H1V48Z"
          fill="currentColor"></path>
      </svg>
    </div>
    <!--end::Curve top-->
    <!--begin::Wrapper-->
    <div class="landing-dark-bg pt-20">
      <!--begin::Container-->
      <div class="container">
        <!--begin::Row-->
        <div class="row py-10 py-lg-20">
          <!--begin::Col-->
          <div class="col-lg-6 pe-lg-16 mb-10 mb-lg-0">
            <!--begin::Block-->
            <div class="rounded landing-dark-border p-9 mb-10">
              <!--begin::Title-->
              <h2 class="text-white">Would you need a Custom License?</h2>
              <!--end::Title-->
              <!--begin::Text-->
              <span class="fw-normal fs-4 text-gray-700">Email us to
                <a href="https://keenthemes.com/support"
                  class="text-white opacity-50 text-hover-primary">support@keenthemes.com</a></span>
              <!--end::Text-->
            </div>
            <!--end::Block-->
            <!--begin::Block-->
            <div class="rounded landing-dark-border p-9">
              <!--begin::Title-->
              <h2 class="text-white">How About a Custom Project?</h2>
              <!--end::Title-->
              <!--begin::Text-->
              <span class="fw-normal fs-4 text-gray-700">Use Our Custom Development Service.
                <a href="pages/user-profile/overview.html" class="text-white opacity-50 text-hover-primary">Click to Get
                  a Quote</a></span>
              <!--end::Text-->
            </div>
            <!--end::Block-->
          </div>
          <!--end::Col-->
          <!--begin::Col-->
          <div class="col-lg-6 ps-lg-16">
            <!--begin::Navs-->
            <div class="d-flex justify-content-center">
              <!--begin::Links-->
              <div class="d-flex fw-semibold flex-column me-20">
                <!--begin::Subtitle-->
                <h4 class="fw-bold text-gray-500 mb-6">More for Metronic</h4>
                <!--end::Subtitle-->
                <!--begin::Link-->
                <a href="https://keenthemes.com/faqs" class="text-white opacity-50 text-hover-primary fs-5 mb-6">FAQ</a>
                <!--end::Link-->
                <!--begin::Link-->
                <a href="https://preview.keenthemes.com/html/metronic/docs"
                  class="text-white opacity-50 text-hover-primary fs-5 mb-6">Documentaions</a>
                <!--end::Link-->
                <!--begin::Link-->
                <a href="https://www.youtube.com/c/KeenThemesTuts/videos"
                  class="text-white opacity-50 text-hover-primary fs-5 mb-6">Video Tuts</a>
                <!--end::Link-->
                <!--begin::Link-->
                <a href="https://preview.keenthemes.com/html/metronic/docs/getting-started/changelog"
                  class="text-white opacity-50 text-hover-primary fs-5 mb-6">Changelog</a>
                <!--end::Link-->
                <!--begin::Link-->
                <a href="https://devs.keenthemes.com/"
                  class="text-white opacity-50 text-hover-primary fs-5 mb-6">Support Forum</a>
                <!--end::Link-->
                <!--begin::Link-->
                <a href="https://keenthemes.com/blog" class="text-white opacity-50 text-hover-primary fs-5">Blog</a>
                <!--end::Link-->
              </div>
              <!--end::Links-->
              <!--begin::Links-->
              <div class="d-flex fw-semibold flex-column ms-lg-20">
                <!--begin::Subtitle-->
                <h4 class="fw-bold text-gray-500 mb-6">Stay Connected</h4>
                <!--end::Subtitle-->
                <!--begin::Link-->
                <a href="https://www.facebook.com/keenthemes" class="mb-6">
                  <img src="assets/media/svg/brand-logos/facebook-4.svg" class="h-20px me-2" alt="" />
                  <span class="text-white opacity-50 text-hover-primary fs-5 mb-6">Facebook</span>
                </a>
                <!--end::Link-->
                <!--begin::Link-->
                <a href="https://github.com/KeenthemesHub" class="mb-6">
                  <img src="assets/media/svg/brand-logos/github.svg" class="h-20px me-2" alt="" />
                  <span class="text-white opacity-50 text-hover-primary fs-5 mb-6">Github</span>
                </a>
                <!--end::Link-->
                <!--begin::Link-->
                <a href="https://twitter.com/keenthemes" class="mb-6">
                  <img src="assets/media/svg/brand-logos/twitter.svg" class="h-20px me-2" alt="" />
                  <span class="text-white opacity-50 text-hover-primary fs-5 mb-6">Twitter</span>
                </a>
                <!--end::Link-->
                <!--begin::Link-->
                <a href="https://dribbble.com/keenthemes" class="mb-6">
                  <img src="assets/media/svg/brand-logos/dribbble-icon-1.svg" class="h-20px me-2" alt="" />
                  <span class="text-white opacity-50 text-hover-primary fs-5 mb-6">Dribbble</span>
                </a>
                <!--end::Link-->
                <!--begin::Link-->
                <a href="https://www.instagram.com/keenthemes" class="mb-6">
                  <img src="assets/media/svg/brand-logos/instagram-2-1.svg" class="h-20px me-2" alt="" />
                  <span class="text-white opacity-50 text-hover-primary fs-5 mb-6">Instagram</span>
                </a>
                <!--end::Link-->
              </div>
              <!--end::Links-->
            </div>
            <!--end::Navs-->
          </div>
          <!--end::Col-->
        </div>
        <!--end::Row-->
      </div>
      <!--end::Container-->
      <!--begin::Separator-->
      <div class="landing-dark-separator"></div>
      <!--end::Separator-->
      <!--begin::Container-->

      <!--end::Container-->
    </div>
    <!--end::Wrapper-->
  </div>
@endsection
