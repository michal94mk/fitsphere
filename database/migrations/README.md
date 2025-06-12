# FitSphere Database Migrations

## Czysta struktura migracji

### Migracje do uruchomienia (w kolejności):

```bash
# Podstawowe tabele Laravel
0001_01_01_000001_create_cache_table.php
0001_01_01_000002_create_jobs_table.php

# Główne tabele aplikacji
2025_02_01_000000_create_users_table.php           # Users + trainer fields
2025_02_20_000000_create_categories_table.php      # Categories + translations  
2025_02_21_000000_create_posts_table.php           # Posts + translations
2025_03_15_000000_create_subscribers_table.php     # Newsletter subscribers
2025_04_10_000000_create_reservations_table.php    # Reservations + polymorphic client
2025_04_12_000000_create_nutritional_profiles_table.php
2025_04_14_000000_create_meal_plans_table.php
```

### Instalacja:

```bash
php artisan migrate
php artisan db:seed
```

### Zmiany wprowadzone:

✅ **Polymorphic client** dodane bezpośrednio do `reservations` migration  
✅ **Trainer fields** dodane bezpośrednio do `users` migration  
✅ **Usunięte niepotrzebne migracje** - wszystko w oryginalnych plikach  

### Struktura tabel:

#### `users` - Ujednolicony model użytkowników i trenerów
- **Podstawowe pola**: `id`, `name`, `email`, `password`, `role`
- **OAuth**: `provider`, `provider_id`  
- **Pola trenera**: `specialization`, `description`, `bio`, `specialties`, `experience`, `is_approved`
- **Kontakt**: `phone`, `twitter_link`, `instagram_link`, `facebook_link`

#### `reservations` - Z obsługą polymorphic relationships
- **Legacy**: `user_id` (nullable dla kompatybilności)
- **Nowy system**: `client_id` + `client_type` (polymorphic)
- **Trener**: `trainer_id` → `users.id`
- **Terminy**: `date`, `start_time`, `end_time`, `status`, `notes`

#### `user_translations` - Wielojęzyczne profile (istniejąca tabela)
- Tłumaczenia dla trenerów: `name`, `description`, `bio`, `specialties`, `specialization`
- Obsługuje locale: `pl`, `en`

### Role w systemie:
- `user` - zwykły użytkownik
- `trainer` - trener (User z dodatkowymi polami)  
- `admin` - administrator
- Multiple role: `user,trainer` (oddzielone przecinkami)

### Polymorphic relationships:
```php
// Reservation model
public function client(): MorphTo 
{
    return $this->morphTo();
}

// W praktyce client_type = 'App\Models\User'
``` 