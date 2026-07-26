<?php
/**
 * Configuracion del sitio. Reemplaza a la tabla `sites` y a `settings`.
 *
 * Los IDs de tracking son publicos (van en el HTML de todas formas), asi que
 * viven aca sin problema. Nada secreto debe entrar en este archivo: para eso
 * esta .env, que no se commitea.
 */

return [
    'domain' => 'capacero.online',
    'name'   => 'Capacero',
    'slug'   => 'capacero',

    'theme_name'    => 'default',
    'primary_color' => '#6366f1',
    'logo_url'      => null,
    'favicon_url'   => null,

    'default_language' => 'es',
    'default_country'  => 'AR',

    'meta_title_template'       => '{title} | Capacero',
    'meta_description_template' => 'Guías y reseñas independientes de herramientas de ciberseguridad para PyMEs y equipos técnicos.',

    'affiliate_disclosure_text' => 'Divulgación: este sitio contiene enlaces de afiliados. Podemos recibir una comisión si comprás a través de nuestros enlaces, sin costo adicional para vos. Esto no influye en nuestras evaluaciones.',

    // Tracking. Dejar en null lo que no uses: el layout omite el snippet.
    'google_analytics_id'                => null,  // G-XXXXXXXXXX
    'google_tag_manager_id'              => null,  // GTM-XXXXXXX
    'google_ads_id'                      => null,  // AW-XXXXXXXXX
    'microsoft_clarity_id'               => null,
    'meta_pixel_id'                      => null,
    'google_search_console_verification' => null,

    // Ex tabla `settings`.
    'settings' => [
        'theme_preset' => 'indigo-night',
        'custom_css'   => '',

        'newsletter_enabled'           => false,
        'newsletter_heading'           => 'Suscribite al newsletter',
        'newsletter_description'       => 'Recibí guías nuevas y análisis sin spam.',
        'newsletter_button_text'       => 'Suscribirme',
        'newsletter_action_url'        => '',
        'newsletter_email_field_name'  => 'email',
        'newsletter_hidden_fields_json' => '',
        'newsletter_success_message'   => 'Listo. Revisá tu email para confirmar.',
    ],
];
