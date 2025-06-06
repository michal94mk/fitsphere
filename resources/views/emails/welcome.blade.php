@extends('emails.layout')

@section('title', 'Witaj w FitSphere')
@section('email-title', 'Witaj, {{ $user->name }}!')

@section('content')
    <p>Dziękujemy za dołączenie do społeczności FitSphere! Jesteśmy podekscytowani, że rozpoczynasz swoją przygodę z fitness razem z nami.</p>

    <div class="highlight-box">
        <h3>Co możesz teraz zrobić:</h3>
        <ul>
            <li>🎯 Ustaw swoje cele fitness</li>
            <li>📊 Śledź swoje postępy</li>
            <li>👨‍🏫 Znajdź idealnego trenera</li>
            <li>🏃‍♀️ Dołącz do treningów grupowych</li>
            <li>📈 Analizuj swoje wyniki</li>
            <li>🍎 Planuj zdrowe posiłki</li>
        </ul>
    </div>

    <p>Twoje konto zostało pomyślnie utworzone i możesz rozpocząć korzystanie ze wszystkich funkcji platformy.</p>

    <div class="success-box">
        <p><strong>✅ Konto aktywne!</strong></p>
        <p>Zaloguj się i zacznij swoją transformację już dziś.</p>
    </div>

    <div class="text-center">
        <a href="{{ config('app.url') }}/profile" class="cta-button">🎯 Przejdź do Panelu</a>
    </div>

    <p>Jeśli masz jakiekolwiek pytania, nasz zespół wsparcia jest zawsze gotowy do pomocy!</p>

    <div class="info-box">
        <p><strong>💡 Potrzebujesz pomocy?</strong></p>
        <p>Skontaktuj się z nami przez formularz kontaktowy na stronie.</p>
    </div>
@endsection 