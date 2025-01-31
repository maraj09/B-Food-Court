<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <title>Payment Mode Report</title>
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
  <h1>Payment Mode Reports</h1>
  <table>
    <thead>
      <tr>
        <th>Date</th>
        @foreach ($data->series as $series)
          <th>{{ $series->name }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @for ($i = 0; $i < count($data->labels); $i++)
        <tr>
          <td>{{ $data->labels[$i] }}</td>
          @foreach ($data->series as $series)
            <td>{{ $series->data[$i] }}</td>
          @endforeach
        </tr>
      @endfor
    </tbody>
  </table>
</body>

</html>
