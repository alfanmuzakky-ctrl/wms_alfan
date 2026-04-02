/* =
   GLOBAL CSRF
= */
window.csrfToken = document
    .querySelector('meta[name="csrf-token"]')
    .getAttribute('content');

/* =
   GLOBAL EVENT LISTENERS
= */

// Auto close sidebar saat klik di luar
document.addEventListener('click', function(event) {
    const sidebar = document.getElementById('sidebar');
    const menuBtn = document.querySelector('.menu-btn');

    if (sidebar && sidebar.classList.contains('active')) {
        if (!sidebar.contains(event.target) && menuBtn && !menuBtn.contains(event.target)) {
            closeSidebar(); // dari sidebar.js
        }
    }
});

// Auto close drawer saat klik di luar
document.addEventListener('click', function(event) {
    const drawer = document.getElementById('drawer');
    if (!drawer) return;

    if (drawer.classList.contains('active') && !drawer.contains(event.target)) {
        if(!event.target.closest('.drawer-arrow') && !event.target.closest('[onclick*="openDetail"]')){
             closeDrawer(); // dari drawer.js
        }
    }
});


/* =
   GENERIC SUBMIT ACTION
= */

function submitAction(url, module, id, payload = {})
{
    fetch(url, {
        method: 'POST',
        headers: {
            'Content-Type': 'application/json',
            'Accept': 'application/json',
            'X-CSRF-TOKEN': csrfToken
        },
        body: JSON.stringify(payload)
    })
    .then(res => res.json())
    .then(data => {

        if (data.success) {
            openDetail(module, id); // refresh drawer
        } else {
            alert("Gagal: " + data.message);
        }

    })
    .catch(err => {
        console.error("Error:", err);
        alert("Server error");
    });
}


/* =
   EXPORT GLOBAL
= */

window.submitAction = submitAction;