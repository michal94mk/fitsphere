# 🖼️ FITSPHERE - AUTOMATYCZNE PRZYPISYWANIE OBRAZKÓW

## 📁 **STRUKTURA FOLDERÓW**

```
storage/app/public/
├── users/          # user1.jpg, user2.jpg, user3.jpg...
├── trainers/       # trainer1.jpg, trainer2.jpg, trainer3.jpg...
└── posts/          # post1.jpg, post2.jpg, post3.jpg...
```

## 🚀 **JAK TO DZIAŁA**

### **1. Wklej obrazki**
- Wklej wszystkie obrazki do odpowiednich folderów w `storage/app/public/`
- **Ważne:** Nazwy plików muszą być w formacie: `user1.jpg`, `trainer1.jpg`, `post1.jpg`

### **2. Uruchom seeder**
```bash
# Na serwerze produkcyjnym:
php artisan db:seed --class=ImageSeeder

# Lub uruchom wszystkie seedery:
php artisan db:seed
```

### **3. Seeder automatycznie:**
- Skanuje foldery `users/`, `trainers/`, `posts/` w `storage/app/public/`
- Znajduje pliki obrazków
- Przypisuje je do odpowiednich rekordów w bazie danych
- Zapisuje ścieżki: `users/user1.jpg`, `trainers/trainer1.jpg`, `posts/post1.jpg`

## ✅ **CO ZOSTANIE ZROBIONE**

- **Użytkownicy:** Otrzymają obrazki z folderu `users/`
- **Trenerzy:** Otrzymają obrazki z folderu `trainers/`
- **Posty:** Otrzymają obrazki z folderu `posts/`
- **Ścieżki:** Będą automatycznie poprawne na produkcji

## 🔧 **TECHNICAL DETAILS**

### **Ścieżki w bazie:**
```php
// Przykłady:
$user->image = 'users/user1.jpg'
$trainer->image = 'trainers/trainer1.jpg'
$post->image = 'posts/post1.jpg'
```

### **URL na produkcji:**
```
https://twoja-domena.com/storage/users/user1.jpg
https://twoja-domena.com/storage/trainers/trainer1.jpg
https://twoja-domena.com/storage/posts/post1.jpg
```

## 📝 **FORMATY OBRAZKÓW**

**Wspierane formaty:**
- JPG/JPEG
- PNG
- GIF
- WebP

**Zalecane rozmiary:**
- **Avatary:** 400x400px
- **Posty:** 800x600px lub 1200x800px

## 🚨 **WAŻNE INFORMACJE**

1. **Nazwy plików** muszą być w formacie: `user1.jpg`, `user2.jpg`, etc.
2. **Sortowanie** jest automatyczne (1, 2, 3, 10, 11...)
3. **Seeder** przypisuje obrazki tylko do rekordów bez obrazków
4. **Ścieżki** są automatycznie poprawne na produkcji
5. **Na serwerze** musisz uruchomić: `php artisan storage:link`

## 🎯 **PRZYKŁAD UŻYCIA**

```bash
# 1. Wklej obrazki do folderów
# 2. Uruchom na serwerze:
php artisan db:seed --class=ImageSeeder

# 3. Sprawdź wyniki:
php artisan tinker
>>> App\Models\User::whereNotNull('image')->count()
>>> App\Models\Post::whereNotNull('image')->count()
```

## ✅ **GOTOWE!**

**Po uruchomieniu seedera wszystkie obrazki będą automatycznie przypisane i będą działać na produkcji!** 🚀
