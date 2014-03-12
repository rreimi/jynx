<?php

return array(

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

    "accepted" => "El campo :attribute debe ser aceptado.",
    "active_url" => "El campo :attribute no es una URL válida.",
    "after" => "El campo :attribute debe ser una fecha después de :date.",
    "alpha" => "El campo :attribute sólo puede contener letras.",
    "alpha_dash" => "El campo :attribute sólo puede contener letras, números y guiones.",
    "alpha_num" => "El campo :attribute sólo puede contener letras y números.",
    "before" => "El campo :attribute debe ser una fecha antes :date.",
    "between" => array(
        "numeric" => "El campo :attribute debe estar entre :min - :max.",
        "file" => "El campo :attribute debe estar entre :min - :max kilobytes.",
        "string" => "El campo :attribute debe estar entre :min - :max caracteres.",
    ),
    "confirmed" => "El campo :attribute confirmación no coincide.",
    "date" => "El campo :attribute no es una fecha válida.",
    "date_format" => "El campo :attribute no coincide con el formato :format.",
    "different" => "Los campos :attribute y :other deben ser diferentes.",
    "digits" => "El campo :attribute debe ser :digits numérico.",
    "digits_between" => "El campo :attribute debe estar entre :min y :max dígitos.",
    "email" => "El formato del :attribute es inválido.",
    "exists" => "El campo :attribute seleccionado es inválido.",
    "image" => "El campo :attribute debe ser una imagen.",
    "in" => "El campo :attribute seleccionado es inválido.",
    "integer" => "El campo :attribute debe ser un entero.",
    "ip" => "El campo :attribute debe ser una dirección IP válida.",
    "match" => "El formato :attribute es inválido.",
    "max" => array(
        "numeric" => "El campo :attribute debe ser menor que :max.",
        "file" => "El campo :attribute debe ser menor que :max kilobytes.",
        "string" => "El campo :attribute debe ser menor que :max caracteres.",
    ),
    "mimes" => "El campo :attribute debe ser un archivo de tipo :values.",
    "min" => array(
        "numeric" => "El campo :attribute debe tener al menos :min.",
        "file" => "El campo :attribute debe tener al menos :min KB.",
        "string" => "El campo :attribute debe tener al menos :min caracteres.",
    ),
    "not_in" => "El campo :attribute seleccionado es inválido.",
    "numeric" => "El campo :attribute debe ser un número.",
    "regex"  => "El campo :attribute tiene formato inválido.",
    "required" => "El campo :attribute es requerido.",
    "required_if" => "El campo :attribute es requerido cuando :other tiene como valor :value.",
    "required_with" => "El campo :attribute es requerido cuando :values está presente.",
    "required_without" => "El campo :attribute es requerido cuando :values no está presente.",
    "same" => "Los campos :attribute y :other deben coincidir.",
    "size" => array(
        "numeric" => "El campo :attribute debe tener :size.",
        "file" => "El campo :attribute debe tener :size KB.",
        "string" => "El campo :attribute debe tener :size caracteres.",
    ),
    "unique" => "El campo :attribute ya ha sido tomado.",
    "url" => "El formato de :attribute es inválido.",
    "publication_category_required" => "Debe seleccionar al menos una categoría",
    "publication_invalid_date_range" => "Rango inválido. La fecha Hasta debe ser posterior a la fecha Desde.",
    "publication_limit_date_range" => "Rango inválido. El máximo permitido es de 3 meses.",
    "current_password_currentpassword" => "La contraseña indicada no coincide con la contraseña actual",
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

    'custom' => array(
        'categories' => array(
            'required' => 'Debe seleccionar al menos una categoría',
        ),
        'from_date' => array(
            'date_format' => 'El formato de fecha no es válido',
        ),
        'comment' => array(
            'max' => 'asdasdas'
        )
    ),


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

    'attributes' => array(
        'title' => 'Título',
        'short_description' => 'Descripción corta',
        'long_description' => 'Descripción',
        'from_date' => 'Desde',
        'to_date' => 'Hasta',
        'categories_name' => 'Categorías',
        'status' => 'Estatus',

        'email' => 'Correo',
        'full_name' => 'Nombre',
        'external_url' => 'Url externa',
        'order' => 'Orden de presentación',
        'password' => 'Contraseña',
        'password_confirmation' => 'Confirma Contraseña',
        'seller_name' => 'Anunciante',
        'publisher_type' => ' Tipo de Persona',
        'letter_rif_ci' => 'Letra Cédula / Rif',
        'rif_ci' => 'Cédula / Rif',
        'state' => 'Estado',
        'city' => 'Ciudad',
        'phone1' => 'Teléfono 1',
        'phone2' => 'Teléfono 2',
        'phone2' => 'Teléfono 2',
        'suggested_products' => 'de sectores de negocio para productos',
        'suggested_services' => 'de sectores de negocio para servicios',
        'avatar' => 'Imagen de perfil',

        'publisher_id_type' => 'Tipo de documento',
        'publisher_id' => 'Cédula / Rif',
        'publisher_seller' => 'Anunciante',
        'publisher_state' => 'Estado',
        'publisher_city' => 'Ciudad',
        'publisher_phone1' => 'Teléfono 1',

        'name' => 'Nombre completo',
        'phone' => 'Teléfono',
        'subject' => 'Asunto',
        'contact_message' => 'Mensaje',
        'comment' => 'Comentario',
        'login_email' => 'Correo Electrónico',
        'login_password' => 'Password',
        'age' => 'Edad',
        'area_ids' => 'Áreas',
        'reset_password' => 'Contraseña',
        'reset_password_confirmation' => 'Confirme la contraseña',
        'web' => 'Página web'
    ),

);
