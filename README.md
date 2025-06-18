# FitSphere

<p align="center">
  <img src="https://img.shields.io/badge/Laravel-11-red?style=flat&logo=laravel" alt="Laravel 12">
  <img src="https://img.shields.io/badge/Livewire-3-blue?style=flat&logo=livewire" alt="Livewire 3">
  <img src="https://img.shields.io/badge/TailwindCSS-3-blue?style=flat&logo=tailwindcss" alt="Tailwind CSS">
  <img src="https://img.shields.io/badge/AlpineJS-3-green?style=flat&logo=alpine.js" alt="Alpine.js">
  <img src="https://img.shields.io/badge/PHP-8.2+-purple?style=flat&logo=php" alt="PHP 8.4+">
</p>

**FitSphere** is a comprehensive fitness and wellness application built with the TALL stack (Tailwind CSS, Alpine.js, Laravel, Livewire). It provides tools for fitness enthusiasts to connect with trainers, track nutrition, plan meals, and access fitness content.

## 🌟 Features

### 👥 User Management
- **Multi-role system**: Regular users, trainers, and administrators
- **User authentication**: Registration, login, email verification
- **Profile management**: Update personal information, photos, passwords
- **Social login**: Google OAuth integration

### 🏋️ Trainer System
- **Trainer profiles**: Detailed profiles with specializations, experience, photos
- **Trainer approval**: Admin approval system for new trainers
- **Reservation system**: Users can book training sessions with trainers
- **Time slot management**: Visual calendar with availability tracking

### 📚 Content Management
- **Blog system**: Fitness articles and tips
- **Categories**: Organized content categorization
- **Comments**: User engagement on posts
- **Multi-language**: English and Polish content support

### 🍎 Nutrition Tools
- **BMI Calculator**: Calculate Body Mass Index
- **Calorie Calculator**: Daily calorie needs based on goals and activity
- **Macro Calculator**: Protein, carbs, and fat recommendations
- **Recipe Search**: Integration with Spoonacular API
- **Recipe Translation**: Automatic Polish translations using DeepL API
- **Nutritional Profiles**: Save and track personal nutrition data

### 🛠 Admin Panel
- **User Management**: Create, edit, and manage users
- **Trainer Management**: Approve trainers, manage profiles
- **Content Management**: Create and edit blog posts and categories
- **Comment Moderation**: Review and manage user comments
- **Dashboard**: Overview of system statistics

### 🌐 Additional Features
- **Multilingual Support**: English and Polish with automatic locale detection
- **Responsive Design**: Mobile-first approach with Tailwind CSS
- **Email System**: Automated emails for registration, verification, notifications
- **Search Functionality**: Find posts, trainers, and content
- **Contact Form**: Direct communication with administrators

## 🚀 Technology Stack

- **Backend**: Laravel 11 with PHP 8.2+
- **Frontend**: Livewire 3, Alpine.js 3, Tailwind CSS 3
- **Database**: MySQL/PostgreSQL
- **Email**: Brevo (Sendinblue) SMTP with queue system
- **File Storage**: Local/S3 compatible storage
- **APIs**: Spoonacular (recipes), DeepL (translations), Google OAuth

## 📋 Requirements

- PHP 8.2 or higher
- Composer 2.5+
- Node.js 18+ & NPM
- MySQL 8.0+ or PostgreSQL 13+
- Redis (recommended for production)
- Web server (Apache/Nginx)
- SSL Certificate (production)

## ⚡ Quick Start

### 1. Clone the Repository
```bash
git clone https://github.com/michal94mk/fitsphere.git
cd fitsphere
```

### 2. Install Dependencies
```bash
# PHP dependencies
composer install

# JavaScript dependencies
npm install
npm run build
```

### 3. Environment Configuration
```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### 4. Database Setup
```bash
# Run migrations
php artisan migrate

# Seed with sample data
php artisan db:seed
```

### 5. Start Development Server
```bash
# Start Laravel server
php artisan serve

# Start queue worker (in separate terminal)
php artisan queue:work
```

Visit `http://localhost:8000` in your browser.

## 🔧 Configuration

### Email Configuration (Brevo)
```env
MAIL_MAILER=smtp
MAIL_HOST=smtp-relay.brevo.com
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-smtp-key
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@fitsphere.com
MAIL_FROM_NAME="FitSphere"
```

