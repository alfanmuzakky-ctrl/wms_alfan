/* =
   GLOBAL
= */
let isConfirming = false;


/* =
   LOAD ORDER
= */

function loadOrder(){

    const input = document.getElementById("outboundInput");

    if(!input){
        console.log("outboundInput tidak ditemukan");
        return;
    }

    const outboundId = input.value.trim();

    if(!outboundId){
        alert("Scan Outbound ID dulu");
        return;
    }

    fetch('/packing-check/load-order',{
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            outbound_id: outboundId
        })
    })
    .then(r => r.json())
    .then(data => {

        if(data.error){
            alert(data.error);
            return;
        }

        const table = document.querySelector("#orderTable tbody");
        if(!table) return;

        table.innerHTML = "";

        data.details.forEach(d => {
            table.innerHTML += `
            <tr>
                <td>${d.sku}</td>
                <td>${d.order_qty}</td>
                <td id="packed-${d.sku}">
                    ${d.qty_packed}
                </td>
            </tr>
            `;
        });

        const skuInput = document.getElementById("skuInput");
        if(skuInput) skuInput.focus();
    })
    .catch(err => {
        console.error(err);
        alert("Error load order");
    });
}


/* =
   SCAN SKU
= */

function scanSku(){

    const input = document.getElementById("skuInput");
    const qtyInput = document.getElementById("qtyInput");
    const enableQty = document.getElementById("enableQty");

    if(!input) return;

    const sku = input.value.trim();

    if(!sku){
        alert("Scan SKU dulu");
        return;
    }

    const outboundInput = document.getElementById("outboundInput");
    const outboundId = outboundInput ? outboundInput.value.trim() : null;

    if(!outboundId){
        alert("Scan outbound dulu");
        return;
    }

    let qty = 1;

    if(enableQty && enableQty.checked){
        qty = parseInt(qtyInput.value) || 1;
    }

    fetch('/packing-check/scan-sku',{
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            outbound_id: outboundId,
            sku: sku,
            qty: qty
        })
    })
    .then(r => r.json())
    .then(data => {

        if(data.error){
            alert(data.error);
            return;
        }

        const cell = document.getElementById("packed-" + data.sku);

        if(cell){
            cell.innerText = data.qty_packed;

            const row = cell.closest("tr");
            row.style.backgroundColor = "#d4edda";

            setTimeout(() => {
                row.style.backgroundColor = "";
            }, 500);
        }

        // AUTO CONFIRM
        checkAutoConfirm();

        // RESET INPUT
        input.value = "";
        input.focus();

        if(enableQty && enableQty.checked){
            qtyInput.value = "";
        }else{
            qtyInput.value = 1;
        }

    })
    .catch(err => {
        console.error(err);
        alert("Terjadi error koneksi");
    });

}


/* =
   AUTO CONFIRM
= */

function checkAutoConfirm(){

    if(isConfirming) return;

    const rows = document.querySelectorAll("#orderTable tbody tr");

    if(rows.length === 0) return;

    let allComplete = true;

    rows.forEach(row => {

        const orderQty = parseInt(row.children[1].textContent.trim()) || 0;
        const packedQty = parseInt(row.children[2].textContent.trim()) || 0;

        if(packedQty < orderQty){
            allComplete = false;
        }

    });

    if(allComplete){
        isConfirming = true;
        confirmPack();
    }

}


/* =
   CONFIRM PACK
= */

function confirmPack(){

    const outboundInput = document.getElementById("outboundInput");
    const outboundId = outboundInput ? outboundInput.value.trim() : null;

    if(!outboundId){
        alert("Load order dulu");
        return;
    }

    fetch('/packing-check/confirm-pack',{
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify({
            outbound_id: outboundId
        })
    })
    .then(r => r.json())
    .then(data => {

        if(data.error){
            alert(data.error);
            isConfirming = false;
            return;
        }

        alert("Packing selesai");
        loadPage('/packing-check');

    })
    .catch(err => {
        console.error(err);
        isConfirming = false;
    });
}


/* =
   INIT EVENT (PENTING)
= */

function initPackingEvent(){

    const outboundInput = document.getElementById('outboundInput');
    const skuInput = document.getElementById('skuInput');
    const qtyInput = document.getElementById('qtyInput');
    const enableQty = document.getElementById('enableQty');

    // autofocus outbound
    if(outboundInput){
        outboundInput.focus();
    }

    // ENTER di SKU
    if(skuInput){
        skuInput.addEventListener('keypress', function(e){

            if(e.key === 'Enter'){
                e.preventDefault();

                if(enableQty && enableQty.checked){
                    if(qtyInput){
                        qtyInput.focus();
                        qtyInput.select();
                    }
                }else{
                    scanSku();
                }
            }

        });
    }

    // ENTER di QTY
    if(qtyInput){
        qtyInput.addEventListener('keypress', function(e){

            if(e.key === 'Enter'){
                e.preventDefault();
                scanSku();
            }

        });
    }

    // toggle qty
    if(enableQty){
        enableQty.addEventListener('change', function() {

            if(qtyInput){
                qtyInput.disabled = !this.checked;

                if(!this.checked){
                    qtyInput.value = 1;
                }
            }

        });
    }

}


/* =
   EXPORT GLOBAL
 */

window.loadOrder = loadOrder;
window.scanSku = scanSku;
window.confirmPack = confirmPack;
window.initPackingEvent = initPackingEvent;