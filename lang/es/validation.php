<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Mensajes de validación en español
    |--------------------------------------------------------------------------
    */

    'accepted' => 'Debes aceptar :attribute.',
    'accepted_if' => 'Debes aceptar :attribute cuando :other sea :value.',
    'active_url' => ':Attribute no es una URL válida.',
    'after' => ':Attribute debe ser una fecha posterior a :date.',
    'after_or_equal' => ':Attribute debe ser una fecha posterior o igual a :date.',
    'alpha' => ':Attribute solo debe contener letras.',
    'alpha_dash' => ':Attribute solo debe contener letras, números, guiones y guiones bajos.',
    'alpha_num' => ':Attribute solo debe contener letras y números.',
    'array' => ':Attribute debe ser una lista.',
    'ascii' => ':Attribute solo debe contener caracteres alfanuméricos y símbolos de un solo byte.',
    'before' => ':Attribute debe ser una fecha anterior a :date.',
    'before_or_equal' => ':Attribute debe ser una fecha anterior o igual a :date.',
    'between' => [
        'array' => ':Attribute debe tener entre :min y :max elementos.',
        'file' => ':Attribute debe pesar entre :min y :max kilobytes.',
        'numeric' => ':Attribute debe estar entre :min y :max.',
        'string' => ':Attribute debe tener entre :min y :max caracteres.',
    ],
    'boolean' => ':Attribute debe ser verdadero o falso.',
    'can' => ':Attribute contiene un valor no autorizado.',
    'confirmed' => 'La confirmación de :attribute no coincide.',
    'current_password' => 'La contraseña es incorrecta.',
    'date' => ':Attribute no es una fecha válida.',
    'date_equals' => ':Attribute debe ser una fecha igual a :date.',
    'date_format' => ':Attribute no coincide con el formato :format.',
    'decimal' => ':Attribute debe tener :decimal decimales.',
    'declined' => 'Debes rechazar :attribute.',
    'declined_if' => 'Debes rechazar :attribute cuando :other sea :value.',
    'different' => ':Attribute y :other deben ser diferentes.',
    'digits' => ':Attribute debe tener :digits dígitos.',
    'digits_between' => ':Attribute debe tener entre :min y :max dígitos.',
    'dimensions' => ':Attribute tiene dimensiones de imagen no válidas.',
    'distinct' => ':Attribute tiene un valor duplicado.',
    'doesnt_end_with' => ':Attribute no debe terminar con uno de los siguientes valores: :values.',
    'doesnt_start_with' => ':Attribute no debe comenzar con uno de los siguientes valores: :values.',
    'email' => ':Attribute debe ser un correo electrónico válido.',
    'ends_with' => ':Attribute debe terminar con uno de los siguientes valores: :values.',
    'enum' => 'El valor seleccionado para :attribute no es válido.',
    'exists' => 'El valor seleccionado para :attribute no es válido.',
    'file' => ':Attribute debe ser un archivo.',
    'filled' => ':Attribute debe tener un valor.',
    'gt' => [
        'array' => ':Attribute debe tener más de :value elementos.',
        'file' => ':Attribute debe pesar más de :value kilobytes.',
        'numeric' => ':Attribute debe ser mayor que :value.',
        'string' => ':Attribute debe tener más de :value caracteres.',
    ],
    'gte' => [
        'array' => ':Attribute debe tener :value elementos o más.',
        'file' => ':Attribute debe pesar :value kilobytes o más.',
        'numeric' => ':Attribute debe ser mayor o igual que :value.',
        'string' => ':Attribute debe tener :value caracteres o más.',
    ],
    'image' => ':Attribute debe ser una imagen.',
    'in' => 'El valor seleccionado para :attribute no es válido.',
    'in_array' => ':Attribute no existe en :other.',
    'integer' => ':Attribute debe ser un número entero.',
    'ip' => ':Attribute debe ser una dirección IP válida.',
    'ipv4' => ':Attribute debe ser una dirección IPv4 válida.',
    'ipv6' => ':Attribute debe ser una dirección IPv6 válida.',
    'json' => ':Attribute debe ser una cadena JSON válida.',
    'list' => ':Attribute debe ser una lista.',
    'lowercase' => ':Attribute debe estar en minúsculas.',
    'lt' => [
        'array' => ':Attribute debe tener menos de :value elementos.',
        'file' => ':Attribute debe pesar menos de :value kilobytes.',
        'numeric' => ':Attribute debe ser menor que :value.',
        'string' => ':Attribute debe tener menos de :value caracteres.',
    ],
    'lte' => [
        'array' => ':Attribute no debe tener más de :value elementos.',
        'file' => ':Attribute debe pesar :value kilobytes o menos.',
        'numeric' => ':Attribute debe ser menor o igual que :value.',
        'string' => ':Attribute debe tener :value caracteres o menos.',
    ],
    'mac_address' => ':Attribute debe ser una dirección MAC válida.',
    'max' => [
        'array' => ':Attribute no debe tener más de :max elementos.',
        'file' => ':Attribute no debe pesar más de :max kilobytes.',
        'numeric' => ':Attribute no debe ser mayor que :max.',
        'string' => ':Attribute no debe tener más de :max caracteres.',
    ],
    'max_digits' => ':Attribute no debe tener más de :max dígitos.',
    'mimes' => ':Attribute debe ser un archivo de tipo: :values.',
    'mimetypes' => ':Attribute debe ser un archivo de tipo: :values.',
    'min' => [
        'array' => ':Attribute debe tener al menos :min elementos.',
        'file' => ':Attribute debe pesar al menos :min kilobytes.',
        'numeric' => ':Attribute debe ser al menos :min.',
        'string' => ':Attribute debe tener al menos :min caracteres.',
    ],
    'min_digits' => ':Attribute debe tener al menos :min dígitos.',
    'missing' => ':Attribute no debe estar presente.',
    'multiple_of' => ':Attribute debe ser múltiplo de :value.',
    'not_in' => 'El valor seleccionado para :attribute no es válido.',
    'not_regex' => 'El formato de :attribute no es válido.',
    'numeric' => ':Attribute debe ser un número.',
    'password' => [
        'letters' => ':Attribute debe contener al menos una letra.',
        'mixed' => ':Attribute debe contener al menos una mayúscula y una minúscula.',
        'numbers' => ':Attribute debe contener al menos un número.',
        'symbols' => ':Attribute debe contener al menos un símbolo.',
        'uncompromised' => ':Attribute apareció en una filtración de datos. Elige otra contraseña.',
    ],
    'present' => ':Attribute debe estar presente.',
    'prohibited' => ':Attribute no está permitido.',
    'regex' => 'El formato de :attribute no es válido.',
    'required' => ':Attribute es obligatorio.',
    'required_array_keys' => ':Attribute debe contener entradas para: :values.',
    'required_if' => ':Attribute es obligatorio cuando :other es :value.',
    'required_if_accepted' => ':Attribute es obligatorio cuando se acepta :other.',
    'required_unless' => ':Attribute es obligatorio a menos que :other esté en :values.',
    'required_with' => ':Attribute es obligatorio cuando :values está presente.',
    'required_with_all' => ':Attribute es obligatorio cuando :values están presentes.',
    'required_without' => ':Attribute es obligatorio cuando :values no está presente.',
    'required_without_all' => ':Attribute es obligatorio cuando ninguno de :values está presente.',
    'same' => ':Attribute y :other deben coincidir.',
    'size' => [
        'array' => ':Attribute debe contener :size elementos.',
        'file' => ':Attribute debe pesar :size kilobytes.',
        'numeric' => ':Attribute debe ser :size.',
        'string' => ':Attribute debe tener :size caracteres.',
    ],
    'starts_with' => ':Attribute debe comenzar con uno de los siguientes valores: :values.',
    'string' => ':Attribute debe ser texto.',
    'timezone' => ':Attribute debe ser una zona horaria válida.',
    'unique' => ':Attribute ya ha sido registrado.',
    'uploaded' => 'No se pudo subir :attribute.',
    'uppercase' => ':Attribute debe estar en mayúsculas.',
    'url' => ':Attribute debe ser una URL válida.',
    'ulid' => ':Attribute debe ser un ULID válido.',
    'uuid' => ':Attribute debe ser un UUID válido.',

    /*
    |--------------------------------------------------------------------------
    | Mensajes personalizados
    |--------------------------------------------------------------------------
    */

    'custom' => [
        // Estas reglas usan la palabra "today" y sin esto el mensaje la mostraría en inglés.
        'fecha_compra' => [
            'before_or_equal' => 'La fecha de compra no puede ser una fecha futura.',
        ],
        'productos.*.fecha_vencimiento' => [
            'after' => 'La fecha de vencimiento debe ser posterior a hoy.',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Nombres de los campos
    |--------------------------------------------------------------------------
    | Así se muestran los campos en los mensajes de error.
    */

    'attributes' => [
        // Productos
        'nombre' => 'el nombre',
        'presentacion' => 'la presentación',
        'marca' => 'la marca',
        'forma_venta' => 'la forma de venta',
        'principio_activo' => 'el principio activo',
        'codigo_barra' => 'el código de barras',
        'precio_venta' => 'el precio de venta',
        'stock_minimo' => 'el stock mínimo',
        'categoria' => 'la categoría',
        'estado' => 'el estado',

        // Usuarios
        'nombres' => 'los nombres',
        'apellidos' => 'los apellidos',
        'usuario' => 'el usuario',
        'telefono' => 'el teléfono',
        'correo' => 'el correo',
        'rol' => 'el rol',
        'password' => 'la contraseña',
        'password_confirmation' => 'la confirmación de la contraseña',

        // Proveedores
        'tipo_producto' => 'el tipo de producto',
        'fecha_visita' => 'la fecha de visita',

        // Compras
        'proveedor_id' => 'el proveedor',
        'fecha_compra' => 'la fecha de compra',
        'productos' => 'los productos',
        'productos.*.producto_id' => 'el producto',
        'productos.*.cantidad' => 'la cantidad',
        'productos.*.precio_unitario' => 'el precio unitario',
        'productos.*.fecha_vencimiento' => 'la fecha de vencimiento',

        // Ventas (la cantidad de cada producto)
        'productos.*' => 'la cantidad',

        // Anulación de ventas
        'motivo_anulacion' => 'el motivo de anulación',

        // Reportes
        'desde' => 'la fecha inicial',
        'hasta' => 'la fecha final',
    ],

];