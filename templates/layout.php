<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $pageTitle ?? 'كنوز الإنجاز' ?> - نظام إدارة الأعمال</title>
    <meta name="description" content="نظام كنوز الإنجاز لإدارة العملاء والخدمات والأعمال">
    
    <!-- Font Awesome -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    
    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
    
    <!-- CSS -->
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/app.css">
    <link rel="stylesheet" href="<?= BASE_URL ?>/assets/css/pages.css">
    
    <?php if (isset($extraCss)): ?>
        <?php foreach ((array)$extraCss as $css): ?>
            <link rel="stylesheet" href="<?= $css ?>">
        <?php endforeach; ?>
    <?php endif; ?>
</head>
<body>
    <div class="app-wrapper">
        <!-- القائمة الجانبية -->
        <?php include __DIR__ . '/sidebar.php'; ?>
        
        <!-- المحتوى الرئيسي -->
        <div class="main-content">
            <!-- الهيدر -->
            <?php include __DIR__ . '/header.php'; ?>
            
            <!-- منطقة المحتوى -->
            <div class="content-area">
                <?php
                // رسائل الفلاش
                $flash = $_SESSION['flash'] ?? null;
                if ($flash) {
                    unset($_SESSION['flash']);
                    echo '<div class="alert alert-' . clean($flash['type']) . '" data-auto-hide>';
                    echo '<i class="fas fa-' . ($flash['type'] === 'success' ? 'check-circle' : ($flash['type'] === 'danger' ? 'exclamation-circle' : 'info-circle')) . '"></i>';
                    echo '<span>' . clean($flash['message']) . '</span>';
                    echo '<button class="alert-close"><i class="fas fa-times"></i></button>';
                    echo '</div>';
                }
                ?>
                
                <!-- المحتوى -->
                <?= $content ?? '' ?>
            </div>
        </div>
    </div>
    
    <!-- JavaScript -->
    <script>const BASE_URL = '<?= BASE_URL ?>';</script>
    <script src="<?= BASE_URL ?>/assets/js/app.js"></script>
    
    <?php if (isset($extraJs)): ?>
        <?php foreach ((array)$extraJs as $js): ?>
            <script src="<?= $js ?>"></script>
        <?php endforeach; ?>
    <?php endif; ?>
    
    <?php if (isset($inlineJs)): ?>
        <script><?= $inlineJs ?></script>
    <?php endif; ?>
</body>
</html>
