/* 
   GLOBAL CSRF
 */

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


/* 
   ALLOCATE STOCK
 */

function allocateOutbound(id){

    fetch(`/outbounds/${id}/allocate`,{
        method:'POST',
        headers:{
            'X-CSRF-TOKEN':csrfToken
        }
    })

    .then(res=>res.json())

    .then(data=>{

        if(data.success){

            alert('Stock Allocated');
            location.reload();

        }

    });

}


/* 
   PICKING
 */

function pickingOutbound(id){

    fetch(`/outbounds/${id}/picking`,{
        method:'POST',
        headers:{
            'X-CSRF-TOKEN':csrfToken
        }
    })

    .then(res=>res.json())

    .then(data=>{

        if(data.success){

            alert('Picking selesai');
            location.reload();

        }

    });

}


/* 
   PACKING
 */

function packingOutbound(id){

    fetch(`/outbounds/${id}/packing`,{
        method:'POST',
        headers:{
            'X-CSRF-TOKEN':csrfToken
        }
    })

    .then(res=>res.json())

    .then(data=>{

        if(data.success){

            alert('Packing selesai');
            location.reload();

        }

    });

}


/* 
   SHIP
 */

function shipOutbound(id){

    if(!confirm("Kirim barang ini?")) return;

    fetch(`/outbounds/${id}/ship`,{
        method:'POST',
        headers:{
            'X-CSRF-TOKEN':csrfToken
        }
    })

    .then(res=>res.json())

    .then(data=>{

        if(data.success){

            alert('Shipment berhasil');
            location.reload();

        }

    });

}


function openReallocate(orderId) {
    const select = document.querySelector(`.location-select[data-order-id='${orderId}']`);
    if (!select) return;

    const dest = select.value;
    const qty = select.dataset.allocatedQty;

    if (!dest || !qty) return;

    fetch('/reallocate', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            order_id: orderId,
            dest_location_id: dest,
            qty: qty
        })
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert('Reallocation berhasil');

            window.location.href = window.location.pathname + '#outbound';

        } else {
            alert(data.message);
        }
    });
}


/* 
   EXPORT GLOBAL
 */

window.allocateOutbound = allocateOutbound;
window.pickingOutbound = pickingOutbound;
window.packingOutbound = packingOutbound;
window.shipOutbound = shipOutbound;
window.openReallocate = openReallocate;


document.addEventListener('DOMContentLoaded', function () {

    if (window.location.hash === '#outbound') {

        const tabBtn = document.querySelector(".tab-item:nth-child(2)");

        if (tabBtn) {
            tabBtn.click();
        }

    }

});