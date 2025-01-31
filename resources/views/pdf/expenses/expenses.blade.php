<!DOCTYPE html>
<html>

<head>
  <title>Expenses</title>
  <style>
    body {
      font-family: 'Arial', sans-serif;
      font-size: 14px;
    }

    table {
      width: 100%;
      max-width: 100%;
      border-collapse: collapse;
      table-layout: fixed;
      /* Ensure fixed table layout */
    }

    table,
    th,
    td {
      border: 1px solid black;
    }

    th,
    td {
      padding: 10px;
      text-align: left;
      vertical-align: top;
      word-wrap: break-word;
      /* Ensure long words break to fit the cell */
    }

    thead {
      display: table-header-group;
    }

    tbody {
      display: table-row-group;
    }

    tr {
      page-break-inside: avoid;
      page-break-after: auto;
    }

    th.title,
    td.title {
      width: 15%;
    }

    th.tags,
    td.tags {
      width: 10%;
    }

    th.amount,
    td.amount {
      width: 15%;
    }

    th.category,
    td.category {
      width: 12.5%;
    }

    th.payment_mode,
    td.payment_mode {
      width: 12.5%;
    }

    th.details,
    td.details {
      width: 15%;
    }

    th.images,
    td.images {
      width: 20%;
    }
  </style>
</head>

<body>
  <h1>Expenses</h1>
  <table>
    <thead>
      <tr>
        <th class="title">Title</th>
        <th class="tags">Tags</th>
        <th class="amount">Amount</th>
        <th class="category">Category</th>
        <th class="payment_mode">Payment Mode</th>
        <th class="details">Details</th>
        <th class="images">Images</th>
      </tr>
    </thead>
    <tbody>
      @php
        $totalAmount = 0;
      @endphp
      @foreach ($expenses as $expense)
        @php
          $totalAmount += $expense->amount;
        @endphp
        <tr>
          <td class="title">{{ $expense->title }}</td>
          <td class="tags">
            @php
              $tags = json_decode($expense->tags, true);
            @endphp
            @if (is_array($tags))
              @foreach ($tags as $tag)
                {{ $tag['value'] . (end($tags) === $tag ? '' : ', ') }}
              @endforeach
            @endif
          </td>
          <td class="amount" style="font-family: 'DejaVu Sans', sans-serif;">₹{{ round($expense->amount) }}</td>
          <td class="category">{{ $expense->expenseCategory->name ?? 'N/A' }}</td>
          <td class="payment_mode">{{ $expense->payment_mode }}</td>
          <td class="details">{{ $expense->details }}</td>
          <td class="images">
            @foreach ($expense->images as $image)
              <a href="{{ asset($image) }}" target="_blank">{{ basename($image) }}</a><br>
            @endforeach
          </td>
        </tr>
      @endforeach
    </tbody>
    <tfoot>
      <tr>
        <td colspan="2"><strong>Total Amount</strong></td>
        <td class="amount"><span
            style="font-family: 'DejaVu Sans', sans-serif;">₹ </span><strong>{{ round($totalAmount) }}</strong></td>
        <td colspan="4"></td>
      </tr>
    </tfoot>
  </table>
</body>

</html>
