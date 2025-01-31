<div class="tab-pane fade" id="kt_ecommerce_add_product_reviews" role="tab-panel">
  <div class="d-flex flex-column gap-7 gap-lg-10">

    <!--begin::Reviews-->
    <div class="card card-flush py-4">
      <!--begin::Card header-->
      <div class="card-header">
        <!--begin::Card title-->
        <div class="card-title">
          <h2>Customer Reviews</h2>
        </div>
        <!--end::Card title-->

        <!--begin::Card toolbar-->
        <div class="card-toolbar">
          <!--begin::Rating label-->
          <span class="fw-bold me-5">Overall Rating: </span>
          <!--end::Rating label-->

          <!--begin::Overall rating-->
          <div class="rating">
            <?php
            
            $ratingValue = $item->itemRating->rating ?? 0;
            
            // Loop to generate star icons based on the rating value
            for ($i = 1; $i <= 5; $i++) {
                if ($i <= $ratingValue) {
                    // Display a filled star icon
                    echo '<div class="rating-label checked"><i class="ki-duotone ki-star fs-2"></i></div>';
                } else {
                    // Display an empty star icon
                    echo '<div class="rating-label"><i class="ki-duotone ki-star fs-2"></i></div>';
                }
            }
            ?>
          </div>
        </div>
        <!--end::Card toolbar-->
      </div>
      <!--end::Card header-->

      <!--begin::Card body-->
      <div class="card-body pt-0">
        <!--begin::Table-->
        <div class="table-responsive">
          <table class="table table-row-dashed fs-6 gy-5 my-0" id="kt_ecommerce_add_product_reviews">
            <thead>
              <tr class="text-start text-gray-500 fw-bold fs-7 text-uppercase gs-0">
                <th class="w-10px pe-2">
                  <div class="form-check form-check-sm form-check-custom form-check-solid me-3">
                    <input class="form-check-input" type="checkbox" data-kt-check="true"
                      data-kt-check-target="#kt_ecommerce_add_product_reviews .form-check-input" value="1">
                  </div>
                </th>
                <th class="min-w-125px">Rating</th>
                <th class="min-w-175px">Customer</th>
                <th class="min-w-175px">Comment</th>
                <th class="min-w-100px text-end fs-7">Date</th>
              </tr>
            </thead>
            <tbody>
              @foreach ($item->ratings as $rating)
                <tr>
                  <td>
                    <!--begin::Checkbox-->
                    <div class="form-check form-check-sm form-check-custom form-check-solid mt-1">
                      <input class="form-check-input" type="checkbox" value="1">
                    </div>
                    <!--end::Checkbox-->
                  </td>
                  <td data-order="rating-5">
                    <!--begin::Rating-->
                    <div class="rating">
                      <?php
                      
                      $ratingValue = $rating->rating;
                      
                      // Loop to generate star icons based on the rating value
                      for ($i = 1; $i <= 5; $i++) {
                          if ($i <= $ratingValue) {
                              // Display a filled star icon
                              echo '<div class="rating-label checked"><i class="ki-duotone ki-star fs-6"></i></div>';
                          } else {
                              // Display an empty star icon
                              echo '<div class="rating-label"><i class="ki-duotone ki-star fs-6"></i></div>';
                          }
                      }
                      ?>
                    </div>
                    <!--end::Rating-->
                  </td>
                  <td>
                    <!--end::Avatar-->
                    <!--begin::Name-->
                    <span class="fw-bold">{{ $rating->user->name }}</span>
                    <!--end::Name-->
                  </td>
                  <td class="text-gray-600 fw-bold">
                    {{ $rating->review }}</td>
                  <td class="text-end">
                    <span
                      class="fw-semibold text-muted">{{ \Carbon\Carbon::parse($rating->created_at)->format('d/m/Y') }}</span>
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
    <!--end::Reviews-->
  </div>
</div>
