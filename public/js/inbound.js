/* 
   INBOUND MANAGEMENT
 */

// Handle form add SKU (GLOBAL LISTENER)
document.addEventListener('submit', function(e) {
    const form = e.target;

    if (form.id !== 'addSkuForm') return;

    e.preventDefault();

    const type = form.dataset.type; 
    const idElement = form.querySelector('.target-id');

    if (!type || !idElement) {
        console.error("Gagal: Atribut data-type atau class 'target-id' hilang!");
        return;
    }

    const id = idElement.value;
    const formData = new FormData(form);

    console.log(`Mengirim ke: /${type}/${id}/add-sku`);

    fetch(`/${type}/${id}/add-sku`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Accept': 'application/json'
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {
            alert(data.message);

            if (typeof openDetail === "function") {
                openDetail(type, id); // drawer.js
            }

        } else {
            alert("Gagal: " + data.message);
        }

    })
    .catch(err => console.error("Error:", err));
});


/* 
   RECEIVE BARANG
 */

function submitReceive(detailId, inboundId) {

    const qtyInput = document.getElementById('qty_' + detailId);
    const qtyValue = qtyInput.value;

    if (!qtyValue || qtyValue <= 0) {
        alert("Masukkan jumlah qty yang valid!");
        return;
    }

    fetch('/inbounds/receive', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            detail_id: detailId,
            inbound_id: inboundId,
            receive_qty: qtyValue
        })
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {
            openDetail('inbounds', inboundId); // refresh drawer
        } else {
            alert("Gagal: " + data.message);
        }

    })
    .catch(err => console.error("Error:", err));
}


/* 
   CLOSE INBOUND
 */

function closeInbound(id) {

    if (!confirm("Tutup Inbound ini? Status tidak bisa dikembalikan.")) return;

    fetch(`/inbounds/${id}/close`, {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {
            openDetail('inbounds', id);
        }

    });
}


/* 
   EXPORT GLOBAL
 */

window.submitReceive = submitReceive;
window.closeInbound = closeInbound;