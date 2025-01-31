@extends('layouts.admin.app')
@section('contents')
  @include('pages.categories.admin.toolbar.indexToolbar')
  <div id="kt_app_content" class="app-content flex-column-fluid mt-20 mt-lg-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl">
      @if (Session::has('success'))
        <div class="alert alert-success">
          {{ Session::get('success') }}
        </div>
      @endif
      <!--begin::Custom Content-->
      <div class="card col-6 mx-auto min-w-300px">
        <!--begin::Form-->
        <form id="category_form" method="POST"
          action="{{ isset($category) ? route('categories.update', $category->id) : route('categories.store') }}">
          @csrf
          @if (isset($category))
            @method('PUT')
          @endif

          <!-- Card Body -->
          <div class="card-body p-9">
            <!-- Category Name -->
            <div class="">
              <label for="name" class="form-label fs-6 fw-semibold">Category Name</label>
              <input type="text" id="name" name="name"
                class="form-control form-control-solid @error('name') is-invalid @enderror"
                value="{{ old('name', isset($category) ? $category->name : '') }}" placeholder="Enter Category Name">
              @error('name')
                <div class="invalid-feedback">{{ $message }}</div>
              @enderror
            </div>

            <div class="mt-5">
              <label for="name" class="form-label fs-6 fw-semibold">Category Ribbon Color</label>
              <select class="form-select" name="ribbon_color" data-control="select2" data-placeholder="Select an option">
                <option></option>
                <option value="bg-light"
                  {{ old('ribbon_color', isset($category) ? $category->ribbon_color : '') == 'bg-light' ? 'selected' : '' }}>
                  Light</option>
                <option value="bg-primary"
                  {{ old('ribbon_color', isset($category) ? $category->ribbon_color : '') == 'bg-primary' ? 'selected' : '' }}>
                  Blue</option>
                <option value="bg-secondary"
                  {{ old('ribbon_color', isset($category) ? $category->ribbon_color : '') == 'bg-secondary' ? 'selected' : '' }}>
                  Gray</option>
                <option value="bg-success"
                  {{ old('ribbon_color', isset($category) ? $category->ribbon_color : '') == 'bg-success' ? 'selected' : '' }}>
                  Green</option>
                <option value="bg-info"
                  {{ old('ribbon_color', isset($category) ? $category->ribbon_color : '') == 'bg-info' ? 'selected' : '' }}>
                  Violet</option>
                <option value="bg-warning"
                  {{ old('ribbon_color', isset($category) ? $category->ribbon_color : '') == 'bg-warning' ? 'selected' : '' }}>
                  Yellow</option>
                <option value="bg-danger"
                  {{ old('ribbon_color', isset($category) ? $category->ribbon_color : '') == 'bg-danger' ? 'selected' : '' }}>
                  Red</option>
                <option value="bg-dark"
                  {{ old('ribbon_color', isset($category) ? $category->ribbon_color : '') == 'bg-dark' ? 'selected' : '' }}>
                  Black</option>
                <option value="bg-white"
                  {{ old('ribbon_color', isset($category) ? $category->ribbon_color : '') == 'bg-white' ? 'selected' : '' }}>
                  White</option>
                <option value="bg-light-primary"
                  {{ old('ribbon_color', isset($category) ? $category->ribbon_color : '') == 'bg-light-primary' ? 'selected' : '' }}>
                  Light Blue</option>
                <option value="bg-light-secondary"
                  {{ old('ribbon_color', isset($category) ? $category->ribbon_color : '') == 'bg-light-secondary' ? 'selected' : '' }}>
                  Light Gray</option>
                <option value="bg-light-success"
                  {{ old('ribbon_color', isset($category) ? $category->ribbon_color : '') == 'bg-light-success' ? 'selected' : '' }}>
                  Light Green</option>
                <option value="bg-light-info"
                  {{ old('ribbon_color', isset($category) ? $category->ribbon_color : '') == 'bg-light-info' ? 'selected' : '' }}>
                  Light Violet</option>
                <option value="bg-light-warning"
                  {{ old('ribbon_color', isset($category) ? $category->ribbon_color : '') == 'bg-light-warning' ? 'selected' : '' }}>
                  Light Yellow</option>
                <option value="bg-light-danger"
                  {{ old('ribbon_color', isset($category) ? $category->ribbon_color : '') == 'bg-light-danger' ? 'selected' : '' }}>
                  Light Red</option>
                <option value="bg-light-dark"
                  {{ old('ribbon_color', isset($category) ? $category->ribbon_color : '') == 'bg-light-dark' ? 'selected' : '' }}>
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
              class="btn btn-primary">{{ isset($category) ? 'Update Category' : 'Create Category' }}</button>
          </div>
          <!-- Card Footer -->
        </form>
        <!--end:Form-->
      </div>
      <!--end::Custom Content-->
    </div>
    <!--end::Content container-->
  </div>
@endsection
