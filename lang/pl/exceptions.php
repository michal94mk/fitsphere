<?php

return [
    // API Exceptions
    'api_call_failed' => 'Usługa jest tymczasowo niedostępna. Spróbuj ponownie później.',
    'spoonacular_api_failed' => 'Serwis przepisów jest obecnie niedostępny. Spróbuj ponownie później.',
    'external_service_error' => 'Wystąpił błąd zewnętrznej usługi. Spróbuj ponownie.',
    
    // Email Exceptions
    'email_sending_failed' => 'Nie udało się wysłać wiadomości email. Spróbuj ponownie lub skontaktuj się z pomocą techniczną.',
    'email_service_unavailable' => 'Usługa email jest tymczasowo niedostępna.',
    
    // Rate Limit Exceptions
    'rate_limit_exceeded' => 'Zbyt wiele żądań. Poczekaj :retry_after sekund przed ponowną próbą.',
    'service_rate_limit' => 'Przekroczono limit żądań do usługi. Spróbuj ponownie później.',
    
    // Validation Exceptions (user-facing)
    'validation_failed' => 'Podane dane są nieprawidłowe.',
    'invalid_input' => 'Sprawdź wprowadzone dane i spróbuj ponownie.',
    
    // General user-facing errors
    'something_went_wrong' => 'Coś poszło nie tak. Spróbuj ponownie.',
    'service_unavailable' => 'Usługa jest tymczasowo niedostępna.',
    'unauthorized_access' => 'Nie masz uprawnień do wykonania tej akcji.',
    'resource_not_found' => 'Nie znaleziono żądanego zasobu.',
    
    // Authentication errors
    'authentication_required' => 'Zaloguj się, aby kontynuować.',
    'session_expired' => 'Twoja sesja wygasła. Zaloguj się ponownie.',
    
    // Permission errors
    'insufficient_permissions' => 'Nie masz uprawnień do wykonania tej akcji.',
    'access_denied' => 'Dostęp zabroniony.',
]; 