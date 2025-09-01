@extends('emails.layout')

@section('title', 'Welcome to FitSphere as a Trainer!')
@section('email-title')
    Welcome, {{ $trainer->name }}!
@endsection

@section('content')
    <p>Congratulations on joining the FitSphere trainer community! We're excited that you want to share your knowledge and help others achieve their fitness goals.</p>

    <div class="warning-box">
        <p><strong>🔐 Important:</strong> First, please verify your email address to activate your trainer account.</p>
    </div>

    <div class="text-center">
        <a href="{{ $verificationUrl }}" class="cta-button">✅ Verify Email & Activate Account</a>
    </div>

    <p>If the button above doesn't work, copy and paste this link into your browser:</p>
    <div class="alternative-link">{{ $verificationUrl }}</div>

    <div class="info-box">
        <p><strong>⏳ Next Steps:</strong></p>
        <p>After email verification, your trainer account will <strong>require approval by an administrator</strong> before you can start accepting bookings.</p>
        <p>You will receive an email notification when your account is approved.</p>
    </div>

    <div class="highlight-box">
        <p><strong>🚀 While waiting for approval, you can:</strong></p>
        <ul>
            <li>📝 Complete your profile and training description</li>
            <li>🎯 Add your specializations and experience</li>
            <li>📚 Get familiar with the platform</li>
            <li>💡 Prepare materials for future clients</li>
            <li>📸 Add a professional profile photo</li>
        </ul>
    </div>

    <div class="success-box">
        <p><strong>💪 After approval you will be able to:</strong></p>
        <ul>
            <li>👥 <strong>Accept bookings</strong> from clients</li>
            <li>📅 Manage your calendar</li>
            <li>💬 Communicate with clients</li>
            <li>📊 Track your statistics</li>
            <li>✍️ Publish articles and tips</li>
            <li>🎯 Build your client base</li>
        </ul>
    </div>

    <div class="highlight-box">
        <p><strong>📋 Your specialization:</strong> {{ $trainer->specialization }}</p>
    </div>

    <div class="info-box">
        <p><strong>⏰ Important:</strong> This verification link is valid for <strong>60 minutes</strong>.</p>
    </div>

    <p>If you have any questions about the verification or approval process, our support team is always ready to help!</p>
@endsection