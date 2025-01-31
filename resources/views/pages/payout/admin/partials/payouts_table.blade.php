@foreach ($payouts as $payout)
  <tr>
    <td>
      <div class="form-check form-check-sm form-check-custom form-check-solid">
        <input class="form-check-input" type="checkbox" value="1" />
      </div>
    </td>
    <td data-kt-ecommerce-order-filter="order_id">
      <div class="d-flex align-items-center">
        <!--begin:: Avatar -->
        <div class="symbol symbol-circle symbol-35px overflow-hidden">
          <a href="/dashboard/vendors/{{ $payout->vendor->user_id }}">
            @if ($payout->vendor->avatar)
              <img src="{{ asset($payout->vendor->avatar) }}" />
            @else
              <img src="{{ asset('assets/media/svg/avatars/blank-dark.svg') }}" width="35px" height="35px" />
            @endif
          </a>
        </div>
        <!--end::Avatar-->
        <div class="ms-2">
          <!--begin::Title-->
          <a href="apps/user-management/users/view.html"
            class="text-gray-800 text-hover-primary fs-6 fw-bold">{{ $payout->vendor->brand_name }}</a>
          <!--end::Title-->
        </div>
      </div>
    </td>
    <td class="pe-0 text-center">
      <div class="d-flex align-items-center">
        <div class="flex-grow-1">
          <span class="badge badge-danger fw-bold fs-6">₹{{ $payout->request_amount }}</span>
          <div class="fs-6 text-gray-800 d-block" data-bs-toggle="tooltip" data-bs-custom-class="tooltip-inverse"
            data-bs-placement="top" title="Request Date">
            {{ \Carbon\Carbon::parse($payout->created_at)->format('d-m-Y') }}
          </div>
        </div>
      </div>
    </td>
    <td class="pe-0 text-center">
      <span class="fw-bold">{{ $payout->transaction_id ? $payout->transaction_id : '-' }}</span>
      @if ($payout->payment_mode == 'upi')
        <div class="badge badge-light-primary fw-bold fs-6 d-block" data-bs-toggle="tooltip"
          data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title=" Payment Mode">UPI</div>
      @elseif ($payout->payment_mode == 'cash')
        <div class="badge badge-light-success fw-bold fs-6 d-block" data-bs-toggle="tooltip"
          data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title=" Payment Mode">Cash</div>
      @elseif ($payout->payment_mode == 'cheque')
        <div class="badge badge-light-warning fw-bold fs-6 d-block" data-bs-toggle="tooltip"
          data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title=" Payment Mode">Cheque</div>
      @elseif ($payout->payment_mode == 'bank_transfer')
        <div class="badge badge-light-info fw-bold fs-6 d-block" data-bs-toggle="tooltip"
          data-bs-custom-class="tooltip-inverse" data-bs-placement="top" title=" Payment Mode">Bank Transfer
        </div>
      @else
        <div>
          -
        </div>
      @endif
    </td>
    <td class="pe-0 text-center">
      <span class="fw-bold text-success">{{ \Carbon\Carbon::parse($payout->date)->format('d-m-Y') }}</span>
    </td>
    <td class="text-center">
      @if ($payout->status == 'transferred')
        <div class="badge badge-light-success fw-bold fs-6">Transferred</div>
      @elseif ($payout->status == 'hold')
        <div class="badge badge-light-danger fw-bold fs-6">On Hold</div>
      @elseif ($payout->status == 'progress')
        <div class="badge badge-light-primary fw-bold fs-6">In Progress</div>
      @else
        <div class="badge badge-light-warning fw-bold fs-6">Pending</div>
      @endif
    </td>
    <td class="text-center">
      @if ($payout->payment_image)
        <div class="symbol symbol-50px symbol-2by3">
          <img src="{{ asset($payout->payment_image) }}" alt="" />
        </div>
      @else
        -
      @endif
    </td>
    <td class="text-center">
      <span class="fs-8">{{ $payout->remark ? $payout->remark : '-' }}</span>
    </td>
    <td class="text-center">
      <button class="btn btn-active-info" data-payout-id="{{ $payout->id }}"
        data-amount="{{ $payout->request_amount }}" data-remark="{{ $payout->remark }}"
        data-image="{{ $payout->payment_image }}" data-payout-id="{{ $payout->id }}" id="kt_add_payout_toggle">
        Edit</i>
      </button>
    </td>
  </tr>
@endforeach
