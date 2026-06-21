<?php
/**
 * دوال مساعدة عامة
 */

/**
 * تنسيق التاريخ
 */
function formatDate(?string $date, string $format = 'Y/m/d'): string
{
    if (!$date) return '-';
    return date($format, strtotime($date));
}

/**
 * تنسيق التاريخ والوقت
 */
function formatDateTime(?string $datetime): string
{
    if (!$datetime) return '-';
    return date('Y/m/d h:i A', strtotime($datetime));
}

/**
 * تنسيق العملة
 */
function formatMoney($amount, bool $showCurrency = true): string
{
    $formatted = number_format((float)$amount, 2, '.', ',');
    return $showCurrency ? $formatted . ' ر.س' : $formatted;
}

/**
 * تنسيق النسبة المئوية
 */
function formatPercent($value): string
{
    return number_format((float)$value, 1) . '%';
}

/**
 * تنسيق رقم الجوال
 */
function formatPhone(?string $phone): string
{
    if (!$phone) return '-';
    $phone = preg_replace('/[^0-9+]/', '', $phone);
    if (strlen($phone) === 10 && substr($phone, 0, 2) === '05') {
        $formatted = substr($phone, 0, 3) . ' ' . substr($phone, 3, 3) . ' ' . substr($phone, 6);
    } else {
        $formatted = $phone;
    }
    return '<span dir="ltr" style="direction:ltr;unicode-bidi:embed;display:inline-block;">' . $formatted . '</span>';
}

/**
 * الوقت النسبي
 */
function timeAgo(string $datetime): string
{
    $time = strtotime($datetime);
    $diff = time() - $time;

    if ($diff < 60) return 'الآن';
    if ($diff < 3600) return floor($diff / 60) . ' دقيقة';
    if ($diff < 86400) return floor($diff / 3600) . ' ساعة';
    if ($diff < 604800) return floor($diff / 86400) . ' يوم';
    if ($diff < 2592000) return floor($diff / 604800) . ' أسبوع';
    if ($diff < 31536000) return floor($diff / 2592000) . ' شهر';
    return floor($diff / 31536000) . ' سنة';
}

/**
 * اختصار النص
 */
function truncate(string $text, int $length = 100): string
{
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . '...';
}

/**
 * تنظيف المدخلات
 */
function clean(?string $value): string
{
    if ($value === null) return '';
    return htmlspecialchars(trim($value), ENT_QUOTES, 'UTF-8');
}

/**
 * CSS class حسب الحالة
 */
function statusClass(string $status): string
{
    $classes = [
        // حالات إيجابية
        'completed' => 'success',
        'paid' => 'success',
        'approved' => 'success',
        'active' => 'success',
        'contracted' => 'success',
        'sold' => 'success',
        
        // حالات تحذيرية
        'in_progress' => 'warning',
        'pending_client' => 'warning',
        'pending_government' => 'warning',
        'partially_paid' => 'warning',
        'sent' => 'info',
        'due' => 'warning',
        'negotiating' => 'warning',
        'follow_up' => 'warning',
        'contacting' => 'info',
        'on_hold' => 'warning',
        
        // حالات خطر
        'overdue' => 'danger',
        'cancelled' => 'danger',
        'rejected' => 'danger',
        'expired' => 'danger',
        'lost' => 'danger',
        'not_interested' => 'danger',
        'inactive' => 'danger',
        'terminated' => 'danger',
        
        // حالات محايدة
        'new' => 'primary',
        'draft' => 'secondary',
        'unpaid' => 'danger',
        'pending' => 'info',
        'quote_sent' => 'info',
        'interested' => 'success',
    ];
    
    return $classes[$status] ?? 'secondary';
}

/**
 * ترجمة الحالة
 */
function statusLabel(string $status, string $type = 'general'): string
{
    $allStatuses = array_merge(
        SERVICE_ORDER_STATUS,
        QUOTATION_STATUS,
        CLAIM_STATUS,
        INVOICE_STATUS,
        OPPORTUNITY_STATUS,
        PAYMENT_TYPES,
        CALL_RESULTS,
        TASK_STATUS,
        PAYMENT_STATUS,
        EMPLOYEE_STATUS
    );
    
    return $allStatuses[$status] ?? $status;
}

/**
 * شارة الحالة HTML
 */
