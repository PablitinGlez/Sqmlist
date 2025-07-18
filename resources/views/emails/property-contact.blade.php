<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nuevo Mensaje de Contacto para tu Propiedad</title>
    <style>
        /* Estilos básicos para compatibilidad con correos */
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .header {
            background-color: #0056b3; /* Color primario de tu marca */
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
        }
        .content {
            padding: 20px;
        }
        .button {
            display: inline-block;
            background-color: #007bff; /* Color del botón */
            color: #ffffff;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 5px;
            text-align: center;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 0.9em;
            color: #666;
            border-top: 1px solid #eee;
            margin-top: 20px;
        }
        ul {
            list-style: none;
            padding: 0;
        }
        ul li {
            margin-bottom: 5px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>Nuevo Mensaje para tu Propiedad</h2>
        </div>
        <div class="content">
            <p>Hola,</p>
            <p>Has recibido un nuevo mensaje de contacto para tu propiedad: <strong>{{ $propertyName }}</strong>.</p>

            <h3>Detalles del Remitente:</h3>
            <ul>
                <li><strong>Nombre:</strong> {{ $senderName }}</li>
                <li><strong>Email:</strong> {{ $senderEmail }}</li>
                @if ($senderPhone)
                <li><strong>Teléfono:</strong> {{ $senderPhone }}</li>
                @endif
            </ul>

            <h3>Mensaje:</h3>
            <p style="border-left: 4px solid #0056b3; padding-left: 10px; margin-left: 0; font-style: italic;">
                "{{ $userMessage }}"
            </p>

            <p style="text-align: center; margin-top: 30px;">
                Para ver los detalles completos de la propiedad, haz clic en el botón:
            </p>
            <p style="text-align: center;">
                <a href="{{ $propertyLink }}" class="button" wire:navigate>Ver Propiedad</a>
            </p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. Todos los derechos reservados.</p>
        </div>
    </div>
</body>
</html>