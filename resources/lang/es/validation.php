<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | such as the size rules. Feel free to tweak each of these messages.
    |
    */

    "accepted"         => ":attribute debe ser aceptado.",
    "active_url"       => ":attribute no es una URL válida.",
    "after"            => ":attribute debe ser una fecha posterior a :date.",
    "alpha"            => ":attribute solo debe contener letras.",
    "alpha_dash"       => ":attribute solo debe contener letras, números y guiones.",
    "alpha_num"        => ":attribute solo debe contener letras y números.",
    "array"            => ":attribute debe ser un conjunto.",
    "before"           => ":attribute debe ser una fecha anterior a :date.",
    "between"          => [
        "numeric" => ":attribute tiene que estar entre :min - :max.",
        "file"    => ":attribute debe pesar entre :min - :max kilobytes.",
        "string"  => ":attribute tiene que tener entre :min - :max caracteres.",
        "array"   => ":attribute tiene que tener entre :min - :max ítems.",
    ],
    "boolean"          => "El campo :attribute debe tener un valor verdadero o falso.",
    "confirmed"        => "La confirmación de :attribute no coincide.",
    "date"             => ":attribute no es una fecha válida.",
    "date_format"      => ":attribute no corresponde al formato :format.",
    "different"        => ":attribute y :other deben ser diferentes.",
    "digits"           => ":attribute debe tener :digits dígitos.",
    "digits_between"   => ":attribute debe tener entre :min y :max dígitos.",
    "email"            => ":attribute no es un correo válido",
    "exists"           => ":attribute es inválido.",
    "filled"           => "El campo :attribute es obligatorio.",
    "image"            => ":attribute debe ser una imagen.",
    "in"               => ":attribute es inválido.",
    "integer"          => ":attribute debe ser un número entero.",
    "ip"               => ":attribute debe ser una dirección IP válida.",
    "max"              => [
        "numeric" => ":attribute no debe ser mayor a :max.",
        "file"    => ":attribute no debe ser mayor que :max kilobytes.",
        "string"  => ":attribute no debe ser mayor que :max caracteres.",
        "array"   => ":attribute no debe tener más de :max elementos.",
    ],
    "mimes"            => ":attribute debe ser un archivo con formato: :values.",
    "min"              => [
        "numeric" => "El tamaño de :attribute debe ser de al menos :min.",
        "file"    => "El tamaño de :attribute debe ser de al menos :min kilobytes.",
        "string"  => ":attribute debe contener al menos :min caracteres.",
        "array"   => ":attribute debe tener al menos :min elementos.",
    ],
    "not_in"           => ":attribute es inválido.",
    "numeric"          => ":attribute debe ser numérico.",
    "regex"            => "El formato de :attribute es inválido.",
    "required"         => "El campo :attribute es obligatorio.",
    "required_if"      => "El campo :attribute es obligatorio cuando :other es :value.",
    "required_with"    => "El campo :attribute es obligatorio cuando :values está presente.",
    "required_with_all" => "El campo :attribute es obligatorio cuando :values está presente.",
    "required_without" => "El campo :attribute es obligatorio cuando :values no está presente.",
    "required_without_all" => "El campo :attribute es obligatorio cuando ninguno de :values estén presentes.",
    "same"             => ":attribute y :other deben coincidir.",
    "size"             => [
        "numeric" => "El tamaño de :attribute debe ser :size.",
        "file"    => "El tamaño de :attribute debe ser :size kilobytes.",
        "string"  => ":attribute debe contener :size caracteres.",
        "array"   => ":attribute debe contener :size elementos.",
    ],
    "string"           => "The :attribute must be a string.",
    "timezone"         => "El :attribute debe ser una zona válida.",
    "unique"           => ":attribute ya ha sido registrado.",
    "url"              => "El formato :attribute es inválido.",
    "img_min_size"     => "El tamaño de la imagen es muy pequeño.",

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

        // Listing create and edit
        'engine_size' => [
            'required'  => 'El campo cilindraje es obligatorio.',
            'numeric'   => 'El campo cilindraje debe ser numerico.',
        ],
        'listing_type' => [
            'required'  => 'Debes seleccionar un tipo de moto.',
            'numeric'   => 'El tipo de moto no es valido.',
        ],
        'manufacturer_id' => [
            'required'  => 'Debes seleccionar una marca.',
            'numeric'   => 'El campo marca debe ser numerico.',
        ],
        'model_id' => [
            'required'  => 'Debes seleccionar una referencia.',
            'numeric'   => 'El campo referencia debe ser numerico.',
        ],
        'fuel_type' => [
            'required'  => 'Debes seleccionar un tipo de combustible.',
            'numeric'   => 'El campo combustible debe ser numerico.',
        ],
        'transmission_type' => [
            'required'  => 'Debes seleccionar un tipo de transmisión.',
            'numeric'   => 'El campo transmisión debe ser numerico.',
        ],
        'city_id' => [
            'required'  => 'Debes seleccionar una ciudad.',
            'numeric'   => 'El campo ciudad debe ser numerico.',
        ],
        'district' => [
            'max'       => 'El campo distrito no puede superar los :max caracteres.',
        ],
        'price' => [
            'required'  => 'El campo precio es obligatorio.',
            'numeric'   => 'El campo precio debe ser numerico.',
        ],
        'year' => [
            'required'  => 'El campo modelo es obligatorio.',
            'numeric'   => 'El campo modelo debe ser numerico.',
        ],
        'odometer' => [
            'required'  => 'El campo kilometraje es obligatorio.',
            'numeric'   => 'El campo kilometraje debe ser numerico.',
        ],
        'color' => [
            'max'       => 'El campo color no puede superar los :max caracteres.',
        ],
        'license_number' => [
            'max'       => 'El campo numero de placa no puede superar los :max caracteres.',
        ],
        'image' => [
            'max' => 'La imagen no debe pesar más de :max',
        ],

        // Auth
        'password' => [
            'required'  => 'El campo contraseña es obligatorio.',
            'min'       => 'La contraseña debe contener al menos :min caracteres.',
        ],
        'email' => [
            'confirmed' => 'El campo correo electrónico debe coincidir con la confirmación.',
            'required'  => 'El campo correo electrónico es obligatorio.',
            'min'       => 'El correo electrónico debe contener al menos :min caracteres.',
            'unique'    => 'El correo electrónico ya fue registrado.',
        ],
        'name' => [
            'required'  => 'El campo nombre es obligatorio.',
        ],
        'username' => [
            'required'          => 'El campo nombre de usuario es obligatorio.',
            'min'               => 'El nombre de usuario debe ser de minimo :min caracteres.',
            'unique'            => 'El nombre de usuario ya fue registrado.',
        ],
        'phone' => [
            'required'          => 'El campo teléfono es obligatorio.',
            'digits_between'    => 'El teléfono debe contener entre :min y :max caracteres.',
            'unique'            => 'El teléfono ya fue registrado.',
        ],
        'phone_1' => [
            'required'          => 'El campo teléfono es obligatorio.',
            'digits_between'    => 'El teléfono debe contener entre :min y :max caracteres.',
            'unique'            => 'El teléfono ya fue registrado.',
        ],
        'phone_2' => [
            'required'          => 'El campo teléfono es obligatorio.',
            'digits_between'    => 'El teléfono debe contener entre :min y :max caracteres.',
            'unique'            => 'El teléfono ya fue registrado.',
        ],

        // Appointment
        'comments' => [
            'required'  => 'El campo comentarios es obligatorio.',
            'max'       => 'El campo comentarios no debe tener más de :max caracteres.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap attribute place-holders
    | with something more reader friendly such as E-Mail Address instead
    | of "email". This simply helps us make messages a little cleaner.
    |
    */

    'attributes' => [],

];
