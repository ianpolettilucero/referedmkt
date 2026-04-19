<?php
namespace Core;

/**
 * Router HTTP minimal.
 *
 * Features:
 *   - Rutas por verbo (GET/POST/PUT/PATCH/DELETE) mas un HEAD implicito sobre GET.
 *   - Placeholders {name} y {name:regex} en el path.
 *   - Grupos de rutas con prefijo.
 *   - Handler puede ser callable(array $params) o [ControllerClass::class, 'method'].
 *   - 404 / 405 handlers custom via setNotFound() / setMethodNotAllowed().
 *
 * Match determinista: se compila cada pattern a regex la primera vez y se cachea.
 */
final class Router
{
    /** @var array<string, array<int, array{pattern:string, regex:string, params:array<int,string>, handler:mixed}>> */
    private array $routes = [];

    private string $prefix = '';

    /** @var callable|null */
    private $notFound = null;

    /** @var callable|null */
    private $methodNotAllowed = null;

    public function get(string $path, $handler): void    { $this->add('GET',    $path, $handler); }
    public function post(string $path, $handler): void   { $this->add('POST',   $path, $handler); }
    public function put(string $path, $handler): void    { $this->add('PUT',    $path, $handler); }
    public function patch(string $path, $handler): void  { $this->add('PATCH',  $path, $handler); }
    public function delete(string $path, $handler): void { $this->add('DELETE', $path, $handler); }

    /**
     * @param string[] $methods
     */
    public function map(array $methods, string $path, $handler): void
    {
        foreach ($methods as $m) {
            $this->add(strtoupper($m), $path, $handler);
        }
    }

    public function group(string $prefix, callable $fn): void
    {
        $previous = $this->prefix;
        $this->prefix = $previous . '/' . trim($prefix, '/');
        try {
            $fn($this);
        } finally {
            $this->prefix = $previous;
        }
    }

    public function setNotFound(callable $fn): void
    {
        $this->notFound = $fn;
    }

    public function setMethodNotAllowed(callable $fn): void
    {
        $this->methodNotAllowed = $fn;
    }

    private function add(string $method, string $path, $handler): void
    {
        $full = '/' . trim($this->prefix . '/' . ltrim($path, '/'), '/');
        if ($full === '/' . '') {
            $full = '/';
        }

        [$regex, $params] = $this->compile($full);

        $this->routes[$method][] = [
            'pattern' => $full,
            'regex'   => $regex,
            'params'  => $params,
            'handler' => $handler,
        ];
    }

    /**
     * Compila un path con placeholders a regex + lista de nombres de parametro.
     * Placeholder por defecto matchea [^/]+. Uno custom puede declararse como {slug:regex}.
     *
     * @return array{0:string,1:array<int,string>}
     */
    private function compile(string $path): array
    {
        $params = [];
        $regex = preg_replace_callback(
            '/\{([a-zA-Z_][a-zA-Z0-9_]*)(?::([^}]+))?\}/',
            function ($m) use (&$params) {
                $params[] = $m[1];
                $inner = $m[2] ?? '[^/]+';
                return '(' . $inner . ')';
            },
            $path
        );
        return ['#^' . $regex . '$#', $params];
    }

    /**
     * Ejecuta el handler que matchea el request actual.
     * Devuelve el valor del handler (por si el caller quiere capturarlo en tests).
     */
    public function dispatch(?string $method = null, ?string $path = null)
    {
        $method = strtoupper($method ?? $_SERVER['REQUEST_METHOD'] ?? 'GET');
        $path = $path ?? parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH) ?? '/';
        if ($path !== '/' && substr($path, -1) === '/') {
            $path = rtrim($path, '/');
        }

        $effectiveMethod = $method === 'HEAD' ? 'GET' : $method;

        // Match en el metodo correcto primero.
        foreach ($this->routes[$effectiveMethod] ?? [] as $route) {
            if (preg_match($route['regex'], $path, $matches)) {
                array_shift($matches);
                $params = array_combine($route['params'], array_map('urldecode', $matches)) ?: [];
                return $this->invoke($route['handler'], $params);
            }
        }

        // Si el path matchea en otro metodo -> 405.
        $allowed = [];
        foreach ($this->routes as $m => $list) {
            if ($m === $effectiveMethod) {
                continue;
            }
            foreach ($list as $route) {
                if (preg_match($route['regex'], $path)) {
                    $allowed[] = $m;
                    break;
                }
            }
        }

        if ($allowed) {
            $allowed = array_values(array_unique($allowed));
            if ($this->methodNotAllowed) {
                return ($this->methodNotAllowed)($allowed);
            }
            header('Allow: ' . implode(', ', $allowed), true, 405);
            echo '405 Method Not Allowed';
            return null;
        }

        if ($this->notFound) {
            return ($this->notFound)($path);
        }
        http_response_code(404);
        echo '404 Not Found';
        return null;
    }

    private function invoke($handler, array $params)
    {
        if (is_array($handler) && count($handler) === 2 && is_string($handler[0])) {
            [$class, $method] = $handler;
            $instance = new $class();
            return $instance->$method($params);
        }
        if (is_callable($handler)) {
            return $handler($params);
        }
        throw new \RuntimeException('Invalid route handler.');
    }
}
