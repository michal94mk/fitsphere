# Tests for Laravel Blog Application

This directory contains tests for our Laravel Blog application. The tests are divided into two main categories:

1. **Unit Tests** - located in `tests/Unit/`
2. **Feature Tests** - located in `tests/Feature/`

## Current Status

All 147 tests are now passing successfully! This includes:
- 54 Unit tests
- 93 Feature tests (including Auth and Livewire components)

## Running Tests

### Using Artisan Command

```bash
# Run all tests
php artisan test

# Run a specific test file
php artisan test --filter=PostTest

# Run a specific test method
php artisan test --filter=PostTest::test_post_can_be_created

# Run tests with coverage report (requires Xdebug)
php artisan test --coverage
```

### Using Scripts

#### Linux/Mac
```bash
# Make it executable first
chmod +x run-tests.sh

# Run the script
./run-tests.sh
```

#### Windows
```powershell
# Run the PowerShell script
.\run-tests.ps1
```

## Test Structure

### Unit Tests

Unit tests are focused on testing individual components (models, services, etc.) in isolation:

- `PostTest.php` - Tests for the Post model
- `UserTest.php` - Tests for the User model
- `CommentTest.php` - Tests for the Comment model
- `CategoryTest.php` - Tests for the Category model
- `TrainerTest.php` - Tests for the Trainer model
- `ReservationTest.php` - Tests for the Reservation model
- `NutritionalProfileTest.php` - Tests for the NutritionalProfile model
- `MealPlanTest.php` - Tests for the MealPlan model
- `PostViewTest.php` - Tests for the PostView model
- `SubscriberTest.php` - Tests for the Subscriber model
- `EmailTest.php` - Tests for the Email classes
- `EmailServiceTest.php` - Tests for the EmailService

### Feature Tests

Feature tests focus on testing complete features and how components work together:

- `BlogPostsTest.php` - Tests for the blog listing and viewing functionality
- `AuthenticationTest.php` - Tests for user authentication
- `TrainerTest.php` - Tests for trainer functionality
- `AdminTest.php` - Tests for admin panel functionality
- `NutritionTest.php` - Tests for nutrition features
- `ProfileTest.php` - Tests for user profile functionality
- `EmailTest.php` - Tests for email sending functionality

### Feature/Auth Tests

Specific tests for authentication-related features:

- `AuthenticationTest.php` - Login, logout and authentication tests
- `PasswordResetTest.php` - Password reset functionality
- `EmailVerificationTest.php` - Email verification process

### Feature/Livewire Tests

Tests for Livewire components:

- `CreateReservationTest.php` - Testing reservation creation flow
- `PostDetailsTest.php` - Testing post details component
- `NutritionCalculatorTest.php` - Testing nutrition calculator
- `UserReservationsTest.php` - Testing user reservations management
- `Admin/DashboardTest.php` - Testing admin dashboard component
- `Admin/UsersTest.php` - Testing users management components

## Recent Fixes

1. **CommentFactory fix** - Removed non-existent 'status' column
2. **Category tests** - Adapted to database changes (removed 'description' column)
3. **Dashboard tests** - Changed the approach to test statistics to avoid hard-coded values
4. **User tests** - Adapted to actual Livewire component implementation
5. **Email verification tests** - Fixed by using Notification::fake() instead of Mail::fake()
6. **Trainer approval tests** - Implemented proper test using Mail::fake() 
7. **Comments** - Converted Polish comments to English in AdminTest.php and EmailTest.php
8. **Test cleanup** - Removed skipped tests that required special conditions

## Creating New Tests

### Creating Unit Tests

```bash
php artisan make:test ModelNameTest --unit
```

### Creating Feature Tests

```bash
php artisan make:test FeatureNameTest
```

### Creating Livewire Tests

```bash
php artisan make:test Livewire/ComponentNameTest
```

## Test Database

The tests use your configured database by default. If you want to use SQLite in-memory database for testing, you can uncomment these lines in `phpunit.xml`:

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

Note: Using SQLite requires the PHP SQLite extension to be enabled.

## Additional Documentation

For more detailed information about the tests, refer to:
- `SUMMARY.md` - Detailed structure and description of all tests
- `SUMMARY_STATUS.md` - Current status of tests and list of completed fixes

## Common Issues

### SQLite Driver Not Found

If you see a "could not find driver" error when using SQLite, you need to enable the SQLite extension in your PHP configuration:

1. Open your php.ini file
2. Uncomment the line `;extension=pdo_sqlite` by removing the semicolon
3. Restart your web server

### Running Tests on Windows

If you have issues with the shell script on Windows, use the PowerShell script instead:

```powershell
.\run-tests.ps1
```

## Best Practices

1. Use the `RefreshDatabase` trait to reset the database between tests
2. Create factories for your models to make test data creation easier
3. Write descriptive test method names that explain what's being tested
4. Keep tests independent - don't rely on state from other tests
5. Test both successful cases and error/edge cases
6. Use assertions effectively to verify expected outcomes
7. Use fake() methods appropriately for Mail, Notification, and other facades
8. Test Livewire components using the Livewire test helpers 