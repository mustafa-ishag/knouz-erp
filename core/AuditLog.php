<?php
/**
 * سجل المراقبة (Audit Log)
 * تسجيل جميع العمليات في النظام
 */

class AuditLog
{
    /**
     * تسجيل نشاط
     */
    public static function log(int $userId, string $action, string $module, ?int $recordId = null, ?string $details = null): void
    {
        try {
            $db = Database::getInstance();
            $db->insert('audit_log', [
                'user_id' => $userId,
                'action' => $action,
                'module' => $module,
                'record_id' => $recordId,
                'details' => $details,
                'ip_address' => $_SERVER['REMOTE_ADDR'] ?? '',
                'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
                'created_at' => date('Y-m-d H:i:s'),
            ]);
        } catch (Exception $e) {
            error_log("AuditLog Error: " . $e->getMessage());
        }
    }

    /**
     * جلب سجل النشاط
     */
    public static function getLog(int $page = 1, int $perPage = 50, ?string $module = null, ?int $userId = null): array
    {
        $db = Database::getInstance();
        $where = '1=1';
        $params = [];

        if ($module) {
            $where .= ' AND al.module = ?';
            $params[] = $module;
        }

        if ($userId) {
            $where .= ' AND al.user_id = ?';
            $params[] = $userId;
        }

        $sql = "SELECT al.*, u.full_name as user_name 
                FROM audit_log al 
                LEFT JOIN users u ON al.user_id = u.id 
                WHERE {$where} 
                ORDER BY al.created_at DESC";

        return $db->paginate($sql, $params, $page, $perPage);
    }

    /**
     * جلب نشاط سجل معين
     */
    public static function getRecordLog(string $module, int $recordId): array
    {
        $db = Database::getInstance();
        return $db->fetchAll(
            "SELECT al.*, u.full_name as user_name 
             FROM audit_log al 
             LEFT JOIN users u ON al.user_id = u.id 
             WHERE al.module = ? AND al.record_id = ? 
             ORDER BY al.created_at DESC",
            [$module, $recordId]
        );
    }

    /**
     * تنظيف السجلات القديمة
     */
    public static function cleanup(int $daysToKeep = 365): int
    {
        $db = Database::getInstance();
        $date = date('Y-m-d', strtotime("-{$daysToKeep} days"));
        return $db->delete('audit_log', 'created_at < ?', [$date]);
    }
}
