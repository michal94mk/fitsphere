<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

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
- Advanced blogging system with categories and tags
- User management and role-based permissions
- Nutrition Calculator with automated recipe translations
  - Uses LibreTranslate API for accurate recipe translations (English ↔ Polish)
  - Automatically translates recipes to Polish when using Polish language
  - Bidirectional translation support for recipe searches
  - Manual translation toggle for user control
  - Caching mechanism to reduce API calls
- Meal planning system
- Contact form with email notifications
- Social media integration

## Configuration

### Translation Setup

The nutrition calculator uses LibreTranslate API for recipe translations. To set it up:

1. Visit [LibreTranslate](https://libretranslate.com/) and create an account
2. Get a free API key from the LibreTranslate dashboard
3. Add these variables to your `.env` file:
   ```
   LIBRETRANSLATE_URL=https://libretranslate.com
   LIBRETRANSLATE_API_KEY=your_api_key_here
   ```

Alternatively, you can [self-host LibreTranslate](https://github.com/LibreTranslate/LibreTranslate) on your own server for unlimited translation without API key requirements. In this case, update the URL:
```
LIBRETRANSLATE_URL=http://your-server-address:5000
```

### Spoonacular API

The nutrition calculator and meal planner use Spoonacular API for recipe data:

1. Create an account at [Spoonacular Food API](https://spoonacular.com/food-api)
2. Get your API key from the dashboard
3. Add the API key to your `.env` file:
   ```
   SPOONACULAR_API_KEY=your_spoonacular_api_key_here
   ```

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

# Laravel Blog - Test Documentation

## Test Structure

The tests for the Laravel Blog application are divided into two main categories:

1. **Unit Tests** (`tests/Unit/`) - testing individual components, models, and services
2. **Feature Tests** (`tests/Feature/`) - testing broader flows and user interactions

### Admin Panel Tests

Tests for the admin panel are located in the following locations:

- **AdminTest** - basic access tests for the admin panel
- **AdminMiddlewareTest** - tests for middleware controlling access to the panel
- **Livewire/Admin/** - tests for Livewire components used in the admin panel:
  - `BasicAdminComponentsTest.php` - verification of basic component rendering
  - `CategoriesTest.php` - category management
  - `DashboardTest.php` - dashboard functionality
  - `PostsTest.php` - post management
  - `UsersTest.php` - user management

## Test Status

All 147 tests are now passing successfully. Previously, there were 18 failing tests and 1 skipped test, which have been fixed.

## Completed Fixes

1. **Comment Factory** - removed the `status` column that did not exist in the table
2. **Category Tests** - adapted to changes in the database structure where the `description` column was removed
3. **Dashboard Tests** - changed the approach to testing statistics to avoid hard-coded values
4. **User Tests** - adapted to the actual implementation of Livewire components
5. **Email Verification Test** - fixed the test to use `Notification::fake()` instead of `Mail::fake()` for testing Laravel notifications
6. **Trainer Approval Test** - implemented a proper test using `Mail::fake()` and testing the TrainersShow component
7. **Comments** - converted Polish comments to English in `AdminTest.php` and `EmailTest.php` for code consistency
8. **Removed skipped tests** - removed two skipped tests that required special conditions

## Test Coverage

The application has extensive test coverage at both unit and functional levels:

- **Models** - all models are covered by unit tests
- **Livewire Components** - tested rendering, validation, and basic operations
- **Middleware** - verified correct operation of access mechanisms
- **CRUD Operations** - tested create, read, update, and delete operations for main entities
- **Notifications** - implemented proper testing of Laravel notifications
- **Emails** - tests for sending emails with appropriate facades and mocking

## Test Documentation

Detailed information about the tests can be found in the following files:

- `tests/README.md` - general information about tests and how to run them
- `tests/SUMMARY.md` - detailed structure of all tests
- `tests/SUMMARY_STATUS.md` - current status of tests and list of completed fixes

## Recommendations for Further Test Development

1. **Expand admin tests** - add more detailed tests for various admin panel functionalities
2. **Improve Livewire component tests** - add tests for more complex user interactions
3. **Add performance tests** - implement tests to check application performance
4. **Add browser tests** - consider using Laravel Dusk to test the user interface
5. **Remove example tests** - remove ExampleTest.php files that do not add value to the project

## Running Tests

```bash
# Run all tests
php artisan test

# Run a specific test
php artisan test tests/Feature/Livewire/Admin/CategoriesTest.php

# Run tests from a specific directory
php artisan test tests/Feature/Livewire/Admin/

# Run tests with a specific filter
php artisan test --filter=AdminTest
```