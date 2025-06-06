<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;
use Illuminate\Support\Facades\Mail;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Komenda do testowania emaili Brevo
Artisan::command('test:email {email}', function (string $email) {
    $this->info('🧪 Testuję konfigurację Brevo...');
    
    try {
        Mail::raw('🎯 Test email z FitSphere przez Brevo SMTP!' . PHP_EOL . 
                   '✅ Konfiguracja działa poprawnie.' . PHP_EOL .
                   '📅 Data: ' . now()->format('Y-m-d H:i:s'), 
            function ($message) use ($email) {
                $message->to($email)
                        ->subject('✅ Test Brevo SMTP - FitSphere')
                        ->from(config('mail.from.address'), config('mail.from.name'));
            }
        );
        
        $this->info("✅ Email wysłany na: {$email}");
        $this->info('📧 Sprawdź także folder SPAM!');
        
    } catch (\Exception $e) {
        $this->error('❌ Błąd: ' . $e->getMessage());
    }
})->purpose('Testuje konfigurację emaili Brevo');
