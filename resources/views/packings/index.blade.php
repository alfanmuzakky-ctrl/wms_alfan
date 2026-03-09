<div class="section-box">

<div class="section-title">
Packing & Checking
</div>

<div class="table-container">

<table class="custom-table">

<thead>

<tr>
<th>SKU</th>
<th>Order</th>
<th>Packed</th>
<th>Status</th>
<th>Action</th>
</tr>

</thead>

<tbody>

@foreach($outbound->details as $detail)

<tr>

<td>
<strong>{{ $detail->sku_id }}</strong>
<br>
<small>{{ $detail->sku->name }}</small>
</td>

<td>
{{ $detail->qty }}
</td>

<td>
<strong>{{ $detail->packed_qty }}</strong> / {{ $detail->qty }}
</td>

<td>

<span class="status-badge status-{{ strtolower($detail->status) }}">
{{ $detail->status }}
</span>

</td>

<td>

@if($detail->packed_qty < $detail->qty)

<div style="display:flex;gap:5px;">

<input
type="number"
id="pack_{{ $detail->id }}"
value="{{ $detail->qty - $detail->packed_qty }}"
min="1"
max="{{ $detail->qty - $detail->packed_qty }}"
class="input-mini">

<button
class="btn-small"
onclick="submitPack('{{ $detail->id }}')">

Pack

</button>

</div>

@endif

</td>

</tr>

@endforeach

</tbody>

</table>

</div>

</div>