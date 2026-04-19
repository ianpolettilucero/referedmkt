<?php
namespace Core;

/**
 * Catalogo de presets visuales para el tema default.
 *
 * Cada preset se materializa como un archivo CSS en
 * public/theme-assets/default/css/presets/{id}.css que override variables
 * del base site.css. Se inyecta como <link> adicional despues del base.
 *
 * El preset activo por sitio se guarda en settings.theme_preset.
 */
final class ThemePresets
{
    /**
     * @return array<string, array{name:string, description:string, vibe:string}>
     */
    public static function all(): array
    {
        return [
            'indigo-night' => [
                'name'        => 'Indigo Night',
                'description' => 'Default. Indigo eléctrico + violet + dorado. Dark premium para cybersec / SaaS B2B / tech.',
                'vibe'        => 'cybersec · SaaS · tech',
            ],
            'hacker-matrix' => [
                'name'        => 'Hacker Matrix',
                'description' => 'Negro absoluto + verde neón + mono font. Vibe terminal / hacking / pentesting.',
                'vibe'        => 'hacking · pentesting · dev tools',
            ],
            'tech-cyan' => [
                'name'        => 'Tech Cyan',
                'description' => 'Azul cyan sobre navy. Limpio, Apple/Cloudflare vibe. Para hosting, dev tools, API.',
                'vibe'        => 'dev tools · hosting · APIs',
            ],
            'industrial-steel' => [
                'name'        => 'Industrial Steel',
                'description' => 'Naranja construcción + hierro + negro. Bordes rectos, tipografía bold. Para herramientas, maquinaria, construcción.',
                'vibe'        => 'herramientas · construcción · industrial',
            ],
            'b2b-corporate' => [
                'name'        => 'B2B Corporate',
                'description' => 'Azul navy + gris profesional. Light mode default. Para servicios financieros B2B, consultoría, legal.',
                'vibe'        => 'consultoría · legal · B2B serio',
            ],
            'retail-vibrant' => [
                'name'        => 'Retail Vibrant',
                'description' => 'Magenta + amarillo + naranja. Energético. Para ecommerce, moda, productos consumer.',
                'vibe'        => 'ecommerce · moda · consumer',
            ],
            'food-warm' => [
                'name'        => 'Food & Warm',
                'description' => 'Tomato + verde olivo + crema. Tipografía friendly. Para comida, delivery, recetas, restaurantes.',
                'vibe'        => 'comida · recetas · delivery',
            ],
            'luxury-gold' => [
                'name'        => 'Luxury Gold',
                'description' => 'Negro absoluto + dorado real. Letter-spacing amplio. Para relojes, joyería, vinos, premium.',
                'vibe'        => 'joyería · relojes · vinos · premium',
            ],
            'finance-emerald' => [
                'name'        => 'Finance Emerald',
                'description' => 'Verde esmeralda + dorado + navy. Tipografía con numerals tabulares. Para fintech, inversiones, trading.',
                'vibe'        => 'fintech · inversiones · trading',
            ],
            'medical-clean' => [
                'name'        => 'Medical Clean',
                'description' => 'Celeste cielo + blanco + verde salud. Light siempre. Para salud, farmacia, wellness, seguros médicos.',
                'vibe'        => 'salud · farmacia · wellness',
            ],
        ];
    }

    public static function get(string $id): ?array
    {
        return self::all()[$id] ?? null;
    }

    /**
     * Valida que el id sea un preset conocido. Evita inyectar nombres arbitrarios
     * en el <link href="..."> del layout.
     */
    public static function exists(string $id): bool
    {
        return isset(self::all()[$id]);
    }
}
