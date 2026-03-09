<div class="section-box">
    <div class="section-title">Inbound Detail</div>
    <div class="form-grid">
        <div class="form-group">
            <label>Inbound ID</label>
            <input type="text" value="{{ $inbound->id }}" readonly class="readonly-field">
        </div>
        <div class="form-group">
            <label>Supplier</label>
            <input type="text" value="{{ $inbound->supplier->name ?? $inbound->supplier_id }}" readonly class="readonly-field">
        </div>
        <div class="form-group">
            <label>Status Inbound</label>
            <div style="display: flex; align-items: center; gap: 10px;">
                <span class="status-badge status-{{ strtolower($inbound->status) }}">
                    {{ $inbound->status }}
                </span>
                @if($inbound->status !== 'CLOSE' && $inbound->status !== 'RECEIVED')
                    <button type="button" class="btn-small btn-danger" onclick="closeInbound('{{ $inbound->id }}')">
                        Close Inbound
                    </button>
                @endif
            </div>
        </div>
    </div>
</div>

@if($inbound->status !== 'CLOSE' && $inbound->status !== 'RECEIVED')
    <div class="section-box">
    <div class="section-title">Tambah Produk ke List</div>
    <form id="addSkuForm" data-type="inbounds">
        @csrf
        <input type="hidden" name="inbound_id" class="target-id" value="{{ $inbound->id }}">

        <div class="form-grid">
            <div class="form-group">
                <label>Pilih SKU</label>
                <select name="sku_id" class="form-control" required>
                    <option value="">-- Pilih SKU --</option>
                    @foreach($skus as $sku)
                        <option value="{{ $sku->id }}">{{ $sku->id }} - {{ $sku->name }}</option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label>Quantity</label>
                <input type="number" name="qty" class="form-control" min="1" required>
            </div>

            <div class="form-group">
                <label>Batch Number</label>
                <input type="text" name="batch_number" class="form-control" placeholder="Contoh: BATCH001">
            </div>

            <div class="form-group">
                <label>Expired Date</label>
                <input type="date" name="expired_date" class="form-control">
            </div>
        </div>

        <div style="margin-top: 15px;">
            <button type="submit" class="btn-primary">+ Tambah SKU</button>
        </div>
    </form>
</div>
@endif

<div class="section-box">
    <div class="section-title">Item List & Receiving</div>
    <div class="table-container">
        <table class="custom-table">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Order</th>
                    <th>Batch/Exp</th>
                    <th>Status</th>
                    <th>Received</th>
                    <th width="250px">Action</th>
                </tr>
            </thead>
            <tbody>
            @foreach($inbound->details as $detail)
                <tr>
                    <td><strong>{{ $detail->sku_id }}</strong><br><small>{{ $detail->sku->name }}</small></td>
                    <td>{{ $detail->qty }}</td>
                    <td>
                        <small>B:</small> {{ $detail->batch_number ?? '-' }}<br>
                        <small>E:</small> {{ $detail->expired_date ?? '-' }}
                    </td>
                    <td>
                        <span class="status-badge status-{{ strtolower($detail->status) }}">
                            {{ $detail->status }}
                        </span>
                    </td>
                    <td><strong>{{ $detail->received_qty }}</strong> / {{ $detail->qty }}</td>
                    <td>
                        <div style="display: flex; flex-direction: column; gap: 5px;">
                            @if($inbound->status !== 'CLOSE' && $detail->received_qty < $detail->qty)
                                <div class="input-group-receive" style="display: flex; gap: 5px;">
                                    <input type="number" 
                                           id="qty_{{ $detail->id }}" 
                                           value="{{ $detail->qty - $detail->received_qty }}" 
                                           max="{{ $detail->qty - $detail->received_qty }}"
                                           min="1" 
                                           class="input-mini">
                                    <button type="button" class="btn-small" onclick="submitReceive('{{ $detail->id }}', '{{ $inbound->id }}')">
                                        Receive
                                    </button>
                                </div>
                            @endif
                            
                            <div style="display: flex; gap: 5px;">
                                <a href="/print/sku/{{ $detail->id }}" target="_blank" class="btn-link">Print Label</a>
                            </div>
                        </div>
                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="toolbar" style="margin-top: 20px;">
    <a href="/print/inbound/{{ $inbound->id }}" target="_blank" class="btn-secondary">🖨️ Print Inbound Task List</a>
</div>