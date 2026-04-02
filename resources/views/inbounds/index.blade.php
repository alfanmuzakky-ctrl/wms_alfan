<x-toolbar resource="inbounds" label="Inbound" />

<div class="table-container">
    <table class="custom-table">
        <thead>
        <tr>
            <th>ID Inbound</th>
            <th>Supplier</th>
            <th>Status</th>
            <th>Create Time</th>
            <th>Action</th>
        </tr>
        </thead>

        <tbody>
            @foreach($inbounds as $inbound)
                    <tr onclick="openDetail('inbounds', '{{ $inbound->id }}')" 
                        style="cursor:pointer;" 
                        class="row-hover">

                    <td class="font-bold">{{ $inbound->id }}</td>

                    <td>
                        {{ $inbound->supplier_id ?? '-' }}<br>
                        <small> {{ $inbound->supplier->name ?? '-' }}</small>
                    </td>

                    <td>
                        <span class="status-badge status-{{ strtolower($inbound->status) }}">
                            {{ $inbound->status }}
                        </span>
                    </td>

                    <td class="text-muted">
                        {{ $inbound->created_at }}
                    </td>

                    <td onclick="event.stopPropagation();">
                        @if($inbound->status !== 'CLOSE')
                            <button class="btn-danger"
                                onclick="closeInbound('{{ $inbound->id }}')">
                                Close Inbound
                            </button>
                        @else
                            <span class="text-locked">Locked</span>
                        @endif
                    </td>

                </tr>
            @endforeach

            @if($inbounds->isEmpty())
                <tr>
                    <td colspan="5" style="text-align: center; padding: 30px;">
                        Data inbound belum tersedia.
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>