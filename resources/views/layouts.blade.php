<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>NIAGA WMS - Dashboard</title>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
</head>
<body onload="openTab('/dashboard', 'Dashboard')">

    <div class="topbar">

    <div class="topbar-left">
        <div class="menu-btn" onclick="openSidebar()">☰</div>
    </div>
    
    <div class="tabs" id="tabsContainer"></div>

    <div class="topbar-right">
        @auth
            <span class="user-info">
                {{ auth()->user()->account }} ({{ auth()->user()->role }})
            </span>

            <form method="POST" action="/logout">
                @csrf
                <button type="submit" class="logout-btn">Logout</button>
            </form>
        @endauth
    </div>

</div>

    <div id="sidebar" class="sidebar">
        <div class="sidebar-header">
            <h2>WMS</h2>
            <small>Warehouse Management</small>
        </div>

        <nav class="sidebar-nav">
            <a onclick="openTab('/dashboard', 'Dashboard')">Dashboard</a>
@if(auth()->user()->role == 'Admin')
            <div class="nav-group">
                <a onclick="toggleMenu('basic')" class="nav-dropdown">Basic Setup <span class="arrow">▾</span></a>
                <div id="basic" class="submenu">
                    <a onclick="openTab('/suppliers', 'Supplier')">Supplier</a>
                    <a onclick="openTab('/customers', 'Customer')">Customer</a>
                    <a onclick="openTab('/skus', 'SKU')">Master SKU</a>
                    <a onclick="openTab('/locations', 'Location')">Master Location</a>
                </div>
            </div>
@endif
            <div class="nav-group">
                <a onclick="toggleMenu('inbound')" class="nav-dropdown">Inbound <span class="arrow">▾</span></a>
                <div id="inbound" class="submenu">
                    @if(auth()->user()->role == 'Admin')
                    <a onclick="openTab('/inbounds', 'Inbound')">Incoming Order</a>
                    @endif
                    <a onclick="openTab('/putaway', 'Putaway')">Putaway Process</a>
                </div>
            </div>

            <a onclick="openTab('/inventories', 'Inventory')" class="nav-item">Inventory Stock</a>

            <div class="nav-group">
                <a onclick="toggleMenu('outbound')" class="nav-dropdown">Outbound <span class="arrow">▾</span></a>
                <div id="outbound" class="submenu">
                    @if(auth()->user()->role == 'Admin')
                    <a onclick="openTab('/outbounds', 'Outbound')">Outbound Order</a>
                    @endif
                    <a onclick="openTab('/packing-check', 'Packing')">Packing & Check</a>
                </div>
            </div>
        </nav>
    </div>

    <main class="content">
    <div id="mainContent" class="work-area">
        Loading...
    </div>
</main>

        <div id="drawer" class="drawer">
            <div class="toolbar">
                <button class="drawer-close" onclick="closeDrawer()">✕ Close</button>
            </div>    
                
            <div id="drawerContent" class="drawer-body">
                </div>
        </div>
    </main>

    
    <script src="{{ asset('js/sidebar.js') }}" defer></script>
    <script src="{{ asset('js/tabs.js') }}" defer></script>
    <script src="{{ asset('js/drawer.js') }}" defer></script>
    <script src="{{ asset('js/forms.js') }}" defer></script>
    <script src="{{ asset('js/inbound.js') }}" defer></script>
    <script src="{{ asset('js/putaway.js') }}" defer></script>
    <script src="{{ asset('js/outbound.js') }}" defer></script>
    <script src="{{ asset('js/packing.js') }}" defer></script>
    <script src="{{ asset('js/global.js') }}" defer></script>
</body>
</html>