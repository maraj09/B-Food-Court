<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Category Sales Report</title>
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
      font-family: 'DejaVu Sans', sans-serif;
    }

    th {
      background-color: #f2f2f2;
    }
  </style>
</head>

<body>
  <h1>Category Sales Report for {{ ucfirst($duration) }}</h1>
  <table>
    <thead>
      <tr>
        <th>Label</th>
        @foreach ($data->series as $series)
          <th>{{ $series->name }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach ($data->labels as $index => $label)
        <tr>
          <td>{{ $label }}</td>
          @foreach ($data->series as $series)
            <td style="font-family: 'DejaVu Sans', sans-serif;">₹{{ number_format($series->data[$index], 2) }}</td>
          @endforeach
        </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
