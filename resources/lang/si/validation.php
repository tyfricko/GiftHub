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

    'accepted' => ':attribute mora biti sprejet.',
    'accepted_if' => ':attribute mora biti sprejet, ko je :other :value.',
    'active_url' => ':attribute ni veljaven URL.',
    'after' => ':attribute mora biti datum po :date.',
    'after_or_equal' => ':attribute mora biti datum po ali enak :date.',
    'alpha' => ':attribute lahko vsebuje samo črke.',
    'alpha_dash' => ':attribute lahko vsebuje samo črke, številke, pomišljaje in podčrtaje.',
    'alpha_num' => ':attribute lahko vsebuje samo črke in številke.',
    'array' => ':attribute mora biti polje.',
    'ascii' => ':attribute lahko vsebuje samo enobajtne alfanumerične znake in simbole.',
    'before' => ':attribute mora biti datum pred :date.',
    'before_or_equal' => ':attribute mora biti datum pred ali enak :date.',
    'between' => [
        'array' => ':attribute mora imeti med :min in :max elementov.',
        'file' => ':attribute mora biti med :min in :max kilobajti.',
        'numeric' => ':attribute mora biti med :min in :max.',
        'string' => ':attribute mora biti med :min in :max znaki.',
    ],
    'boolean' => ':attribute mora biti true ali false.',
    'can' => ':attribute vsebuje nedovoljeno vrednost.',
    'confirmed' => 'Potrditev :attribute se ne ujema.',
    'current_password' => 'Geslo je napačno.',
    'date' => ':attribute ni veljaven datum.',
    'date_equals' => ':attribute mora biti datum enak :date.',
    'date_format' => ':attribute se ne ujema z obliko :format.',
    'decimal' => ':attribute mora imeti :decimal decimalnih mest.',
    'declined' => ':attribute mora biti zavrnjen.',
    'declined_if' => ':attribute mora biti zavrnjen, ko je :other :value.',
    'different' => ':attribute in :other morata biti različna.',
    'digits' => ':attribute mora biti :digits številk.',
    'digits_between' => ':attribute mora biti med :min in :max številkami.',
    'dimensions' => ':attribute ima neveljavne dimenzije slike.',
    'distinct' => ':attribute ima podvojeno vrednost.',
    'doesnt_end_with' => ':attribute se ne sme končati z enim od naslednjih: :values.',
    'doesnt_start_with' => ':attribute se ne sme začeti z enim od naslednjih: :values.',
    'email' => ':attribute mora biti veljaven e-poštni naslov.',
    'ends_with' => ':attribute se mora končati z enim od naslednjih: :values.',
    'enum' => 'Izbrani :attribute ni veljaven.',
    'exists' => 'Izbrani :attribute ni veljaven.',
    'extensions' => ':attribute mora imeti eno od naslednjih končnic: :values.',
    'file' => ':attribute mora biti datoteka.',
    'filled' => ':attribute mora imeti vrednost.',
    'gt' => [
        'array' => ':attribute mora imeti več kot :value elementov.',
        'file' => ':attribute mora biti večji od :value kilobajtov.',
        'numeric' => ':attribute mora biti večji od :value.',
        'string' => ':attribute mora biti daljši od :value znakov.',
    ],
    'gte' => [
        'array' => ':attribute mora imeti :value elementov ali več.',
        'file' => ':attribute mora biti večji ali enak :value kilobajtov.',
        'numeric' => ':attribute mora biti večji ali enak :value.',
        'string' => ':attribute mora biti daljši ali enak :value znakov.',
    ],
    'hex_color' => ':attribute mora biti veljavna šestnajstiška barva.',
    'image' => ':attribute mora biti slika.',
    'in' => 'Izbrani :attribute ni veljaven.',
    'in_array' => ':attribute mora obstajati v :other.',
    'integer' => ':attribute mora biti celo število.',
    'ip' => ':attribute mora biti veljaven IP naslov.',
    'ipv4' => ':attribute mora biti veljaven IPv4 naslov.',
    'ipv6' => ':attribute mora biti veljaven IPv6 naslov.',
    'json' => ':attribute mora biti veljaven JSON niz.',
    'lowercase' => ':attribute mora biti z malimi črkami.',
    'lt' => [
        'array' => ':attribute mora imeti manj kot :value elementov.',
        'file' => ':attribute mora biti manjši od :value kilobajtov.',
        'numeric' => ':attribute mora biti manjši od :value.',
        'string' => ':attribute mora biti krajši od :value znakov.',
    ],
    'lte' => [
        'array' => ':attribute ne sme imeti več kot :value elementov.',
        'file' => ':attribute mora biti manjši ali enak :value kilobajtov.',
        'numeric' => ':attribute mora biti manjši ali enak :value.',
        'string' => ':attribute mora biti krajši ali enak :value znakov.',
    ],
    'mac_address' => ':attribute mora biti veljaven MAC naslov.',
    'max' => [
        'array' => ':attribute ne sme imeti več kot :max elementov.',
        'file' => ':attribute ne sme biti večji od :max kilobajtov.',
        'numeric' => ':attribute ne sme biti večji od :max.',
        'string' => ':attribute ne sme biti daljši od :max znakov.',
    ],
    'max_digits' => ':attribute ne sme imeti več kot :max številk.',
    'mimes' => ':attribute mora biti datoteka tipa: :values.',
    'mimetypes' => ':attribute mora biti datoteka tipa: :values.',
    'min' => [
        'array' => ':attribute mora imeti vsaj :min elementov.',
        'file' => ':attribute mora biti vsaj :min kilobajtov.',
        'numeric' => ':attribute mora biti vsaj :min.',
        'string' => ':attribute mora biti vsaj :min znakov.',
    ],
    'min_digits' => ':attribute mora imeti vsaj :min številk.',
    'missing' => ':attribute mora manjkati.',
    'missing_if' => ':attribute mora manjkati, ko je :other :value.',
    'missing_unless' => ':attribute mora manjkati, razen če je :other :value.',
    'missing_with' => ':attribute mora manjkati, ko je prisoten :values.',
    'missing_with_all' => ':attribute mora manjkati, ko so prisotni :values.',
    'multiple_of' => ':attribute mora biti večkratnik :value.',
    'not_in' => 'Izbrani :attribute ni veljaven.',
    'not_regex' => 'Oblika :attribute ni veljavna.',
    'numeric' => ':attribute mora biti število.',
    'password' => [
        'letters' => ':attribute mora vsebovati vsaj eno črko.',
        'mixed' => ':attribute mora vsebovati vsaj eno veliko in eno malo črko.',
        'numbers' => ':attribute mora vsebovati vsaj eno številko.',
        'symbols' => ':attribute mora vsebovati vsaj en simbol.',
        'uncompromised' => 'Podani :attribute se je pojavil v podatkovni uhajanju. Izberite drug :attribute.',
    ],
    'present' => ':attribute mora biti prisoten.',
    'present_if' => ':attribute mora biti prisoten, ko je :other :value.',
    'present_unless' => ':attribute mora biti prisoten, razen če je :other :value.',
    'present_with' => ':attribute mora biti prisoten, ko je prisoten :values.',
    'present_with_all' => ':attribute mora biti prisoten, ko so prisotni :values.',
    'prohibited' => ':attribute je prepovedan.',
    'prohibited_if' => ':attribute je prepovedan, ko je :other :value.',
    'prohibited_unless' => ':attribute je prepovedan, razen če je :other v :values.',
    'prohibits' => ':attribute prepoveduje prisotnost :other.',
    'regex' => 'Oblika :attribute ni veljavna.',
    'required' => ':attribute je obvezen.',
    'required_array_keys' => ':attribute mora vsebovati vnose za: :values.',
    'required_if' => ':attribute je obvezen, ko je :other :value.',
    'required_if_accepted' => ':attribute je obvezen, ko je :other sprejet.',
    'required_unless' => ':attribute je obvezen, razen če je :other v :values.',
    'required_with' => ':attribute je obvezen, ko je prisoten :values.',
    'required_with_all' => ':attribute je obvezen, ko so prisotni :values.',
    'required_without' => ':attribute je obvezen, ko :values ni prisoten.',
    'required_without_all' => ':attribute je obvezen, ko noben od :values ni prisoten.',
    'same' => ':attribute se mora ujemati z :other.',
    'size' => [
        'array' => ':attribute mora vsebovati :size elementov.',
        'file' => ':attribute mora biti :size kilobajtov.',
        'numeric' => ':attribute mora biti :size.',
        'string' => ':attribute mora biti :size znakov.',
    ],
    'starts_with' => ':attribute se mora začeti z enim od naslednjih: :values.',
    'string' => ':attribute mora biti niz.',
    'timezone' => ':attribute mora biti veljavna časovna zona.',
    'unique' => ':attribute je že zaseden.',
    'uploaded' => 'Prenos :attribute ni uspel.',
    'uppercase' => ':attribute mora biti z velikimi črkami.',
    'url' => ':attribute mora biti veljaven URL.',
    'ulid' => ':attribute mora biti veljaven ULID.',
    'uuid' => ':attribute mora biti veljaven UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "rule.attribute". This makes it quick to specify a specific
    | custom language line for a given attribute rule.
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
