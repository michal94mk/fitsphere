<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Potwierdź swój adres email</title>
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: #ffffff;
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .header {
            text-align: center;
            margin-bottom: 30px;
        }
        .logo {
            font-size: 32px;
            font-weight: bold;
            color: #4f46e5;
            margin-bottom: 10px;
        }
        .title {
            color: #1f2937;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
        }
        .verification-box {
            background-color: #fef3c7;
            border: 2px solid #f59e0b;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .cta-button {
            display: inline-block;
            background-color: #10b981;
            color: white;
            padding: 15px 35px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            font-size: 16px;
            margin: 20px 0;
        }
        .alternative-link {
            background-color: #f3f4f6;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            font-size: 14px;
            word-break: break-all;
        }
        .footer {
            text-align: center;
            font-size: 14px;
            color: #6b7280;
            border-top: 1px solid #e5e7eb;
            padding-top: 20px;
            margin-top: 30px;
        }
        .warning {
            color: #dc2626;
            font-weight: bold;
        }
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🏋️‍♂️ FitSphere</div>
            <h1 class="title">Potwierdź swój adres email</h1>
        </div>

        <div class="content">
            <p>Cześć {{ $user->name }}!</p>

            <p>Aby ukończyć proces rejestracji w FitSphere, musisz potwierdzić swój adres email. To pomoże nam upewnić się, że to rzeczywiście Ty utworzyłeś to konto.</p>

            <div class="verification-box">
                <p><strong>⚠️ Ważne!</strong></p>
                <p>Link weryfikacyjny jest ważny przez <strong>60 minut</strong> od momentu wysłania tego emaila.</p>
            </div>

            <div style="text-align: center;">
                <a href="{{ $verificationUrl }}" class="cta-button">✅ Potwierdź Email</a>
            </div>

            <p>Jeśli przycisk powyżej nie działa, skopiuj i wklej poniższy link do przeglądarki:</p>

            <div class="alternative-link">
                {{ $verificationUrl }}
            </div>

            <p><strong>Co się stanie po weryfikacji?</strong></p>
            <ul>
                <li>✅ Twoje konto zostanie w pełni aktywowane</li>
                <li>🎯 Będziesz mógł korzystać ze wszystkich funkcji FitSphere</li>
                <li>📧 Otrzymasz dostęp do powiadomień email</li>
                <li>🏋️‍♂️ Możesz rozpocząć swoją podróż fitness!</li>
            </ul>

            <p class="warning">⚠️ Jeśli to nie Ty utworzyłeś to konto, możesz zignorować ten email.</p>
        </div>

        <div class="footer">
            <p>Pozdrowienia,<br>Zespół FitSphere</p>
            <p>
                <a href="{{ $appUrl }}">{{ $appUrl }}</a><br>
                Ten email został wysłany automatycznie - prosimy nie odpowiadać.
            </p>
        </div>
    </div>
</body>
</html> 