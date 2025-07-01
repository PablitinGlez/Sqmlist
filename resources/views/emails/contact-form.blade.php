<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Nuevo mensaje de contacto</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #1f2937; color: white; padding: 20px; text-align: center; }
        .content { background: #f9fafb; padding: 30px; }
        .info-row { margin-bottom: 15px; }
        .label { font-weight: bold; color: #374151; }
        .message-box { background: white; padding: 20px; border-left: 4px solid #3b82f6; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Nuevo Mensaje de Contacto</h1>
        </div>
        
        <div class="content">
            <p>Has recibido un nuevo mensaje a través del formulario de contacto:</p>
            
            <div class="info-row">
                <span class="label">Nombre:</span> {{ $contact->full_name }}
            </div>
            
            <div class="info-row">
                <span class="label">Email:</span> {{ $contact->email }}
            </div>
            
            @if($contact->phone)
            <div class="info-row">
                <span class="label">Teléfono:</span> {{ $contact->phone }}
            </div>
            @endif
            
            <div class="info-row">
                <span class="label">Motivo:</span> {{ $contact->subject_label }}
            </div>
            
            <div class="info-row">
                <span class="label">Fecha:</span> {{ $contact->created_at->format('d/m/Y H:i') }}
            </div>
            
            <div class="message-box">
                <div class="label">Mensaje:</div>
                <p>{{ $contact->message }}</p>
            </div>
            
            <p style="margin-top: 30px;">
                <strong>Puedes responder directamente a este correo para contactar al usuario.</strong>
            </p>
        </div>
        
        <div class="footer">
            <p>Este email fue generado automáticamente desde tu sitio web.</p>
        </div>
    </div>
</body>
</html>