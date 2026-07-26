<?php
/**
 * Links de afiliado. La clave es el tracking_slug: lo que va en /go/{slug}.
 *
 * `destination_url` es la URL real del programa. Nunca se expone al visitante:
 * /go/ lee esta tabla, agrega los UTM y redirige.
 */

return [
    'bitdefender' => [
        'name'            => 'Bitdefender GravityZone',
        'destination_url' => 'https://example.com/bitdefender',
        'network'         => 'Impact',
        'active'          => true,
    ],
    '1password' => [
        'name'            => '1Password Business',
        'destination_url' => 'https://example.com/1password',
        'network'         => 'Impact',
        'active'          => true,
    ],
    'nordlayer' => [
        'name'            => 'NordLayer',
        'destination_url' => 'https://example.com/nordlayer',
        'network'         => 'PartnerStack',
        'active'          => true,
    ],
];
