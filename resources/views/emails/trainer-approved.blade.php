@extends('emails.layout')

@section('title', 'Konto trenera zatwierdzone - FitSphere')
@section('email-title', '🎉 Zostałeś zatwierdzony jako trener!')

@section('content')
    <p>Witaj {{ $trainer->name }},</p>
    
    <div class="success-box">
        <p><strong>✅ Gratulacje!</strong></p>
        <p>Twoje konto trenera w FitSphere zostało zatwierdzone przez administratora.</p>
    </div>

    <div class="highlight-box">
        <p><strong>🚀 Od teraz możesz:</strong></p>
        <ul>
            <li>🔐 Logować się na swoje konto trenera</li>
            <li>📝 Tworzyć artykuły i porady treningowe</li>
            <li>👥 Budować swoją bazę klientów</li>
            <li>🎯 Korzystać ze wszystkich funkcji dostępnych dla trenerów</li>
            <li>📊 Analizować postępy swoich podopiecznych</li>
            <li>💬 Komunikować się z klientami</li>
        </ul>
    </div>

    <p>Dziękujemy za dołączenie do naszego zespołu trenerów! Razem możemy pomóc większej liczbie osób osiągnąć ich cele zdrowotne i fitness.</p>

    <div class="text-center">
        <a href="{{ $dashboardUrl }}" class="cta-button">🎯 Przejdź do Panelu Trenera</a>
    </div>

    <div class="info-box">
        <p><strong>💡 Pierwsze kroki:</strong></p>
        <ul>
            <li>📝 Uzupełnij swój profil trenera</li>
            <li>📸 Dodaj zdjęcie profilowe</li>
            <li>💪 Opisz swoje specjalizacje</li>
            <li>🎯 Ustal swoje cele i ofertę</li>
        </ul>
    </div>

    <p>Jeśli masz jakiekolwiek pytania dotyczące platformy, nasz zespół wsparcia jest zawsze gotowy do pomocy!</p>
@endsection 