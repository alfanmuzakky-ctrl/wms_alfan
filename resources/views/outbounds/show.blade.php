<h2>Outbound Detail</h2>

<div class="header-box">

<div>
<label>Outbound ID</label>
<input value="{{ $outbound->id }}" readonly>
</div>

<div>
<label>Customer</label>
<input value="{{ $outbound->customer->name }}" readonly>
</div>

<div>
<label>Status</label>
<input value="{{ $outbound->status }}" readonly>
</div>

<div>
<label>Create Time</label>
<input value="{{ $outbound->created_at }}" readonly>
</div>

</div>

<hr>

<h3>Tambah SKU</h3>

<form method="POST" action="/outbounds/add-sku">

@csrf

<input type="hidden" name="outbound_id" value="{{ $outbound->id }}">

<select name="sku">

@foreach($skus as $sku)

<option value="{{ $sku->id }}">
{{ $sku->id }} - {{ $sku->name }}
</option>

@endforeach

</select>

<input type="number" name="qty" placeholder="Qty">

<button type="submit">Tambah</button>

</form>

<hr>

<h3>Order Detail</h3>

<table border="1">

<tr>
<th>Status</th>
<th>SKU</th>
<th>Nama SKU</th>
<th>Order</th>
<th>Allocated</th>
<th>Picked</th>
<th>Packed</th>
</tr>

@foreach($details as $d)

<tr>

<td>{{ $d->status }}</td>
<td>{{ $d->sku }}</td>
<td>{{ $d->skuData->name }}</td>
<td>{{ $d->order_qty }}</td>
<td>{{ $d->qty_allocated }}</td>
<td>{{ $d->qty_picked }}</td>
<td>{{ $d->qty_packed }}</td>

</tr>

@endforeach

</table>

<hr>

<h3>Allocation Detail</h3>

<table border="1">

<tr>
<th>SKU</th>
<th>Location</th>
<th>Allocated</th>
<th>Picked</th>
</tr>

@foreach($allocations as $a)

<tr>

<td>{{ $a->outboundDetail->sku }}</td>
<td>{{ $a->location }}</td>
<td>{{ $a->qty_allocated }}</td>
<td>{{ $a->qty_picked }}</td>

</tr>

@endforeach

</table>