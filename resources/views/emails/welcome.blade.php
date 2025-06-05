<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Witaj w FitSphere</title>
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
        .welcome-title {
            color: #1f2937;
            font-size: 24px;
            margin-bottom: 20px;
        }
        .content {
            margin-bottom: 30px;
        }
        .highlight {
            background-color: #f3f4f6;
            padding: 20px;
            border-radius: 8px;
            border-left: 4px solid #4f46e5;
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
    </style>
</head>
<body>
    <div class="email-container">
        <div class="header">
            <div class="logo">🏋️‍♂️ FitSphere</div>
            <h1 class="welcome-title">Witaj, {{ $user->name }}!</h1>
        </div>

        <div class="content">
            <p>Dziękujemy za dołączenie do społeczności FitSphere! Jesteśmy podekscytowani, że rozpoczynasz swoją przygodę z fitness razem z nami.</p>

            <div class="highlight">
                <h3>Co możesz teraz zrobić:</h3>
                <ul>
                    <li>🎯 Ustaw swoje cele fitness</li>
                    <li>📊 Śledź swoje postępy</li>
                    <li>👨‍🏫 Znajdź idealnego trenera</li>
                    <li>🏃‍♀️ Dołącz do treningów grupowych</li>
                    <li>📈 Analizuj swoje wyniki</li>
                </ul>
            </div>

            <p>Twoje konto zostało pomyślnie utworzone i możesz rozpocząć korzystanie ze wszystkich funkcji platformy.</p>

            <div style="text-align: center;">
                <a href="{{ $appUrl }}/dashboard" class="cta-button">Przejdź do Panelu</a>
            </div>

            <p>Jeśli masz jakiekolwiek pytania, nasz zespół wsparcia jest zawsze gotowy do pomocy!</p>
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