<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Top Ordered Items Report</title>
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
  <h1>Top Ordered Items Report for {{ ucfirst($duration) }}</h1>
  <table>
    <thead>
      <tr>
        <th>Item Name</th>
        <th>Quantity Sold</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($data->data as $item)
        <tr>
          <td>{{ $item->name }}</td>
          <td>{{ $item->count }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
</body>

</html>
