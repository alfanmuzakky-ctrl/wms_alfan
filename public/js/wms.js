/* Page Loader & Tabs */
function loadPage(url) {
    fetch(url)
        .then(res => res.text())
        .then(data => {
            const main = document.getElementById('mainContent');
            if (main) main.innerHTML = data;
            closeSidebar(); // otomatis tutup sidebar
            initPackingEvent();
        })
        .catch(err => console.error(err));
}

function openTab(url, title) {
    const tabsContainer = document.getElementById("tabsContainer");

    // cek apakah tab sudah ada
    let existing = document.querySelector(`.tab[data-url="${url}"]`);

    if (existing) {
        activateTab(existing);
        loadPage(url);
        return;
    }

    const tab = document.createElement("div");
    tab.className = "tab";
    tab.dataset.url = url;

    tab.innerHTML = `
        ${title}
        <span class="close" onclick="closeTab(event,'${url}')">×</span>
    `;

    tab.onclick = function() {
        activateTab(tab);
        loadPage(url);
    };

    tabsContainer.appendChild(tab);
    activateTab(tab);
    loadPage(url);
}
function activateTab(tab) {
    document.querySelectorAll(".tab").forEach(t => {
        t.classList.remove("active");
    });
    tab.classList.add("active");
}
function closeTab(event, url) {
    event.stopPropagation();
    const tab = document.querySelector(`.tab[data-url="${url}"]`);

    if (tab) {
        tab.remove();
    }

    // cek tab yang masih ada
    const tabs = document.querySelectorAll('.tab');

    if (tabs.length > 0) {
        // aktifkan tab terakhir
        const lastTab = tabs[tabs.length - 1];
        document.querySelectorAll('.tab').forEach(t => {
            t.classList.remove('active');
        });
        lastTab.classList.add('active');
        const newUrl = lastTab.getAttribute('data-url');
        loadPage(newUrl);
    } else {
        // jika tidak ada tab buka dashboard
        loadPage('/dashboard');
    }
}
function loadDashboard() {
    document.getElementById('mainContent').innerHTML = `
        <h1>Dashboard</h1>
        <p>Selamat datang di Warehouse Management System.</p>
    `;
    closeSidebar();
}

/* Drawer Section */
function closeDrawer() {
    const drawer = document.getElementById('drawer');
    const content = document.getElementById('drawerContent');

    if (drawer) drawer.classList.remove('active');
    if (content) {
        setTimeout(() => {
            content.innerHTML = '';
        }, 300);
    }
}

function openDetail(module, id) {
    const encodedId = encodeURIComponent(id);
    const drawer = document.getElementById('drawer');
    const content = document.getElementById('drawerContent');

    fetch(`/${module}/${encodedId}`)
        .then(res => res.text())
        .then(data => {
            content.innerHTML = data;
            drawer.classList.add('active');
            openEdit(); // bind edit form
        })
        .catch(err => console.error(err));
}

/* Generic Form Binder */

function bindForm(formId, url, method = 'POST') {
    const form = document.getElementById(formId);
    if (!form) return;

    form.onsubmit = null;
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const formData = new FormData(form);
        fetch(url, {
            method: method,
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message ?? 'Data berhasil disimpan');
                closeDrawer();
                loadPage('/' + data.module);
            }
        })
        .catch(err => console.error(err));
    });
}

function openCreate(module) {
    const drawer = document.getElementById('drawer');
    const content = document.getElementById('drawerContent');

    fetch('/' + module + '/create')
        .then(res => res.text())
        .then(data => {
            content.innerHTML = data;
            drawer.classList.add('active');
            bindForm('addForm', '/' + module);
        });
}

function openEdit() {
    const form = document.getElementById('editForm');
    if (!form) return;

    form.onsubmit = null;
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        e.stopPropagation();

        const resource = form.dataset.resource;
        const id = form.dataset.id;
        const formData = new FormData(form);

        fetch('/' + resource + '/' + encodeURIComponent(id), {
            method: 'POST',
            headers: {
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: formData
        })
        .then(res => res.json())
        .then(data => {
            if (data.success) {
                alert(data.message);
                closeDrawer();
                loadPage('/' + resource);
            }
        })
        .catch(err => console.error(err));
    });
}

/* Inbound Management */
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

    console.log(`Mengirim ke: /${type}/${id}/add-sku`); // Cek di console (F12)

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
                openDetail(type, id);
            }
        } else {
            alert("Gagal: " + data.message);
        }
    })
    .catch(err => console.error("Error:", err));
});

