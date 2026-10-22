<?php

namespace Core;

/**
 * Creatsify Studio - High-Performance URL Router
 */
class Router
{
    private array $routes = [];
    private array $groupStack = [];
    private array $namedRoutes = [];

    public function get(string $path, array|callable|string $action, array $middleware = []): self
    {
        return $this->addRoute('GET', $path, $action, $middleware);
    }

    public function post(string $path, array|callable|string $action, array $middleware = []): self
    {
        return $this->addRoute('POST', $path, $action, $middleware);
    }

    public function put(string $path, array|callable|string $action, array $middleware = []): self
    {
        return $this->addRoute('PUT', $path, $action, $middleware);
    }

    public function delete(string $path, array|callable|string $action, array $middleware = []): self
    {
        return $this->addRoute('DELETE', $path, $action, $middleware);
    }

    public function group(array $attributes, callable $callback): void
    {
        $this->groupStack[] = $attributes;
        $callback($this);
        array_pop($this->groupStack);
    }

    private function addRoute(string $method, string $path, array|callable|string $action, array $middleware = []): self
    {
        $prefix = '';
        $groupMiddleware = [];

        foreach ($this->groupStack as $group) {
            if (isset($group['prefix'])) {
                $prefix .= '/' . trim($group['prefix'], '/');
            }
            if (isset($group['middleware'])) {
                $groupMiddleware = array_merge($groupMiddleware, (array)$group['middleware']);
            }
        }

        $fullPath = '/' . trim($prefix . '/' . trim($path, '/'), '/');
        if ($fullPath !== '/') {
            $fullPath = rtrim($fullPath, '/');
        }

        $allMiddleware = array_merge($groupMiddleware, $middleware);

        // Convert {param} placeholders to regex named groups
        $pattern = preg_replace('/\{([a-zA-Z0-9_]+)\}/', '(?P<$1>[^/]+)', $fullPath);
        $pattern = '#^' . $pattern . '$#';

        $route = [
            'method' => $method,
            'path' => $fullPath,
            'pattern' => $pattern,
            'action' => $action,
            'middleware' => $allMiddleware
        ];

        $this->routes[] = $route;
        return $this;
    }

    public function dispatch(Request $request): Response
    {
        $method = $request->getMethod();
        if ($method === 'HEAD') {
            $method = 'GET';
        }
        $path = $request->getPath();

        foreach ($this->routes as $route) {
            if ($route['method'] !== $method) {
                continue;
            }

            if (preg_match($route['pattern'], $path, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                // Run middleware pipeline
                $pipeline = new MiddlewarePipeline($route['middleware']);
                return $pipeline->handle($request, function ($req) use ($route, $params) {
                    return $this->executeAction($route['action'], $req, $params);
                });
            }
        }

        // Route not found -> 404
        return $this->handleNotFound($request);
    }

    private function executeAction(array|callable|string $action, Request $request, array $params): Response
    {
        if (is_callable($action)) {
            $result = call_user_func_array($action, array_merge([$request], $params));
        } elseif (is_array($action)) {
            [$controllerClass, $method] = $action;
            $controller = new $controllerClass();
            $result = call_user_func_array([$controller, $method], array_merge([$request], $params));
        } elseif (is_string($action)) {
            [$controllerClass, $method] = explode('@', $action, 2);
            $fullClass = str_starts_with($controllerClass, 'App\\') ? $controllerClass : "App\\Controllers\\{$controllerClass}";
            $controller = new $fullClass();
            $result = call_user_func_array([$controller, $method], array_merge([$request], $params));
        } else {
            throw new \RuntimeException("Invalid route action specified.");
        }

        if ($result instanceof Response) {
            return $result;
        }
        if (is_string($result)) {
            return Response::make($result);
        }
        if (is_array($result)) {
            return Response::json($result);
        }
        return Response::make('');
    }

    private function handleNotFound(Request $request): Response
    {
        if ($request->isAjax() || str_starts_with($request->getPath(), '/api')) {
            return Response::json(['success' => false, 'error' => 'Resource not found'], 404);
        }

        try {
            return Response::html(view('errors/404', ['message' => 'The requested page does not exist.']), 404);
        } catch (\Throwable $e) {
            return Response::make("<!DOCTYPE html><html><head><title>404 Not Found</title><style>body{background:#07080e;color:#eef0f7;font-family:sans-serif;text-align:center;padding:80px}a{color:#8b7bff}</style></head><body><h1>404 — Page Not Found</h1><p>The requested page does not exist.</p><a href='/'>Return to Home</a></body></html>", 404);
        }
    }
}
