<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Twoje konto trenera zostało zatwierdzone</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .header {
            background-color: #4F46E5;
            color: white;
            padding: 20px;
            text-align: center;
        }
        .content {
            padding: 20px;
            background-color: #f9f9f9;
        }
        .footer {
            text-align: center;
            padding: 20px;
            font-size: 12px;
            color: #666;
        }
        .button {
            display: inline-block;
            background-color: #4F46E5;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin-top: 20px;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Twoje konto trenera zostało zatwierdzone!</h1>
        </div>
        
        <div class="content">
            <p>Witaj {{ $trainer->name }},</p>
            
            <p>Z przyjemnością informujemy, że Twoje konto trenera w serwisie Zdrowie & Fitness Blog zostało zatwierdzone przez administratora.</p>
            
            <p>Od teraz możesz:</p>
            <ul>
                <li>Logować się na swoje konto</li>
                <li>Tworzyć artykuły i porady treningowe</li>
                <li>Budować swoją bazę klientów</li>
                <li>Korzystać ze wszystkich funkcji dostępnych dla trenerów</li>
            </ul>
            
            <p>Dziękujemy za dołączenie do naszego zespołu trenerów! Razem możemy pomóc większej liczbie osób osiągnąć ich cele zdrowotne i fitness.</p>
            
            <a href="{{ route('home') }}" class="button">Przejdź do strony głównej</a>
        </div>
        
        <div class="footer">
            <p>&copy; {{ date('Y') }} Zdrowie & Fitness Blog. Wszystkie prawa zastrzeżone.</p>
        </div>
    </div>
</body>
</html> 