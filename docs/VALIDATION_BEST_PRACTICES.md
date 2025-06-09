# Najlepsze Praktyki Walidacji w TALL Stack

## Przegląd

W aplikacji FitSphere używamy **TALL stack** (Tailwind, Alpine.js, Laravel, Livewire), gdzie walidacja odbywa się bezpośrednio w komponentach Livewire, a nie przez FormRequest klasy.

## Podstawowe Zasady

### 1. Walidacja w Czasie Rzeczywistym
```php
public function updated($propertyName)
{
    // 1. Najpierw sanityzacja
    $this->sanitizeInput($propertyName);
    
    // 2. Wyczyść poprzednie błędy
    $this->resetErrorBag($propertyName);
    
    // 3. Waliduj tylko zmienione pole
    $this->validateOnly($propertyName);
}
```

### 2. Sanityzacja Danych
Zawsze sanityzuj dane wejściowe przed walidacją:
```php
private function sanitizeInput(string $propertyName): void
{
    switch($propertyName) {
        case 'name':
            $this->name = trim(strip_tags($this->name));
            break;
        case 'email':
            $this->email = trim(strtolower(strip_tags($this->email)));
            break;
    }
}
```

### 3. Struktura Reguł Walidacji
Używaj tablicowej składni dla lepszej czytelności:
```php
protected function rules()
{
    return [
        'name' => [
            'required',
            'string',
            'min:2',
            'max:100',
            'regex:/^[\pL\s\-\'\.\u{00C0}-\u{017F}]+$/u',
        ],
        'email' => [
            'required',
            'email:rfc,dns',
            'max:320',
            'unique:users,email',
        ],
    ];
}
```

## Rozsądne Limity dla Pól

### Pola Tekstowe
- **Imię/Nazwisko**: min:2, max:50
- **Email**: max:100 (praktyczny limit)
- **Hasło**: min:8, max:128
- **Specjalizacja**: max:100
- **Opis**: max:500
- **Biografia**: max:2000
- **Wiadomość**: min:10, max:1000

### Posty/Artykuły
- **Tytuł posta**: min:3, max:200
- **Treść posta**: min:10, max:15000
- **Streszczenie**: max:500 (opcjonalne)

### Kategorie
- **Nazwa kategorii**: min:2, max:50

### Komentarze
- **Treść komentarza**: min:3, max:500

### Pliki
- **Zdjęcia profilowe**: max:1024KB (1MB)
- **Dokumenty**: max:2048KB (2MB)
- **Wymiary obrazów**: min:100x100, max:1500x1500px

### Liczby
- **Doświadczenie (lata)**: min:0, max:50

## Wzorce Regex dla Bezpieczeństwa

### Imiona i Nazwiska
```php
'regex:/^[\pL\s\-\'\.\u{00C0}-\u{017F}]+$/u'
```
Obsługuje znaki międzynarodowe, spacje, myślniki, apostrofy.

### Email
```php
'regex:/^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$/'
```

### Hasła (kompleksowe)
```php
'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[@$!%*?&])[A-Za-z\d@$!%*?&]/'
```
Wymaga: małe litery, wielkie litery, cyfry, znaki specjalne.

### Treść tekstowa
```php
'regex:/^[\pL\pN\s\-\'\.\,\!\?\:\;\(\)\"\/\&\@\#\$\%\+\=\*\[\]\{\}\|\\\\\u{00C0}-\u{017F}\r\n]*$/u'
```

## Przykład Kompletnego Komponentu

```php
<?php

namespace App\Livewire;

use Livewire\Component;
use App\Livewire\Traits\ValidationTrait;

class ExampleForm extends Component
{
    use ValidationTrait;
    
    public string $name = '';
    public string $email = '';
    public string $message = '';
    
    public function updated($propertyName)
    {
        $this->validateFieldRealTime($propertyName);
    }
    
    protected function rules()
    {
        return [
            'name' => $this->getNameValidationRules(),
            'email' => $this->getEmailValidationRules(['unique:users,email']),
            'message' => $this->getTextValidationRules(10, 2000),
        ];
    }
    
    protected function validationAttributes(): array
    {
        return $this->getCommonValidationAttributes();
    }
    
    public function submit()
    {
        $validatedData = $this->validate();
        
        // Przetwórz dane...
        
        session()->flash('success', 'Dane zostały zapisane!');
        $this->reset();
    }
}
```

## Użycie Validation Trait

Aplikacja zawiera `ValidationTrait` z gotowymi metodami:

```php
use App\Livewire\Traits\ValidationTrait;

class YourComponent extends Component
{
    use ValidationTrait;
    
    // Automatyczna sanityzacja
    public function updated($propertyName)
    {
        $this->validateFieldRealTime($propertyName);
    }
    
    // Gotowe reguły
    protected function rules()
    {
        return [
            'name' => $this->getNameValidationRules(2, 50),
            'email' => $this->getEmailValidationRules(['unique:users']),
            'password' => $this->getPasswordValidationRules(true),
            'photo' => $this->getPhotoValidationRules(1024),
            
            // Nowe reguły dla postów i kategorii
            'title' => $this->getPostTitleValidationRules(),
            'content' => $this->getPostContentValidationRules(),
            'excerpt' => $this->getPostExcerptValidationRules(),
            'category_name' => $this->getCategoryNameValidationRules(['unique:categories']),
        ];
    }
}
```

## Struktura Tłumaczeń

Wszystkie komunikaty są w `resources/lang/pl/validation.php`:

```php
'user' => [
    'name' => [
        'required' => 'Imię jest wymagane.',
        'min' => 'Imię musi mieć co najmniej 2 znaki.',
        'max' => 'Imię nie może być dłuższe niż 100 znaków.',
        'format' => 'Imię może zawierać tylko litery...',
    ],
],
```

## Najczęstsze Błędy do Unikania

1. **Brak sanityzacji** - zawsze czyść dane wejściowe
2. **Zbyt długie limity** - ogranicz rozsądnie długości
3. **Słabe hasła** - wymagaj znaków specjalnych
4. **Brak walidacji w czasie rzeczywistym** - frustruje użytkowników
5. **Niejasne komunikaty błędów** - używaj polskich tłumaczeń
6. **Brak walidacji obrazów** - sprawdzaj wymiary i formaty
7. **Pomijanie edge cases** - testuj z różnymi danymi

## Testowanie Walidacji

```php
// W testach jednostkowych
public function test_name_validation()
{
    $component = Livewire::test(YourComponent::class);
    
    $component->set('name', 'a') // Za krótkie
              ->assertHasErrors(['name' => 'min']);
              
    $component->set('name', str_repeat('a', 101)) // Za długie  
              ->assertHasErrors(['name' => 'max']);
              
    $component->set('name', 'Jan Kowalski') // OK
              ->assertHasNoErrors('name');
}
```

## Podsumowanie

- Używaj walidacji w czasie rzeczywistym
- Sanityzuj wszystkie dane wejściowe  
- Ustaw rozsądne limity długości
- Używaj silnych reguł regex
- Zapewnij czytelne komunikaty błędów
- Korzystaj z ValidationTrait dla konsystentności
- Testuj wszystkie przypadki brzegowe 