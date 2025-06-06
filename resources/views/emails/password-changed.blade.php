@extends('emails.layout')

@section('title', 'Hasło zostało zmienione - FitSphere')
@section('email-title', '🔐 Hasło zostało zmienione')

@section('content')
    <p>Cześć {{ $user->name }}!</p>

    <div class="success-box">
        <p><strong>✅ Twoje hasło zostało pomyślnie zmienione!</strong></p>
        <p>Twoje konto jest teraz zabezpieczone nowym hasłem.</p>
    </div>

    <div class="highlight-box">
        <p><strong>📋 Szczegóły zmiany:</strong></p>
        <ul>
            <li><strong>Konto:</strong> {{ $user->email }}</li>
            <li><strong>Data i czas:</strong> {{ $changeTime }}</li>
            <li><strong>Adres IP:</strong> {{ request()->ip() ?? 'Nieznany' }}</li>
        </ul>
    </div>

    <p>Jeśli to Ty zmieniłeś hasło, możesz zignorować ten email. Twoje konto jest bezpieczne.</p>

    <div class="warning-box">
        <p class="text-warning"><strong>⚠️ Czy to nie Ty zmieniłeś hasło?</strong></p>
        <p>Jeśli nie zmieniałeś hasła, Twoje konto mogło zostać skompromitowane. <strong>Natychmiast:</strong></p>
        <ul>
            <li>🔒 Zaloguj się i zmień hasło ponownie</li>
            <li>📱 Sprawdź aktywne sesje w ustawieniach konta</li>
            <li>📞 Skontaktuj się z naszym zespołem wsparcia</li>
        </ul>
        <div class="text-center">
            <a href="{{ config('app.url') }}/login" class="cta-button danger">🔐 Zaloguj się teraz</a>
        </div>
    </div>

    <div class="info-box">
        <p><strong>💡 Wskazówki bezpieczeństwa:</strong></p>
        <ul>
            <li>🔐 Używaj silnych, unikalnych haseł</li>
            <li>🔄 Regularnie zmieniaj hasła</li>
            <li>📱 Rozważ włączenie uwierzytelniania dwuskładnikowego</li>
            <li>🚫 Nigdy nie udostępniaj swoich danych logowania</li>
        </ul>
    </div>

    <p>Dziękujemy za dbanie o bezpieczeństwo swojego konta FitSphere!</p>
@endsection 