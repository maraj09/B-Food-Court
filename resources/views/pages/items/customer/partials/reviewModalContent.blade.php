<div class="modal-content border border-3 border-gray-900 rounded">
  <div class="modal-header border-gray-900 border-bottom p-3">
    <div class="w-100">
      <div class="d-flex align-items-sm-center">
        <!--begin::Content-->
        <div class="d-flex flex-row-fluid align-items-center flex-wrap my-lg-0 me-2">
          <!--begin::Title-->
          <div class="flex-grow-1 me-2">
            <a href="#" class="fs-5 text-gray-800 text-hover-primary fw-bold d-block">{{ $item->item_name }}
              @if ($item->item_type == 'nonVegetarian')
                <img src="{{ asset('custom/assets/images/item-types/nonveg.png') }}" alt="" class="h-15px ms-1"
                  data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                  aria-label="Non Vegetarian" data-bs-original-title="Non Vegetarian">
              @elseif ($item->item_type == 'eggetarian')
                <img src="{{ asset('custom/assets/images/item-types/egg.png') }}" alt="" class="h-15px ms-1"
                  data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                  aria-label="Eggetarian" data-bs-original-title="Eggetarian">
              @elseif ($item->item_type == 'vegetarian')
                <img src="{{ asset('custom/assets/images/item-types/veg.png') }}" alt="" class="h-15px ms-1"
                  data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                  aria-label="Vegetarian" data-bs-original-title="Vegetarian">
              @endif
              <i class="fa fa-star-half-alt text-warning fs-5"></i><span class="text-gray-800 fw-bold">
                {{ $item->itemRating?->rating ?? 0 }}</span>
            </a>
            <span class="text-gray-500 fw-semibold fs-7">By:<a href="#"
                class="text-primary fw-bold">{{ $item->vendor->brand_name }}</a></span><span
              class="badge badge-outline badge-warning my-2 ms-1">Stall No. {{ $item->vendor->stall_no ?? 0 }}</span>
          </div>
          <!--end::Title-->
          <!--begin::Section-->
          <div class="d-flex align-items-center">
            <div class="ms-2">
              <label for="" class="form-label fs-8 mb-1">Sort By</label>
              <select class="form-select form-select-sm w-100px w-md-125px reviewSortSelect"
                aria-label="Select example">
                <option value="recent" {{ $sortOption == 'recent' ? 'selected' : '' }}>Recent</option>
                <option value="old" {{ $sortOption == 'old' ? 'selected' : '' }}>Old</option>
                <option value="lowest" {{ $sortOption == 'lowest' ? 'selected' : '' }}>Lowest</option>
                <option value="highest" {{ $sortOption == 'highest' ? 'selected' : '' }}>Highest</option>
              </select>
            </div>
          </div>
          <!--end::Section-->
        </div>
        <!--end::Content-->
      </div>
      <span class="text-gray-700 fw-semibold fs-7 my-1">
        {{ $item->description }}
      </span>
    </div>
  </div>
  @if ($item->ratings->count() > 0)
    <div class="modal-body">
      @foreach ($item->ratings as $rating)
        <div class="d-flex mb-5">
          <!--begin::Symbol-->
          <div class="symbol symbol-35px symbol-circle flex-shrink-0 me-4">
            <img
              src="{{ $rating->user->customer->avatar ? asset($rating->user->customer->avatar) : asset('assets/media/avatars/blank.png') }}"
              class="mw-100" alt="">
          </div>
          <!--end::Symbol-->
          <!--begin::Section-->
          <div class="d-flex align-items-center align-top flex-wrap flex-grow-1 mt-n2 mt-lg-n1">
            <!--begin::Title-->
            <div class="d-flex flex-column flex-grow-1 my-lg-0 my-2 pe-3">
              <a href="#" class="fs-5 text-gray-800 text-hover-primary fw-bold">{{ $rating->user->name }}</a>
              <span class="text-gray-500 fw-semibold fs-7">
                <span
                  class="text-gray-800 fs-6">{{ \Carbon\Carbon::parse($rating->created_at)->format('j-M-Y | h:iA') }}</span>
              </span>
              <span class="text-gray-500 fw-semibold fs-7 my-1">
                {{ $rating->review }} </span>
            </div>
            <!--end::Title-->
          </div>
          <!--end::Section-->
          <!--begin::Info-->
          <div class="text-end align-top">
            <i class="fa fa-star-half-alt text-warning fs-5"></i><span class="text-gray-800 fw-bold">
              {{ $rating->rating ?? 0 }}</span>
          </div>
          <!--end::Info-->
        </div>
        <div class="separator separator-dashed border-primary my-5"></div>
      @endforeach
    </div>
  @endif

  <div class="modal-footer py-2 mt-auto">
    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
    <form class="itemForm" action="{{ auth()->check() ? '/cart/add/' . $item->id : '/cart/add-to-session' }} "
      method="post" data-item-id="{{ $item->id }}">
      @csrf
      <a href="javascript:void(0)" onclick="submitFormWithAjax(this)" class="btn btn-danger hover-elevate-up">
        <i class="fa-solid fa-cart-plus"></i>₹{{ round($item->price) }}</a>
    </form>
  </div>
</div>
<script>
  $('.reviewSortSelect').on('change', function() {
    populateModal('{{ $item->id }}');
  });
</script>
