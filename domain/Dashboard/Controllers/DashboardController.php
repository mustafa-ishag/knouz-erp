<?php
/**
 * متحكم لوحة التحكم الرئيسية
 */

class DashboardController extends Controller
{
    /**
     * لوحة التحكم
     */
    public function index(): void
    {
        $db = $this->db;
        
        // إحصائيات عامة
        $stats = [
            'total_clients' => $db->count('clients', 'deleted_at IS NULL'),
            'total_companies' => $db->count('companies', 'deleted_at IS NULL'),
            'open_orders' => $db->count('service_orders', "status NOT IN ('completed','cancelled') AND deleted_at IS NULL"),
            'completed_orders' => $db->count('service_orders', "status = 'completed' AND deleted_at IS NULL"),
            'total_orders' => $db->count('service_orders', 'deleted_at IS NULL'),
            'completed_orders_value' => $db->fetchColumn("SELECT COALESCE(SUM(price), 0) FROM service_orders WHERE status = 'completed' AND deleted_at IS NULL") ?: 0,
            'open_orders_value' => $db->fetchColumn("SELECT COALESCE(SUM(price), 0) FROM service_orders WHERE status NOT IN ('completed','cancelled') AND deleted_at IS NULL") ?: 0,
            'total_revenue' => $db->fetchColumn("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE deleted_at IS NULL") ?: 0,
            'monthly_revenue' => $db->fetchColumn("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE MONTH(payment_date) = MONTH(CURDATE()) AND YEAR(payment_date) = YEAR(CURDATE()) AND deleted_at IS NULL") ?: 0,
            'unpaid_invoices' => $db->count('invoices', "status IN ('unpaid','partially_paid') AND deleted_at IS NULL"),
            'due_claims' => $db->count('claims', "status IN ('sent','due','overdue') AND deleted_at IS NULL"),
            'unpaid_invoices_amount' => $db->fetchColumn("SELECT COALESCE(SUM(total - paid_amount), 0) FROM invoices WHERE status IN ('unpaid','partially_paid') AND deleted_at IS NULL") ?: 0,
            'due_claims_amount' => $db->fetchColumn("SELECT COALESCE(SUM(total - paid_amount), 0) FROM claims WHERE status IN ('sent','due','overdue') AND deleted_at IS NULL") ?: 0,
        ];
        
        // أكثر الخدمات طلباً
        $topServices = $db->fetchAll(
            "SELECT s.name, COUNT(so.id) as count 
             FROM service_orders so 
             INNER JOIN services s ON so.service_id = s.id 
             WHERE so.deleted_at IS NULL 
             GROUP BY s.id, s.name 
             ORDER BY count DESC 
             LIMIT 5"
        );
        
        // أفضل العملاء
        $topClients = $db->fetchAll(
            "SELECT c.name, COALESCE(SUM(p.amount), 0) as total_paid 
             FROM clients c 
             LEFT JOIN payments p ON c.id = p.client_id AND p.deleted_at IS NULL
             WHERE c.deleted_at IS NULL 
             GROUP BY c.id, c.name 
             HAVING total_paid > 0
             ORDER BY total_paid DESC 
             LIMIT 5"
        );
        
        // تنبيهات انتهاء السجلات
        $expiringCR = $db->fetchAll(
            "SELECT co.name_ar, co.cr_expiry_date, c.name as client_name 
             FROM companies co 
             LEFT JOIN clients c ON co.client_id = c.id 
             WHERE co.cr_expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL 30 DAY)
             AND co.deleted_at IS NULL 
             ORDER BY co.cr_expiry_date ASC 
             LIMIT 10"
        );
        
        // آخر الأنشطة
        $recentActivities = $db->fetchAll(
            "SELECT al.*, u.full_name as user_name 
             FROM audit_log al 
             LEFT JOIN users u ON al.user_id = u.id 
             ORDER BY al.created_at DESC 
             LIMIT 10"
        );
        
        // الإيرادات الشهرية (آخر 6 أشهر)
        $monthlyRevenue = $db->fetchAll(
            "SELECT 
                DATE_FORMAT(payment_date, '%Y-%m') as month,
                COALESCE(SUM(amount), 0) as total
             FROM payments 
             WHERE payment_date >= DATE_SUB(CURDATE(), INTERVAL 6 MONTH)
             AND deleted_at IS NULL
             GROUP BY month 
             ORDER BY month ASC"
        );
        
        // المطالبات المتأخرة
        $overdueClaims = $db->fetchAll(
            "SELECT cl.claim_number, cl.total, cl.due_date, c.name as client_name
             FROM claims cl 
             LEFT JOIN clients c ON cl.client_id = c.id 
             WHERE cl.status IN ('overdue','due') AND cl.due_date < CURDATE()
             AND cl.deleted_at IS NULL 
             ORDER BY cl.due_date ASC 
             LIMIT 5"
        );
        
        $pageTitle = 'لوحة التحكم';
        $breadcrumbs = [['title' => 'لوحة التحكم']];
        
        $this->render('domain/Dashboard/Views/index.php', compact(
            'pageTitle', 'breadcrumbs', 'stats', 'topServices', 'topClients',
            'expiringCR', 'recentActivities', 'monthlyRevenue', 'overdueClaims'
        ));
    }

    /**
     * إحصائيات AJAX
     */
    public function stats(): void
    {
        $db = $this->db;
        $stats = [
            'total_clients' => $db->count('clients', 'deleted_at IS NULL'),
            'open_orders' => $db->count('service_orders', "status NOT IN ('completed','cancelled') AND deleted_at IS NULL"),
        ];
        $this->json($stats);
    }
}
