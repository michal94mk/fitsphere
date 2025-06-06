@extends('emails.layout')

@section('title', 'Potwierdzenie subskrypcji - FitSphere')
@section('email-title', '🎉 Potwierdzenie subskrypcji')

@section('content')
    <p>Dziękujemy {{ $user->name }} za subskrypcję <strong>{{ $subscriptionType }}</strong>!</p>
    
    <div class="success-box">
        <p><strong>✅ Subskrypcja aktywna!</strong></p>
        <p>Twoja subskrypcja została pomyślnie aktywowana i możesz już korzystać ze wszystkich korzyści.</p>
    </div>

    <div class="highlight-box">
        <p><strong>🚀 Co masz teraz dostępne:</strong></p>
        <ul>
            <li>🎯 Pełny dostęp do wszystkich funkcji</li>
            <li>💪 Spersonalizowane plany treningowe</li>
            <li>🥗 Zaawansowane planowanie posiłków</li>
            <li>📊 Szczegółowe analizy postępów</li>
            <li>👨‍🏫 Konsultacje z trenerami</li>
        </ul>
    </div>

    <div class="text-center">
        <a href="{{ config('app.url') }}/profile" class="cta-button">🎯 Przejdź do Panelu</a>
    </div>

    <p>Możesz teraz korzystać ze wszystkich funkcji dostępnych w ramach Twojej subskrypcji <strong>{{ $subscriptionType }}</strong>.</p>
@endsection 