/* ===============================
   SIDEBAR SECTION
=============================== */

function openSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) sidebar.classList.add('active');
}

function closeSidebar() {
    const sidebar = document.getElementById('sidebar');
    if (sidebar) sidebar.classList.remove('active');
}

function toggleMenu(id) {
    const menu = document.getElementById(id);
    if (!menu) return;
    menu.style.display = (menu.style.display === "block") ? "none" : "block";
}

/* ===============================
   EXPORT TO GLOBAL (WAJIB)
=============================== */
window.openSidebar = openSidebar;
window.closeSidebar = closeSidebar;
window.toggleMenu = toggleMenu;