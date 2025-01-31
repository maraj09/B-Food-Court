@foreach ($payouts as $payout)
  <tr>
    <td>
      <div class="form-check form-check-sm form-check-custom form-check-solid">
        <input class="form-check-input" type="checkbox" value="1" />
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
    <td class="pe-0 text-center" data-order="2024-01-10">
      <span class="fw-bold text-success">{{ $payout->date }}</span>
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
  </tr>
@endforeach
