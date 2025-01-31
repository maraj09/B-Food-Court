<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>PDF Report</title>
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

<body>
  <h1>Report for Profit, Earning & Expenses</h1>
  <table>
    <thead>
      <tr>
        <th>Label</th>
        <th>Net Earnings</th>
        <th>Expenses</th>
        <th>Gross Earnings</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data->labels as $index => $label)
        <tr>
          <td>{{ $label }}</td>
          <td style="font-family: 'DejaVu Sans', sans-serif;">₹{{ number_format($data->earnings[$index], 2) }}</td>
          <td style="font-family: 'DejaVu Sans', sans-serif;">₹{{ number_format($data->expenses[$index], 2) }}</td>
          <td style="font-family: 'DejaVu Sans', sans-serif;">₹{{ number_format($data->revenues[$index], 2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
