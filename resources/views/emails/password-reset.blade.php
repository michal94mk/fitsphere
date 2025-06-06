@extends('emails.layout')

@section('title', 'Reset hasła - FitSphere')
@section('email-title', '🔐 Reset hasła')

@section('content')
    <p>Cześć {{ $user->name }}!</p>

    <p>Otrzymaliśmy prośbę o zresetowanie hasła do Twojego konta FitSphere. Jeśli to Ty złożyłeś tę prośbę, kliknij przycisk poniżej, aby utworzyć nowe hasło.</p>

    <div class="warning-box">
        <p><strong>🔒 Ważne informacje bezpieczeństwa:</strong></p>
        <ul>
            <li>Jeśli to nie Ty prosiłeś o reset hasła, zignoruj ten email</li>
            <li>Nigdy nie udostępniaj tego linku nikomu</li>
            <li>Po użyciu linku, zostanie on automatycznie dezaktywowany</li>
        </ul>
    </div>

    <div class="info-box">
        <p><strong>⏰ Ten link jest ważny do: {{ $validUntil }}</strong></p>
    </div>

    <div class="text-center">
        <a href="{{ $resetUrl }}" class="cta-button danger">🔑 Zresetuj Hasło</a>
    </div>

    <p>Jeśli przycisk powyżej nie działa, skopiuj i wklej poniższy link do przeglądarki:</p>

    <div class="alternative-link">
        {{ $resetUrl }}
    </div>

    <div class="highlight-box">
        <p><strong>Co się stanie po kliknięciu w link?</strong></p>
        <ul>
            <li>🌐 Zostaniesz przekierowany na bezpieczną stronę FitSphere</li>
            <li>🔐 Będziesz mógł wprowadzić nowe hasło</li>
            <li>✅ Twoje konto zostanie zabezpieczone nowym hasłem</li>
            <li>📧 Otrzymasz potwierdzenie zmiany hasła</li>
        </ul>
    </div>

    <p class="text-warning">⚠️ Jeśli nie prosiłeś o reset hasła, skontaktuj się z naszym zespołem wsparcia.</p>
@endsection 