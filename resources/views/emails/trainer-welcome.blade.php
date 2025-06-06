@extends('emails.layout')

@section('title', 'Witaj w FitSphere jako Trener!')
@section('email-title', 'Witaj, {{ $trainer->name }}!')

@section('content')
    <p>Gratulujemy dołączenia do społeczności trenerów FitSphere! Jesteśmy podekscytowani, że chcesz dzielić się swoją wiedzą i pomagać innym w osiąganiu celów fitness.</p>

    <div class="info-box">
        <p><strong>⏳ Ważne: Oczekiwanie na zatwierdzenie</strong></p>
        <p>Twoje konto trenerskie zostało utworzone, ale <strong>wymaga zatwierdzenia przez administratora</strong> przed rozpoczęciem przyjmowania rezerwacji.</p>
        <p>Otrzymasz email powiadomienia, gdy Twoje konto zostanie zatwierdzone.</p>
    </div>

    <div class="highlight-box">
        <p><strong>🚀 Co możesz zrobić już teraz:</strong></p>
        <ul>
            <li>📧 <strong>Potwierdź swój adres email</strong> (sprawdź skrzynkę)</li>
            <li>📝 Przygotuj opis swojej oferty treningowej</li>
            <li>🎯 Zaplanuj swoje specjalizacje</li>
            <li>📚 Zapoznaj się z platformą</li>
            <li>💡 Przygotuj materiały dla klientów</li>
            <li>📸 Dodaj profesjonalne zdjęcie profilowe</li>
        </ul>
    </div>

    <div class="success-box">
        <p><strong>💪 Po zatwierdzeniu będziesz mógł/mogła:</strong></p>
        <ul>
            <li>👥 <strong>Przyjmować rezerwacje</strong> od klientów</li>
            <li>📅 Zarządzać swoim kalendarzem</li>
            <li>💬 Komunikować się z klientami</li>
            <li>📊 Śledzić swoje statystyki</li>
            <li>✍️ Publikować artykuły i porady</li>
            <li>🎯 Budować swoją bazę klientów</li>
        </ul>
    </div>

    <div class="highlight-box">
        <p><strong>📋 Twoja specjalizacja:</strong> {{ $trainer->specialization }}</p>
    </div>

    <div class="text-center">
        <a href="{{ config('app.url') }}/login" class="cta-button">🔐 Zaloguj się do Platformy</a>
    </div>

    <p>Jeśli masz jakiekolwiek pytania dotyczące procesu zatwierdzania lub platformy, nasz zespół wsparcia jest zawsze gotowy do pomocy!</p>

    <div class="info-box">
        <p><strong>💡 Wskazówka:</strong> Dodaj nasz adres email do zaufanych nadawców, aby nie przegapić powiadomienia o zatwierdzeniu konta!</p>
    </div>
@endsection 