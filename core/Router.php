<?php

class Router {
    private array $routes = [];
    private string $prefix = '';

    public function __construct(string $prefix = '') {
        $this->prefix = $prefix;
    }

    public function get(string $path, array $handler): self {
        $this->addRoute('GET', $path, $handler);
        return $this;
    }

    public function post(string $path, array $handler): self {
        $this->addRoute('POST', $path, $handler);
        return $this;
    }

    public function map(string $method, string $path, array $handler): self {
        $this->addRoute(strtoupper($method), $path, $handler);
        return $this;
    }

    private function addRoute(string $method, string $path, array $handler): void {
        $fullPath = $this->prefix . $path;
        $this->routes[] = [
            'method'  => $method,
            'path'    => $fullPath === '' ? '/' : $fullPath,
            'handler' => $handler,
        ];
    }

    public function dispatch(Request $request): void {
        $method = $request->method();
        $uri    = $request->uri();
        $uri    = $uri === '' ? '/' : $uri;

        foreach ($this->routes as $route) {
            $pattern = $this->buildPattern($route['path']);
            if ($route['method'] === $method && preg_match($pattern, $uri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);
                $controllerClass = $route['handler'][0];
                $action          = $route['handler'][1];
                $controller      = new $controllerClass();
                call_user_func_array([$controller, $action], array_values($params));
                return;
            }
        }

        Response::view('partials/404.php', ['title' => '404 - Page Not Found'], 404);
    }

    private function buildPattern(string $path): string {
        $pattern = preg_replace('/\{(\w+)\}/', '(?P<$1>[^/]+)', $path);
        return '#^' . $pattern . '$#';
    }
}