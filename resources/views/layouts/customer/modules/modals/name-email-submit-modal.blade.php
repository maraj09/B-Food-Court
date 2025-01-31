<!-- Modal-->
<div class="modal fade" id="shareDetailsModal" data-backdrop="static" data-keyboard="false" tabindex="-1" role="dialog"
  aria-labelledby="shareDetailsLabel" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered" role="document">
    <form class="form w-100" action="/settings" method="POST">
      @csrf
      @method('PUT')
      <div class="modal-content">
        <div class="modal-header">
          <h5 class="modal-title" id="shareDetailsLabel">Get Started - Share Your Details</h5>
        </div>
        <div class="modal-body">
          <div class="mb-3">
            <!--begin::Name-->
            <label for="name" class="form-label">Name</label>
            <input type="text" placeholder="Name" name="name" id="name" autocomplete="off"
              class="form-control bg-light @error('name') is-invalid @enderror" value="{{ old('name') }}" />
            @error('name')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <!--end::Name-->
          </div>
          <div class="mb-3">
            <!--begin::Email-->
            <label for="email" class="form-label">Email Address</label>
            <input type="email" placeholder="Email Address" name="email" id="email" autocomplete="off"
              class="form-control bg-light @error('email') is-invalid @enderror" value="{{ old('email') }}" />
            @error('email')
              <div class="invalid-feedback">{{ $message }}</div>
            @enderror
            <!--end::Email-->
          </div>
        </div>
        <div class="modal-footer">
          <button type="submit" class="btn btn-primary font-weight-bold w-100">Save changes</button>
        </div>
      </div>
    </form>
  </div>
</div>
