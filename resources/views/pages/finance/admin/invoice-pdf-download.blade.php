<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Invoice</title>
</head>

<body>
  <h2>Invoice</h2>

  <!-- Invoice Header -->
  <table width="100%">
    <tr>
      <td><strong>Invoice #:</strong> {{ $invoice->custom_id }}</td>
      <td align="right">
        @if ($invoice->billFrom->logo)
          <img
            src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path($invoice->billFrom->logo))) }}"
            alt="Logo" height="60">
        @endif
      </td>
    </tr>
    <tr>
      <td><strong>Issue Date:</strong> {{ \Carbon\Carbon::parse($invoice->date)->format('d/m/Y') }}</td>
      <td align="right"><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($invoice->due_date)->format('d/m/Y') }}</td>
    </tr>
    @php
      $dueDate = \Carbon\Carbon::parse($invoice->due_date);
      $daysDue = $dueDate->diffInDays(\Carbon\Carbon::now());
    @endphp
    <tr>
      <td></td>
      <td align="right"><strong>Due in:</strong> {{ $daysDue }} days</td>
    </tr>
  </table>

  <hr>

  <!-- Bill From and Bill To -->
  <table width="100%">
    <tr>
      <td>
        <strong>Bill From:</strong><br>
        {{ $invoice->billFrom->name }}<br>
        {{ $invoice->billFrom->address }}<br>
        GSTIN: {{ $invoice->billFrom->gst_no }}
      </td>
      <td align="right">
        <strong>Bill To:</strong><br>
        {{ $invoice->billTo->company_name }}<br>
        {{ $invoice->billTo->address }}<br>
        GSTIN: {{ $invoice->billTo->gst_no }}
      </td>
    </tr>
  </table>

  <hr>

  <!-- Invoice Items -->
  <table width="100%" border="1" cellpadding="5" cellspacing="0">
    <thead>
      <tr>
        <th align="left">Title</th>
        <th align="center">Quantity</th>
        <th align="center">Rate</th>
        <th align="center">Tax</th>
        <th align="right">Amount</th>
      </tr>
    </thead>
    <tbody>
      @php
        $subtotal = 0;
      @endphp
      @foreach ($invoice->items as $invoiceItem)
        @php
          $subtotal += $invoiceItem->total;
        @endphp
        <tr>
          <td>
            {{ $invoiceItem->item_name }}<br>
            <small>{{ $invoiceItem->item_description }}</small>
          </td>
          <td align="center">{{ $invoiceItem->quantity }}</td>
          <td align="center" style="font-family: DejaVu Sans; sans-serif;">₹{{ number_format($invoiceItem->price, 2) }}
          </td>
          <td align="center" style="font-family: DejaVu Sans; sans-serif;">
            ₹{{ number_format($invoiceItem->tax_value, 2) }}<br>
            <small>{{ $invoiceItem->invoiceTax->tax_rate ?? '0' }}%
              {{ $invoiceItem->invoiceTax->tax_title ?? '' }}</small>
          </td>
          <td align="right" style="font-family: DejaVu Sans; sans-serif;">₹{{ number_format($invoiceItem->total, 2) }}
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <hr>

  <!-- Invoice Totals -->
  <table width="100%">
    <tr>
      <td align="right"><strong>Subtotal:</strong></td>
      <td align="right" style="font-family: DejaVu Sans; sans-serif;">₹ {{ $subtotal }}</td>
    </tr>
    <tr>
      <td align="right"><strong>Tax {{ $invoice->tax_rate > 0 ? '(' . $invoice->tax_rate . '%)' : '' }}:</strong></td>
      <td align="right" style="font-family: DejaVu Sans; sans-serif;">₹ {{ $invoice->tax_value }}</td>
    </tr>
    <tr>
      <td align="right"><strong>Discount
          {{ $invoice->discount_rate > 0 ? '(' . $invoice->discount_rate . '%)' : '' }}:</strong></td>
      <td align="right" style="font-family: DejaVu Sans; sans-serif;">₹ {{ $invoice->discount_value }}</td>
    </tr>
    <tr>
      <td align="right"><strong>Total:</strong></td>
      <td align="right"><strong style="font-family: DejaVu Sans; sans-serif;">₹ {{ $invoice->total_amount }}</strong>
      </td>
    </tr>
  </table>

</body>

</html>
