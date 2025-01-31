<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Vendor Earnings Report</title>
  <style>
    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid #000;
      padding: 8px;
      text-align: left;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
</head>
@php
  use Carbon\Carbon;

  $startDate = Carbon::parse($data->startDate);
  $endDate = Carbon::parse($data->endDate);

  $formattedStartDate = $startDate->format('d-M-Y');
  $formattedEndDate = $endDate->format('d-M-Y');
@endphp

<body>
  <h1>Vendor Earnings Report</h1>
  <h3>From {{ $formattedStartDate }} to {{ $formattedEndDate }}</h3>
  <table>
    <thead>
      <tr>
        <th>Vendor</th>
        <th style="font-family: DejaVu Sans; sans-serif;">Earnings (₹)</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data->labels as $index => $label)
        <tr>
          <td>{{ $label }}</td>
          <td style="font-family: DejaVu Sans; sans-serif;">₹{{ number_format($data->earnings[$index], 2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
