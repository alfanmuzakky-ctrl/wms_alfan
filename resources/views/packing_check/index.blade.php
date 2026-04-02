<h2>Packing Check</h2>

<div class="section-box">

    <div class="section-title">Scan Outbound</div>


    <form onsubmit="loadOrder(); return false;">
        <input type="text" id="outboundInput" autofocus>
        <button type="submit">Load</button>
    </form>

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


    <form onsubmit="scanSku(); return false;">
        
        <input type="text" id="skuInput" placeholder="Scan SKU">

        <label style="margin-left:10px;">
            <input type="checkbox" id="enableQty"> Input Qty
        </label>

        <input type="number" id="qtyInput" value="1" min="1" style="width:70px;" disabled>

        <button type="submit">Scan</button>

    </form>

</div>


<button onclick="confirmPack()">Confirm Packed</button>