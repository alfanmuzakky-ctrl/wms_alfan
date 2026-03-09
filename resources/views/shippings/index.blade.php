<div class="toolbar" style="margin-top:20px;">

@if($outbound->status == 'PACKED')

<button
class="btn-primary"
onclick="shipOutbound('{{ $outbound->id }}')">

Ship Order

</button>

@endif


<a href="/print/outbound/{{ $outbound->id }}"
target="_blank"
class="btn-secondary">

🖨️ Print Picking List

</a>

</div>