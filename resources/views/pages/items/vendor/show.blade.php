@extends('layouts.vendor.app')
@section('contents')
  <form action="/vendor/items/{{ $item->id }}/delete" method="post" id="item_delete_form">
    @csrf
    @method('delete')
  </form>
  <!--begin::Toolbar-->
  @include('pages.items.vendor.toolbar.showToolbar')
  <!--end::Toolbar-->
  <!--begin::Content-->
  <div id="kt_app_content" class="app-content flex-column-fluid mt-20 mt-lg-0">
    <!--begin::Content container-->
    <div id="kt_app_content_container" class="app-container container-xxl ">
      <!--begin::Form-->
      <form id="kt_edit_item_form" action="/vendor/items/{{ $item->id }}" enctype="multipart/form-data" method="POST"
        class="form d-flex flex-column flex-lg-row fv-plugins-bootstrap5 fv-plugins-framework">
        @csrf
        @method('PUT')
        <!--begin::Aside column-->
        <div class="d-flex flex-column gap-7 gap-lg-10 w-100 w-lg-300px mb-7 me-lg-10">
          <!--begin::Thumbnail settings-->
          <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
              <!--begin::Card title-->
              <div class="card-title">
                <h2>Thumbnail</h2>
              </div>
              <!--end::Card title-->
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body text-center pt-0">
              <!--begin::Image input-->

              <div class="image-input image-input-empty image-input-outline image-input-placeholder mb-3"
                data-kt-image-input="true">
                <!--begin::Preview existing avatar-->
                <div class="image-input-wrapper w-150px h-150px"
                  style="background-image: url({{ $item->item_image ? asset($item->item_image) : asset('assets/media/svg/files/blank-image-dark.svg') }})">
                </div>
                <!--end::Preview existing avatar-->

                <!--begin::Label-->
                <label class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                  data-kt-image-input-action="change" data-bs-toggle="tooltip" aria-label="Change avatar"
                  data-bs-original-title="Change avatar" data-kt-initialized="1">
                  <i class="ki-duotone ki-pencil fs-7"><span class="path1"></span><span class="path2"></span></i>
                  <!--begin::Inputs-->
                  <input type="file" name="item_image" accept=".png, .jpg, .jpeg">
                  <input type="hidden" name="avatar_remove">
                  <!--end::Inputs-->
                </label>
                <!--end::Label-->

                <!--begin::Cancel-->
                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                  data-kt-image-input-action="cancel" data-bs-toggle="tooltip" aria-label="Cancel avatar"
                  data-bs-original-title="Cancel avatar" data-kt-initialized="1">
                  <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                </span>
                <!--end::Cancel-->

                <!--begin::Remove-->
                <span class="btn btn-icon btn-circle btn-active-color-primary w-25px h-25px bg-body shadow"
                  data-kt-image-input-action="remove" data-bs-toggle="tooltip" aria-label="Remove avatar"
                  data-bs-original-title="Remove avatar" data-kt-initialized="1">
                  <i class="ki-duotone ki-cross fs-2"><span class="path1"></span><span class="path2"></span></i>
                </span>
                <!--end::Remove-->
              </div>
              <!--end::Image input-->

              <!--begin::Description-->
              <div class="text-muted fs-7">Set the product thumbnail image. Only *.png, *.jpg and *.jpeg image files
                are accepted</div>
              <!--end::Description-->
            </div>
            <!--end::Card body-->
          </div>
          <!--end::Thumbnail settings-->
          <!--begin::Status-->
          <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
              <!--begin::Card title-->
              <div class="card-title">
                <h2>Status</h2>
              </div>
              <!--end::Card title-->

              <!--begin::Card toolbar-->
              <div class="card-toolbar">
                <div class="rounded-circle {{ $item->status === 1 ? 'bg-success' : 'bg-danger' }} w-15px h-15px"
                  id="kt_ecommerce_add_product_status"></div>
              </div>
              <!--begin::Card toolbar-->
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
              <!--begin::Select2-->
              <select class="form-select" name="status" data-control="select2" data-placeholder="Select an option"
                data-hide-search="true">
                <option></option>
                <option value="1" {{ $item->status === 1 ? 'selected' : '' }}>Show</option>
                <option value="0" {{ $item->status === 0 ? 'selected' : '' }}>Hide</option>
              </select>
              <!--end::Select2-->

              <!--begin::Description-->
              <div class="text-muted fs-7 mt-3">Set the item status.</div>
              <!--end::Description-->
            </div>
            <!--end::Card body-->
          </div>
          <!--end::Status-->

          <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
              <!--begin::Card title-->
              <div class="card-title">
                <h2>Points Status</h2>
              </div>
              <!--end::Card title-->

              <!--begin::Card toolbar-->
              <div class="card-toolbar">
                <div class="rounded-circle {{ $item->points_status === 1 ? 'bg-success' : 'bg-danger' }} w-15px h-15px"
                  id="kt_ecommerce_add_product_status"></div>
              </div>
              <!--begin::Card toolbar-->
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
              <!--begin::Select2-->
              <select class="form-select" name="points_status" data-control="select2" data-placeholder="Select an option"
                data-hide-search="true">
                <option></option>
                <option value="1" {{ $item->points_status === 1 ? 'selected' : '' }}>Enable</option>
                <option value="0" {{ $item->points_status === 0 ? 'selected' : '' }}>Disable</option>
              </select>
              <!--end::Select2-->

              <!--begin::Description-->
              <div class="text-muted fs-7 mt-3">Set the item point status.</div>
              <!--end::Description-->
            </div>
            <!--end::Card body-->
          </div>

          <!--begin::Category & tags-->
          <div class="card card-flush py-4">
            <!--begin::Card header-->
            <div class="card-header">
              <!--begin::Card title-->
              <div class="card-title">
                <h2>Product Details</h2>
              </div>
              <!--end::Card title-->
            </div>
            <!--end::Card header-->

            <!--begin::Card body-->
            <div class="card-body pt-0">
              <!--begin::Input group-->
              <!--begin::Label-->
              <label class="form-label">Categories</label>
              <!--end::Label-->

              <!--begin::Select2-->
              <select class="form-select" name="category_id" data-control="select2" data-placeholder="Select an option">
                <option></option>
                @foreach (App\Models\ItemCategory::all() as $category)
                  <option value="{{ $category->id }}" {{ $item->category_id === $category->id ? 'selected' : '' }}>
                    {{ $category->name }}
                  </option>
                @endforeach
              </select>
              <!--end::Select2-->

              <!--begin::Description-->
              <div class="text-muted fs-7 mb-7">Set item to a category.</div>
              <!--end::Description-->
              <!--end::Input group-->
              <label class="form-label d-block">Types</label>
              <select class="form-select" name="item_type" data-control="select2" data-placeholder="Select an option">
                <option></option>
                <option value="vegetarian" {{ $item->item_type === 'vegetarian' ? 'selected' : '' }}>Vegetarian</option>
                <option value="nonVegetarian" {{ $item->item_type === 'nonVegetarian' ? 'selected' : '' }}>
                  Non-Vegetarian
                </option>
                <option value="eggetarian" {{ $item->item_type === 'eggetarian' ? 'selected' : '' }}>Eggetarian</option>
              </select>
            </div>
            <!--end::Card body-->
          </div>
          <!--end::Category & tags-->
          <!--begin::Card widget 6-->

          <!--end::Card widget 6-->
        </div>
        <!--end::Aside column-->

        <!--begin::Main column-->
        <div class="d-flex flex-column flex-row-fluid gap-7 gap-lg-10">
          <!--begin:::Tabs-->
          <ul class="nav nav-custom nav-tabs nav-line-tabs nav-line-tabs-2x border-0 fs-4 fw-semibold mb-n2"
            role="tablist">
            <!--begin:::Tab item-->
            <li class="nav-item" role="presentation">
              <a class="nav-link text-active-primary pb-4 active" data-bs-toggle="tab"
                href="#kt_ecommerce_add_product_general" aria-selected="true" role="tab">General</a>
            </li>
            <!--end:::Tab item-->

            <!--begin:::Tab item-->
            <li class="nav-item" role="presentation">
              <a class="nav-link text-active-primary pb-4" data-bs-toggle="tab"
                href="#kt_ecommerce_add_product_reviews" aria-selected="false" role="tab"
                tabindex="-1">Reviews</a>
            </li>
            <!--end:::Tab item-->
            <li class="nav-item ms-auto">
              <!--begin::Action menu-->
              <a href="#" class="btn btn-primary ps-7" data-kt-menu-trigger="click" data-kt-menu-attach="parent"
                data-kt-menu-placement="bottom-end">
                Actions
                <i class="ki-duotone ki-down fs-2 me-0"></i> </a>
              <!--begin::Menu-->
              <div
                class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-800 menu-state-bg-light-primary fw-semibold py-4 w-250px fs-6"
                data-kt-menu="true" style="">
                <!--begin::Menu item-->


                <!--begin::Menu item-->
                <div class="menu-item px-5">
                  <a href="javascript:void(0)" class="menu-link text-danger px-5"
                    onclick="submitParentForm(this, '#item_delete_form')"
                    data-kt-ecommerce-order-filter="delete_row">Delete</a>
                </div>
                <!--end::Menu item-->
              </div>
              <!--end::Menu-->
            </li>
          </ul>
          <!--end:::Tabs-->
          @if (Session::has('success'))
            <div class="alert alert-success">
              {{ Session::get('success') }}
            </div>
          @endif
          <!--begin::Tab content-->
          <div class="tab-content">
            <!--begin::Tab pane-->
            <div class="tab-pane fade active show" id="kt_ecommerce_add_product_general" role="tab-panel">
              <div class="d-flex flex-column gap-7 gap-lg-10">

                <!--begin::General options-->
                <div class="card card-flush py-4">
                  <!--begin::Card header-->
                  <div class="card-header">
                    <div class="card-title">
                      <h2>General</h2>
                    </div>
                  </div>
                  <!--end::Card header-->

                  <!--begin::Card body-->
                  <div class="card-body pt-0">
                    <div class="mb-10 fv-row fv-plugins-icon-container">
                      <!--begin::Label-->
                      <label class="required form-label">Item Name & Price</label>
                      <!--end::Label-->

                      <!--begin::Input-->
                      <div class="d-flex gap-3">
                        <input type="text" name="item_name"
                          class="form-control mb-2 @error('item_name') is-invalid @enderror"
                          placeholder="Enter Item Name" value="{{ old('item_name', $item->item_name) }}">
                        <input type="text" name="price"
                          class="form-control mb-2 @error('price') is-invalid @enderror" placeholder="Enter Item Amount"
                          value="{{ old('price', round($item->price)) }}">
                      </div>
                      <!--end::Input-->

                      <!--begin::Description-->
                      <div class="text-muted fs-7">Enter the product quantity.</div>
                      <!--end::Description-->
                      <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                        @error('item_name')
                          <div>{{ $message }}</div>
                        @enderror
                        @error('price')
                          <div>{{ $message }}</div>
                        @enderror
                      </div>
                      <div class="fv-plugins-message-container fv-plugins-message-container--enabled invalid-feedback">
                      </div>
                    </div>
                    <!--end::Input group-->



                    <!--begin::Input group-->
                    <div>
                      <!--begin::Label-->
                      <label class="form-label">Description</label>
                      <!--end::Label-->
                      <textarea class="form-control" data-kt-autosize="true" rows="10" data-kt-initialized="1"
                        style="overflow-x: hidden; overflow-wrap: break-word;" name="description">{{ $item->description }}</textarea>
                      <!--begin::Description-->
                      <div class="text-muted fs-7 mt-2">Set a description to the product for better visibility.</div>
                      <!--end::Description-->
                    </div>
                    <!--end::Input group-->
                  </div>
                  <!--end::Card header-->
                </div>
                <!--end::General options-->
              </div>
            </div>
            <!--end::Tab pane-->
            <!--begin::Tab pane-->
            @include('pages.items.vendor.components.show.reviews')
            <!--end::Tab pane-->
          </div>
          <!--end::Tab content-->

          <div class="d-flex justify-content-end">


            <!--begin::Button-->
            <button type="submit" id="kt_ecommerce_add_product_submit" class="btn btn-primary">
              <span class="indicator-label">
                Save Changes
              </span>
              <span class="indicator-progress">
                Please wait... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
              </span>
            </button>
            <!--end::Button-->
          </div>
        </div>
        <!--end::Main column-->
      </form>
      <!--end::Form-->
    </div>
    <!--end::Content container-->
  </div>
  <!--end::Content-->
@endsection
