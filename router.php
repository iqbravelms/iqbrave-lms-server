<?php
class Router
{
    private $routes = [];

    public function add($method, $uri, $action)
    {
        // Convert {param} into a regex pattern
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '([^/]+)', $uri);
        // Escape forward slashes for regex
        $pattern = str_replace('/', '\/', $pattern);
        $this->routes[] = compact('method', 'pattern', 'action');
    }
    
    public function dispatch($method, $uri)
    {
        // Sanitize the URI
        $uri = trim(filter_var($uri, FILTER_SANITIZE_URL)); 
        foreach ($this->routes as $route) {
          
            if ($route['method'] === $method && preg_match("#^{$route['pattern']}$#", $uri, $params)) {
                array_shift($params); // Remove the first element (the full match)
                list($controller, $action) = explode('@', $route['action']);
                $controller = "App\\Controllers\\$controller";
                $controllerInstance = new $controller;
    
                return call_user_func_array([$controllerInstance, $action], $params);
            }
        }
    
        // Handle not found
        http_response_code(404);
        echo json_encode(['status' => 'error', 'message' => 'Route not found.']);
    }
    
    
}