/* Putaway Section */

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
            loadPage('/putaway');
        }
    });
}

/* Global Event Listeners */

// Auto close sidebar saat klik di luar
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const menuBtn = document.querySelector('.menu-btn');

    if (sidebar && sidebar.classList.contains('active')) {
        if (!sidebar.contains(event.target) && menuBtn && !menuBtn.contains(event.target)) {
            closeSidebar();
        }
    }
});

// Auto close drawer saat klik di luar
document.addEventListener('click', function(event) {
    const drawer = document.getElementById('drawer');
    if (!drawer) return;

    if (drawer.classList.contains('active') && !drawer.contains(event.target)) {
        // Pastikan tidak menutup saat sedang interaksi dengan elemen yang memicu drawer
        if(!event.target.closest('.drawer-arrow') && !event.target.closest('[onclick*="openDetail"]')){
             closeDrawer();
        }
    }
});


// Submit Receive
// Fungsi untuk Receive Barang
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
            // Refresh tampilan tab detail tanpa reload halaman
            openDetail('inbounds', inboundId);
        } else {
            alert("Gagal: " + data.message);
        }
    })
    .catch(err => console.error("Error:", err));
}

// Fungsi untuk Menutup Inbound secara manual
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

// Fungsi untuk memproses Putaway (Pindah dari Staging ke Rak)
function submitPutaway(inventoryId) {
    // Ambil elemen input berdasarkan ID inventory
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

    // Kirim data ke Laravel menggunakan Fetch API
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
            // Refresh halaman putaway agar list stok staging terupdate
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

/*
|--------------------------------------------------------------------------
| GLOBAL CSRF
|--------------------------------------------------------------------------
*/

const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

/*
|--------------------------------------------------------------------------
| ALLOCATE STOCK
|--------------------------------------------------------------------------
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
|--------------------------------------------------------------------------
| PICKING
|--------------------------------------------------------------------------
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
|--------------------------------------------------------------------------
| PACKING
|--------------------------------------------------------------------------
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
|--------------------------------------------------------------------------
| SHIP
|--------------------------------------------------------------------------
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



// ================================
// GLOBAL
// ================================
let isConfirming = false;

// ================================
// LOAD ORDER
// ================================

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


// ================================
// SCAN SKU
// ================================

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

        // 🔥 AUTO CONFIRM
        checkAutoConfirm();

        // 🔄 RESET INPUT
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


// ================================
// CONFIRM PACK
// ================================
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



// ================================
// EXPORT TO WINDOW
// ================================

window.loadOrder = loadOrder;
window.scanSku = scanSku;
window.confirmPack = confirmPack;

// ================================
// GENERIC SUBMIT ACTION
// ================================

function submitAction(url, module, id, payload = {})
{

fetch(url, {
    method: 'POST',
    headers: {
        'Content-Type': 'application/json',
        'Accept': 'application/json',
        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
    },
    body: JSON.stringify(payload)
})
.then(res => res.json())
.then(data => {

    if (data.success) {

        // refresh drawer
        openDetail(module, id);

    } else {

        alert("Gagal: " + data.message);

    }

})
.catch(err => {

    console.error("Error:", err);
    alert("Server error");

});

}   

function initPackingEvent(){

    const outboundInput = document.getElementById('outboundInput');
    const skuInput = document.getElementById('skuInput');
    const qtyInput = document.getElementById('qtyInput');
    const enableQty = document.getElementById('enableQty');

    // autofocus outbound saat load
    if(outboundInput){
        outboundInput.focus();
    }

    // 🔥 HANDLE ENTER DI SKU
    if(skuInput){
        skuInput.addEventListener('keypress', function(e){

            if(e.key === 'Enter'){
                e.preventDefault();

                if(enableQty && enableQty.checked){
                    // 👉 pindah ke input qty
                    if(qtyInput){
                        qtyInput.focus();
                        qtyInput.select();
                    }
                }else{
                    // 👉 langsung scan
                    scanSku();
                }
            }

        });
    }

    // 🔥 HANDLE ENTER DI QTY
    if(qtyInput){
        qtyInput.addEventListener('keypress', function(e){

            if(e.key === 'Enter'){
                e.preventDefault();
                scanSku();
            }

        });
    }

    // toggle enable qty
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