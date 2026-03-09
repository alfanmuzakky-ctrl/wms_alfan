<table class="custom-table">
    <thead>
        <tr>
            <th>SKU</th>
            <th>Qty Staging</th>
            <th>Qty Putaway</th>
            <th>Tujuan</th>
            <th>Aksi</th>
        </tr>
    </thead>
    <tbody>
        @foreach($stagingInventories as $item)
        <tr>
            <td>{{ $item->sku_id }}</td>
            <td>{{ $item->qty_stock }}</td>
            <td>
                <input type="number" 
                       id="qty_{{ $item->id }}" 
                       value="{{ $item->qty_stock }}" 
                       max="{{ $item->qty_stock }}"
                       min="1" 
                       class="input-mini">
            </td>
            <td>
                <select id="dest_{{ $item->id }}" required>
                    <option value="">-- Pilih Lokasi --</option>
                    @foreach($locations as $loc)
                        <option value="{{ $loc->id }}">{{ $loc->id }}</option>
                    @endforeach
                </select>
            </td>
            <td>
                <button type="button" 
                        class="btn-primary" 
                        onclick="submitPutaway('{{ $item->id }}')">
                    Execute
                </button>
            </td>
        </tr>
        @endforeach
    </tbody>
</table>