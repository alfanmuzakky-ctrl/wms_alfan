/* ===============================
   PUTAWAY SECTION
=============================== */

// Submit dari button (generic)
function submitButton(button) {
    const form = button.closest('form');
    const formData = new FormData(form);

    fetch(form.action, {
        method: 'POST',
        body: formData,
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        }
    })
    .then(res => res.json())
    .then(data => {
        if (data.success) {
            alert(data.message);
            loadPage('/putaway'); // refresh page
        }
    });
}


/* ===============================
   PROCESS PUTAWAY
=============================== */

function submitPutaway(inventoryId) {

    const qtyInput = document.getElementById('qty_' + inventoryId);
    const destInput = document.getElementById('dest_' + inventoryId);

    if (!qtyInput || !destInput) {
        console.error("Elemen input tidak ditemukan!");
        return;
    }

    const qtyValue = qtyInput.value;
    const destinationValue = destInput.value;

    if (!destinationValue) {
        alert("Pilih lokasi tujuan terlebih dahulu!");
        return;
    }

    if (!qtyValue || qtyValue <= 0) {
        alert("Jumlah Qty tidak valid!");
        return;
    }

    fetch('/putaway/process', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: JSON.stringify({
            inventory_id: inventoryId,
            destination: destinationValue,
            qty: qtyValue
        })
    })
    .then(response => response.json())
    .then(data => {

        if (data.success) {
            alert(data.message);

            // reload tab putaway
            openTab('/putaway', 'Putaway');

        } else {
            alert("Gagal: " + data.message);
        }

    })
    .catch(error => {
        console.error('Error:', error);
        alert("Terjadi kesalahan sistem saat Putaway.");
    });
}


/* ===============================
   EXPORT GLOBAL
=============================== */

window.submitButton = submitButton;
window.submitPutaway = submitPutaway;