/* ===============================
   GLOBAL CSRF
=============================== */

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');


/* ===============================
   ALLOCATE STOCK
=============================== */

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


/* ===============================
   PICKING
=============================== */

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


/* ===============================
   PACKING
=============================== */

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


/* ===============================
   SHIP
=============================== */

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


/* ===============================
   EXPORT GLOBAL
=============================== */

window.allocateOutbound = allocateOutbound;
window.pickingOutbound = pickingOutbound;
window.packingOutbound = packingOutbound;
window.shipOutbound = shipOutbound;
