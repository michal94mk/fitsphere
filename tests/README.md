# Tests for Laravel Blog Application

This directory contains tests for our Laravel Blog application. The tests are divided into two main categories:

1. **Unit Tests** - located in `tests/Unit/`
2. **Feature Tests** - located in `tests/Feature/`

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
- Add more unit tests here...

### Feature Tests

Feature tests focus on testing complete features and how components work together:

- `BlogPostsTest.php` - Tests for the blog listing and viewing functionality
- `AuthenticationTest.php` - Tests for user authentication
- `TrainerTest.php` - Tests for trainer functionality
- `AdminTest.php` - Tests for admin panel functionality
- `NutritionTest.php` - Tests for nutrition features
- Add more feature tests here...

## Creating New Tests

### Creating Unit Tests

```bash
php artisan make:test ModelNameTest --unit
```

### Creating Feature Tests

```bash
php artisan make:test FeatureNameTest
```

## Test Database

The tests use your configured database by default. If you want to use SQLite in-memory database for testing, you can uncomment these lines in `phpunit.xml`:

```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
```

Note: Using SQLite requires the PHP SQLite extension to be enabled.

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