@extends('emails.layout')

@section('title', 'Password Reset - FitSphere')
@section('email-title', '🔐 Password Reset')

@section('content')
    <p>Hi {{ $user->name }}!</p>

    <p>We received a request to reset your password for your FitSphere account. If you made this request, click the button below to create a new password.</p>

    <div class="warning-box">
        <p><strong>🔒 Important security information:</strong></p>
        <ul>
            <li>If you didn't request a password reset, ignore this email</li>
            <li>Never share this link with anyone</li>
            <li>After using the link, it will be automatically deactivated</li>
        </ul>
    </div>

    <div class="info-box">
        <p><strong>⏰ This link is valid until: {{ $validUntil }}</strong></p>
    </div>

    <div class="text-center">
        <a href="{{ $resetUrl }}" class="cta-button danger">🔑 Reset Password</a>
    </div>

    <p>If the button above doesn't work, copy and paste the following link into your browser:</p>

    <div class="alternative-link">
        {{ $resetUrl }}
    </div>

    <div class="highlight-box">
        <p><strong>What happens after clicking the link?</strong></p>
        <ul>
            <li>🌐 You'll be redirected to the secure FitSphere page</li>
            <li>🔐 You'll be able to enter a new password</li>
            <li>✅ Your account will be secured with the new password</li>
            <li>📧 You'll receive confirmation of the password change</li>
        </ul>
    </div>

    <p class="text-warning">⚠️ If you didn't request a password reset, contact our support team.</p>
@endsection