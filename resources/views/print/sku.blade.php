@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="utf-8">
<title>Label SKU - {{ $detail->sku_id }}</title>

<style>
@page {
    size: 95mm 30mm;
    margin: 0;
}

body {
    font-family: Arial, sans-serif;
    margin: 0;
    width: 95mm;
    height: 30mm;
    box-sizing: border-box;
}


.label-container{
    display:flex;
    align-items:center;
    border:1px solid #000;
    width:100%;
    height:100%;
    box-sizing:border-box;
    padding:4px;
}


.label-qr{
    width:28mm;
    display:flex;
    justify-content:center;
    align-items:center;
}


.label-info{
    flex:1;
    padding-left:6px;
    display:flex;
    flex-direction:column;
    justify-content:center;
}


.text-id{
    font-size:13px;
    font-weight:bold;
    display:inline-block;
    border-bottom:1px solid #999;
    padding-bottom:1px;
    margin-bottom:2px;
}


.text-batch{
    font-size:10px;
    font-weight:bold;
    color:#555;
    margin-bottom:3px;
}


.text-sku{
    font-size:13px;
    font-weight:bold;
    display:inline-block;
    padding-bottom:1px;
    margin-bottom:2px;
}


.text-name{
    font-size:10px;
    line-height:1.2;
    word-break:break-word;
}

@media print{
    .no-print{
        display:none;
    }
}
</style>
</head>

<body onload="window.print()">

<div class="no-print" style="position:absolute;top:10px;right:10px;">
    <button onclick="window.print()">Cetak</button>
    <button onclick="window.close()">Tutup</button>
</div>

<div class="label-container">

    <div class="label-qr">
        {!! QrCode::size(65)->margin(1)->generate($detail->sku_id) !!}
    </div>

    <div class="label-info">

        <div class="text-id">
            {{ $detail->inbound_id }}
        </div>

        <div class="text-batch">
            BATCH: {{ $detail->batch_number ?? 'N/A' }}
        </div>

        <div class="text-sku">
            {{ $detail->sku_id }}
        </div>

        <div class="text-name">
            {{ $detail->sku->name ?? 'No Name' }}
        </div>

    </div>

</div>

</body>
</html>