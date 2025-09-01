@extends('emails.layout')

@section('title', 'Verify Your Email Address')
@section('email-title')
    📧 Verify Your Email Address
@endsection

@section('content')
    <p>Hi {{ $user->name }}!</p>

    <p>Thank you for registering with FitSphere! To complete your registration and start using all platform features, please verify your email address.</p>

    <div class="info-box">
        <p><strong>⏰ Important:</strong> This verification link is valid for <strong>60 minutes</strong> from the time it was sent.</p>
    </div>

    <div class="text-center">
        <a href="{{ $verificationUrl }}" class="cta-button">✅ Verify Email Address</a>
    </div>

    <p>If the button above doesn't work, copy and paste the following link into your browser:</p>

    <div class="alternative-link">
        {{ $verificationUrl }}
    </div>

    <div class="highlight-box">
        <p><strong>🎯 After verification you will be able to:</strong></p>
        <ul>
            <li>🔐 Access all platform features</li>
            <li>👨‍🏫 Book sessions with trainers</li>
            <li>📊 Use fitness calculators</li>
            <li>💬 Comment on posts</li>
            <li>📧 Receive important notifications</li>
        </ul>
    </div>

    <div class="warning-box">
        <p><strong>🔒 Security:</strong> If you didn't create this account, please ignore this email or contact our support team.</p>
    </div>

    <p>Welcome to the FitSphere community!</p>
@endsection