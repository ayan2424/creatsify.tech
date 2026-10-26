<?php

namespace Core;

/**
 * Creatsify Studio - Middleware Pipeline Runner
 * Executes an array of middleware callables or classes in onion/chain order.
 */
class MiddlewarePipeline
{
    private array $middleware;

    public function __construct(array $middleware = [])
    {
        $this->middleware = $middleware;
    }

    /**
     * Run request through the middleware stack to the destination core action.
     *
     * @param Request $request
     * @param callable $destination
     * @return Response
     */
    public function handle(Request $request, callable $destination): Response
    {
        $pipeline = array_reduce(
            array_reverse($this->middleware),
            function ($next, $middleware) {
                return function ($request) use ($next, $middleware) {
                    return $this->resolveMiddleware($middleware, $request, $next);
                };
            },
            function ($request) use ($destination) {
                $result = $destination($request);
                if ($result instanceof Response) {
                    return $result;
                }
                if (is_array($result)) {
                    return Response::json($result);
                }
                return Response::html((string)$result);
            }
        );

        return $pipeline($request);
    }

    /**
     * Resolve a middleware identifier (class name or callable)
     */
    private function resolveMiddleware(mixed $middleware, Request $request, callable $next): Response
    {
        if (is_string($middleware)) {
            // Check alias map or direct class name
            $class = $this->resolveAlias($middleware);
            if (!class_exists($class)) {
                if (is_callable($class)) {
                    $response = $class($request, $next);
                } else {
                    throw new \RuntimeException("Middleware class not found: {$class}");
                }
            } else {
                $instance = new $class();
                if (!method_exists($instance, 'handle')) {
                    throw new \RuntimeException("Middleware {$class} must implement a handle() method.");
                }
                $response = $instance->handle($request, $next);
            }
        } elseif (is_callable($middleware)) {
            $response = $middleware($request, $next);
        } else {
            throw new \InvalidArgumentException("Invalid middleware definition.");
        }

        if ($response instanceof Response) {
            return $response;
        }

        if (is_array($response)) {
            return Response::json($response);
        }

        return Response::html((string)$response);
    }

    /**
     * Aliases for quick middleware referencing
     */
    private function resolveAlias(string $alias): string
    {
        // Handle parameterized middleware like "role:admin,super_admin"
        $name = explode(':', $alias)[0];

        $map = [
            'auth' => \App\Middleware\Authenticate::class,
            'role' => \App\Middleware\RoleMiddleware::class,
            'csrf' => \App\Middleware\CsrfMiddleware::class,
            'analytics' => \App\Middleware\AnalyticsMiddleware::class,
            'demo' => \App\Middleware\DemoProtectMiddleware::class,
        ];

        return $map[$name] ?? $alias;
    }
}
