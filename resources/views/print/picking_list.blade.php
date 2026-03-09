@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

<!DOCTYPE html>
<html>
<head>
<meta charset="UTF-8">
<title>Picking List</title>

<style>

body{
    font-family: Arial, sans-serif;
}

.container{
    width: 500px;
}

.header{
    display:flex;
    justify-content:space-between;
    align-items:flex-start;
}

.title{
    font-size:28px;
    font-weight:bold;
}

.order{
    margin-top:5px;
    font-size:16px;
}

.qr{
    text-align:right;
}

table{
    width:100%;
    border-collapse:collapse;
    margin-top:10px;
}

th, td{
    border:1px solid black;
    padding:6px;
    font-size:14px;
}

th{
    background:#f2f2f2;
}

</style>
</head>

<body>

<div class="container">

<div class="header">

<div>
<div class="title">ORDERPICKINGLIST</div>
<div class="order">OrderNo : {{ $outbound_id }}</div>
</div>

<div class="qr">
{!! QrCode::size(80)->generate($outbound_id) !!}
</div>

</div>

<table>

<thead>
<tr>
<th>Location</th>
<th>SKU</th>
<th>NAME</th>
<th>QTY</th>
</tr>
</thead>

<tbody>

@foreach($allocations as $alloc)

<tr>
<td>{{ $alloc->location_id }}</td>
<td>{{ $alloc->sku_id }}</td>
<td>{{ $alloc->sku->name }}</td>
<td style="text-align:center">{{ $alloc->qty_allocated }}</td>
</tr>

@endforeach

</tbody>

</table>

</div>

</body>
</html>