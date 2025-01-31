@extends('layouts.customer.app')
@section('contents')
  <div id="kt_app_content" class="app-content flex-column-fluid mt-20">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      <!--begin::Products-->
      <!--begin::Input group-->
      <div class="row g-9 mb-7">
        <!--begin::Col-->
        <div class="col-md-6 fv-row">
          <!--begin::Input group-->
          <div class="row g-9 mb-7">
            <!--begin::Col-->
            <div class="col ">
              <a href="{{ $settings->facebook }}" class="btn btn-icon btn-facebook me-5 "><i
                  class="fab fa-facebook-f fs-4"></i></a>
              <a href="{{ $settings->whatsapp }}" class="btn btn-icon btn-success me-5 "><i
                  class="fa-brands fa-whatsapp fs-4"></i></a>
              <a href="{{ $settings->instagram }}" class="btn btn-icon btn-instagram me-5 "><i
                  class="fab fa-instagram fs-4"></i></a>
              <a href="{{ $settings->youtube }}" class="btn btn-icon btn-youtube me-5 "><i
                  class="fab fa-youtube fs-4"></i></a>
            </div>
            <!--end::Col-->
          </div>
          <!--end::Input group-->
          <iframe src="{{ $settings->embed_map_link }}" width="600" height="450" style="border-radius:5px;"
            allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade"></iframe>
        </div>
        <!--end::Col-->
        <!--begin::Col-->
        <div class="col-md-6 fv-row">
          <!--begin::Input group-->
          @if (session('success'))
            <div class="alert alert-success">
              {{ session('success') }}
            </div>
          @endif

          <form method="POST" action="/contact-us/submit">
            @csrf
            <div class="row g-9 mb-7">
              <div class="col-md-6 fv-row">
                <label class="fs-6 fw-semibold mb-2">Name</label>
                <input class="form-control form-control-solid @error('name') is-invalid @enderror" placeholder="Full Name"
                  name="name" value="{{ old('name') }}">
                @error('name')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6 fv-row">
                <label class="fs-6 fw-semibold mb-2">Email</label>
                <input class="form-control form-control-solid @error('email') is-invalid @enderror" placeholder="Email"
                  name="email" value="{{ old('email') }}">
                @error('email')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="row g-9 mb-7">
              <div class="col-md-6 fv-row">
                <label class="fs-6 fw-semibold mb-2">Contact No.</label>
                <input class="form-control form-control-solid @error('contact_no') is-invalid @enderror"
                  placeholder="Contact No." name="contact_no" value="{{ old('contact_no') }}">
                @error('contact_no')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
              <div class="col-md-6 fv-row">
                <label class="fs-6 fw-semibold mb-2">Topic</label>
                <select class="form-select @error('topic') is-invalid @enderror" aria-label="Select example"
                  name="topic" data-control="select2">
                  <option value="Vendorship" {{ old('topic') == 'Vendorship' ? 'selected' : '' }}>Vendorship</option>
                  <option value="Quality" {{ old('topic') == 'Quality' ? 'selected' : '' }}>Quality</option>
                  <option value="Staff" {{ old('topic') == 'Staff' ? 'selected' : '' }}>Staff</option>
                  <option value="Event" {{ old('topic') == 'Event' ? 'selected' : '' }}>Event</option>
                  <option value="Play Area" {{ old('topic') == 'Play Area' ? 'selected' : '' }}>Play Area</option>
                </select>
                @error('topic')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="row g-9 mb-7">
              <div class="col fv-row">
                <label for="" class="form-label">Message</label>
                <textarea class="form-control @error('message') is-invalid @enderror" data-kt-autosize="true" name="message">{{ old('message') }}</textarea>
                @error('message')
                  <div class="invalid-feedback">{{ $message }}</div>
                @enderror
              </div>
            </div>
            <div class="row g-9 mt-7">
              <div class="col-md-6 row"></div>
              <div class="col-md-6 row text-end">
                <button type="submit" class="btn btn-primary hover-scale">Send</button>
              </div>
            </div>
          </form>
          <!--end::Input group-->
        </div>
        <!--end::Col-->
      </div>
      <!--end::Input group-->
      <!--end::Products-->
      <!--end::Content container-->
    </div>
    <!--end::Content-->
  </div>
@endsection
