<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>403 - غير مصرح</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/app.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages.css">
</head>
<body>
    <div class="error-page">
        <div>
            <div class="error-code">403</div>
            <div class="error-message">غير مصرح بالوصول</div>
            <p class="text-muted mb-3">عذراً، ليس لديك صلاحية للوصول إلى هذه الصفحة.</p>
            <a href="<?= BASE_URL ?>/" class="btn btn-primary">
                <i class="fas fa-home"></i>
                العودة للرئيسية
            </a>
        </div>
    </div>
</body>
</html>
