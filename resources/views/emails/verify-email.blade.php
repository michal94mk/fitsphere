@extends('emails.layout')

@section('title', 'Potwierdź swój adres email - FitSphere')
@section('email-title', 'Potwierdź swój adres email')

@section('content')
    <p>Cześć {{ $user->name }}!</p>

    <p>Aby ukończyć proces rejestracji w FitSphere, musisz potwierdzić swój adres email. To pomoże nam upewnić się, że to rzeczywiście Ty utworzyłeś to konto.</p>

    <div class="info-box">
        <p><strong>⚠️ Ważne!</strong></p>
        <p>Link weryfikacyjny jest ważny przez <strong>60 minut</strong> od momentu wysłania tego emaila.</p>
    </div>

    <div class="text-center">
        <a href="{{ $verificationUrl }}" class="cta-button success">✅ Potwierdź Email</a>
    </div>

    <p>Jeśli przycisk powyżej nie działa, skopiuj i wklej poniższy link do przeglądarki:</p>

    <div class="alternative-link">
        {{ $verificationUrl }}
    </div>

    <div class="success-box">
        <p><strong>Co się stanie po weryfikacji?</strong></p>
        <ul>
            <li>✅ Twoje konto zostanie w pełni aktywowane</li>
            <li>🎯 Będziesz mógł korzystać ze wszystkich funkcji FitSphere</li>
            <li>📧 Otrzymasz dostęp do powiadomień email</li>
            <li>🏋️‍♂️ Możesz rozpocząć swoją podróż fitness!</li>
        </ul>
    </div>

    <div class="warning-box">
        <p class="text-warning">⚠️ Jeśli to nie Ty utworzyłeś to konto, możesz zignorować ten email.</p>
    </div>
@endsection 