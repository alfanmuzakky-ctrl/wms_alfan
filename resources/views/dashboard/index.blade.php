<div class="content">
    <div class="dashboard">
        <h2>Dashboard</h2>

        <!-- MASTER -->
        <div class="grid-4">
            <div class="card"><span>SKU</span><h3>{{ $totalSku }}</h3></div>
            <div class="card"><span>Supplier</span><h3>{{ $totalSupplier }}</h3></div>
            <div class="card"><span>Customer</span><h3>{{ $totalCustomer }}</h3></div>
            <div class="card"><span>Location</span><h3>{{ $totalLocation }}</h3></div>
        </div>

        <!-- INVENTORY -->
        <div class="grid-2">
            <div class="card">
                <h4>Inventory</h4>
                <p>Total: {{ $totalStock }}</p>
                <p>Allocated: {{ $allocatedStock }}</p>
                <p>Available: {{ $totalStock - $allocatedStock }}</p>
            </div>

            <div class="card">
                <h4>Staging</h4>
                <p>INB: {{ $stockInbound }}</p>
                <p>OUT: {{ $stockOutbound }}</p>
            </div>
        </div>

        <!-- FLOW -->
        <div class="grid-2">
            <div class="card">
                <h4>Inbound</h4>
                <p>Total: {{ $totalInbound }}</p>
                <p>Create: {{ $inboundCreate }}</p>
                <p>Received: {{ $inboundReceived }}</p>
            </div>

            <div class="card">
                <h4>Outbound</h4>
                <p>Total: {{ $totalOutbound }}</p>
                <p>Packed: {{ $outboundPacked }}</p>
                <p>Shipped: {{ $outboundShipped }}</p>
            </div>
        </div>

        <!-- TABLE -->
        <div class="grid-2">

            <div class="card">
                <h4>Latest Inbound</h4>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Status</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @forelse($latestInbound as $inb)
                        <tr>
                            <td>{{ $inb->id }}</td>
                            <td>{{ $inb->status }}</td>
                            <td>{{ $inb->created_at }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3">No Data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="card">
                <h4>Latest Outbound</h4>
                <table>
                    <thead>
                        <tr><th>ID</th><th>Status</th><th>Date</th></tr>
                    </thead>
                    <tbody>
                        @forelse($latestOutbound as $out)
                        <tr>
                            <td>{{ $out->id }}</td>
                            <td>{{ $out->status }}</td>
                            <td>{{ $out->created_at }}</td>
                        </tr>
                        @empty
                        <tr><td colspan="3">No Data</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

        </div>

    </div>
</div>