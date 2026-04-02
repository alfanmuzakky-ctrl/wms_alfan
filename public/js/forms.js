/* 
   GENERIC FORM BINDER
 */

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

/* 
   CREATE
 */

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

/* 
   EDIT
 */

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

/* 
   EXPORT GLOBAL
 */

window.bindForm = bindForm;
window.openCreate = openCreate;
window.openEdit = openEdit;