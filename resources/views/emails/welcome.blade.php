@extends('emails.layout')

@section('title', 'Welcome to FitSphere')
@section('email-title')
    Welcome, {{ $user->name }}!
@endsection

@section('content')
    <p>Thank you for joining the FitSphere community! We're excited that you're starting your fitness journey with us.</p>

    <div class="warning-box">
        <p><strong>🔐 Important:</strong> To activate your account and access all features, please verify your email address first.</p>
    </div>

    <div class="text-center">
        <a href="{{ $verificationUrl }}" class="cta-button">✅ Verify Email & Get Started</a>
    </div>

    <p>If the button above doesn't work, copy and paste this link into your browser:</p>
    <div class="alternative-link">{{ $verificationUrl }}</div>

    <div class="highlight-box">
        <h3>After verification, you can:</h3>
        <ul>
            <li>🎯 Set your fitness goals</li>
            <li>📊 Track your progress</li>
            <li>👨‍🏫 Find the perfect trainer</li>
            <li>🏃‍♀️ Join group workouts</li>
            <li>📈 Analyze your results</li>
            <li>🍎 Plan healthy meals</li>
        </ul>
    </div>

    <div class="info-box">
        <p><strong>⏰ Important:</strong> This verification link is valid for <strong>60 minutes</strong>.</p>
    </div>

    <p>If you have any questions, our support team is always ready to help!</p>

    <div class="success-box">
        <p><strong>🚀 Ready to transform your fitness journey?</strong></p>
        <p>Click the verification button above to get started!</p>
    </div>
@endsection 