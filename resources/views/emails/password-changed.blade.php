<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Hasło zostało zmienione - FitSphere</title>
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
        .success-notice {
            background-color: #dcfce7;
            border: 2px solid #10b981;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: center;
        }
        .change-details {
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .security-tips {
            background-color: #fef3c7;
            border-left: 4px solid #f59e0b;
            padding: 20px;
            margin: 20px 0;
        }
        .emergency-notice {
            background-color: #fee2e2;
            border: 2px solid #dc2626;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
        }
        .cta-button {
            display: inline-block;
            background-color: #4f46e5;
            color: white;
            padding: 12px 30px;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 20px 0;
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
            <h1 class="title">🔐 Hasło zostało zmienione</h1>
        </div>

        <div class="content">
            <p>Cześć {{ $user->name }}!</p>

            <div class="success-notice">
                <p><strong>✅ Twoje hasło zostało pomyślnie zmienione!</strong></p>
                <p>Twoje konto jest teraz zabezpieczone nowym hasłem.</p>
            </div>

            <div class="change-details">
                <p><strong>📋 Szczegóły zmiany:</strong></p>
                <ul>
                    <li><strong>Konto:</strong> {{ $user->email }}</li>
                    <li><strong>Data i czas:</strong> {{ $changeTime }}</li>
                    <li><strong>Adres IP:</strong> {{ request()->ip() ?? 'Nieznany' }}</li>
                </ul>
            </div>

            <p>Jeśli to Ty zmieniłeś hasło, możesz zignorować ten email. Twoje konto jest bezpieczne.</p>

            <div class="emergency-notice">
                <p class="warning"><strong>⚠️ Czy to nie Ty zmieniłeś hasło?</strong></p>
                <p>Jeśli nie zmieniałeś hasła, Twoje konto mogło zostać skompromitowane. <strong>Natychmiast:</strong></p>
                <ul>
                    <li>🔒 Zaloguj się i zmień hasło ponownie</li>
                    <li>📱 Sprawdź aktywne sesje w ustawieniach konta</li>
                    <li>📞 Skontaktuj się z naszym zespołem wsparcia</li>
                </ul>
                <div style="text-align: center;">
                    <a href="{{ $appUrl }}/login" class="cta-button">🔐 Zaloguj się teraz</a>
                </div>
            </div>

            <div class="security-tips">
                <p><strong>💡 Wskazówki bezpieczeństwa:</strong></p>
                <ul>
                    <li>🔐 Używaj silnych, unikalnych haseł</li>
                    <li>🔄 Regularnie zmieniaj hasła</li>
                    <li>📱 Rozważ włączenie uwierzytelniania dwuskładnikowego</li>
                    <li>🚫 Nigdy nie udostępniaj swoich danych logowania</li>
                </ul>
            </div>

            <p>Dziękujemy za dbanie o bezpieczeństwo swojego konta FitSphere!</p>
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