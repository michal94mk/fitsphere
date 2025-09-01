@extends('emails.layout')

@section('title', 'Welcome to FitSphere as a Trainer!')
@section('email-title', 'Welcome, {{ $trainer->name }}!')

@section('content')
    <p>Congratulations on joining the FitSphere trainer community! We're excited that you want to share your knowledge and help others achieve their fitness goals.</p>

    <div class="info-box">
        <p><strong>⏳ Important: Waiting for approval</strong></p>
        <p>Your trainer account has been created, but <strong>requires approval by an administrator</strong> before you can start accepting bookings.</p>
        <p>You will receive an email notification when your account is approved.</p>
    </div>

    <div class="highlight-box">
        <p><strong>🚀 What you can do right now:</strong></p>
        <ul>
            <li>📧 <strong>Verify your email address</strong> (check your inbox)</li>
            <li>📝 Prepare your training offer description</li>
            <li>🎯 Plan your specializations</li>
            <li>📚 Get familiar with the platform</li>
            <li>💡 Prepare materials for clients</li>
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

    <div class="text-center">
        <a href="{{ config('app.url') }}/login" class="cta-button">🔐 Log in to Platform</a>
    </div>

    <p>If you have any questions about the approval process or the platform, our support team is always ready to help!</p>

    <div class="info-box">
        <p><strong>💡 Tip:</strong> Add our email address to trusted senders so you don't miss the account approval notification!</p>
    </div>
@endsection