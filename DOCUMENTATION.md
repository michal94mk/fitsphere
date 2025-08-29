# FitSphere - Livewire Application Documentation

## Overview

FitSphere is a modern fitness application built with **Laravel 11** and **Livewire 3**, following the **TALL Stack** (Tailwind CSS, Alpine.js, Laravel, Livewire) architecture. The application provides a comprehensive fitness platform with real-time interactions, user management, and content management.

## Technology Stack

- **Backend**: Laravel 11 (PHP 8.2+)
- **Frontend**: Livewire 3, Tailwind CSS, Alpine.js
- **Database**: SQLite (development), MySQL (production)
- **Authentication**: Laravel's built-in authentication with email verification
- **Testing**: PHPUnit with comprehensive test coverage
- **Internationalization**: Multi-language support (EN/PL)

## Architecture

### Livewire Components Structure

```
app/Livewire/
├── Admin/                    # Admin panel components
│   ├── Dashboard.php        # Admin dashboard with statistics
│   ├── PostsIndex.php       # Posts management
│   ├── TrainersIndex.php    # Trainers management
│   └── UsersIndex.php       # Users management
├── Auth/                    # Authentication components
│   ├── Login.php           # User login
│   ├── Register.php        # User registration
│   └── ForgotPassword.php  # Password reset
├── Trainer/                 # Trainer-specific components
│   ├── Dashboard.php       # Trainer dashboard
│   └── Reservations.php    # Trainer reservations
├── User/                    # User-specific components
│   ├── Profile.php         # User profile management
│   └── Reservations.php    # User reservations
├── Blog.php                # Blog posts listing
├── HomePage.php            # Homepage with latest content
├── PostsPage.php           # Enhanced posts page
├── PostDetails.php         # Individual post view
├── TrainersList.php        # Trainers listing
├── TrainerDetails.php      # Individual trainer view
├── NutritionCalculator.php # Nutrition calculations
├── MealPlanner.php         # Meal planning functionality
├── SearchResultsPage.php   # Search functionality
└── ContactPage.php         # Contact form
```

### Key Features

#### 1. Real-time User Interface
- **Livewire Components**: All user interactions are handled through Livewire components
- **Real-time Updates**: Forms, lists, and data updates happen without page refreshes
- **Progressive Enhancement**: Works with JavaScript disabled, enhanced with Alpine.js

#### 2. Authentication & Authorization
- **Multi-role System**: Users, Trainers, and Admins
- **Email Verification**: Required for full access
- **Password Reset**: Secure password recovery
- **Social Login**: Google OAuth integration

#### 3. Content Management
- **Blog System**: Posts with categories, comments, and translations
- **Multi-language Support**: Content available in English and Polish
- **Media Management**: Image uploads and management
- **SEO Optimization**: Meta tags, slugs, and structured data

#### 4. Fitness Features
- **Nutrition Calculator**: BMI, BMR, and daily calorie calculations
- **Meal Planning**: Integration with Spoonacular API for recipes
- **Trainer Management**: Trainer profiles, specializations, and bookings
- **Reservation System**: Appointment booking and management

#### 5. Admin Panel
- **Dashboard**: Statistics and overview
- **User Management**: User approval, role management
- **Content Moderation**: Post approval, comment management
- **System Monitoring**: Logs, error tracking

## Component Examples

### HomePage Component
```php
class HomePage extends Component
{
    public $latestPosts;
    public $categories;
    public $popularPosts;
    
    public function mount()
    {
        $this->loadPosts();
    }
    
    protected function loadPosts()
    {
        $locale = App::getLocale();
        
        $this->latestPosts = Post::with(['user', 'category'])
            ->with(['translations' => function($query) use ($locale) {
                $query->where('locale', $locale);
            }])
            ->withCount('comments')
            ->latest()
            ->take(3)
            ->get();
    }
    
    #[Layout('layouts.blog')]
    public function render()
    {
        return view('livewire.home-page');
    }
}
```

### PostsPage Component
```php
class PostsPage extends Component
{
    use WithPagination;
    
    public $searchQuery = '';
    public $category = '';
    public $sortBy = 'newest';
    
    protected $queryString = [
        'searchQuery' => ['except' => ''],
        'category' => ['except' => ''],
        'sortBy' => ['except' => 'newest'],
    ];
    
    public function updatedSearchQuery()
    {
        $this->resetPage();
    }
    
    #[Layout('layouts.blog')]
    public function render()
    {
        $query = Post::with(['user', 'category'])->withCount('comments');
        
        if (!empty($this->searchQuery)) {
            $query->where(function($q) {
                $q->where('title', 'like', '%' . $this->searchQuery . '%')
                  ->orWhere('content', 'like', '%' . $this->searchQuery . '%');
            });
        }
        
        $posts = $query->paginate(9);
        $categories = Category::all();
        
        return view('livewire.posts-page', [
            'posts' => $posts,
            'categories' => $categories,
        ]);
    }
}
```

