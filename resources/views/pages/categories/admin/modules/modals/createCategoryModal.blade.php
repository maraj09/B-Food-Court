<div class="modal fade" id="createCategoryModal" tabindex="-1" aria-labelledby="createCategoryLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="createCategoryLabel">Create</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <div class="modal-body">
        <!-- Your form or content goes here -->
        <form id="category_form" method="POST" action="{{ route('categories.store') }}">
          @csrf
          <!-- Card Body -->
          <div class="card-body">
            <!-- Category Name -->
            <div class="">
              <label for="name" class="form-label fs-6 fw-semibold">Category Name</label>
              <input type="text" id="name" name="name"
                class="form-control form-control-solid @error('name') is-invalid @enderror" value="{{ old('name') }}"
                placeholder="Enter Category Name">
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mt-5">
              <label for="name" class="form-label fs-6 fw-semibold">Category Ribbon Color</label>
              <select class="form-select @error('ribbon_color') is-invalid @enderror" name="ribbon_color" data-control="select2"
                data-placeholder="Select an option">
                <option></option>
                <option value="bg-light" {{ old('ribbon_color') == 'bg-light' ? 'selected' : '' }}>
                  Light</option>
                <option value="bg-primary" {{ old('ribbon_color') == 'bg-primary' ? 'selected' : '' }}>
                  Blue</option>
                <option value="bg-secondary" {{ old('ribbon_color') == 'bg-secondary' ? 'selected' : '' }}>
                  Gray</option>
                <option value="bg-success" {{ old('ribbon_color') == 'bg-success' ? 'selected' : '' }}>
                  Green</option>
                <option value="bg-info" {{ old('ribbon_color') == 'bg-info' ? 'selected' : '' }}>
                  Violet</option>
                <option value="bg-warning" {{ old('ribbon_color') == 'bg-warning' ? 'selected' : '' }}>
                  Yellow</option>
                <option value="bg-danger" {{ old('ribbon_color') == 'bg-danger' ? 'selected' : '' }}>
                  Red</option>
                <option value="bg-dark" {{ old('ribbon_color') == 'bg-dark' ? 'selected' : '' }}>
                  Black</option>
                <option value="bg-white" {{ old('ribbon_color') == 'bg-white' ? 'selected' : '' }}>
                  White</option>
                <option value="bg-light-primary" {{ old('ribbon_color') == 'bg-light-primary' ? 'selected' : '' }}>
                  Light Blue</option>
                <option value="bg-light-secondary" {{ old('ribbon_color') == 'bg-light-secondary' ? 'selected' : '' }}>
                  Light Gray</option>
                <option value="bg-light-success" {{ old('ribbon_color') == 'bg-light-success' ? 'selected' : '' }}>
                  Light Green</option>
                <option value="bg-light-info" {{ old('ribbon_color') == 'bg-light-info' ? 'selected' : '' }}>
                  Light Violet</option>
                <option value="bg-light-warning" {{ old('ribbon_color') == 'bg-light-warning' ? 'selected' : '' }}>
                  Light Yellow</option>
                <option value="bg-light-danger" {{ old('ribbon_color') == 'bg-light-danger' ? 'selected' : '' }}>
                  Light Red</option>
                <option value="bg-light-dark" {{ old('ribbon_color') == 'bg-light-dark' ? 'selected' : '' }}>
                  Light Black</option>
              </select>
              @error('ribbon_color')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

          </div>
          <!-- Card Body -->

          <!-- Card Footer -->
          <div class="card-footer d-flex justify-content-end py-6 px-9">
            <button type="submit"
              class="btn btn-primary">Create Category</button>
          </div>
          <!-- Card Footer -->
        </form>
      </div>
    </div>
  </div>
</div>
