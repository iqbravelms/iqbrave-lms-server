<?php
class Router
{
    private $routes = [];

    public function add($method, $uri, $action)
    {
        $this->routes[] = compact('method', 'uri', 'action');
    }

    public function dispatch($method, $uri)
    {
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $route['uri'] === $uri) {
                list($controller, $method) = explode('@', $route['action']);
                $controller = "App\\Controllers\\$controller";
                $controllerInstance = new $controller;
                return $controllerInstance->$method();
            }
        }
        http_response_code(404);
        echo "Route not found.";
        // cc
    }
}
