@extends('emails.layout')

@section('title', 'Welcome to FitSphere')
@section('email-title', 'Welcome, {{ $user->name }}!')

@section('content')
    <p>Thank you for joining the FitSphere community! We're excited that you're starting your fitness journey with us.</p>

    <div class="highlight-box">
        <h3>What you can do now:</h3>
        <ul>
            <li>🎯 Set your fitness goals</li>
            <li>📊 Track your progress</li>
            <li>👨‍🏫 Find the perfect trainer</li>
            <li>🏃‍♀️ Join group workouts</li>
            <li>📈 Analyze your results</li>
            <li>🍎 Plan healthy meals</li>
        </ul>
    </div>

    <p>Your account has been successfully created and you can start using all platform features.</p>

    <div class="success-box">
        <p><strong>✅ Account Active!</strong></p>
        <p>Log in and start your transformation today.</p>
    </div>

    <div class="text-center">
        <a href="{{ config('app.url') }}/profile" class="cta-button">🎯 Go to Dashboard</a>
    </div>

    <p>If you have any questions, our support team is always ready to help!</p>

    <div class="info-box">
        <p><strong>💡 Need help?</strong></p>
        <p>Contact us through the contact form on our website.</p>
    </div>
@endsection 