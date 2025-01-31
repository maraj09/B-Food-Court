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
      <td><strong>Invoice #:</strong> {{ $data['custom_id'] }}</td>
      <td align="right">
        @if ($billForm->logo)
          <img src="{{ 'data:image/png;base64,' . base64_encode(file_get_contents(public_path($billForm->logo))) }}"
            alt="Logo" height="60">
        @endif
      </td>
    </tr>
    <tr>
      <td><strong>Issue Date:</strong> {{ \Carbon\Carbon::parse($data['date'])->format('d/m/Y') }}</td>
      <td align="right"><strong>Due Date:</strong> {{ \Carbon\Carbon::parse($data['due_date'])->format('d/m/Y') }}</td>
    </tr>
    @php
      $dueDate = \Carbon\Carbon::parse($data['due_date']);
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
        {{ $billForm->name }}<br>
        {{ $billForm->address }}<br>
        GSTIN: {{ $billForm->gst_no }}
      </td>
      <td align="right">
        <strong>Bill To:</strong><br>
        {{ $billTo->company_name }}<br>
        {{ $billTo->address }}<br>
        GSTIN: {{ $billTo->gst_no }}
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
      @foreach ($data['name'] as $index => $name)
        <tr>
          <td>
            {{ $name }}<br>
            <small>{{ $data['description'][$index] }}</small>
          </td>
          <td align="center">{{ $data['quantity'][$index] }}</td>
          <td align="center" style="font-family: DejaVu Sans; sans-serif;">
            ₹{{ number_format($data['price'][$index], 2) }}</td>
          <td align="center" style="font-family: DejaVu Sans; sans-serif;">
            ₹{{ number_format($data['tax_values'][$index], 2) }}<br>
            <small>{{ $data['tax'][$index]['rate'] }}% {{ $data['tax'][$index]['name'] }}</small>
          </td>
          <td align="right" style="font-family: DejaVu Sans; sans-serif;">
            ₹{{ number_format($data['total'][$index], 2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <hr>

  <!-- Invoice Totals -->
  <table width="100%">
    <tr>
      <td align="right"><strong>Subtotal:</strong></td>
      <td align="right" style="font-family: DejaVu Sans; sans-serif;">₹ {{ $data['subtotal'] }}</td>
    </tr>
    <tr>
      <td align="right"><strong>Tax {{ $data['tax_rate'] > 0 ? '(' . $data['tax_rate'] . '%)' : '' }}:</strong></td>
      <td align="right" style="font-family: DejaVu Sans; sans-serif;">₹ {{ $data['tax_value'] }}</td>
    </tr>
    <tr>
      <td align="right"><strong>Discount
          {{ $data['discount_rate'] > 0 ? '(' . $data['discount_rate'] . '%)' : '' }}:</strong></td>
      <td align="right" style="font-family: DejaVu Sans; sans-serif;">₹ {{ $data['discount_value'] }}</td>
    </tr>
    <tr>
      <td align="right"><strong>Total:</strong></td>
      <td align="right"><strong style="font-family: DejaVu Sans; sans-serif;">₹ {{ $data['total_amount'] }}</strong>
      </td>
    </tr>
  </table>

</body>

</html>
