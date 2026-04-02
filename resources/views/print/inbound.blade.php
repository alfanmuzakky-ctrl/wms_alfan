@php
    use SimpleSoftwareIO\QrCode\Facades\QrCode;
@endphp

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>Inbound Task List - {{ $inbound->id }}</title>
    <style>
      
        body {
            font-family: 'Courier New', Courier, monospace; 
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #333;
        }


        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #000;
            padding-bottom: 10px;
            margin-bottom: 20px;
        }

        .header-title {
            font-size: 22px;
            font-weight: bold;
            text-transform: uppercase;
        }

  
        .barcode-section {
            display: flex;
            gap: 20px;
            margin-bottom: 20px;
        }

        .barcode-box {
            flex: 1;
            border: 1px solid #000;
            padding: 10px;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
        }

        .barcode-label {
            margin-top: 5px;
            font-weight: bold;
            font-size: 10px;
        }

        
        .info-container {
            display: flex;
            justify-content: space-between;
            margin-bottom: 15px;
            line-height: 1.6;
        }

          table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        table th, table td {
            border: 1px solid #000;
            padding: 8px 5px;
            text-align: left;
        }

        table th {
            background: #eee;
            text-transform: uppercase;
            font-size: 11px;
        }

        .text-center { text-align: center; }

       
        @media print {
            button, .no-print {
                display: none;
            }
            body {
                padding: 0;
            }
        }
    </style>
</head>

<body>

    <div class="no-print" style="margin-bottom: 20px;">
        <button onclick="window.print()" style="padding: 10px 20px; cursor: pointer;">🖨️ Cetak Dokumen</button>
    </div>

    <div class="header">
        <div>
            <img src="{{ asset('logo.png') }}" height="50" alt="Logo">
        </div>
        <div class="header-title">Inbound Task List</div>
        <div style="text-align: right;">
            <strong>Page:</strong> 1 / 1
        </div>
    </div>

    <div class="barcode-section">
        <div class="barcode-box">
            {!! QrCode::size(70)->generate($inbound->id) !!}
            <div class="barcode-label">INBOUND ID: {{ $inbound->id }}</div>
        </div>
        <div class="barcode-box">
            {!! QrCode::size(70)->generate($inbound->supplier->name ?? 'N/A') !!}
            <div class="barcode-label">SUPPLIER: {{ $inbound->supplier->name ?? 'N/A' }}</div>
        </div>
    </div>

    <div class="info-container">
        <div>
            <strong>Supplier:</strong> {{ $inbound->supplier->name ?? '-' }}<br>
            <strong>Status:</strong> {{ strtoupper($inbound->status) }}<br>
            <strong>Reference:</strong> {{ $inbound->reference_no ?? '-' }}
        </div>
        <div style="text-align: right;">
            <strong>Print Time:</strong> {{ now()->format('d/m/Y H:i:s') }}<br>
            <strong>Warehouse:</strong> WH-MAIN-01<br>
            <strong>Authorized by:</strong> __________
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th width="15%">SKU ID</th>
                <th width="30%">Description</th>
                <th width="10%" class="text-center">Order Qty</th>
                <th width="10%" class="text-center">Actual</th>
                <th width="15%">Batch / Lot</th>
                <th width="15%">Expiry Date</th>
            </tr>
        </thead>
        <tbody>
            @foreach($inbound->details as $detail)
            <tr>
                <td class="font-bold">{{ $detail->sku->id }}</td>
                <td>{{ $detail->sku->name }}</td>
                <td class="text-center">{{ number_format($detail->qty) }}</td>
                <td class="text-center">{{ number_format($detail->received_qty) }}</td>
                <td>{{ $detail->batch_number ?? '__________' }}</td>
                <td>{{ $detail->expired_date ?? '__________' }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div style="margin-top: 30px;">
        <p><em>* Dokumen ini digunakan sebagai bukti penerimaan barang fisik di area Staging.</em></p>
    </div>

    <script>
        
    </script>
</body>
</html>