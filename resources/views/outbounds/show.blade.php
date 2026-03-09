<div class="section-box">

<div class="section-title">
Outbound Order
</div>

<div class="form-grid">

<div class="form-group">
<label>Outbound ID</label>
<input type="text"
value="{{ $outbound->id }}"
readonly
class="readonly-field">
</div>

<div class="form-group">
<label>Customer</label>
<input type="text"
value="{{ $outbound->customer->name ?? $outbound->customer_id }}"
readonly
class="readonly-field">
</div>

<div class="form-group">

<label>Status</label>

<div style="display:flex;align-items:center;gap:10px;">

<span class="status-badge status-{{ strtolower($outbound->status) }}">
{{ $outbound->status }}
</span>

@if($outbound->status != 'SHIPPED')
<button
type="button"
class="btn-small btn-danger"
onclick="closeOutbound('{{ $outbound->id }}')">
Close
</button>
@endif

</div>
</div>

</div>
</div>
@if($outbound->status == 'ORDER')

<div class="section-box">

<div class="section-title">
Tambah Produk ke Order
</div>

<form id="addSkuForm" data-type="outbounds">

@csrf

<input type="hidden"
name="outbound_id"
value="{{ $outbound->id }}">

<div class="form-grid">

<div class="form-group">
<label>Pilih SKU</label>

<select name="sku_id" required>

<option value="">-- Pilih SKU --</option>

@foreach($skus as $sku)

<option value="{{ $sku->id }}">
{{ $sku->id }} - {{ $sku->name }}
</option>

@endforeach

</select>

</div>

<div class="form-group">
<label>Quantity</label>

<input
type="number"
name="qty"
min="1"
required>

</div>

</div>

<br>

<button class="btn-primary">
+ Tambah SKU
</button>

</form>

</div>

@endif