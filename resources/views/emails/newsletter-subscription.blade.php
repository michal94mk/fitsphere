@extends('emails.layout')

@section('title', 'Subskrypcja newslettera - FitSphere')
@section('email-title', '📧 Dziękujemy za subskrypcję!')

@section('content')
    <p>Cześć {{ $subscriberName }}!</p>
    
    <p>Dziękujemy za zapisanie się do naszego newslettera na adres: <strong>{{ $subscriberEmail }}</strong></p>
    
    <div class="success-box">
        <p><strong>✅ Subskrypcja potwierdzona!</strong></p>
        <p>Będziesz otrzymywać nasze najnowsze aktualizacje i ofertas specjalne.</p>
    </div>
    
    <p>Będziemy przesyłać Ci najnowsze informacje o:</p>
    <ul>
        <li>🏋️ Nowych treningach i ćwiczeniach</li>
        <li>🥗 Planach żywieniowych i przepisach</li>
        <li>💪 Wskazówkach fitness od ekspertów</li>
        <li>🎯 Promocjach i oferach specjalnych</li>
        <li>📊 Nowych funkcjach platformy</li>
    </ul>
    
    <div class="highlight-box">
        <p><strong>💡 Wskazówka:</strong> Dodaj nasz adres email do zaufanych nadawców, aby nasze wiadomości nie trafiały do folderu SPAM.</p>
    </div>
    
    <div class="text-center">
        <a href="{{ config('app.url') }}/profile" class="cta-button">🎯 Przejdź do Panelu</a>
    </div>
    
    <p class="text-muted">Możesz zrezygnować z subskrypcji w każdej chwili klikając link w stopce emaila.</p>
@endsection 