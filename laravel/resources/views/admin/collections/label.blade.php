<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Label - {{ $title }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: Arial, sans-serif;
            padding: 20px;
        }
        .label {
            width: 3in;
            height: 1.5in;
            border: 1px dashed #ccc;
            padding: 10px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            page-break-inside: avoid;
            background: white;
        }
        .label-header {
            text-align: center;
            font-size: 10pt;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .label-title {
            font-size: 9pt;
            font-weight: bold;
            margin-bottom: 3px;
            line-height: 1.2;
            max-height: 40px;
            overflow: hidden;
        }
        .label-author {
            font-size: 8pt;
            color: #666;
            margin-bottom: 5px;
        }
        .label-call-number {
            font-size: 8pt;
            font-family: 'Courier New', monospace;
            margin-bottom: 5px;
            font-weight: bold;
        }
        .label-barcode {
            text-align: center;
            margin-top: auto;
        }
        .label-barcode img {
            max-width: 100%;
            height: 30px;
        }
        .label-qr {
            position: absolute;
            top: 10px;
            right: 10px;
            width: 40px;
            height: 40px;
        }
        .label-qr img {
            width: 100%;
            height: 100%;
        }
        @media print {
            body {
                padding: 0;
            }
            .label {
                border: 1px solid #000;
            }
        }
    </style>
</head>
<body>
    <div class="label">
        <div class="label-qr">
            <img src="{{ $qrUrl }}" alt="QR Code">
        </div>
        <div class="label-header">PERPUSTAKAAN</div>
        <div class="label-title">{{ $title }}</div>
        <div class="label-author">{{ $author }}</div>
        <div class="label-call-number">{{ $callNumber }}</div>
        <div class="label-barcode">
            <img src="{{ $barcodeUrl }}" alt="Barcode">
            <div style="font-size: 7pt; margin-top: 2px;">{{ $barcode }}</div>
        </div>
    </div>
</body>
</html>