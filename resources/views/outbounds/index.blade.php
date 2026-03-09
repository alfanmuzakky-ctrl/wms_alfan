<x-toolbar resource="outbounds" label="Outbound" />

<div class="table-container">
<table class="custom-table">

<thead>
<tr>
    <th>ID Outbound</th>
    <th>Customer</th>
    <th>Status</th>
    <th>Create Time</th>
    <th>Action</th>
</tr>
</thead>

<tbody>

@foreach($outbounds as $outbound)

<tr onclick="openDetail('outbounds','{{ $outbound->id }}')" 
    class="row-hover"
    style="cursor:pointer;">

<td class="font-bold">
{{ $outbound->id }}
</td>

<td>
{{ $outbound->customer->name ?? $outbound->customer_id }}
</td>

<td>
<span class="status-badge status-{{ strtolower($outbound->status) }}">
{{ $outbound->status }}
</span>
</td>

<td class="text-muted">
{{ $outbound->created_at }}
</td>

<td onclick="event.stopPropagation();">

@if($outbound->status != 'SHIPPED')

<button class="btn-danger"
onclick="closeOutbound('{{ $outbound->id }}')">
Close Order
</button>

@else

<span class="text-locked">
Shipped
</span>

@endif

</td>

</tr>

@endforeach


@if($outbounds->isEmpty())

<tr>
<td colspan="5" style="text-align:center;padding:30px;">
Belum ada data outbound
</td>
</tr>

@endif

</tbody>
</table>
</div>