<?php
/**
 * Base Controller
 * الفئة الأساسية لجميع المتحكمات
 */

class Controller
{
    protected Database $db;
    protected array $appConfig;

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->appConfig = require dirname(__DIR__) . '/config/app.php';
    }

    /**
     * عرض صفحة
     */
    protected function render(string $view, array $data = [], string $layout = 'layout'): void
    {
        // استخراج المتغيرات
        extract($data);
        
        // تحديد مسار الـ view
        $viewPath = dirname(__DIR__) . '/' . $view;
        
        if (!file_exists($viewPath)) {
            die("View not found: {$viewPath}");
        }

        // التقاط محتوى الـ view
        ob_start();
        include $viewPath;
        $content = ob_get_clean();

        // تضمين القالب
        $layoutPath = dirname(__DIR__) . "/templates/{$layout}.php";
        if (file_exists($layoutPath)) {
            include $layoutPath;
        } else {
            echo $content;
        }
    }

    /**
     * عرض صفحة بدون قالب
     */
    protected function renderPartial(string $view, array $data = []): void
    {
        extract($data);
        $viewPath = dirname(__DIR__) . '/' . $view;
        if (file_exists($viewPath)) {
            include $viewPath;
        }
    }

    /**
     * إرجاع JSON
     */
    protected function json(array $data, int $statusCode = 200): void
    {
        http_response_code($statusCode);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($data, JSON_UNESCAPED_UNICODE);
        exit;
    }

    /**
     * إعادة التوجيه
     */
    protected function redirect(string $module, string $action = 'index', array $params = []): void
    {
        $url = Router::url($module, $action, $params);
        header("Location: {$url}");
        exit;
    }

    /**
     * الحصول على بيانات POST
     */
    protected function input(string $key, $default = null)
    {
        return $_POST[$key] ?? $default;
    }

    /**
     * الحصول على بيانات GET
     */
    protected function query(string $key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }

    /**
     * التحقق من أن الطلب POST
     */
    protected function isPost(): bool
    {
        return $_SERVER['REQUEST_METHOD'] === 'POST';
    }

    /**
     * التحقق من طلب AJAX
     */
    protected function isAjax(): bool
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }

    /**
     * رسالة فلاش
     */
    protected function setFlash(string $type, string $message): void
    {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
    }

    /**
     * قراءة رسالة فلاش
     */
    protected function getFlash(): ?array
    {
        $flash = $_SESSION['flash'] ?? null;
        unset($_SESSION['flash']);
        return $flash;
    }

    /**
     * التحقق من CSRF Token
     */
    protected function validateCsrf(): bool
    {
        $token = $_POST['_csrf_token'] ?? '';
        return hash_equals($_SESSION['csrf_token'] ?? '', $token);
    }

    /**
     * توليد CSRF Token
     */
    protected function generateCsrf(): string
    {
        if (empty($_SESSION['csrf_token'])) {
            $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
        }
        return $_SESSION['csrf_token'];
    }

    /**
     * الحصول على المستخدم الحالي
     */
    protected function currentUser(): ?array
    {
        return Auth::getInstance()->getUser();
    }

    /**
     * تسجيل نشاط
     */
    protected function logActivity(string $action, string $module, ?int $recordId = null, ?string $details = null): void
    {
        $user = $this->currentUser();
        AuditLog::log(
            $user['id'] ?? 0,
            $action,
            $module,
            $recordId,
            $details
        );
    }
}
