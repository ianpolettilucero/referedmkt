<?php
/**
 * Configuracion del sitio. Generado por bin/export-content.php.
 * Nada secreto va aca: para eso esta .env, que no se commitea.
 */

return [
    'domain' => 'capacero.online',
    'name' => 'Capa Cero',
    'slug' => 'ciberseguridad',
    'theme_name' => 'default',
    'primary_color' => '#ff0000',
    'logo_url' => '/uploads/ciberseguridad/2026/04/capacero-5cc468c5.png',
    'favicon_url' => '/uploads/ciberseguridad/2026/04/capacero-5cc468c5.png',
    // H1 del home. Es la unica pagina donde el titulo no sale del contenido.
    'tagline' => 'Guías y reseñas de ciberseguridad para PyMEs',
    'default_language' => 'es',
    'default_country' => 'AR',
    // {title} es obligatorio: sin el, todas las paginas comparten el mismo <title>.
    'meta_title_template' => '{title} | Capa Cero',
    'meta_description_template' => 'Descubre en Capa Cero las mejores reseñas, guías y análisis sobre ciberseguridad. Protege tus datos y aprende a navegar de forma segura con contenido experto.',
    'affiliate_disclosure_text' => 'Divulgación: este sitio contiene enlaces de afiliados. Podemos recibir una comisión si comprás a través de ellos, sin costo adicional para vos. Esto no influye en nuestras evaluaciones.',
    'google_analytics_id' => 'G-SV0MDSTQL4',
    'google_tag_manager_id' => null,
    'google_ads_id' => 'AW-17984303851',
    'microsoft_clarity_id' => 'wgytkj3a9g',
    'meta_pixel_id' => null,
    'google_search_console_verification' => '4SBe-Ro4jmgI6osGV_kfKjUTBg7HQI73nemlB-w6k4A',
    'settings' => [
        'theme_preset' => 'indigo-night',
        'newsletter_enabled' => '1',
        'newsletter_heading' => 'Suscribite al newsletter',
        'newsletter_description' => 'Recibí guías nuevas y análisis sin spam.',
        'newsletter_button_text' => 'Suscribirme',
        'newsletter_email_field_name' => 'email',
        'newsletter_success_message' => 'Listo. Revisá tu email para confirmar.',
    ],
];
