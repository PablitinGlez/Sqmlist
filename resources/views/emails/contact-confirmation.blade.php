<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Confirmación de mensaje recibido</title>
    <style>
        body { font-family: Arial, sans-serif; line-height: 1.6; color: #333; }
        .container { max-width: 600px; margin: 0 auto; padding: 20px; }
        .header { background: #10b981; color: white; padding: 20px; text-align: center; }
        .content { background: #f9fafb; padding: 30px; }
        .highlight { background: #dcfce7; padding: 15px; border-radius: 8px; margin: 20px 0; }
        .info-box { background: white; padding: 20px; border-radius: 8px; margin: 20px 0; }
        .footer { text-align: center; padding: 20px; color: #6b7280; font-size: 14px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>¡Gracias por contactarnos!</h1>
        </div>
        
        <div class="content">
            <p>Hola <strong>{{ $contact->first_name }}</strong>,</p>
            
            <div class="highlight">
                <p><strong>✓ Hemos recibido tu mensaje correctamente</strong></p>
                <p>Te responderemos en un plazo máximo de 24 horas.</p>
            </div>
            
            <p>Aquí tienes un resumen de tu consulta:</p>
            
            <div class="info-box">
                <p><strong>Motivo:</strong> {{ $contact->subject_label }}</p>
                <p><strong>Fecha:</strong> {{ $contact->created_at->format('d/m/Y H:i') }}</p>
                <p><strong>Tu mensaje:</strong></p>
                <p style="font-style: italic; border-left: 3px solid #e5e7eb; padding-left: 15px; margin-left: 10px;">
                    {{ $contact->message }}
                </p>
            </div>
            
            <p>Si tienes alguna consulta urgente, puedes contactarnos directamente:</p>
            <ul>
                <li><strong>Teléfono:</strong> +1012 3456 789</li>
                <li><strong>Email:</strong> demo@gmail.com</li>
            </ul>
            
            <p>¡Gracias por confiar en nosotros!</p>
        </div>
        
        <div class="footer">
            <p>Este es un mensaje automático, por favor no respondas a este correo.</p>
        </div>
    </div>
</body>
</html>