# HasFlashMessages Trait

Ten trait zapewnia spójną funkcjonalność komunikatów flash dla komponentów Livewire w panelu admina.

## Użycie

```php
<?php

namespace App\Livewire\Admin;

use App\Livewire\Admin\Traits\HasFlashMessages;
use Livewire\Component;

class MyAdminComponent extends Component
{
    use HasFlashMessages;
    
    public function someAction()
    {
        try {
            // Wykonaj jakąś operację
            $this->setSuccessMessage(__('admin.operation_success'));
        } catch (\Exception $e) {
            $this->setErrorMessage(__('admin.operation_error', ['error' => $e->getMessage()]));
        }
    }
}
```

## Dostępne metody

### `setSuccessMessage(string $message)`
Ustawia komunikat sukcesu i czyści inne komunikaty.

### `setErrorMessage(string $message)`
Ustawia komunikat błędu i czyści inne komunikaty.

### `setInfoMessage(string $message)`
Ustawia komunikat informacyjny i czyści inne komunikaty.

### `clearMessages()`
Czyści wszystkie komunikaty.

### `hasMessages(): bool`
Sprawdza czy są jakieś komunikaty.

## Właściwości

- `$successMessage` - komunikat sukcesu
- `$errorMessage` - komunikat błędu  
- `$infoMessage` - komunikat informacyjny

## Komponent FlashMessages

Komunikaty są automatycznie wyświetlane przez komponent `@livewire('admin.flash-messages')` w layoutu admin. Komponent:

- Przechwytuje komunikaty z sesji
- Umożliwia zamykanie pojedynczych komunikatów
- Ma opcję "Ukryj wszystkie" gdy jest więcej niż 1 komunikat
- Używa animacji transition z Alpine.js
- Stylizowany zgodnie z designem aplikacji

## Alternatywny komponent blade

Dla prostszych przypadków można użyć komponentu blade:

```blade
<x-admin.flash-message type="success" :message="$successMessage" />
<x-admin.flash-message type="error" :message="$errorMessage" />
<x-admin.flash-message type="info" :message="$infoMessage" />
``` 