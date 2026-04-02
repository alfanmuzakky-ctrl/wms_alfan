/* ===============================
   DRAWER SECTION
=============================== */

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
            openEdit(); // dari forms.js
        })
        .catch(err => console.error(err));
}

/* ===============================
   EXPORT GLOBAL
=============================== */

window.closeDrawer = closeDrawer;
window.openDetail = openDetail;