### API Configuration
```env
# Spoonacular API (for recipes)
SPOONACULAR_API_KEY=your-spoonacular-key

# DeepL API (for translations)
DEEPL_API_KEY=your-deepl-key

# Google OAuth
GOOGLE_CLIENT_ID=your-google-client-id
GOOGLE_CLIENT_SECRET=your-google-client-secret
```

## 🎯 Field Validation Limits

### User Data
- **Names**: 2-50 characters
- **Email**: max 100 characters
- **Password**: 8-128 characters
- **Specialization**: 3-100 characters

### Content
- **Post Title**: 3-200 characters
- **Post Content**: 10-15,000 characters
- **Post Excerpt**: max 500 characters
- **Category Name**: 2-50 characters
- **Comments**: 3-500 characters
- **Contact Message**: 10-1,000 characters

### Media
- **Profile Photos**: max 1MB, 100x100-1500x1500px
- **Post Images**: max 1MB, JPEG/PNG/WebP formats

## 🧪 Testing

```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Feature

# Run with coverage
php artisan test --coverage
```

## 📦 Database Seeders

The application includes comprehensive seeders:

- **UserSeeder**: Creates admin and sample users
- **TrainerSeeder**: Adds fitness trainers with specializations
- **FitnessContentSeeder**: Populates blog posts, categories, and comments

```bash
# Run all seeders
php artisan db:seed

# Run specific seeder
php artisan db:seed --class=UserSeeder
```

## 🔒 Security Features

- **Input Validation**: Comprehensive validation with sanitization
- **CSRF Protection**: Built-in Laravel CSRF protection
- **XSS Prevention**: Input sanitization and output escaping
- **SQL Injection Prevention**: Eloquent ORM with parameter binding
- **Rate Limiting**: API and form submission rate limiting
- **Email Verification**: Required for account activation

## 🌍 Internationalization

FitSphere supports multiple languages:

- **English** (default)
- **Polish** (complete translation)

Language files are located in `lang/` directory and include:
- User interface translations
- Exception and error messages
- Middleware messages
- Admin panel content

## 🚀 Production Deployment

### Pre-deployment Checklist
- [ ] Set `APP_ENV=production` and `APP_DEBUG=false`
- [ ] Configure SSL certificates
- [ ] Set up Redis for caching and sessions
- [ ] Configure production database
- [ ] Set up email service (Brevo SMTP)
- [ ] Configure API keys (Spoonacular, DeepL, Google OAuth)
- [ ] Set up queue workers
- [ ] Configure proper logging
- [ ] Enable security cookies (`SESSION_SECURE_COOKIE=true`)

### Deployment Commands
```bash
# Install dependencies
composer install --optimize-autoloader --no-dev

# Build frontend assets
npm ci
npm run build

# Configure application
php artisan key:generate
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Database
php artisan migrate --force
php artisan db:seed

# Queue worker (supervisor recommended)
php artisan queue:work --daemon

# Set permissions
chown -R www-data:www-data storage bootstrap/cache
chmod -R 775 storage bootstrap/cache
```

### Server Configuration
See `ssl-production-setup.md` for detailed SSL setup instructions.

### Environment Variables
See `production-env-example.txt` for complete production configuration.

## 📁 Project Structure

```
app/
├── Http/
│   ├── Controllers/     # Traditional controllers
│   └── Middleware/      # Custom middleware
├── Livewire/           # Livewire components
│   ├── Admin/          # Admin panel components
│   ├── Auth/           # Authentication components
│   ├── User/           # User profile components
│   └── Traits/         # Reusable traits
├── Models/             # Eloquent models
├── Services/           # Business logic services
└── Mail/               # Email templates

resources/
├── views/              # Blade templates
├── lang/               # Translation files
└── js/                 # Frontend assets

database/
├── migrations/         # Database migrations
└── seeders/           # Database seeders
```

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch: `git checkout -b feature/amazing-feature`
3. Commit your changes: `git commit -m 'feat: add amazing feature'`
4. Push to the branch: `git push origin feature/amazing-feature`
5. Open a pull request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 🙏 Acknowledgments

- [Laravel](https://laravel.com) - The PHP framework for web artisans
- [Livewire](https://livewire.laravel.com) - A full-stack framework for Laravel
- [Tailwind CSS](https://tailwindcss.com) - A utility-first CSS framework
- [Alpine.js](https://alpinejs.dev) - A rugged, minimal framework
- [Spoonacular API](https://spoonacular.com/food-api) - Recipe and nutrition data
- [DeepL API](https://www.deepl.com/api) - High-quality translations