<!DOCTYPE html>
<html>
<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        th, td {
            border: 1px solid black;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f0f0f0;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center;">SOLICITUD DE MATERIALES, ACCESORIOS Y EQUIPOS</h2>
    <p><strong>Docente:</strong> {{ $profesor->usuario->nombre }}</p>
    <p><strong>Unidad didáctica:</strong> {{ $reserva->unidadDidactica->nombre }}</p>
    <p><strong>Turno:</strong> {{ ucfirst($reserva->turno) }} <strong>Semestre:</strong> {{ $reserva->unidadDidactica->ciclo }} <strong>Ambiente:</strong> {{ $reserva->ambiente }}</p>
    <p><strong>Fecha:</strong> {{ $reserva->fecha_prestamo }} <strong>Hora:</strong> {{ now()->format('H:i') }} <strong>N° Grupos:</strong> {{ $reserva->numero_grupos }}</p>

    <table>
        <tr>
            <th>CANT</th>
            <th>DESCRIPCIÓN DEL PEDIDO</th>
        </tr>
        @foreach($detalles as $detalle)
        <tr>
            <td>{{ $detalle->cantidad_reservada }}</td>
            <td>{{ $detalle->item->descripcion }}</td>
        </tr>
        @endforeach
    </table>
</body>
</html>
