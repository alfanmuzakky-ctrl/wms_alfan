<x-toolbar resource="skus" label="SKU" />

<div class="table-container">
    <table class="custom-table">
        <thead>
        <tr>
            <th>SKU ID</th>
            <th>Alternative Code</th>
            <th>Product Name</th>
            <th>Description</th>
            <th>Category</th>
            <th>UOM</th>
            <th>Create Time</th>
            <th>Edit Time</th>
        </tr>
        </thead>

        <tbody>
            @foreach($skus as $sku)
                <tr onclick="openDetail('skus', '{{ $sku->id }}')" 
                    style="cursor:pointer;" 
                    class="row-hover">
                    
                    <td class="font-bold">{{ $sku->id }}</td>
                    <td>{{ $sku->alternative_code ?? '-' }}</td>
                    <td>{{ $sku->name }}</td>
                    <td class="truncate" title="{{ $sku->description }}">
                        {{ $sku->description }}
                    </td>
                    <td>{{ $sku->category }}</td>
                    <td>{{ $sku->uom }}</td>
                    <td>{{ $sku->created_at }}</td>
                    <td>{{ $sku->updated_at }}</td>
                </tr>
            @endforeach

            @if($skus->isEmpty())
                <tr>
                    <td colspan="8" style="text-align: center; padding: 30px;">
                        Data SKU belum tersedia. Silakan klik "Tambah SKU".
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>