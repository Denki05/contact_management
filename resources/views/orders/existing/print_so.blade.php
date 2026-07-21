<?php
  $code = $result->code;
?>

<style type="text/css">
  body {
    color: #333;
    font-family: Arial, sans-serif;
    font-size: 12px;
  }
  table.borderless {
    border-collapse: collapse;
    border-spacing: 0;
  }
  .borderless td, .borderless th {
    border: none;
  }
  .info td, .info th {
    padding: 2px;
    margin: 2px;
    box-sizing: border-box;
  }
  .column-float {
    float: left;
    width: 50%;
  }
  .row-float {
    position: relative;
  }
  .row-float:after {
    content: "";
    display: block;
    clear: both;
  }
  table.table-data {
    width: 100%;
    border-collapse: collapse;
    color: #333;
  }
  table.table-data th {
    font-size: 12px;
    background-color: #d3d3d3;
  }
  table.table-data td {
    border: none;
  }
  table.table-data tbody {
    text-align: center;
    font-size: 12px;
  }
  @page {
    margin-top: 0px;
  }
  .text-right {
    text-align: right;
  }
  .text-left {
    text-align: left;
  }
  .page-break {
    page-break-after: always;
  }
  .clearfix::after {
    content: "";
    display: table;
    clear: both;
  }
</style>

@php
    $limit = 12;
    $doDetails = $result_detail->sortBy(function ($row) {
        return $row->product_name ?? '';
    });
    $totalItems = $doDetails->count();
    $totalPages = ceil($totalItems / $limit);
    $offset = 0;
@endphp

@for ($page = 0; $page < $totalPages; $page++)
<div>
  <h2 style="text-align: center; margin-bottom: 5px;"><u>SALES ORDER</u></h2>
  
  <div style="margin-bottom: 15px; font-size: 11px;">
    <div class="row-float">
      <div class="column-float" style="width: 60%;">
        <table class="table borderless info" style="width: 100%;">
          <tbody>
            <tr>
              <td>Sales</td>
              <td>:</td>
              <td>{{ ['26' => 'LINDY', '32' => 'NIA', '35' => 'ERIC', '38' => 'ALIVI'][$result->created_by] ?? 'S.A' }}</td>
            </tr>
            <tr>
              <td>Customer</td>
              <td>:</td>
              <td>{{ $customers->name }} {{ $customers->text_kota }}</td>
            </tr>
          </tbody>
        </table>
      </div>
      <div class="column-float" style="width: 40%;">
        <table class="table borderless info" style="width: 100%;">
          <tbody>
            <tr>
              <td><b>No. Nota</b></td>
              <td>:</td>
              <td><b>{{ $result->so_code }}</b></td>
            </tr>
            <tr>
              <td>Tanggal</td>
              <td>:</td>
              <td>{{ date('d-m-Y', strtotime($result->created_at ?? now())) }}</td>
            </tr>
            <tr>
              <td>Pembayaran</td>
              <td>:</td>
              <td>{{ $result->type_transaction }}</td>
            </tr>
            <tr>
              <td>Disc (%)</td>
              <td>:</td>
              <td>{{ $result->catatan }}</td>
            </tr>
          </tbody>
        </table>
      </div>
    </div>
  </div>
  
  <table class="table-data">
    <thead>
      <tr>
        <th>No</th>
        <th>Product</th>
        <th>Kg</th>
        <th>Packing</th>
        <th class="text-right">Harga ($)</th>
        <th class="text-right">Disc</th>
      </tr>
    </thead>
    <tbody>
      @foreach ($doDetails->slice($page * $limit, $limit)->values() as $index => $row)
      <tr>
        <td>{{ $offset + $index + 1 }}</td>
        <td>{{ $row->product_code }} {{ $row->product_name }}</td>
        <td>{{ number_format($row->qty, 0, ',', '.') }}</td>
        <td>{{ $row->packaging }}</td>
        <td class="text-right">{{ number_format($row->price, 0, ',', '.') }}</td>
        <td class="text-right">{{ number_format($row->disc_usd, 0, ',', '.') }}</td>
      </tr>
      @endforeach
    </tbody>
  </table>
</div>

@php
  $offset += $doDetails->slice($page * $limit, $limit)->count();
@endphp

@if ($page < $totalPages - 1)
<div class="page-break"></div>
@endif
@endfor

<div>
  <div style="font-size: 12px; position: absolute; bottom: 0; width: 100%; margin-top: 30px;">
    <div class="row-float clearfix" style="display: flex; justify-content: space-between;">
      <div class="column-float" style="width: 60%; font-size: 10px;">
        <strong>Syarat Pembayaran : </strong> <br>
        - Barang yang sudah dibeli tidak dapat ditukarkan / dikembalikan<br>
        - Pembayaran dengan cheque / wesel / BG dianggap sah apabila telah diuangkan <br>
        - Barang telah diperiksa dan diterima dengan baik <br>
      </div>
      <div class="column-float" style="width: 20%; text-align: center;">
        <br><br><br><br>
        .......................
        <br>
        Marketing
      </div>
      <div class="column-float" style="width: 20%; text-align: center;">
        <br><br><br><br>
        .......................
        <br>
        Menyetujui
      </div>
    </div>
    <div id="footer">
      <div class="page-number"></div>
    </div>
  </div>
</div>