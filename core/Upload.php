<?php
namespace Core;

/**
 * Manejo de uploads de imagenes.
 *
 * Seguridad:
 *   - Whitelist de MIME por contenido real (no por extension).
 *   - Filename regenerado (no se confia en el nombre del usuario).
 *   - Guardado fuera del namespace /admin/* y dentro de /public/uploads/ para
 *     servirse estaticamente.
 *   - Extension forzada segun MIME real (evita file.php.jpg + exec por mal config).
 *   - Max size por archivo configurable.
 */
final class Upload
{
    public const MAX_BYTES = 5 * 1024 * 1024; // 5 MB

    /** @var array<string, string> MIME -> extension forzada */
    public const ALLOWED = [
        'image/jpeg' => 'jpg',
        'image/png'  => 'png',
        'image/webp' => 'webp',
        'image/gif'  => 'gif',
        'image/svg+xml' => 'svg',
    ];

    /**
     * Procesa un upload y lo persiste. Devuelve info del archivo.
     *
     * @param array{name:string, type:string, tmp_name:string, error:int, size:int} $file
     * @return array{path:string, url:string, filename:string, mime:string, size:int, width:?int, height:?int}
     */
    public static function store(array $file, int $siteId, string $siteSlug): array
    {
        if (!isset($file['error']) || is_array($file['error'])) {
            throw new \RuntimeException('Request invalido.');
        }
        switch ($file['error']) {
            case UPLOAD_ERR_OK: break;
            case UPLOAD_ERR_INI_SIZE:
            case UPLOAD_ERR_FORM_SIZE:
                throw new \RuntimeException('Archivo excede el tamaño permitido.');
            case UPLOAD_ERR_NO_FILE:
                throw new \RuntimeException('No se adjunto ningun archivo.');
            default:
                throw new \RuntimeException('Error subiendo el archivo (#' . $file['error'] . ').');
        }
        if ($file['size'] > self::MAX_BYTES) {
            throw new \RuntimeException('Archivo > ' . (self::MAX_BYTES / 1048576) . ' MB.');
        }
        if (!is_uploaded_file($file['tmp_name'])) {
            throw new \RuntimeException('Upload invalido.');
        }

        $mime = self::detectMime($file['tmp_name']);
        if (!isset(self::ALLOWED[$mime])) {
            throw new \RuntimeException("Tipo no permitido: $mime");
        }
        $ext = self::ALLOWED[$mime];

        // Verificacion defensiva para SVG: bloquear si contiene <script>.
        if ($mime === 'image/svg+xml') {
            $head = (string)file_get_contents($file['tmp_name'], false, null, 0, 65536);
            if (preg_match('/<script|on\w+\s*=|javascript:/i', $head)) {
                throw new \RuntimeException('SVG con contenido activo rechazado.');
            }
        }

        // Estructura: uploads/{site-slug}/YYYY/MM/
        $relDir = 'uploads/' . self::safeSegment($siteSlug) . '/' . date('Y') . '/' . date('m');
        $absDir = APP_ROOT . '/public/' . $relDir;
        if (!is_dir($absDir) && !mkdir($absDir, 0755, true) && !is_dir($absDir)) {
            throw new \RuntimeException('No se pudo crear el directorio de upload.');
        }

        $baseName = self::safeBasename(pathinfo($file['name'], PATHINFO_FILENAME));
        $rand = bin2hex(random_bytes(4));
        $filename = ($baseName ?: 'img') . '-' . $rand . '.' . $ext;
        $absPath = $absDir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $absPath)) {
            throw new \RuntimeException('No se pudo mover el archivo.');
        }
        @chmod($absPath, 0644);

        [$w, $h] = self::imageSize($absPath, $mime);

        return [
            'path'     => $relDir . '/' . $filename,
            'url'      => '/' . $relDir . '/' . $filename,
            'filename' => $filename,
            'mime'     => $mime,
            'size'     => (int)$file['size'],
            'width'    => $w,
            'height'   => $h,
        ];
    }

    public static function delete(string $relPath): bool
    {
        $abs = APP_ROOT . '/public/' . ltrim($relPath, '/');
        // Defensa path traversal.
        $real = realpath($abs);
        $baseReal = realpath(APP_ROOT . '/public/uploads');
        if (!$real || !$baseReal || strncmp($real, $baseReal, strlen($baseReal)) !== 0) {
            return false;
        }
        return @unlink($real);
    }

    public static function detectMime(string $path): string
    {
        if (function_exists('finfo_open')) {
            $f = finfo_open(FILEINFO_MIME_TYPE);
            $m = $f ? finfo_file($f, $path) : false;
            if ($f) { finfo_close($f); }
            if ($m) { return (string)$m; }
        }
        // Fallback: mime_content_type
        if (function_exists('mime_content_type')) {
            $m = mime_content_type($path);
            if ($m) { return (string)$m; }
        }
        return 'application/octet-stream';
    }

    /**
     * @return array{0:?int, 1:?int}
     */
    private static function imageSize(string $path, string $mime): array
    {
        if ($mime === 'image/svg+xml') { return [null, null]; }
        $info = @getimagesize($path);
        if (!is_array($info)) { return [null, null]; }
        return [(int)$info[0], (int)$info[1]];
    }

    private static function safeSegment(string $s): string
    {
        $s = preg_replace('/[^a-z0-9-]+/i', '-', strtolower($s));
        return trim($s, '-') ?: 'site';
    }

    private static function safeBasename(string $s): string
    {
        $s = preg_replace('/[^a-z0-9-]+/i', '-', strtolower($s));
        return trim($s, '-');
    }
}
