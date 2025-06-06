@extends('emails.layout')

@section('title', 'Formularz kontaktowy - FitSphere')
@section('email-title', '📧 Nowa wiadomość z formularza kontaktowego')

@section('content')
    <p>Otrzymałeś nową wiadomość z formularza kontaktowego na stronie <strong>FitSphere</strong>.</p>

    <div class="info-box">
        <p><strong>👤 Dane nadawcy:</strong></p>
        <ul>
            <li><strong>Imię:</strong> {{ $contactData['name'] }}</li>
            <li><strong>Email:</strong> {{ $contactData['email'] }}</li>
        </ul>
    </div>

    <div class="highlight-box">
        <p><strong>💬 Treść wiadomości:</strong></p>
        <p>{{ $contactData['message'] }}</p>
    </div>

    <div class="text-center">
        <a href="mailto:{{ $contactData['email'] }}" class="cta-button">📧 Odpowiedz bezpośrednio</a>
    </div>

    <p class="text-muted">Ta wiadomość została wysłana automatycznie z formularza kontaktowego FitSphere.</p>
@endsection
