<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset hasła - FitSphere</title>
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
        .security-notice {
            background-color: #fee2e2;
            border: 2px solid #dc2626;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .expiry-notice {
            background-color: #fef3c7;
            border: 2px solid #f59e0b;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .cta-button {
            display: inline-block;
            background-color: #dc2626;
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
            <h1 class="title">🔐 Reset hasła</h1>
        </div>

        <div class="content">
            <p>Cześć {{ $user->name }}!</p>

            <p>Otrzymaliśmy prośbę o zresetowanie hasła do Twojego konta FitSphere. Jeśli to Ty złożyłeś tę prośbę, kliknij przycisk poniżej, aby utworzyć nowe hasło.</p>

            <div class="security-notice">
                <p><strong>🔒 Ważne informacje bezpieczeństwa:</strong></p>
                <ul>
                    <li>Jeśli to nie Ty prosiłeś o reset hasła, zignoruj ten email</li>
                    <li>Nigdy nie udostępniaj tego linku nikomu</li>
                    <li>Po użyciu linku, zostanie on automatycznie dezaktywowany</li>
                </ul>
            </div>

            <div class="expiry-notice">
                <p><strong>⏰ Ten link jest ważny do: {{ $validUntil }}</strong></p>
            </div>

            <div style="text-align: center;">
                <a href="{{ $resetUrl }}" class="cta-button">🔑 Zresetuj Hasło</a>
            </div>

            <p>Jeśli przycisk powyżej nie działa, skopiuj i wklej poniższy link do przeglądarki:</p>

            <div class="alternative-link">
                {{ $resetUrl }}
            </div>

            <p><strong>Co się stanie po kliknięciu w link?</strong></p>
            <ul>
                <li>🌐 Zostaniesz przekierowany na bezpieczną stronę FitSphere</li>
                <li>🔐 Będziesz mógł wprowadzić nowe hasło</li>
                <li>✅ Twoje konto zostanie zabezpieczone nowym hasłem</li>
                <li>📧 Otrzymasz potwierdzenie zmiany hasła</li>
            </ul>

            <p class="warning">⚠️ Jeśli nie prosiłeś o reset hasła, skontaktuj się z naszym zespołem wsparcia.</p>
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