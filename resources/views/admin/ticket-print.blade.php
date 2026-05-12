<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ticket Mesa #{{ $mesa->numero }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier New', Courier, monospace;
            font-size: 12px;
            width: 80mm;
            max-width: 80mm;
            padding: 8px;
            color: #000;
            background: #fff;
        }
        .center {
            text-align: center;
        }
        .header {
            margin-bottom: 10px;
        }
        .restaurant-name {
            font-size: 14px;
            font-weight: bold;
            text-transform: uppercase;
        }
        .dashed-line {
            border-top: 1px dashed #000;
            margin: 8px 0;
        }
        .cliente-section {
            margin-bottom: 10px;
        }
        .cliente-nombre {
            font-weight: bold;
            margin-bottom: 5px;
            text-transform: uppercase;
        }
        .item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
        }
        .item-cantidad {
            width: 25px;
        }
        .item-nombre {
            flex: 1;
            padding-left: 5px;
        }
        .item-precio {
            width: 50px;
            text-align: right;
        }
        .totals {
            margin-top: 10px;
        }
        .total-line {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 13px;
            margin-top: 5px;
        }
        .footer {
            margin-top: 15px;
            font-size: 10px;
            text-align: center;
        }
        .mesa-info {
            font-size: 11px;
            margin-bottom: 3px;
        }
        .text-right {
            text-align: right;
        }
        .subtotal-cliente {
            font-size: 11px;
            margin-top: 3px;
            padding-top: 3px;
            border-top: 1px dotted #ccc;
        }
        @media print {
            body {
                width: 80mm;
                margin: 0;
                padding: 5mm;
            }
            .no-print {
                display: none;
            }
        }
        .btn-print {
            background: #000;
            color: #fff;
            border: none;
            padding: 10px 20px;
            font-family: inherit;
            font-size: 12px;
            cursor: pointer;
            margin: 10px 0;
            width: 100%;
        }
        .btn-print:hover {
            background: #333;
        }
    </style>
</head>
<body>
    {{-- Botón imprimir (solo visible en pantalla) --}}
    <div class="no-print" style="text-align: center; margin-bottom: 10px;">
        <button onclick="window.print()" class="btn-print">
            <i class="bi bi-printer"></i> Imprimir Ticket (Ctrl+P)
        </button>
        <button onclick="window.close()" class="btn-print" style="background: #666;">
            Cerrar
        </button>
    </div>

    <div class="header center">
        <div class="restaurant-name">{{ configuracion('nombre_restaurante', 'BuffeFast') }}</div>
        <div class="mesa-info">{{ configuracion('direccion', 'Dirección del local') }}</div>
        <div class="mesa-info">Tel: {{ configuracion('telefono', '-') }}</div>
        <div class="dashed-line"></div>
        <div class="mesa-info">Mesa #{{ $mesa->numero }}</div>
        <div class="mesa-info">{{ $fecha }}</div>
        <div class="mesa-info">Clientes: {{ $sesionActiva->clientes()->count() }}</div>
    </div>

    <div class="dashed-line"></div>

    {{-- Listado por cliente --}}
    @forelse($pedidosAgrupados as $nombreCliente => $datosCliente)
        <div class="cliente-section">
            <div class="cliente-nombre">> {{ $nombreCliente }}</div>
            <div style="font-size: 10px; margin-bottom: 5px;">({{ $datosCliente['cantidad_rondas'] }} rondas)</div>
            
            @foreach($datosCliente['detalle_platos'] as $nombrePlato => $detalle)
                <div class="item">
                    <span class="item-cantidad">{{ $detalle['cantidad'] }}x</span>
                    <span class="item-nombre">{{ $nombrePlato }}</span>
                    <span class="item-precio">{{ number_format($detalle['subtotal'], 2, ',', '.') }}€</span>
                </div>
            @endforeach
            
            <div class="text-right subtotal-cliente">
                Subtotal {{ $nombreCliente }}: {{ number_format($datosCliente['total_euros'], 2, ',', '.') }}€
            </div>
        </div>
        <div class="dashed-line"></div>
    @empty
        <div class="center" style="font-style: italic;">Sin pedidos</div>
        <div class="dashed-line"></div>
    @endforelse

    {{-- Menú buffet base --}}
    <div class="item">
        <span class="item-cantidad">1x</span>
        <span class="item-nombre">Menú Buffet (base)</span>
        <span class="item-precio">{{ number_format($precioBase, 2, ',', '.') }}€</span>
    </div>

    <div class="dashed-line"></div>

    {{-- Totales --}}
    <div class="totals">
        <div class="item">
            <span>Subtotal:</span>
            <span>{{ number_format($totalMesa, 2, ',', '.') }}€</span>
        </div>
        <div class="item">
            <span>IVA {{ $iva }}%:</span>
            <span>{{ number_format($ivaCalculado, 2, ',', '.') }}€</span>
        </div>
        <div class="total-line">
            <span>TOTAL</span>
            <span>{{ number_format($totalConIva, 2, ',', '.') }}€</span>
        </div>
    </div>

    <div class="footer">
        <div class="dashed-line"></div>
        <p>{{ configuracion('texto_ticket_pie', '¡Gracias por su visita!') }}</p>
        <p style="font-size: 9px; margin-top: 5px;">- Ticket #{{ $sesionActiva->id }}</p>
    </div>
    
    <script>
         window.onload = function() { window.print(); }
    </script>
</body>
</html>