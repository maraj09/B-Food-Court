<div class="accordion mt-15 mt-md-2" id="kt_accordion_1">
  <div class="accordion-item">
    <h2 class="accordion-header">
      <div class="accordion-button fs-4 fw-semibold collapsed d-flex align-items-center flex-row-fluid flex-wrap py-2">
        <!--begin::Section-->
        <div class="flex-grow-1 me-2" id="kt_accordion_1_header_1" data-bs-toggle="collapse"
          data-bs-target="#kt_accordion_1_body_1" aria-expanded="true" aria-controls="kt_accordion_1_body_1">
          Dashboard
        </div>
        <!--begin::Input group-->
        <div class="form-floating fs-6">
          <input type="number" class="form-control  w-90px" id="income-input" value="{{ $expenseDetail->income }}" />
          <label for="income-input">Income</label>
        </div>
        <!--end::Input group-->
        <div class="form-floating fs-6 mx-2">
          <input type="number" class="form-control w-85px" id="budget-input" value="{{ $expenseDetail->budget }}" />
          <label for="budget-input">Budget</label>
        </div>
        <!--end::Section-->
      </div>
      <p id="save-text" class="text-muted fs-7 text-end me-10" style="display: none">Click outside to save value</p>
    </h2>
    <div id="kt_accordion_1_body_1" class="accordion-collapse collapse show" aria-labelledby="kt_accordion_1_header_1"
      data-bs-parent="#kt_accordion_1">
      <div class="accordion-body">
        <!--begin::Row-->
        <div class="row  row-cols-1 row-cols-lg-4 g-0">
          <!--begin::Col-->
          <div
            class="col d-flex flex-column flex-row-fluid hover-elevate-up bg-light-warning p-2 rounded-2 mb-2 border border-warning border-dashed border-active active">
            <div class="flex-grow-1 mb-n8">
              <div class="expenses-used" data-chart-color="primary" style="height: 200px"></div>
            </div>
            <div class="d-flex flex-stack">
              <!--begin::Label-->
              <div class="text-center">
                <span class="badge badge-light-success fs-8 mb-1">Income</span>
                <span class="d-block">₹{{ number_format($expenseDetail->income) }}</span>
              </div>
              <!--end::Label-->
              <!--begin::Label-->
              <div class="text-center">
                <span class="badge badge-light-info fs-8 mb-1">Budget</span>
                <span class="d-block">₹{{ number_format($expenseDetail->budget) }}</span>
              </div>
              <!--end::Label-->
              <!--begin::Label-->
              <div class="text-center">
                <span class="badge badge-light-danger fs-8 mb-1">Safe to Spend</span>
                <span class="d-block">₹{{ number_format($safeToSpendPerDay, 0) }}/Day</span>
              </div>
              <!--end::Label-->
            </div>
          </div>
          <!--end::Col-->
          <!--begin::Col-->
          <div
            class="col d-flex flex-column flex-row-fluid hover-elevate-up bg-light-primary p-2 rounded-2 mb-2 mx-0 mx-md-4 border border-primary border-dashed border-active active">
            <div class="d-flex">
              <!--begin::Block-->
              <div class="d-flex align-items-center flex-grow-1 me-2 me-sm-5">
                <!--begin::Chart-->
                <div id="expense_category_chart" class="mx-auto mb-4"></div>
                <!--end::Chart-->
              </div>
              <!--end::Block-->
              <!--begin::Info-->
              <div class="d-flex align-items-center">
                <div class="mx-auto">
                  @php
                    $bgColors = ['bg-primary', 'bg-danger', 'bg-info', 'bg-success', 'bg-warning', 'bg-dark'];
                  @endphp
                  @foreach ($categoryUsageCounts as $categoryUsage)
                    <!--begin::Labels-->
                    <!--begin::Label-->
                    <div class="d-flex align-items-center mb-2">
                      <!--begin::Bullet-->
                      <div class="bullet bullet-dot w-8px h-7px {{ $bgColors[$loop->index % count($bgColors)] }} me-2"></div>
                      <!--end::Bullet-->
                      <!--begin::Label-->
                      <div class="fs-8 fw-semibold text-muted">
                        {{ $categoryUsage->expenseCategory->name }}({{ $categoryUsage->count }})</div>
                      <!--end::Label-->
                    </div>
                    <!--end::Label-->
                  @endforeach
                </div>
                <!--end::Labels-->
              </div>
              <!--end::Info-->
            </div>
          </div>
          <!--end::Col-->
          <!--begin::Col-->
          <div
            class="col d-flex flex-column flex-row-fluid hover-elevate-up bg-light-success p-2 rounded-2 mb-2 border border-success border-dashed border-active active">
            <!--begin::Chart-->
            <div id="expense_bar_chart" class="h-200px w-100 min-h-auto"></div>
            <!--end::Chart-->
          </div>
          <!--end::Col-->
        </div>
        <!--end::Row-->
      </div>
    </div>
  </div>
</div>
