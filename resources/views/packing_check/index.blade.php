<h2>Packing Check</h2>

<div class="section-box">

<div class="section-title">Scan Outbound</div>

<input type="text" id="outboundInput">

<button onclick="loadOrder()">Load</button>

</div>


<div class="section-box">

<div class="section-title">Order Items</div>

<table id="orderTable">

<thead>
<tr>
<th>SKU</th>
<th>Order Qty</th>
<th>Packed</th>
</tr>
</thead>

<tbody></tbody>

</table>

</div>


<div class="section-box">

<div class="section-title">Scan SKU</div>

<input type="text" id="skuInput">

<button onclick="scanSku()">Scan</button>

</div>


<button onclick="confirmPack()">Confirm Packed</button>