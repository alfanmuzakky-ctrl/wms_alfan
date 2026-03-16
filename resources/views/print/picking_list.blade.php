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
    width:500px;
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

th,td{
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
<div class="title">ORDER PICKING LIST</div>
<div class="order">Order No : {{ $outbound->id }}</div>
</div>

<div class="qr">
{!! QrCode::size(80)->generate($outbound->id) !!}
</div>

</div>

<table>

<thead>
<tr>
<th>Location</th>
<th>SKU</th>
<th>Name</th>
<th>Qty</th>
</tr>
</thead>

<tbody>

@foreach($orderDetails as $order)

<tr>
<td>{{ $order->location }}</td>
<td>{{ $order->outboundDetail->sku }}</td>
<td>{{ $order->outboundDetail->skuData->name }}</td>
<td style="text-align:center">{{ $order->qty_allocated }}</td>
</tr>

@endforeach

</tbody>

</table>

</div>

</body>
</html>