@extends('layouts.guest')
@section('contents')
  <div class="d-flex mx-auto flex-column flex-center text-center p-10">
    <!--begin::Wrapper-->
    <div class="card card-flush w-lg-650px py-5">
      <div class="card-body py-15 py-lg-20">
        <!--begin::Logo-->
        <div class="mb-14">
          <a href="index.html" class="">
            <img alt="Logo" src="assets/media/logos/custom-2.svg" class="h-40px" />
          </a>
        </div>
        <!--end::Logo-->
        <!--begin::Title-->
        <h1 class="fw-bolder text-gray-900 mb-5">Verify your email</h1>
        <!--end::Title-->
        <!--begin::Action-->
        @if (session('status') == 'verification-link-sent')
          <span class="fw-semibold text-gray-500">A new verification link has been sent to the email address you provided
            during registration.</span>
        @endif
        <div class="fs-6 mb-8">
          <span class="fw-semibold text-gray-500">Didn't receive an email?</span>
          <form method="POST" action="{{ route('verification.send') }}" id="verification_sent_form">
            @csrf
            <a href="javascript:void(0)" onclick="submitParentForm(this, '#verification_sent_form')" class="link-primary fw-bold">Try
              Again</a>
          </form>
        </div>
        <!--end::Action-->
        <!--begin::Link-->
        <div class="mb-11">
          <form method="POST" action="{{ route('logout') }}" id="verification_logout_form">
            @csrf
            <a href="javascript:void(0)" onclick="submitParentForm(this, '#verification_logout_form')" class="btn btn-sm btn-primary">Log Out</a>
          </form>
        </div>
        <!--end::Link-->
        <!--begin::Illustration-->
        <div class="mb-0">
          <img src="assets/media/auth/please-verify-your-email.png" class="mw-100 mh-300px theme-light-show"
            alt="" />
          <img src="assets/media/auth/please-verify-your-email-dark.png" class="mw-100 mh-300px theme-dark-show"
            alt="" />
        </div>
        <!--end::Illustration-->
      </div>
    </div>
    <!--end::Wrapper-->
  </div>
@endsection
