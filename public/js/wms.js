
/* Sidebar Section */

// Fungsi : membuka sidebar
function openSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) sidebar.classList.add('active');
}

// Fungsi : menutup sidebar
function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) sidebar.classList.remove('active');
}

// Fungsi : toggle dropdown menu di sidebar
function toggleMenu(id) {
    const menu = document.getElementById(id);
    if (!menu) return;
    menu.style.display = (menu.style.display === "block") ? "none" : "block";
}

/* Page Loader & Tabs */

// Fungsi : load halaman ke mainContent via AJAX
function loadPage(url) {
    fetch(url)
        .then(res => res.text())
        .then(data => {
            const main = document.getElementById('mainContent');
            if (main) main.innerHTML = data;
            closeSidebar(); // otomatis tutup sidebar
        })
        .catch(err => console.error(err));
}

// Fungsi : Open Tab Navbar
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

// Tab Aktif
function activateTab(tab) {
    document.querySelectorAll(".tab").forEach(t => {
        t.classList.remove("active");
    });
    tab.classList.add("active");
}

// Tutup Tab
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

// Fungsi : load dashboard default
function loadDashboard() {
    document.getElementById('mainContent').innerHTML = `
        <h1>Dashboard</h1>
        <p>Selamat datang di Warehouse Management System.</p>
    `;
    closeSidebar();
}

/* Drawer Section */

// Fungsi : menutup drawer
function closeDrawer() {
    const drawer = document.getElementById('drawer');
    const content = document.getElementById('drawerContent');

    if (drawer) drawer.classList.remove('active');

    // kosongkan isi setelah animasi
    if (content) {
        setTimeout(() => {
            content.innerHTML = '';
        }, 300);
    }
}

// Fungsi : membuka detail data di drawer
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

// Fungsi : binding form create agar tidak double submit
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

// Fungsi : membuka form create di drawer
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

// Fungsi : binding edit form
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

// Fungsi : submit form receive inbound
document.addEventListener('submit', function(e) {
    if (e.target.id !== 'receiveForm') return;
    e.preventDefault();

    const form = e.target;
    const detailId = form.querySelector('[name="detail_id"]').value;
    const inboundId = form.querySelector('[name="inbound_id"]').value;
    const formData = new FormData(form);

    fetch('/inbounds/' + detailId + '/receive', {
        method: 'POST',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
        },
        body: formData
    })
    .then(res => res.json())
    .then(data => {
        alert(data.message);
        if (data.success) {
            openDetail('inbounds', inboundId);
        }
    });
});

// Fungsi : submit form add SKU ke inbound/outbound// Pastikan tidak ada duplikasi, hapus listener submit yang lama jika ada
document.addEventListener('submit', function(e) {
    const form = e.target;
    
    // Hanya proses jika ini adalah addSkuForm
    if (form.id !== 'addSkuForm') return;
    
    e.preventDefault();

    const type = form.dataset.type; // Mengambil 'inbounds' atau 'outbounds'
    
    // Ambil ID dari input yang punya class 'target-id'
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
                openDetail(type, id); // Refresh otomatis
            }
        } else {
            alert("Gagal: " + data.message);
        }
    })
    .catch(err => console.error("Error:", err));
});

/* Putaway Section */

// Fungsi : submit button custom tanpa reload halaman
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
| FORM SUBMIT (CREATE DATA)
|--------------------------------------------------------------------------
*/

const addForm = document.getElementById('addForm');

if(addForm){

    addForm.addEventListener('submit', function(e){

        e.preventDefault();

        let formData = new FormData(this);

        fetch(window.location.pathname,{
            method:'POST',
            headers:{
                'X-CSRF-TOKEN':csrfToken
            },
            body:formData
        })

        .then(response => {

            if(!response.ok){
                throw new Error("Server Error");
            }

            return response.json();
        })

        .then(data=>{

            if(data.success){

                location.reload();

            }else{

                alert("Gagal menyimpan");

            }

        })

        .catch(err=>{

            console.error(err);
            alert("Terjadi error server");

        });

    });

}


/*
|--------------------------------------------------------------------------
| ADD SKU TO OUTBOUND
|--------------------------------------------------------------------------
*/

function addSku(outboundId){

    const sku = document.getElementById('sku_id').value;
    const qty = document.getElementById('qty_order').value;

    fetch('/outbound/add-sku',{
        method:'POST',
        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':csrfToken
        },
        body:JSON.stringify({
            outbound_id:outboundId,
            sku_id:sku,
            qty_order:qty
        })
    })

    .then(res=>res.json())

    .then(data=>{

        if(data.success){

            location.reload();

        }

    });

}


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