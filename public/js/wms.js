/* =========================================================
   GLOBAL CSRF
========================================================= */

const csrfToken = document.querySelector('meta[name="csrf-token"]')?.content;

/* =========================================================
   SIDEBAR
========================================================= */

function openSidebar() {
    document.getElementById('sidebar')?.classList.add('active');
}

function closeSidebar() {
    document.getElementById('sidebar')?.classList.remove('active');
}

function toggleMenu(id) {
    const menu = document.getElementById(id);
    if (!menu) return;

    menu.style.display = menu.style.display === "block" ? "none" : "block";
}

/* =========================================================
   PAGE LOADER
========================================================= */

function loadPage(url) {

    fetch(url)
        .then(res => res.text())
        .then(html => {

            const main = document.getElementById('mainContent');

            if (main) main.innerHTML = html;

            closeSidebar();

        })
        .catch(err => console.error(err));
}

/* =========================================================
   TAB SYSTEM
========================================================= */

function openTab(url,title){

    const tabsContainer = document.getElementById("tabsContainer");

    let existing = document.querySelector(`.tab[data-url="${url}"]`);

    if(existing){

        activateTab(existing);
        loadPage(url);
        return;

    }

    const tab = document.createElement("div");

    tab.className="tab";
    tab.dataset.url=url;

    tab.innerHTML=`
        ${title}
        <span class="close" onclick="closeTab(event,'${url}')">×</span>
    `;

    tab.onclick=function(){

        activateTab(tab);
        loadPage(url);

    }

    tabsContainer.appendChild(tab);

    activateTab(tab);
    loadPage(url);

}

function activateTab(tab){

    document.querySelectorAll(".tab").forEach(t=>t.classList.remove("active"));

    tab.classList.add("active");

}

function closeTab(event,url){

    event.stopPropagation();

    const tab=document.querySelector(`.tab[data-url="${url}"]`);

    if(tab) tab.remove();

    const tabs=document.querySelectorAll('.tab');

    if(tabs.length>0){

        const lastTab=tabs[tabs.length-1];

        activateTab(lastTab);

        loadPage(lastTab.dataset.url);

    }else{

        loadPage('/dashboard');

    }

}

/* =========================================================
   DRAWER
========================================================= */

function closeDrawer(){

    const drawer=document.getElementById('drawer');
    const content=document.getElementById('drawerContent');

    drawer?.classList.remove('active');

    setTimeout(()=>{

        if(content) content.innerHTML='';

    },300);

}

function openDetail(module,id){

    const drawer=document.getElementById('drawer');
    const content=document.getElementById('drawerContent');

    fetch(`/${module}/${encodeURIComponent(id)}`)
        .then(res=>res.text())
        .then(html=>{

            content.innerHTML=html;

            drawer.classList.add('active');

            bindEditForm();

        })
        .catch(err=>console.error(err));

}

/* =========================================================
   GENERIC FORM BIND
========================================================= */

function bindForm(formId,url){

    const form=document.getElementById(formId);

    if(!form) return;

    form.addEventListener('submit',function(e){

        e.preventDefault();

        const formData=new FormData(form);

        fetch(url,{
            method:'POST',
            headers:{
                'X-CSRF-TOKEN':csrfToken
            },
            body:formData
        })

        .then(res=>res.json())

        .then(data=>{

            if(data.success){

                alert(data.message || "Data saved");

                closeDrawer();

                loadPage('/'+data.module);

            }

        })

        .catch(err=>console.error(err));

    });

}

/* =========================================================
   CREATE FORM
========================================================= */

function openCreate(module){

    const drawer=document.getElementById('drawer');
    const content=document.getElementById('drawerContent');

    fetch(`/${module}/create`)
        .then(res=>res.text())
        .then(html=>{

            content.innerHTML=html;

            drawer.classList.add('active');

            bindForm('addForm','/'+module);

        });

}

/* =========================================================
   EDIT FORM
========================================================= */

function bindEditForm(){

    const form=document.getElementById('editForm');

    if(!form) return;

    form.addEventListener('submit',function(e){

        e.preventDefault();

        const resource=form.dataset.resource;
        const id=form.dataset.id;

        const formData=new FormData(form);

        fetch(`/${resource}/${id}`,{
            method:'POST',
            headers:{
                'X-CSRF-TOKEN':csrfToken
            },
            body:formData
        })

        .then(res=>res.json())
        .then(data=>{

            if(data.success){

                alert(data.message);

                closeDrawer();

                loadPage('/'+resource);

            }

        });

    });

}

/* =========================================================
   INBOUND RECEIVE
========================================================= */

function submitReceive(detailId,inboundId){

    const qty=document.getElementById('qty_'+detailId).value;

    fetch('/inbounds/receive',{

        method:'POST',

        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':csrfToken
        },

        body:JSON.stringify({

            detail_id:detailId,
            inbound_id:inboundId,
            receive_qty:qty

        })

    })

    .then(res=>res.json())

    .then(data=>{

        if(data.success){

            openDetail('inbounds',inboundId);

        }

    });

}

/* =========================================================
   OUTBOUND PROCESS
========================================================= */

function processOutbound(id,action){

    if(!confirm("Process "+action+" ?")) return;

    fetch(`/outbounds/${id}/${action}`,{

        method:'POST',

        headers:{
            'X-CSRF-TOKEN':csrfToken
        }

    })

    .then(res=>res.json())

    .then(data=>{

        if(data.success){

            openDetail('outbounds',id);

        }

    })

    .catch(err=>console.error(err));

}

/* =========================================================
   PUTAWAY
========================================================= */

function submitPutaway(inventoryId){

    const qty=document.getElementById('qty_'+inventoryId).value;
    const dest=document.getElementById('dest_'+inventoryId).value;

    fetch('/putaway/process',{

        method:'POST',

        headers:{
            'Content-Type':'application/json',
            'X-CSRF-TOKEN':csrfToken
        },

        body:JSON.stringify({

            inventory_id:inventoryId,
            destination:dest,
            qty:qty

        })

    })

    .then(res=>res.json())

    .then(data=>{

        if(data.success){

            alert(data.message);

            openTab('/putaway','Putaway');

        }

    });

}

/* =========================================================
   AUTO CLOSE SIDEBAR
========================================================= */

document.addEventListener('click',function(e){

    const sidebar=document.getElementById('sidebar');
    const menuBtn=document.querySelector('.menu-btn');

    if(sidebar?.classList.contains('active')){

        if(!sidebar.contains(e.target) && !menuBtn?.contains(e.target)){

            closeSidebar();

        }

    }

});