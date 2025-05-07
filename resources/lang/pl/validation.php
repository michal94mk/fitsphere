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
            'regex' => 'Imię może zawierać tylko litery, spacje, myślniki i apostrofy.',
        ],
        'email' => [
            'required' => 'Adres email jest wymagany.',
            'email' => 'Podaj poprawny adres email.',
            'unique' => 'Ten adres email jest już zajęty.',
            'dns' => 'Domena email wydaje się nieprawidłowa.',
        ],
        'password' => [
            'required' => 'Hasło jest wymagane.',
            'min' => 'Hasło musi zawierać co najmniej :min znaków.',
            'confirmed' => 'Potwierdzenie hasła nie zgadza się.',
            'regex' => 'Hasło musi zawierać przynajmniej jedną małą literę, jedną wielką literę i jedną cyfrę.',
        ],
        'role' => [
            'required' => 'Rola jest wymagana.',
            'in' => 'Wybrana rola jest nieprawidłowa.',
        ],
        'image' => [
            'image' => 'Wybrany plik musi być obrazem.',
            'max' => 'Zdjęcie nie może być większe niż :max kilobajtów.',
        ],
        'specialization' => [
            'required' => 'Specjalizacja jest wymagana dla trenera.',
        ],
    ],
    
    'attributes' => [],
]; 