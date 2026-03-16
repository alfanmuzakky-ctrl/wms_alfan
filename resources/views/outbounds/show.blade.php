<div class="section-box">
    <div class="section-title">Outbound Detail</div>

    <div class="form-grid">

        <div class="form-group">
            <label>Outbound ID</label>
            <input type="text" value="{{ $outbound->id }}" readonly class="readonly-field">
        </div>

        <div class="form-group">
            <label>Customer</label>
            <input type="text" value="{{ $outbound->customer->name ?? '-' }}" readonly class="readonly-field">
        </div>

        <div class="form-group">
            <label>Status Outbound</label>
            <span class="status-badge status-{{ strtolower($outbound->status) }}">
                {{ $outbound->status }}
            </span>
        </div>

    </div>
</div>


@if($outbound->status !== 'SHIPPED')
<div class="section-box">
    <div class="section-title">Tambah Produk ke Order</div>

    <form id="addSkuForm" data-type="outbounds">
    @csrf

    <input type="hidden" class="target-id" value="{{ $outbound->id }}">

    <div class="form-grid">

        <div class="form-group">
            <label>Pilih SKU</label>
            <select name="sku" class="form-control" required>
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
            <input type="number" name="qty" class="form-control" min="1" required>
        </div>

    </div>

    <div style="margin-top:15px;">
        <button type="submit" class="btn-primary">
            + Tambah SKU
        </button>
    </div>

</form>


</div>
@endif



<div class="section-box">

    <div class="section-title">Item List</div>

    <div class="table-container">
        <table class="custom-table">

            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Order</th>
                    <th>Allocated</th>
                    <th>Picked</th>
                    <th>Packed</th>
                    <th>Status</th>
                </tr>
            </thead>

            <tbody>

            @foreach($details as $d)

            <tr>

                <td>
                    <strong>{{$d->sku}}</strong>
                    <br>
                    <small>{{$d->skuData->name ?? '-'}}</small>
                </td>

                <td>{{$d->order_qty}}</td>

                <td>{{$d->qty_allocated}}</td>

                <td>{{$d->qty_picked}}</td>

                <td>{{$d->qty_packed}}</td>

                <td>
                    <span class="status-badge status-{{ strtolower($d->status) }}">
                        {{$d->status}}
                    </span>
                </td>

            </tr>

            @endforeach

            </tbody>

        </table>
    </div>

</div>



<div class="toolbar" style="margin-top:20px; display:flex; gap:10px; flex-wrap:wrap;">

<button
onclick="submitAction(
'/outbounds/{{ $outbound->id }}/allocate',
'outbounds',
'{{ $outbound->id }}'
)">
Allocate
</button>

<button
onclick="submitAction(
'/outbounds/{{ $outbound->id }}/picking',
'outbounds',
'{{ $outbound->id }}'
)">
Picking
</button>

<button class="btn-primary"
onclick="closeDrawer(); openTab('/packing-check','Packing & Check')">
Start Packing
</button>

<button
onclick="submitAction(
'/outbounds/{{ $outbound->id }}/ship',
'outbounds',
'{{ $outbound->id }}'
)">
Ship
</button>

<button class="btn-primary"
onclick="window.open('/print/picking/{{ $outbound->id }}','_blank')">
Print Picking
</button>

</div>



