<div class="page-header">
    <h2>Inventory Monitoring</h2>
</div>

<div class="toolbar">
    <button class="btn-secondary" onclick="loadPage('/inventories')">
        <span class="icon">⟳</span> Refresh Page
    </button>
</div>

<div class="table-container">
    <table class="custom-table">
        <thead>
            <tr>
                <th width="15%">SKU</th>
                <th width="15%">Location</th>
                <th width="15%">Batch</th>
                <th width="15%">Expired</th>
                <th width="13%">Qty Stock</th>
                <th width="13%">Qty Available</th>
                <th width="14%">Qty Allocated</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inventories as $inv)
                <tr class="row-hover">
                    <td class="font-bold">{{ $inv->sku_id }}</td>
                    <td>
                        <span class="location-badge">
                            {{ $inv->location_id }}
                        </span>
                    </td>
                    <td>{{ $inv->batch_number ?? '-' }}</td>
                    <td>{{ $inv->expired_date ?? '-' }}</td>
                    
                    <td class="text-center font-bold">
                        {{ number_format($inv->qty_stock) }}
                    </td>
                    
                    <td class="text-center text-success font-bold">
                        {{ number_format($inv->qty_stock - $inv->qty_allocated) }}
                    </td>
                    
                    <td class="text-center text-warning">
                        {{ number_format($inv->qty_allocated) }}
                    </td>
                </tr>
            @endforeach

            @if($inventories->isEmpty())
                <tr>
                    <td colspan="7" style="text-align: center; padding: 30px;">
                        Data inventori kosong.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>