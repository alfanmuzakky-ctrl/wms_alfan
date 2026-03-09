<x-toolbar resource="suppliers" label="Supplier" />

<div class="table-container">
    <table class="custom-table">
        <thead>
        <tr>
            <th >Supplier ID</th>
            <th >Supplier Name</th>
            <th >Company Name</th>
            <th >Phone</th>
            <th >Email Address</th>
            <th >Full Address</th>
            <th >Create Time</th>
            <th >Edit Time</th>
        </tr>
        </thead>

        <tbody>
            @foreach($suppliers as $supplier)
                <tr onclick="openDetail('suppliers', '{{ $supplier->id }}')" 
                    style="cursor:pointer;" 
                    class="row-hover">
                    
                    <td class="font-bold">{{ $supplier->id }}</td>
                    <td>{{ $supplier->name }}</td>
                    <td>{{ $supplier->company_name }}</td>
                    <td>{{ $supplier->phone ?? '-' }}</td>
                    <td class="text-muted">{{ $supplier->email ?? '-' }}</td>
                    <td class="truncate" title="{{ $supplier->address }}">{{ $supplier->address }}</td>
                    <td>{{ $supplier->created_at }}</td>
                    <td>{{ $supplier->updated_at }}</td>
                </tr>
            @endforeach

            @if($suppliers->isEmpty())
                <tr>
                    <td colspan="8" style="text-align: center; padding: 30px;">
                        Data supplier belum tersedia. Silakan klik "Tambah Pemasok".
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>
