<?php

namespace Core;

/**
 * Creatsify Studio - Application Kernel
 * Zero-framework, high-performance PHP 8.2+ core engine.
 */
class App
{
    private static ?App $instance = null;
    private Router $router;
    private Request $request;
    private ?Database $db = null;
    private Session $session;
    private array $config = [];
    private string $basePath;

    public function __construct(string $basePath)
    {
        self::$instance = $this;
        $this->basePath = rtrim($basePath, '/\\');

        $this->registerAutoloader();
        $this->loadEnvironment();
        $this->loadConfiguration();
        $this->bootstrapServices();
    }

    public static function getInstance(): App
    {
        if (self::$instance === null) {
            throw new \RuntimeException("App has not been initialized.");
        }
        return self::$instance;
    }

    /**
     * Fallback PSR-4 Autoloader in case Composer hasn't run
     */
    private function registerAutoloader(): void
    {
        spl_autoload_register(function ($class) {
            $prefixes = [
                'App\\' => $this->basePath . '/app/',
                'Core\\' => $this->basePath . '/core/'
            ];

            foreach ($prefixes as $prefix => $baseDir) {
                $len = strlen($prefix);
                if (strncmp($prefix, $class, $len) !== 0) {
                    continue;
                }

                $relativeClass = substr($class, $len);
                $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

                if (file_exists($file)) {
                    require_once $file;
                    return;
                }
            }
        });

        // Load helpers
        $helpersPath = $this->basePath . '/app/Helpers/functions.php';
        if (file_exists($helpersPath)) {
            require_once $helpersPath;
        }
    }

