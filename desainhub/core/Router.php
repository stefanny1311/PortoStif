<?php
/**
 * Router sederhana — mapping URL ke Controller@method
 */

class Router
{
    private array $routes = [];

    public function add(string $method, string $path, string $handler): void
    {
        $this->routes[] = compact('method', 'path', 'handler');
    }

    public function get(string $path, string $handler): void
    {
        $this->add('GET', $path, $handler);
    }

    public function post(string $path, string $handler): void
    {
        $this->add('POST', $path, $handler);
    }

    public function dispatch(string $uri, string $method): void
    {
        $uri = parse_url($uri, PHP_URL_PATH);
        $basePath = parse_url(BASE_URL, PHP_URL_PATH);
        if ($basePath && str_starts_with($uri, $basePath)) {
            $uri = substr($uri, strlen($basePath));
        }
        $uri = '/' . trim($uri, '/');

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            $pattern = preg_replace('/\{[a-zA-Z_]+\}/', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches);
                [$controllerName, $methodName] = explode('@', $route['handler']);

                if (!class_exists($controllerName)) {
                    http_response_code(404);
                    require APP_ROOT . '/views/errors/404.php';
                    return;
                }

                $controller = new $controllerName();
                call_user_func_array([$controller, $methodName], $matches);
                return;
            }
        }

        http_response_code(404);
        require APP_ROOT . '/views/errors/404.php';
    }
}
