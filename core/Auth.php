<?php
/**
 * نظام المصادقة (Authentication)
 */

class Auth
{
    private static ?Auth $instance = null;
    private Database $db;
    private ?array $user = null;

    private function __construct()
    {
        $this->db = Database::getInstance();
        $this->loadUser();
    }

    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * تحميل بيانات المستخدم من الجلسة
     */
    private function loadUser(): void
    {
        if (isset($_SESSION['user_id'])) {
            $this->user = $this->db->fetch(
                "SELECT u.*, r.name as role_name, r.slug as role_slug 
                 FROM users u 
                 LEFT JOIN roles r ON u.role_id = r.id 
                 WHERE u.id = ? AND u.is_active = 1 AND u.deleted_at IS NULL",
                [$_SESSION['user_id']]
            );
            
            if (!$this->user) {
                $this->logout();
            }
        }
    }

    /**
     * تسجيل الدخول
     */
    public function login(string $username, string $password): array
    {
        // البحث عن المستخدم
        $user = $this->db->fetch(
            "SELECT u.*, r.name as role_name, r.slug as role_slug 
             FROM users u 
             LEFT JOIN roles r ON u.role_id = r.id 
             WHERE (u.username = ? OR u.email = ?) AND u.deleted_at IS NULL",
            [$username, $username]
        );

        if (!$user) {
            return ['success' => false, 'message' => 'اسم المستخدم أو كلمة المرور غير صحيحة'];
        }

        if (!$user['is_active']) {
            return ['success' => false, 'message' => 'هذا الحساب معطل. تواصل مع مدير النظام'];
        }

        if (!password_verify($password, $user['password'])) {
            // تسجيل محاولة فاشلة
            $this->db->update('users', [
                'failed_login_attempts' => $user['failed_login_attempts'] + 1,
                'last_failed_login' => date('Y-m-d H:i:s')
            ], 'id = ?', [$user['id']]);
            
            return ['success' => false, 'message' => 'اسم المستخدم أو كلمة المرور غير صحيحة'];
        }

        // تسجيل الدخول بنجاح
        session_regenerate_id(true);
        $_SESSION['user_id'] = $user['id'];
        $_SESSION['login_time'] = time();

        // تحديث آخر تسجيل دخول
        $this->db->update('users', [
            'last_login_at' => date('Y-m-d H:i:s'),
            'last_login_ip' => $_SERVER['REMOTE_ADDR'] ?? '',
            'failed_login_attempts' => 0
        ], 'id = ?', [$user['id']]);

        $this->user = $user;

        // تسجيل النشاط
        AuditLog::log($user['id'], 'login', 'auth', $user['id'], 'تسجيل دخول ناجح');

        return ['success' => true, 'message' => 'تم تسجيل الدخول بنجاح', 'user' => $user];
    }

    /**
     * تسجيل الخروج
     */
    public function logout(): void
    {
        if ($this->user) {
            AuditLog::log($this->user['id'], 'logout', 'auth', $this->user['id'], 'تسجيل خروج');
        }

        $this->user = null;
        $_SESSION = [];
        
        if (ini_get("session.use_cookies")) {
            $params = session_get_cookie_params();
            setcookie(session_name(), '', time() - 42000,
                $params["path"], $params["domain"],
                $params["secure"], $params["httponly"]
            );
        }
        
        session_destroy();
    }

    /**
     * التحقق من تسجيل الدخول
     */
    public function isLoggedIn(): bool
    {
        return $this->user !== null;
    }

    /**
     * الحصول على المستخدم الحالي
     */
    public function getUser(): ?array
    {
        return $this->user;
    }

    /**
     * الحصول على معرف المستخدم
     */
    public function getUserId(): int
    {
        return $this->user['id'] ?? 0;
    }

    /**
     * الحصول على دور المستخدم
     */
    public function getRole(): string
    {
        return $this->user['role_slug'] ?? '';
    }

    /**
     * التحقق من الدور
     */
    public function hasRole(string $role): bool
    {
        return $this->getRole() === $role;
    }

    /**
     * هل المستخدم مدير؟
     */
    public function isAdmin(): bool
    {
        return $this->hasRole('admin');
    }

    /**
     * تغيير كلمة المرور
     */
    public function changePassword(int $userId, string $currentPassword, string $newPassword): array
    {
        $user = $this->db->fetch("SELECT * FROM users WHERE id = ?", [$userId]);
        
        if (!$user || !password_verify($currentPassword, $user['password'])) {
            return ['success' => false, 'message' => 'كلمة المرور الحالية غير صحيحة'];
        }

        $this->db->update('users', [
            'password' => password_hash($newPassword, PASSWORD_DEFAULT)
        ], 'id = ?', [$userId]);

        AuditLog::log($userId, 'change_password', 'auth', $userId, 'تغيير كلمة المرور');

        return ['success' => true, 'message' => 'تم تغيير كلمة المرور بنجاح'];
    }
}