    private function loadEnvironment(): void
    {
        $envFile = $this->basePath . '/.env';
        if (file_exists($envFile)) {
            $lines = file($envFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
            foreach ($lines as $line) {
                if (str_starts_with(trim($line), '#') || !str_contains($line, '=')) {
                    continue;
                }
                [$name, $value] = explode('=', $line, 2);
                $name = trim($name);
                $value = trim($value, " \t\n\r\0\x0B\"'");
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
                if (function_exists('putenv')) {
                    @putenv(sprintf('%s=%s', $name, $value));
                }
            }
        }
    }

    private function loadConfiguration(): void
    {
        $configDir = $this->basePath . '/config';
        if (is_dir($configDir)) {
            foreach (glob($configDir . '/*.php') as $configFile) {
                $key = basename($configFile, '.php');
                if ($key === 'routes') {
                    continue;
                }
                $this->config[$key] = require $configFile;
            }
        }
    }

    private function bootstrapServices(): void
    {
        // Error handling & reporting
        $debug = (bool)($this->config('app.debug', false));
        if ($debug) {
            error_reporting(E_ALL);
            ini_set('display_errors', '1');
        } else {
            error_reporting(0);
            ini_set('display_errors', '0');
        }

        // Timezone
        date_default_timezone_set($this->config('app.timezone', 'UTC'));

        // Initialize Session
        $this->session = new Session();

        // Initialize Request
        $this->request = new Request();

        // Initialize Router
        $this->router = new Router();
    }

    public function run(): void
    {
        try {
            // 1. If not installed yet, redirect to web installer wizard
            if (!file_exists($this->basePath . '/storage/installed.lock')) {
                $uri = $_SERVER['REQUEST_URI'] ?? '';
                if (!str_contains($uri, 'install.php')) {
                    $sub = function_exists('app_base_path') ? app_base_path() : '';
                    $target = $sub ? $sub . '/install.php' : '/install.php';
                    header("Location: {$target}");
                    exit;
                }
            }

            // Load routes
            $routesFile = $this->basePath . '/config/routes.php';
            if (file_exists($routesFile)) {
                $router = $this->router;
                require $routesFile;
            }

            // Dispatch request
            $response = $this->router->dispatch($this->request);
            $response->send();
        } catch (\Throwable $e) {
            $this->handleException($e);
        }
    }

    private function handleException(\Throwable $e): void
    {
        $debug = (bool)($this->config('app.debug', false));
        $logPath = $this->basePath . '/storage/logs/error.log';
        @error_log("[" . date('Y-m-d H:i:s') . "] " . $e->getMessage() . " in " . $e->getFile() . ":" . $e->getLine() . "\n" . $e->getTraceAsString() . "\n", 3, $logPath);

        if ($this->request->isAjax() || str_starts_with($this->request->getPath(), '/api')) {
            $code = $e->getCode() >= 400 && $e->getCode() < 600 ? $e->getCode() : 500;
            Response::json([
                'success' => false,
                'error' => $debug ? $e->getMessage() : 'An unexpected server error occurred.',
                'trace' => $debug ? explode("\n", $e->getTraceAsString()) : null
            ], $code)->send();
            return;
        }

        http_response_code(500);

        // Check if error is database connection failure
        if (str_contains($e->getMessage(), 'Database Connection Failed') || str_contains($e->getMessage(), 'Access denied')) {
            echo "<!DOCTYPE html><html><head><title>Database Setup Required | Creatsify Studio</title><style>body{background:#07080e;color:#eef0f7;font-family:'Segoe UI',sans-serif;padding:40px 20px;display:flex;justify-content:center;align-items:center;min-height:90vh;}div.box{background:#12131c;border:1px solid #252736;padding:36px;border-radius:14px;max-width:640px;box-shadow:0 20px 50px rgba(0,0,0,0.6);}h2{color:#ff4d6d;margin-top:0;font-size:22px;}p{color:#a3a7bb;font-size:14px;line-height:1.6;margin-bottom:18px;}code{background:#090a10;border:1px solid #252736;padding:2px 8px;border-radius:4px;color:#8b7bff;font-size:13px;word-break:break-all;}ol{margin-left:20px;color:#cbd0e3;font-size:13.5px;line-height:1.8;}li strong{color:#eef0f7;}.btn{display:inline-block;margin-top:20px;background:#8b7bff;color:#fff;padding:12px 24px;border-radius:8px;text-decoration:none;font-weight:600;font-size:14px;}</style></head><body>";
            echo "<div class='box'>";
            echo "<h2>Database Connection Required</h2>";
            echo "<p>Creatsify Studio cannot connect to MySQL with the current configuration.<br>Error: <code>" . htmlspecialchars($e->getMessage()) . "</code></p>";
            echo "<div style='background:#090a10;border:1px solid #252736;padding:18px;border-radius:10px;'>";
            echo "<strong style='color:#8b7bff;display:block;margin-bottom:10px;'>Server Setup & Configuration:</strong>";
            echo "<ol>";
            echo "<li>Check your hosting control panel or database administration tool to verify database name, username, and password.</li>";
            echo "<li>Ensure your project <code>.env</code> file contains valid credentials for <code>DB_DATABASE</code>, <code>DB_USERNAME</code>, and <code>DB_PASSWORD</code>.</li>";
            echo "<li>Alternatively, launch the web installer to automatically test credentials and migrate tables:</li>";
            echo "</ol>";
            echo "<a href='/install.php' class='btn'>Launch 1-Click Web Installer &rarr;</a>";
            echo "</div></div></body></html>";
            return;
        }

        if ($debug) {
            echo "<!DOCTYPE html><html><head><title>Creatsify Error</title><style>body{background:#07080e;color:#eef0f7;font-family:monospace;padding:30px}h1{color:#ff4fa3}.trace{background:#11131c;padding:20px;border-radius:10px;border:1px solid #252736;overflow-x:auto}</style></head><body>";
            echo "<h1>Application Error: " . htmlspecialchars($e->getMessage()) . "</h1>";
            echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Line " . $e->getLine() . ")</p>";
            echo "<div class='trace'><pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre></div>";
            echo "</body></html>";
        } else {
            // Friendly production 500 view
            $errView = $this->basePath . '/views/errors/500.php';
            if (file_exists($errView)) {
                require $errView;
            } else {
                echo "<!DOCTYPE html><html><head><title>500 Internal Error | Creatsify</title><style>body{background:#07080e;color:#eef0f7;font-family:sans-serif;text-align:center;padding:80px}</style></head><body><h1>Server Error</h1><p>Something went wrong on our end. Please try again shortly.</p><a href='/' style='color:#8b7bff'>Return to Home</a></body></html>";
            }
        }
    }

    public function getDb(): Database
    {
        if ($this->db === null) {
            $this->db = new Database($this->config['database'] ?? []);
        }
        return $this->db;
    }

    public function getRouter(): Router
    {
        return $this->router;
    }

    public function getRequest(): Request
    {
        return $this->request;
    }

    public function getSession(): Session
    {
        return $this->session;
    }

    public function getBasePath(): string
    {
        return $this->basePath;
    }

    public function config(string $key, mixed $default = null): mixed
    {
        $parts = explode('.', $key);
        $val = $this->config;
        foreach ($parts as $part) {
            if (!is_array($val) || !array_key_exists($part, $val)) {
                return $default;
            }
            $val = $val[$part];
        }
        return $val;
    }
}
