<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'required' => 'Pole :attribute jest wymagane.',
    'string' => 'Pole :attribute musi być tekstem.',
    'email' => 'Pole :attribute musi być prawidłowym adresem email.',
    'unique' => 'Wartość pola :attribute jest już zajęta.',
    'not_in' => 'Pole :attribute ma nieprawidłową wartość.',
    'mimes' => 'Pole :attribute musi być plikiem typu: :values.',
    'dimensions' => 'Pole :attribute ma nieprawidłowe wymiary obrazu.',
    'min' => [
        'string' => 'Pole :attribute musi mieć co najmniej :min znaków.',
    ],
    'max' => [
        'string' => 'Pole :attribute nie może być dłuższe niż :max znaków.',
        'file' => 'Plik :attribute nie może być większy niż :max kilobajtów.',
    ],
    'confirmed' => 'Potwierdzenie pola :attribute nie zgadza się.',
    'regex' => 'Format pola :attribute jest nieprawidłowy.',
    'in' => 'Wybrana wartość pola :attribute jest nieprawidłowa.',
    'image' => 'Pole :attribute musi być obrazem.',
    'boolean' => 'Pole :attribute musi mieć wartość prawda lub fałsz.',
    'integer' => 'Pole :attribute musi być liczbą całkowitą.',
    'numeric' => 'Pole :attribute musi być liczbą.',
    'dns' => 'Domena w polu :attribute wydaje się nieprawidłowa.',

    'password' => [
        'current_required' => 'Pole obecnego hasła jest wymagane.',
        'new_required' => 'Pole nowego hasła jest wymagane.',
        'min' => 'Nowe hasło musi mieć co najmniej :min znaków.',
        'confirmed' => 'Potwierdzenie nowego hasła nie zgadza się.',
        'complex' => 'Hasło musi zawierać co najmniej jedną wielką literę, jedną małą literę i jedną cyfrę.',
    ],
    
    'user' => [
        'name' => [
            'required' => 'Imię jest wymagane.',
            'min' => 'Imię musi mieć co najmniej 2 znaki.',
            'max' => 'Imię nie może być dłuższe niż 50 znaków.',
            'format' => 'Imię może zawierać tylko litery, spacje, myślniki i apostrofy.',
            'regex' => 'Imię może zawierać tylko litery, spacje, myślniki i apostrofy.',
        ],
        'email' => [
            'required' => 'Adres email jest wymagany.',
            'format' => 'Podaj poprawny adres email.',
            'email' => 'Podaj poprawny adres email.',
            'unique' => 'Ten adres email jest już zajęty.',
            'max' => 'Adres email nie może być dłuższy niż 100 znaków.',
            'dns' => 'Domena email wydaje się nieprawidłowa.',
            'exists_other_type' => 'Ten email jest już zarejestrowany jako inny typ konta.',
        ],
        'password' => [
            'required' => 'Hasło jest wymagane.',
            'min' => 'Hasło musi zawierać co najmniej 8 znaków.',
            'max' => 'Hasło nie może być dłuższe niż 128 znaków.',
            'confirmed' => 'Potwierdzenie hasła nie zgadza się.',
            'complex' => 'Hasło musi zawierać małe i wielkie litery, cyfrę oraz znak specjalny (@$!%*?&).',
            'regex' => 'Hasło musi zawierać małe i wielkie litery, cyfrę oraz znak specjalny.',
        ],
        'password_confirmation' => [
            'required' => 'Potwierdzenie hasła jest wymagane.',
            'min' => 'Potwierdzenie hasła musi zawierać co najmniej 8 znaków.',
            'max' => 'Potwierdzenie hasła nie może być dłuższe niż 128 znaków.',
        ],
        'account_type' => [
            'required' => 'Typ konta jest wymagany.',
            'invalid' => 'Wybrano nieprawidłowy typ konta.',
        ],
        'role' => [
            'required' => 'Rola jest wymagana.',
            'invalid' => 'Wybrana rola jest nieprawidłowa.',
            'in' => 'Wybrana rola jest nieprawidłowa.',
        ],
        'specialization' => [
            'required' => 'Specjalizacja jest wymagana dla trenera.',
            'min' => 'Specjalizacja musi mieć co najmniej 3 znaki.',
            'max' => 'Specjalizacja nie może być dłuższa niż 100 znaków.',
            'format' => 'Specjalizacja zawiera niedozwolone znaki.',
        ],
        'photo' => [
            'image' => 'Wybrany plik musi być obrazem.',
            'max' => 'Zdjęcie nie może być większe niż 1MB.',
            'mimes' => 'Zdjęcie musi być w formacie: JPEG, JPG, PNG lub WebP.',
            'dimensions' => 'Zdjęcie musi mieć wymiary między 100x100 a 1500x1500 pikseli.',
        ],
    ],
    
    'contact' => [
        'name' => [
            'required' => 'Imię jest wymagane.',
            'min' => 'Imię musi mieć co najmniej 2 znaki.',
            'max' => 'Imię nie może być dłuższe niż 50 znaków.',
            'format' => 'Imię może zawierać tylko litery, spacje, myślniki i apostrofy.',
        ],
        'email' => [
            'required' => 'Adres email jest wymagany.',
            'format' => 'Podaj poprawny adres email.',
            'max' => 'Adres email nie może być dłuższy niż 100 znaków.',
        ],
        'message' => [
            'required' => 'Wiadomość jest wymagana.',
            'min' => 'Wiadomość musi mieć co najmniej 10 znaków.',
            'max' => 'Wiadomość nie może być dłuższa niż 1000 znaków.',
            'format' => 'Wiadomość zawiera niedozwolone znaki.',
        ],
    ],
    
    'trainer' => [
        'description' => [
            'max' => 'Opis nie może być dłuższy niż 500 znaków.',
            'format' => 'Opis zawiera niedozwolone znaki.',
        ],
        'biography' => [
            'max' => 'Biografia nie może być dłuższa niż 2000 znaków.',
            'format' => 'Biografia zawiera niedozwolone znaki.',
        ],
        'experience' => [
            'integer' => 'Doświadczenie musi być liczbą całkowitą.',
            'min' => 'Doświadczenie nie może być ujemne.',
            'max' => 'Doświadczenie nie może przekraczać 50 lat.',
        ],
    ],
    
    'post' => [
        'title' => [
            'required' => 'Tytuł jest wymagany.',
            'min' => 'Tytuł musi mieć co najmniej 3 znaki.',
            'max' => 'Tytuł nie może być dłuższy niż 200 znaków.',
        ],
        'content' => [
            'required' => 'Treść jest wymagana.',
            'min' => 'Treść musi mieć co najmniej 10 znaków.',
            'max' => 'Treść nie może być dłuższa niż 15000 znaków.',
        ],
        'excerpt' => [
            'max' => 'Streszczenie nie może być dłuższe niż 500 znaków.',
        ],
    ],
    
    'category' => [
        'name' => [
            'required' => 'Nazwa kategorii jest wymagana.',
            'min' => 'Nazwa kategorii musi mieć co najmniej 2 znaki.',
            'max' => 'Nazwa kategorii nie może być dłuższa niż 50 znaków.',
            'unique' => 'Kategoria o tej nazwie już istnieje.',
        ],
    ],
    
    'attributes' => [
        'full_name' => 'imię i nazwisko',
        'email_address' => 'adres email',
        'password' => 'hasło',
        'password_confirmation' => 'potwierdzenie hasła',
        'account_type' => 'typ konta',
        'user_role' => 'rola użytkownika',
        'profile_photo' => 'zdjęcie profilowe',
        'specialization' => 'specjalizacja',
        'message' => 'wiadomość',
        'description' => 'opis',
        'biography' => 'biografia',
        'experience' => 'doświadczenie',
        'title' => 'tytuł',
        'content' => 'treść',
        'excerpt' => 'streszczenie',
        'category_name' => 'nazwa kategorii',
    ],
]; 