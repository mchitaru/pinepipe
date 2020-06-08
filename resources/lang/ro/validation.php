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

    'accepted' => ':attribute trebuie acceptat.',
    'active_url' => ':attribute nu este o adresa URL validă.',
    'after' => ':attribute trebuie sa fie o dată ulterioară :date.',
    'after_or_equal' => ':attribute trebuie sa fie o data ulterioară sau egală cu :date.',
    'alpha' => ':attribute poate conține doar litere.',
    'alpha_dash' => ':attribute poate conține doar litere, numere, cratime și underscores.',
    'alpha_num' => ':attribute poate conține doar litere și cifre.',
    'array' => ':attribute trebuie să fie o înșiruire.',
    'before' => ':attribute trebuie sa fie precedentă datei de :date.',
    'before_or_equal' => ':attribute trebuie să fie o dată anterioară sau egală cu :date.',
    'between' => [
        'numeric' => ':attribute trebuie sa fie o valoare între :min și :max.',
        'file' => ':attribute trebuie să aibă o dimensiune între :min și :max kilobiți.',
        'string' => ':attribute trebuie să aibă între :min și :max caractere.',
        'array' => ':attribute trebuie să conțină între :min și :max de articole.',
    ],
    'boolean' => 'Câmpul :attribute trebuie să fie adevărat sau fals.',
    'confirmed' => 'Confirmarea :attribute nu se potrivește.',
    'date' => ':attribute nu este o dată validă.',
    'date_equals' => ':attribute trebuie să fie o dată egală cu :date.',
    'date_format' => ':attribute nu are formatul :format.',
    'different' => ':attribute și :other trebuie să fie diferite.',
    'digits' => ':attribute trebuie să fie de :digits cifre.',
    'digits_between' => ':attribute trebuie să fie între :min și :max cifre.',
    'dimensions' => ':attribute are dimensiuni ale imaginii neacceptate.',
    'distinct' => 'Câmpul :attribute conține o valoare duplicată.',
    'email' => ':attribute trebuie să fie o adresă de email validă.',
    'exists' => ':attribute selectat nu este valid.',
    'file' => ':attribute trebuie să fie un fișier.',
    'filled' => 'Câmpul :attribute trebuie să conțină o valoare.',
    'gt' => [
        'numeric' => ':attribute trebuie să fie mai mare decât :value.',
        'file' => ':attribute trebuie să fie mai mare de :value kilobiți.',
        'string' => ':attribute trebuie să conțină mai mult de :value caractere.',
        'array' => ':attribute trebuie să conțină mai mult de :value articole.',
    ],
    'gte' => [
        'numeric' => ':attribute trebuie să fie mai mare sau egal cu :value.',
        'file' => ':attribute trebuie să aibă o dimensiune egală cu :value kilobiți sau mai mare.',
        'string' => ':attribute trebuie să conțină :value sau mai multe caractere.',
        'array' => ':attribute trebuie să aibă :value sau mai multe articole.',
    ],
    'image' => ':attribute trebuie să fie o imagine.',
    'in' => ':attribute selectat este invalid.',
    'in_array' => 'Câmpul :attribute nu există în :other.',
    'integer' => ':attribute trebuie să fie un număr întreg.',
    'ip' => ':attribute trebuie să fie o adresă IP validă.',
    'ipv4' => ':attribute trebuie să fie o adresă IPv4 validă.',
    'ipv6' => ':attribute trebuie să fie o adresă IPv6 validă.',
    'json' => ':attribute trebuie să fie un șir JSON valid.',
    'lt' => [
        'numeric' => ':attribute trebuie să fie mai mic decât :value.',
        'file' => 'Dimensiunea :attribute trebuie să fie mai mică de :value kilobiți.',
        'string' => ':attribute trebuie să contină mai puțin de :value caractere.',
        'array' => ':attribute trebuie să conțină mai puțin de :value articole.',
    ],
    'lte' => [
        'numeric' => ':attribute trebuie să fie mai mic sau egal cu :value.',
        'file' => 'Dimensiunea :attribute trebuie să fie mai mică sau egală cu :value kilobiți.',
        'string' => ':attribute trebuie să conțină :value sau mai puține caractere.',
        'array' => ':attribute nu trebuie să aibă mai mult de :value articole.',
    ],
    'max' => [
        'numeric' => ':attribute nu poate fi mai mare decât :max.',
        'file' => 'Dimensiunea :attribute nu poate fi mai mare de :max kilobiți.',
        'string' => ':attribute nu poate conține mai mult de :max caractere.',
        'array' => ':attribute nu poate conține mai mult de :max articole.',
    ],
    'mimes' => ':attribute trebuie să fie un fișier de tipul type: :values.',
    'mimetypes' => ':attribute trebuie să fie un fișier de tipul type: :values.',
    'min' => [
        'numeric' => ':attribute poate fi de minimum :min.',
        'file' => 'Dimensiunea :attribute trebuie să fie de minim :min kilobiți.',
        'string' => ':attribute trebuie să conțină minim :min caractere.',
        'array' => ':attribute trebuie să conțină minim :min articole.',
    ],
    'not_in' => ':attribute selectat este invalid.',
    'not_regex' => 'Formatul :attribute este invalid.',
    'numeric' => ':attribute trebuie să fie un număr.',
    'present' => 'Câmpul :attribute trebuie să existe.',
    'regex' => 'Formatul :attribute este invalid.',
    'required' => 'Câmpul :attribute este necesar.',
    'required_if' => 'Câmpul :attribute este necesar în cazul în care :other este :value.',
    'required_unless' => 'Câmpul :attribute câmpul este obligatoriu doar dacă :other nu este :values.',
    'required_with' => 'Câmpul :attribute este necesar atunci când :values este prezentă.',
    'required_with_all' => 'Câmpul :attribute este necesar atunci când :values este prezent.',
    'required_without' => 'Câmpul :attribute este necesar atunci când :values este inexistentă.',
    'required_without_all' => 'Câmpul :attribute este necesar atunci când niciuna dintre valorile :values nu este prezentă.',
    'same' => ':attribute și :other trebuie să coincidă.',
    'size' => [
        'numeric' => ':attribute trebuie să fie de :size.',
        'file' => 'Dimensiunea :attribute trebuie să fie de :size kilobiți.',
        'string' => ':attribute trebuie să conțină :size caractere.',
        'array' => ':attribute trebuie să conțină :size articole.',
    ],
    'starts_with' => ':attribute trebuie să înceapă cu una din următoarele: :values',
    'string' => ':attribute trebuie să fie o înșiruire.',
    'timezone' => ':attribute trebuie să fie o locație validă.',
    'unique' => 'Acest :attribute a fost deja utilizat.',
    'uploaded' => ':attribute nu s-a încărcat cu succes.',
    'url' => 'Formatul :attribute nu este valid.',
    'uuid' => ':attribute trebuie să fie un UUID valid.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
           'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
