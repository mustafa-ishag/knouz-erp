<?php
/**
 * نظام التوجيه (Router)
 * يعتمد على GET parameters للتوجيه
 */

class Router
{
    private array $routes = [];
    private static ?Router $instance = null;

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * تسجيل مسار
     */
    public function register(string $module, string $action, string $controller, string $method, array $permissions = []): void
    {
        $this->routes["{$module}.{$action}"] = [
            'controller' => $controller,
            'method' => $method,
            'permissions' => $permissions,
        ];
    }

    /**
     * تنفيذ الطلب
     */
    public function dispatch(): void
    {
        $module = $_GET['module'] ?? 'dashboard';
        $action = $_GET['action'] ?? 'index';
        $routeKey = "{$module}.{$action}";

        // التحقق من وجود المسار
        if (!isset($this->routes[$routeKey])) {
            $this->notFound();
            return;
        }

        $route = $this->routes[$routeKey];

        // التحقق من تسجيل الدخول (إلا لصفحة الدخول)
        if ($module !== 'auth') {
            $auth = Auth::getInstance();
            if (!$auth->isLoggedIn()) {
                header('Location: ' . BASE_URL . '/?module=auth&action=login');
                exit;
            }

            // التحقق من الصلاحيات
            if (!empty($route['permissions'])) {
                $rbac = new RBAC();
                foreach ($route['permissions'] as $permission) {
                    if (!$rbac->hasPermission($permission)) {
                        $this->forbidden();
                        return;
                    }
                }
            }
        }

        // إنشاء Controller وتنفيذ Method
        $controllerClass = $route['controller'];
        if (class_exists($controllerClass)) {
            $controller = new $controllerClass();
            $method = $route['method'];
            if (method_exists($controller, $method)) {
                $controller->$method();
            } else {
                $this->notFound();
            }
        } else {
            $this->notFound();
        }
    }

    /**
     * صفحة غير موجودة
     */
    private function notFound(): void
    {
        http_response_code(404);
        include dirname(__DIR__) . '/templates/errors/404.php';
    }

    /**
     * غير مصرح
     */
    private function forbidden(): void
    {
        http_response_code(403);
        include dirname(__DIR__) . '/templates/errors/403.php';
    }

    /**
     * توليد رابط
     */
    public static function url(string $module, string $action = 'index', array $params = []): string
    {
        $url = BASE_URL . "/?module={$module}&action={$action}";
        foreach ($params as $key => $value) {
            $url .= "&{$key}=" . urlencode($value);
        }
        return $url;
    }
}
