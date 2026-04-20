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
     * @return array<string, array{name:string, description:string, vibe:string, swatches:array<int,string>, mode:string}>
     */
    public static function all(): array
    {
        return [
            'indigo-night' => [
                'name'        => 'Indigo Night',
                'description' => 'Default. Indigo eléctrico + violet + dorado. Dark premium.',
                'vibe'        => 'cybersec · SaaS · tech',
                'swatches'    => ['#0a0e1a', '#6366f1', '#a855f7', '#fbbf24'],
                'mode'        => 'dark',
            ],
            'hacker-matrix' => [
                'name'        => 'Hacker Matrix',
                'description' => 'Negro absoluto + verde neón + mono font. Vibe terminal.',
                'vibe'        => 'hacking · pentesting · dev tools',
                'swatches'    => ['#000000', '#00ff41', '#00d4ff', '#ffd700'],
                'mode'        => 'dark',
            ],
            'tech-cyan' => [
                'name'        => 'Tech Cyan',
                'description' => 'Azul cyan sobre navy. Limpio, Apple/Cloudflare vibe.',
                'vibe'        => 'dev tools · hosting · APIs',
                'swatches'    => ['#0b1120', '#06b6d4', '#0ea5e9', '#facc15'],
                'mode'        => 'dark',
            ],
            'industrial-steel' => [
                'name'        => 'Industrial Steel',
                'description' => 'Naranja construcción + hierro + negro. Bordes rectos, bold.',
                'vibe'        => 'herramientas · construcción · industrial',
                'swatches'    => ['#111111', '#f97316', '#fbbf24', '#4a4a4a'],
                'mode'        => 'dark',
            ],
            'b2b-corporate' => [
                'name'        => 'B2B Corporate',
                'description' => 'Azul navy + gris profesional. Light default.',
                'vibe'        => 'consultoría · legal · B2B serio',
                'swatches'    => ['#f8fafc', '#1e40af', '#0891b2', '#b45309'],
                'mode'        => 'light',
            ],
            'retail-vibrant' => [
                'name'        => 'Retail Vibrant',
                'description' => 'Magenta + amarillo + naranja. Energético.',
                'vibe'        => 'ecommerce · moda · consumer',
                'swatches'    => ['#fff7fb', '#ec4899', '#f97316', '#eab308'],
                'mode'        => 'light',
            ],
            'food-warm' => [
                'name'        => 'Food & Warm',
                'description' => 'Tomato + verde olivo + crema. Tipografía friendly.',
                'vibe'        => 'comida · recetas · delivery',
                'swatches'    => ['#fffaf3', '#dc2626', '#16a34a', '#f97316'],
                'mode'        => 'light',
            ],
            'luxury-gold' => [
                'name'        => 'Luxury Gold',
                'description' => 'Negro absoluto + dorado real. Letter-spacing amplio.',
                'vibe'        => 'joyería · relojes · vinos · premium',
                'swatches'    => ['#000000', '#d4af37', '#ffd700', '#ffffff'],
                'mode'        => 'dark',
            ],
            'finance-emerald' => [
                'name'        => 'Finance Emerald',
                'description' => 'Verde esmeralda + dorado + navy. Tabular numerals.',
                'vibe'        => 'fintech · inversiones · trading',
                'swatches'    => ['#041512', '#059669', '#d4af37', '#10b981'],
                'mode'        => 'dark',
            ],
            'medical-clean' => [
                'name'        => 'Medical Clean',
                'description' => 'Celeste cielo + blanco + verde salud. Light siempre.',
                'vibe'        => 'salud · farmacia · wellness',
                'swatches'    => ['#f0f9ff', '#0ea5e9', '#22c55e', '#bae6fd'],
                'mode'        => 'light',
            ],
            'minimal-mono' => [
                'name'        => 'Minimal Mono',
                'description' => 'Blanco y negro con un solo accent rojo. Zine/editorial minimalista.',
                'vibe'        => 'minimal · editorial · tipografia',
                'swatches'    => ['#fafaf9', '#18181b', '#dc2626', '#a8a29e'],
                'mode'        => 'light',
            ],
            'forest-green' => [
                'name'        => 'Forest Green',
                'description' => 'Verde bosque + tierra + crema. Outdoor, naturaleza, sustentable.',
                'vibe'        => 'naturaleza · outdoor · sustentable',
                'swatches'    => ['#0a1410', '#65a30d', '#a16207', '#fbbf24'],
                'mode'        => 'dark',
            ],
            'ocean-blue' => [
                'name'        => 'Ocean Blue',
                'description' => 'Azul océano + turquesa. Calm, wellness, productos zen.',
                'vibe'        => 'viajes · wellness · zen',
                'swatches'    => ['#051b2e', '#0284c7', '#14b8a6', '#5eead4'],
                'mode'        => 'dark',
            ],
            'sunset-gradient' => [
                'name'        => 'Sunset Gradient',
                'description' => 'Naranja + rosa + violeta. Creativo, artistico, diseño.',
                'vibe'        => 'diseño · arte · creatividad',
                'swatches'    => ['#1a0b1f', '#f97316', '#ec4899', '#a855f7'],
                'mode'        => 'dark',
            ],
            'paper-editorial' => [
                'name'        => 'Paper Editorial',
                'description' => 'Color papel + tinta azul. Serif. Blog largo form, revistas, libros.',
                'vibe'        => 'blog · revistas · libros · long-form',
                'swatches'    => ['#faf7f0', '#1e3a8a', '#991b1b', '#b8a986'],
                'mode'        => 'light',
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