function statusBadge(string $status, string $type = 'general'): string
{
    $class = statusClass($status);
    $label = statusLabel($status, $type);
    return "<span class=\"badge badge-{$class}\">{$label}</span>";
}

/**
 * توليد CSRF Token HTML
 */
function csrfField(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return '<input type="hidden" name="_csrf_token" value="' . $_SESSION['csrf_token'] . '">';
}

/**
 * رابط URL
 */
function url(string $module, string $action = 'index', array $params = []): string
{
    return Router::url($module, $action, $params);
}

/**
 * هل الصفحة الحالية؟
 */
function isCurrentPage(string $module, ?string $action = null): bool
{
    $currentModule = $_GET['module'] ?? 'dashboard';
    $currentAction = $_GET['action'] ?? 'index';
    
    if ($action) {
        return $currentModule === $module && $currentAction === $action;
    }
    return $currentModule === $module;
}

/**
 * CSS class للقائمة النشطة
 */
function activeMenu(string $module): string
{
    return isCurrentPage($module) ? 'active' : '';
}

/**
 * حساب ضريبة القيمة المضافة
 */
function calculateVAT(float $amount, float $rate = 15): float
{
    return round($amount * $rate / 100, 2);
}

/**
 * إجمالي مع الضريبة
 */
function totalWithVAT(float $amount, float $rate = 15): float
{
    return round($amount + calculateVAT($amount, $rate), 2);
}

/**
 * تحويل الأرقام إلى عربية
 */
function arabicNumbers(string $string): string
{
    $arabic = ['٠','١','٢','٣','٤','٥','٦','٧','٨','٩'];
    $english = ['0','1','2','3','4','5','6','7','8','9'];
    return str_replace($english, $arabic, $string);
}

/**
 * تفقيط المبلغ (تحويل الأرقام إلى كلمات عربية)
 */
function amountInWords(float $amount): string
{
    $ones = ['', 'واحد', 'اثنان', 'ثلاثة', 'أربعة', 'خمسة', 'ستة', 'سبعة', 'ثمانية', 'تسعة',
             'عشرة', 'أحد عشر', 'اثنا عشر', 'ثلاثة عشر', 'أربعة عشر', 'خمسة عشر', 'ستة عشر',
             'سبعة عشر', 'ثمانية عشر', 'تسعة عشر'];
    $tens = ['', '', 'عشرون', 'ثلاثون', 'أربعون', 'خمسون', 'ستون', 'سبعون', 'ثمانون', 'تسعون'];
    $hundreds = ['', 'مائة', 'مئتان', 'ثلاثمائة', 'أربعمائة', 'خمسمائة', 'ستمائة', 'سبعمائة', 'ثمانمائة', 'تسعمائة'];

    if ($amount == 0) return 'صفر ريال سعودي';
    
    $intPart = (int) $amount;
    $decPart = round(($amount - $intPart) * 100);
    
    $result = '';
    
    if ($intPart >= 1000) {
        $thousands = (int)($intPart / 1000);
        if ($thousands == 1) $result .= 'ألف';
        elseif ($thousands == 2) $result .= 'ألفان';
        elseif ($thousands <= 10) $result .= $ones[$thousands] . ' آلاف';
        else $result .= $thousands . ' ألف';
        $intPart %= 1000;
        if ($intPart > 0) $result .= ' و';
    }
    
    if ($intPart >= 100) {
        $result .= $hundreds[(int)($intPart / 100)];
        $intPart %= 100;
        if ($intPart > 0) $result .= ' و';
    }
    
    if ($intPart >= 20) {
        $onesDigit = $intPart % 10;
        if ($onesDigit > 0) {
            $result .= $ones[$onesDigit] . ' و';
        }
        $result .= $tens[(int)($intPart / 10)];
    } elseif ($intPart > 0) {
        $result .= $ones[$intPart];
    }
    
    $result .= ' ريال سعودي';
    
    if ($decPart > 0) {
        $result .= ' و' . $decPart . ' هللة';
    }
    
    return $result;
}

/**
 * تنسيق حجم الملف
 */
function formatFileSize(int $bytes): string
{
    $units = ['بايت', 'كيلو', 'ميجا', 'جيجا'];
    $i = 0;
    while ($bytes >= 1024 && $i < count($units) - 1) {
        $bytes /= 1024;
        $i++;
    }
    return round($bytes, 2) . ' ' . $units[$i];
}
