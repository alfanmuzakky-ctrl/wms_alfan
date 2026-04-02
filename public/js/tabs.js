/* 
   PAGE LOADER & TABS
 */

function loadPage(url) {
    fetch(url)
        .then(res => res.text())
        .then(data => {
            const main = document.getElementById('mainContent');
            if (main) main.innerHTML = data;
            closeSidebar(); // dari sidebar.js
            initPackingEvent(); // dari packing.js
        })
        .catch(err => console.error(err));
}

function openTab(url, title) {
    const tabsContainer = document.getElementById("tabsContainer");

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
    if (tab) tab.remove();

    const tabs = document.querySelectorAll('.tab');

    if (tabs.length > 0) {
        const lastTab = tabs[tabs.length - 1];

        document.querySelectorAll('.tab').forEach(t => {
            t.classList.remove('active');
        });

        lastTab.classList.add('active');
        const newUrl = lastTab.getAttribute('data-url');
        loadPage(newUrl);
    } else {
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

window.switchTab = function(event, tab) {

    // sembunyikan semua tab
    document.querySelectorAll('.tab-content').forEach(el => {
        el.style.display = 'none';
    });

    // reset semua tab button
    document.querySelectorAll('.tab-item').forEach(btn => {
        btn.classList.remove('active');
    });

    // tampilkan tab yang dipilih
    document.getElementById('tab-' + tab).style.display = 'block';

    // aktifkan tab yang diklik
    event.currentTarget.classList.add('active');

};

/* 
   EXPORT GLOBAL (WAJIB)
 */

window.loadPage = loadPage;
window.openTab = openTab;
window.activateTab = activateTab;
window.closeTab = closeTab;
window.loadDashboard = loadDashboard;
window.switchTab = switchTab;