## Database Structure

### Core Models
- **User**: Authentication, roles, profiles
- **Post**: Blog posts with translations
- **Category**: Post categories with translations
- **Comment**: Post comments
- **Reservation**: Appointment bookings
- **NutritionalProfile**: User nutrition data
- **Subscriber**: Newsletter subscriptions

### Relationships
```php
// User relationships
User -> hasMany(Post)
User -> hasMany(Comment)
User -> hasMany(Reservation)
User -> hasOne(NutritionalProfile)

// Post relationships
Post -> belongsTo(User)
Post -> belongsTo(Category)
Post -> hasMany(Comment)
Post -> hasMany(PostTranslation)

// Category relationships
Category -> hasMany(Post)
Category -> hasMany(CategoryTranslation)
```

## Testing Strategy

### Test Coverage
- **Unit Tests**: Model relationships, service classes, calculations
- **Feature Tests**: Livewire components, user workflows
- **Integration Tests**: Database operations, external API calls

### Livewire Testing
```php
// Testing Livewire components
Livewire::test(PostsPage::class)
    ->set('searchQuery', 'fitness')
    ->assertSee('Fitness Tips')
    ->assertSet('searchQuery', 'fitness');

// Testing user interactions
Livewire::test(NutritionCalculator::class)
    ->set('weight', 70)
    ->set('height', 175)
    ->call('calculateBMI')
    ->assertSet('bmi', 22.86);
```

## Performance Optimizations

### Caching Strategy
- **Component Caching**: Livewire component state caching
- **Database Caching**: Query result caching
- **View Caching**: Blade template caching
- **Translation Caching**: Language file caching

### Database Optimizations
- **Eager Loading**: Prevents N+1 queries
- **Indexing**: Strategic database indexes
- **Pagination**: Efficient data loading
- **Query Optimization**: Optimized database queries

## Security Features

### Authentication
- **CSRF Protection**: Built-in Laravel CSRF protection
- **Session Security**: Secure session management
- **Password Hashing**: Bcrypt password hashing
- **Rate Limiting**: API and form submission rate limiting

### Authorization
- **Role-based Access**: User, Trainer, Admin roles
- **Policy-based Authorization**: Laravel policies for model access
- **Middleware Protection**: Route and component protection

## Deployment

### Requirements
- PHP 8.2+
- Composer
- Node.js & NPM
- SQLite/MySQL
- Web server (Apache/Nginx)

### Installation
```bash
# Clone repository
git clone [repository-url]
cd fitsphere

# Install dependencies
composer install
npm install

# Environment setup
cp .env.example .env
php artisan key:generate

# Database setup
php artisan migrate
php artisan db:seed

# Build assets
npm run build

# Start development server
php artisan serve
```

### Production Deployment
```bash
# Optimize for production
composer install --optimize-autoloader --no-dev
npm run build
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## Development Guidelines

### Livewire Best Practices
1. **Component Organization**: Keep components focused and single-purpose
2. **State Management**: Use public properties for component state
3. **Event Handling**: Use Livewire events for component communication
4. **Validation**: Implement form validation in components
5. **Performance**: Use pagination and lazy loading for large datasets

### Code Standards
- **PSR-12**: PHP coding standards
- **Laravel Conventions**: Follow Laravel naming conventions
- **Documentation**: Comprehensive inline documentation
- **Testing**: Maintain high test coverage

## Troubleshooting

### Common Issues
1. **Component Not Updating**: Check for JavaScript errors
2. **Database Connection**: Verify database configuration
3. **Cache Issues**: Clear application cache
4. **Permission Errors**: Check file permissions

### Debug Tools
- **Laravel Debugbar**: Development debugging
- **Livewire DevTools**: Component debugging
- **Log Files**: Application error logging
- **Database Logging**: Query performance monitoring

## Conclusion

FitSphere demonstrates modern web development practices using the TALL Stack. The application provides a robust foundation for fitness-related web applications with real-time interactions, comprehensive testing, and scalable architecture.

The Livewire-based approach ensures excellent user experience with minimal JavaScript while maintaining the power and flexibility of a full-stack PHP framework.
