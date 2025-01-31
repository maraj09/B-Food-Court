<div class="tab-pane fade {{ $lastSegment == 'point-logs' ? 'show active' : '' }}" id="kt_tab_pane_3" role="tabpanel">
  <!--begin::Points Log--->
  <div class="card shadow-sm card-flush my-3">
    <!--begin::Card header-->
    <div class="card-header collapsible cursor-pointer rotate my-1">
      <!--begin::Title-->
      <div class="card-title">
        <!--begin::User-->
        <div class="d-flex justify-content-center flex-column me-3">
          <a href="#" class="fs-6 fw-bold text-gray-900 mb-2 lh-1">Points Log</a>
          <!--begin::Info-->
          <div class="mb-0 lh-1">
            <!--begin::Search-->
            <div class="d-flex align-items-center position-relative my-1">
              <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-5">
                <span class="path1"></span>
                <span class="path2"></span>
              </i>
              <input type="text" data-kt-customer-table-filter="search"
                class="form-control form-control-solid w-150px ps-13" placeholder="Search Points" />
            </div>
            <!--end::Search-->
          </div>
          <!--end::Info-->
        </div>
        <!--end::User-->
      </div>
      <!--end::Title-->
      @php
        $redeemedPoints = App\Models\CustomerPointLog::where('user_id', auth()->id())
            ->where(function ($query) {
                $query->where('action', 'Redeem')->orWhere('action', 'Penalty');
            })
            ->sum('points');

        $pointsEarned = App\Models\CustomerPointLog::where('user_id', auth()->id())
            ->where('action', '!=', 'Redeem')
            ->where('action', '!=', 'Penalty')
            ->sum('points');
      @endphp
      <div class="card-toolbar">
        <span class="badge badge-light-warning" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
          data-bs-placement="top" data-bs-html="true" title="Earn">{{ $pointsEarned }}</span>
        <span class="badge badge-light-danger mx-2" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
          data-bs-placement="top" data-bs-html="true" title="Redeem">{{ $redeemedPoints }}</span>
        <span class="badge badge-light-success" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
          data-bs-placement="top" data-bs-html="true" title="Balance">{{ auth()->user()->point->points }}</span>
        <h3 class="card-title align-items-center flex-column ms-2">
          <span class="card-label fw-bold fs-3 mb-1">{{ $pointLogs->count() }}</span>
          <span class="text-muted fw-semibold fs-7">Total</span>
        </h3>
      </div>
    </div>
    <!--end::Card header-->
    <div class="card-body">
      <div class="table-responsive">
        <!--begin::Table-->
        <table class="table align-middle table-row-dashed fs-6 gy-5" id="kt_customers_points_logs_table">
          <thead>
            <tr class="text-center text-gray-500 fw-bold fs-7 text-uppercase gs-0">
              <th class="min-w-125px text-center">Points For</th>
              <th class="min-w-125px text-center">Date</th>
              <th class="min-w-125px text-center">Points</th>
            </tr>
          </thead>
          <!--begain::Table body-->
          <tbody class="fw-semibold text-gray-600">
            @foreach ($pointLogs as $log)
              <tr class="text-center">
                <td>
                  @if ($log->action == 'Order')
                    <span class="fw-bold">Points added of Order Placed <a
                        href="/dashboard/orders/{{ $log->order_id ?? '-' }}" class="fw-bold text-success fs-6"
                        data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                        data-bs-html="true" title="Order ID">#{{ $log->order->custom_id ?? '-' }}</a>
                    </span>
                  @elseif ($log->action == 'Redeem')
                    <span class="fw-bold">Points redeem on Order <a href="/dashboard/orders/{{ $log->order_id ?? '-' }}"
                        class="fw-bold text-danger fs-6" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
                        data-bs-placement="top" data-bs-html="true"
                        title="Order ID">#{{ $log->order->custom_id ?? '-' }}</a></span>
                  @elseif ($log->action == 'Review')
                    <span class="fw-bold">Points added for Item reviewed <a
                        href="/dashboard/items/{{ $log->item_id ?? '-' }}" class="fw-bold text-warning fs-6"
                        data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                        data-bs-html="true" title="Item ID">#{{ $log->item_id ?? '-' }}</a></span>
                  @elseif ($log->action == 'Rating')
                    <span class="fw-bold">Points added for Item rating <a
                        href="/dashboard/items/{{ $log->item_id ?? '-' }}" class="fw-bold text-warning fs-6"
                        data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse" data-bs-placement="top"
                        data-bs-html="true" title="Item ID">#{{ $log->item_id ?? '-' }}</a></span>
                  @else
                    <span class="fw-bold">{{ $log->details }}</span>
                  @endif
                </td>
                <td data-order="{{ $log->created_at }}">{{ \Carbon\Carbon::parse($log->created_at)->format('d-m-Y') }}
                </td>
                <td
                  data-order="{{ $log->action == 'Redeem' || $log->action == 'Penalty' ? $log->points * -1 : $log->points }}">
                  <span
                    class="badge 
                      @if ($log->action == 'Redeem' || $log->action == 'Penalty') badge-light-danger
                      @else
                          badge-light-success @endif">
                    @if ($log->action == 'Redeem' || $log->action == 'Penalty')
                      -{{ $log->points }}
                    @else
                      +{{ $log->points }}
                    @endif
                  </span>
                </td>
              </tr>
            @endforeach
          </tbody>
          <!--end::Table body-->
        </table>
        <!--end::Table-->
      </div>
    </div>

  </div>
  <!--end::Points Log-->
</div>
