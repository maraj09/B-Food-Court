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

      <!--begin::Products-->
      <div class="card card-flush">
        <!--begin::Card header-->
        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
          <!--begin::Card title-->
          <div class="card-title">
            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative my-1">
              <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
              <input type="text" data-kt-ecommerce-order-filter="search"
                class="form-control form-control-solid w-175px w-md-350px ps-12" placeholder="Search Category" />
            </div>
            <!--end::Search-->
          </div>
          <!--end::Card title-->
          <!--begin::Card toolbar-->
          <div class="card-toolbar flex-row-fluid justify-content-end gap-5">
            <h3 class="card-title align-items-center flex-column">
              <span class="card-label fw-bold fs-3 mb-1">{{ $categories->count() }}</span>

              <span class="text-muted fw-semibold fs-7">Total Categories</span>
            </h3>
          </div>
          <!--end::Card toolbar-->
        </div>
        <!--end::Card header-->
        <!--begin::Card body-->
        <div class="card-body pt-0">
          <!--begin::Table-->
          <div class="table-responsive">
            <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_ecommerce_sales_table">
              <thead>
                <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                  <th class="w-10px pe-2">
                    <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                      <input class="form-check-input" type="checkbox" data-kt-check="true"
                        data-kt-check-target="#kt_ecommerce_sales_table .form-check-input" value="1" />
                    </div>
                  </th>
                  <th class="min-w-175px">Category Name</th>
                  <th class="text-center min-w-75px">Number of Items</th>
                  <th class="min-w-75px">Created On</th>
                  <th class="text-center min-w-100px">Actions</th>
                </tr>
              </thead>
              <tbody class="fw-semibold text-gray-600">
                @foreach ($categories as $category)
                  <tr>
                    <td>
                      <div class="form-check form-check-sm form-check-custom form-check-solid">
                        <input class="form-check-input" type="checkbox" value="1" />
                      </div>
                    </td>
                    <td class="">
                      <div class="d-flex align-items-center">
                        <span>{{ $category->name }}</span>
                        <span class="{{ $category->ribbon_color }} ms-3 w-15px h-15px rounded-circle"></span>
                      </div>
                    </td>
                    <td class="text-center">
                      <span class="fw-bold">{{ $category->items->count() }}</span>
                    </td>
                    <td data-order="{{ $category->created_at }}">
                      <span>{{ $category->created_at->format('d/m/Y') }}</span>
                    </td>
                    <td class="text-center min-w-150px">
                      <a href="javascript:void(0)" class="btn btn-sm btn-warning" data-bs-toggle="modal"
                        data-bs-target="#createCategoryModal" data-category-id="{{ $category->id }}"
                        data-category-name="{{ $category->name }}" data-category-ribbon="{{ $category->ribbon_color }}">
                        Edit
                      </a>
                      <form action="{{ route('categories.destroy', $category->id) }}" method="POST"
                        style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <a href="javascript:void(0)" class="mx-3 btn btn-sm btn-danger"
                          onclick="submitParentForm(this)">Delete</a>
                      </form>
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
          </div>
          <!--end::Table-->
        </div>
        <!--end::Card body-->
      </div>
      <!--end::Products-->
      <!--end::Content container-->
    </div>
    <!--end::Content-->
  </div>
@endsection
@section('modules')
  @include('pages.coupons.admin.modules.toasts.status')
  @include('pages.categories.admin.modules.modals.createCategoryModal')
  <!--end::Toast-->
@endsection
@section('scripts')
  <script src="{{ asset('assets/js/custom/apps/ecommerce/sales/listing.js') }}"></script>
  @php
    $hasCreateErrors = $errors->has('name') || $errors->has('ribbon_color');
  @endphp
  <script>
    const hasCreateErrors = @json($hasCreateErrors);
    if (hasCreateErrors) {
      const createCategoryModal = new bootstrap.Modal(document.getElementById('createCategoryModal'), {
        backdrop: 'static',
        keyboard: false
      });
      createCategoryModal.show();
    }

    document.addEventListener('DOMContentLoaded', function() {
      const createCategoryModal = document.getElementById('createCategoryModal');
      createCategoryModal.addEventListener('show.bs.modal', function(event) {
        const button = event.relatedTarget;
        const categoryId = button.getAttribute('data-category-id');
        const categoryName = button.getAttribute('data-category-name');
        const categoryRibbon = button.getAttribute('data-category-ribbon');

        const modalTitle = createCategoryModal.querySelector('.modal-title');
        const nameInput = createCategoryModal.querySelector('#name');
        const ribbonSelect = createCategoryModal.querySelector('select[name="ribbon_color"]');
        const form = createCategoryModal.querySelector('form');

        if (categoryId) {
          modalTitle.textContent = 'Edit Category';
          nameInput.value = categoryName;
          ribbonSelect.value = categoryRibbon;
          $(ribbonSelect).val(categoryRibbon).trigger('change');
          form.action = `/dashboard/categories/${categoryId}`;
          form.method = 'POST';
          form.insertAdjacentHTML('beforeend', '<input type="hidden" name="_method" value="PUT">');
        } else {
          modalTitle.textContent = 'Create Category';
          nameInput.value = '';
          ribbonSelect.value = '';
          $(ribbonSelect).val('').trigger('change');
          form.action = '{{ route('categories.store') }}';
          form.method = 'POST';
          const methodInput = form.querySelector('input[name="_method"]');
          if (methodInput) methodInput.remove();
        }
      });
    });

    function submitParentForm(button) {
      button.closest('form').submit();
    }
  </script>
@endsection
