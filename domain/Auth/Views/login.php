<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>تسجيل الدخول - كنوز الإنجاز</title>
    <meta name="description" content="تسجيل الدخول إلى نظام كنوز الإنجاز لإدارة الأعمال">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="/kn/public/assets/css/app.css">
    <link rel="stylesheet" href="/kn/public/assets/css/pages.css">
</head>
<body>
    <div class="login-page">
        <!-- الخلفية المتحركة -->
        <div class="login-bg">
            <div class="grid-pattern"></div>
            <div class="circle"></div>
            <div class="circle"></div>
            <div class="circle"></div>
        </div>
        
        <!-- بطاقة تسجيل الدخول -->
        <div class="login-card">
            <div class="login-logo">
                <img src="<?= BASE_URL ?>/assets/images/logo.png" alt="شعار النظام" style="max-height: 80px; width: auto; object-fit: contain; margin-bottom: 10px;">
                <h2>كنوز الإنجاز</h2>
                <p>نظام إدارة العملاء والخدمات والأعمال</p>
            </div>
            
            <?php if (!empty($error)): ?>
                <div class="login-error">
                    <i class="fas fa-exclamation-circle"></i>
                    <span><?= htmlspecialchars($error) ?></span>
                </div>
            <?php endif; ?>
            
            <form class="login-form" method="POST" action="<?= url('auth', 'do_login') ?>" id="loginForm">
                <div class="form-group">
                    <label class="form-label">اسم المستخدم</label>
                    <div class="input-icon">
                        <i class="fas fa-user"></i>
                        <input type="text" name="username" class="form-control" 
                               placeholder="أدخل اسم المستخدم" required autofocus
                               autocomplete="username">
                    </div>
                </div>
                
                <div class="form-group">
                    <label class="form-label">كلمة المرور</label>
                    <div class="input-icon">
                        <i class="fas fa-lock"></i>
                        <input type="password" name="password" class="form-control" 
                               placeholder="أدخل كلمة المرور" required
                               autocomplete="current-password">
                    </div>
                </div>
                
                <button type="submit" class="btn btn-primary login-btn">
                    <i class="fas fa-sign-in-alt"></i>
                    تسجيل الدخول
                </button>
            </form>
            
            <div class="login-footer">
                <p>نظام كنوز الإنجاز لإدارة الأعمال &copy; <?= date('Y') ?></p>
            </div>
        </div>
    </div>
</body>
</html>
