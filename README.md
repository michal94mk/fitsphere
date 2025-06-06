<p align="center">
<h1 align="center">FitSphere</h1>
<p align="center">Fitness application built with Laravel, Livewire, Tailwind CSS and Alpine.js</p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About FitSphere

FitSphere is a comprehensive fitness application built with Laravel, Livewire, Tailwind CSS, and Alpine.js. It provides tools for fitness enthusiasts to track workouts, plan meals, calculate nutrition, and connect with trainers.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

You may also try the [Laravel Bootcamp](https://bootcamp.laravel.com), where you will be guided through building a modern Laravel application from scratch.

If you don't feel like reading, [Laracasts](https://laracasts.com) can help. Laracasts contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Features

- Modern responsive design using Tailwind CSS
- Multi-language support (English and Polish)
- Advanced fitness tracking system
- User management and role-based permissions
- Nutrition Calculator with automated recipe translations
  - Uses DeepL API for accurate recipe translations (English ↔ Polish)
  - Automatically translates recipes to Polish when using Polish language
  - Bidirectional translation support for recipe searches
  - Manual translation toggle for user control
  - Caching mechanism to reduce API calls
- Meal planning system
- Contact form with email notifications
- Social media integration

## Database Seeders

The application comes with several database seeders to populate the database with sample data:

- **UserSeeder** - Creates 8 sample users (1 admin and 7 regular users)
- **TrainerSeeder** - Adds 3 fitness trainers with different specializations
- **FitnessContentSeeder** - Populates the database with fitness-related content:
  - 10 fitness categories
  - 30+ blog posts about training, nutrition, and supplements
  - Multilingual content (English and Polish translations)
  - Sample comments from users

To seed the database, run:
```bash
# Run all seeders
php artisan db:seed

# Run a specific seeder
php artisan db:seed --class=UserSeeder
```

## Configuration

### Email Configuration (Brevo)

FitSphere uses Brevo (formerly Sendinblue) for sending emails in production. The application includes:

- **Welcome emails** - sent after user registration
- **Email verification** - for account security
- **Password reset emails** - for forgotten passwords
- **Password change notifications** - for security alerts

#### Brevo Setup:

1. Create account at [Brevo](https://www.brevo.com/)
2. Get your SMTP credentials from Brevo dashboard
3. **Verify sender email/domain** in Brevo panel under "Senders & IP"
4. Add to your `.env` file:
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp-relay.brevo.com
   MAIL_PORT=587
   MAIL_USERNAME=your-brevo-email@domain.com
   MAIL_PASSWORD=your-brevo-smtp-key
   MAIL_ENCRYPTION=tls
   
   # Option 1: Own domain (requires DNS verification)
   MAIL_FROM_ADDRESS="noreply@fitsphere.com"
   MAIL_FROM_NAME="FitSphere"
   
   # Option 2: Same as USERNAME (easier setup)
   # MAIL_FROM_ADDRESS="your-brevo-email@domain.com"
   # MAIL_FROM_NAME="FitSphere"
   ```

**📝 Note:** `MAIL_FROM_ADDRESS` doesn't need to be a real mailbox, but must be verified in Brevo panel.

#### Queue System:

FitSphere uses Laravel queues to send emails asynchronously. This prevents blocking the web response while emails are being sent.

**Start the queue worker:**
```bash
php artisan queue:work database --sleep=3 --tries=3 --timeout=60
```

**Queue commands:**
```bash
# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all

# Clear failed jobs
php artisan queue:flush
```

#### Usage in Code:

```php
use App\Services\EmailService;

// In controllers or Livewire components
$emailService = app(EmailService::class);

// Send emails (queued automatically)
$emailService->sendWelcomeEmail($user);
$emailService->sendEmailVerification($user);
$emailService->sendPasswordResetEmail($user, $token);
```

## Queue System Explained

### 🔄 **How Laravel Queues Work**

FitSphere uses Laravel's queue system to handle email sending asynchronously. Here's how it works:

#### **Without Queues (Synchronous):**
```
User clicks "Register" → App creates user → App sends email (3-5 seconds) → User sees success page
```
**Problem:** User waits for email to be sent before seeing response.

#### **With Queues (Asynchronous):**
```
User clicks "Register" → App creates user → App queues email job → User sees success page immediately
                                        ↓
                           Queue worker picks up job → Sends email in background
```
**Benefit:** Instant response, better user experience.

### 🏗️ **Queue Architecture in FitSphere**

```
EmailService.sendWelcomeEmail($user)
           ↓
    Mail::to($user)->queue(new WelcomeEmail($user))
           ↓
    Job stored in 'jobs' table
           ↓
    Queue worker processes job
           ↓
    Email sent via Brevo SMTP
```

### 📊 **Database Tables Used**

- **`jobs`** - Pending jobs waiting to be processed
- **`failed_jobs`** - Jobs that failed after max retries
- **`job_batches`** - Grouped jobs (if using job batching)

### ⚙️ **Queue Configuration**

**Queue Connection** (`config/queue.php`):
```php
'default' => env('QUEUE_CONNECTION', 'database'), // Uses database driver
```

**Email Service** (`app/Services/EmailService.php`):
```php
// Queues email instead of sending immediately
Mail::to($user->email)->queue(new WelcomeEmail($user));
```

**Mailable Classes** (`app/Mail/`):
```php
class WelcomeEmail extends Mailable implements ShouldQueue
{
    use Queueable; // Enables queueing
    
    public function __construct(User $user)
    {
        $this->onQueue('emails'); // Use 'emails' queue
    }
}
```

### 🚀 **Running Queue Workers**

**Development:**
```bash
php artisan queue:work database --sleep=3 --tries=3 --timeout=60
```

**Production (with Supervisor):**
```ini
[program:fitsphere-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /path/to/fitsphere/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
numprocs=2
user=www-data
```

### 🔍 **Monitoring Queues**

```bash
# See failed jobs
php artisan queue:failed

# Retry specific failed job
php artisan queue:retry 5

# Retry all failed jobs
php artisan queue:retry all

# Clear all failed jobs
php artisan queue:flush

# Check job status in database
SELECT * FROM jobs;
SELECT * FROM failed_jobs;
```

### 🛠️ **Queue Job Lifecycle**

1. **Created** - Job added to `jobs` table
2. **Processing** - Worker picks up job
3. **Success** - Job completed, removed from table
4. **Failed** - Job moved to `failed_jobs` after max retries

### 🔧 **Error Handling**

If email sending fails:
- **Retry 3 times** (--tries=3)
- **Wait 3 seconds** between jobs (--sleep=3)
- **Log errors** to `storage/logs/laravel.log`
- **Move to failed_jobs** after max retries

### Translation Setup

The nutrition calculator uses DeepL API for recipe translations. To set it up:

1. Visit [DeepL](https://www.deepl.com/) and create an account
2. Get a free API key from the DeepL dashboard
3. Add these variables to your `.env` file:
   ```
   DEEPL_API_KEY=your_api_key_here
   DEEPL_FREE_API=true  # Set to false if using DeepL Pro API
   ```

### Spoonacular API

The nutrition calculator and meal planner use Spoonacular API for recipe data:

1. Create an account at [Spoonacular Food API](https://spoonacular.com/food-api)
2. Get your API key from the dashboard
3. Add the API key to your `.env` file:
   ```
   SPOONACULAR_API_KEY=your_spoonacular_api_key_here
   ```

## Konfiguracja Emaili (Brevo SMTP)

Aplikacja używa Brevo (dawniej Sendinblue) do wysyłania emaili. Wszystkie emaile są kolejkowane i wysyłane asynchronicznie.

### Dostępne typy emaili:

1. **Email powitalny** - wysyłany po rejestracji użytkownika
2. **Email weryfikacyjny** - do potwierdzenia adresu email
3. **Email resetowania hasła** - do resetowania hasła
4. **Powiadomienie o zmianie hasła** - po zmianie hasła
5. **Powiadomienie o zatwierdzeniu trenera** - gdy admin zatwierdzi trenera
6. **Potwierdzenie subskrypcji** - po zapisaniu się do newslettera
7. **Email z formularza kontaktowego** - wiadomości od użytkowników

### Konfiguracja .env:

```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your-brevo-email@example.com
MAIL_PASSWORD=your-brevo-smtp-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="FitSphere"
```

### Użycie EmailService:

```php
use App\Services\EmailService;

$emailService = new EmailService();

// Email powitalny
$emailService->sendWelcomeEmail($user);

// Email zatwierdzenia trenera  
$emailService->sendTrainerApprovedEmail($trainer);

// Email subskrypcji
$emailService->sendSubscriptionConfirmationEmail($user, 'premium');

// Email kontaktowy
$emailService->sendContactFormEmail($name, $email, $message);
```

### Template emaili:

Wszystkie template emaili znajdują się w `resources/views/emails/`:
- `welcome.blade.php` - email powitalny
- `verify-email.blade.php` - weryfikacja email
- `password-reset.blade.php` - reset hasła
- `password-changed.blade.php` - zmiana hasła
- `trainer-approved.blade.php` - zatwierdzenie trenera
- `subscription-confirmation.blade.php` - potwierdzenie subskrypcji
- `contact.blade.php` - formularz kontaktowy

### Testowanie konfiguracji:

```php
$emailService = new EmailService();
$result = $emailService->testEmailConfiguration();

if ($result) {
    echo "Konfiguracja Brevo działa poprawnie!";
} else {
    echo "Błąd konfiguracji Brevo - sprawdź logi.";
}
```

### Kolejkowanie emaili:

Wszystkie emaile są automatycznie kolejkowane w queue `emails`. Aby przetworzyć kolejkę:

```bash
php artisan queue:work --queue=emails
```

### Monitorowanie:

Logi emaili są zapisywane w standardowych logach Laravel. Sprawdź `storage/logs/laravel.log` w przypadku problemów.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).