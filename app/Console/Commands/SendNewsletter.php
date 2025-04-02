<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Subscriber;
use Illuminate\Support\Facades\Mail;
use App\Mail\NewsletterMail;

class SendNewsletter extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'newsletter:send';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Wysyła newsletter do wszystkich subskrybentów';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $subscribers = Subscriber::all();

        foreach ($subscribers as $subscriber) {
            Mail::to($subscriber->email)->send(new NewsletterMail());
        }

        $this->info('Newsletter został wysłany do wszystkich subskrybentów.');

        return 0;
    }
}
