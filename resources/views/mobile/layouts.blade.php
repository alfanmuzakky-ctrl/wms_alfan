<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>WMS Module</title>

    <!-- CSS -->
    <link rel="stylesheet" href="{{ asset('css/m.wms.css') }}">
</head>

<body>

<div class="container">
    <div class="title">Module List</div>

    <div class="module-grid">

        <!-- RECEIVED -->
        <div class="module-card" onclick="goTo('received')">
            <div class="icon">📥</div>
            <div class="module-text">Received Proses</div>
        </div>

        <!-- PUTAWAY -->
        <div class="module-card" onclick="goTo('putaway')">
            <div class="icon">📦</div>
            <div class="module-text">Putaway Proses</div>
        </div>

        <!-- PICKING -->
        <div class="module-card" onclick="goTo('picking')">
            <div class="icon">🛒</div>
            <div class="module-text">Picking Order</div>
        </div>

    </div>
</div>

<!-- JS -->
<script src="{{ asset('js/m.wms.js') }}"></script>

</body>
</html>