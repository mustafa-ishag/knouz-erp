<?php
/**
 * متحكم المصادقة
 */

class AuthController extends Controller
{
    /**
     * صفحة تسجيل الدخول
     */
    public function login(): void
    {
        // إذا كان مسجل الدخول، توجيه للوحة التحكم
        if (Auth::getInstance()->isLoggedIn()) {
            header('Location: ' . url('dashboard'));
            exit;
        }
        
        $error = $_SESSION['login_error'] ?? null;
        unset($_SESSION['login_error']);
        
        // عرض بدون قالب
        include BASE_PATH . '/domain/Auth/Views/login.php';
    }

    /**
     * تنفيذ تسجيل الدخول
     */
    public function doLogin(): void
    {
        if (!$this->isPost()) {
            $this->redirect('auth', 'login');
            return;
        }

        $username = trim($this->input('username', ''));
        $password = $this->input('password', '');

        if (empty($username) || empty($password)) {
            $_SESSION['login_error'] = 'يرجى إدخال اسم المستخدم وكلمة المرور';
            $this->redirect('auth', 'login');
            return;
        }

        $auth = Auth::getInstance();
        $result = $auth->login($username, $password);

        if ($result['success']) {
            header('Location: ' . url('dashboard'));
            exit;
        } else {
            $_SESSION['login_error'] = $result['message'];
            $this->redirect('auth', 'login');
        }
    }

    /**
     * تسجيل الخروج
     */
    public function logout(): void
    {
        Auth::getInstance()->logout();
        header('Location: ' . url('auth', 'login'));
        exit;
    }

    /**
     * تغيير كلمة المرور
     */
    public function changePassword(): void
    {
        if ($this->isPost()) {
            $currentPassword = $this->input('current_password', '');
            $newPassword = $this->input('new_password', '');
            $confirmPassword = $this->input('confirm_password', '');

            if (empty($currentPassword) || empty($newPassword)) {
                $this->setFlash('danger', 'جميع الحقول مطلوبة');
                $this->redirect('auth', 'change_password');
                return;
            }

            if ($newPassword !== $confirmPassword) {
                $this->setFlash('danger', 'كلمة المرور الجديدة غير متطابقة');
                $this->redirect('auth', 'change_password');
                return;
            }

            if (strlen($newPassword) < 6) {
                $this->setFlash('danger', 'كلمة المرور يجب أن تكون 6 أحرف على الأقل');
                $this->redirect('auth', 'change_password');
                return;
            }

            $auth = Auth::getInstance();
            $result = $auth->changePassword($auth->getUserId(), $currentPassword, $newPassword);

            $this->setFlash($result['success'] ? 'success' : 'danger', $result['message']);
            $this->redirect('auth', 'change_password');
            return;
        }

        $pageTitle = 'تغيير كلمة المرور';
        $breadcrumbs = [['title' => 'تغيير كلمة المرور']];
        $this->render('domain/Auth/Views/change_password.php', compact('pageTitle', 'breadcrumbs'));
    }
}
