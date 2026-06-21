<?php
/**
 * نظام الإشعارات
 */

class NotificationSystem
{
    private Database $db;

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * إنشاء إشعار
     */
    public function create(int $userId, string $title, string $message, string $type = 'info', ?string $link = null): int
    {
        return $this->db->insert('notifications', [
            'user_id' => $userId,
            'title' => $title,
            'message' => $message,
            'type' => $type, // info, success, warning, danger
            'link' => $link,
            'is_read' => 0,
            'created_at' => date('Y-m-d H:i:s'),
        ]);
    }

    /**
     * إرسال إشعار لجميع المستخدمين بدور معين
     */
    public function notifyRole(string $roleSlug, string $title, string $message, string $type = 'info', ?string $link = null): void
    {
        $users = $this->db->fetchAll(
            "SELECT u.id FROM users u 
             INNER JOIN roles r ON u.role_id = r.id 
             WHERE r.slug = ? AND u.is_active = 1 AND u.deleted_at IS NULL",
            [$roleSlug]
        );

        foreach ($users as $user) {
            $this->create($user['id'], $title, $message, $type, $link);
        }
    }

    /**
     * إشعار لجميع المستخدمين
     */
    public function notifyAll(string $title, string $message, string $type = 'info', ?string $link = null): void
    {
        $users = $this->db->fetchAll(
            "SELECT id FROM users WHERE is_active = 1 AND deleted_at IS NULL"
        );

        foreach ($users as $user) {
            $this->create($user['id'], $title, $message, $type, $link);
        }
    }

    /**
     * جلب إشعارات المستخدم
     */
    public function getUserNotifications(int $userId, int $limit = 20): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM notifications 
             WHERE user_id = ? 
             ORDER BY created_at DESC 
             LIMIT ?",
            [$userId, $limit]
        );
    }

    /**
     * عدد الإشعارات غير المقروءة
     */
    public function getUnreadCount(int $userId): int
    {
        return $this->db->count('notifications', 'user_id = ? AND is_read = 0', [$userId]);
    }

    /**
     * تحديد كمقروء
     */
    public function markAsRead(int $id): void
    {
        $this->db->update('notifications', ['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')], 'id = ?', [$id]);
    }

    /**
     * تحديد الكل كمقروء
     */
    public function markAllAsRead(int $userId): void
    {
        $this->db->update(
            'notifications',
            ['is_read' => 1, 'read_at' => date('Y-m-d H:i:s')],
            'user_id = ? AND is_read = 0',
            [$userId]
        );
    }

    /**
     * حذف إشعار
     */
    public function deleteNotification(int $id): void
    {
        $this->db->delete('notifications', 'id = ?', [$id]);
    }

    /**
     * فحص التنبيهات التلقائية
     */
    public function checkAutoAlerts(): void
    {
        $appConfig = require dirname(__DIR__) . '/config/app.php';
        $alertDays = $appConfig['alerts'];

        // تنبيه انتهاء السجلات التجارية
        $this->checkExpiringRecords('cr_expiry_date', $alertDays['cr_expiry_days'], 
            'تنبيه انتهاء السجل التجاري', 'السجل التجاري للشركة');

        // تنبيه الفواتير المستحقة
        $this->checkDueInvoices($alertDays['invoice_due_days']);

        // تنبيه المطالبات المتأخرة
        $this->checkOverdueClaims();
    }

    /**
     * فحص السجلات المنتهية
     */
    private function checkExpiringRecords(string $dateField, int $days, string $title, string $label): void
    {
        $futureDate = date('Y-m-d', strtotime("+{$days} days"));
        $companies = $this->db->fetchAll(
            "SELECT c.*, cl.name as client_name 
             FROM companies c 
             LEFT JOIN clients cl ON c.client_id = cl.id 
             WHERE c.{$dateField} BETWEEN CURDATE() AND ? 
             AND c.deleted_at IS NULL",
            [$futureDate]
        );

        foreach ($companies as $company) {
            $message = "{$label} \"{$company['name_ar']}\" (العميل: {$company['client_name']}) ينتهي بتاريخ {$company[$dateField]}";
            $this->notifyRole('admin', $title, $message, 'warning');
            $this->notifyRole('operations_manager', $title, $message, 'warning');
        }
    }

    /**
     * فحص الفواتير المستحقة
     */
    private function checkDueInvoices(int $days): void
    {
        $futureDate = date('Y-m-d', strtotime("+{$days} days"));
        $invoices = $this->db->fetchAll(
            "SELECT i.*, c.name as client_name 
             FROM invoices i 
             LEFT JOIN clients c ON i.client_id = c.id 
             WHERE i.due_date BETWEEN CURDATE() AND ? 
             AND i.status IN ('unpaid', 'partially_paid') 
             AND i.deleted_at IS NULL",
            [$futureDate]
        );

        foreach ($invoices as $invoice) {
            $message = "الفاتورة رقم {$invoice['invoice_number']} (العميل: {$invoice['client_name']}) تستحق بتاريخ {$invoice['due_date']}";
            $this->notifyRole('accountant', 'تنبيه استحقاق فاتورة', $message, 'warning');
        }
    }

    /**
     * فحص المطالبات المتأخرة
     */
    private function checkOverdueClaims(): void
    {
        $this->db->query(
            "UPDATE claims SET status = 'overdue' 
             WHERE due_date < CURDATE() 
             AND status IN ('sent', 'due') 
             AND deleted_at IS NULL"
        );
    }
}
