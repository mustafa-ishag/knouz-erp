<?php
class RenewalController extends Controller
{
    public function index(): void
    {
        $today = date('Y-m-d');
        $days30 = date('Y-m-d', strtotime('+30 days'));
        $days60 = date('Y-m-d', strtotime('+60 days'));
        $days90 = date('Y-m-d', strtotime('+90 days'));
        $filterDays = (int)$this->query('days', 30);
        $filterType = $this->query('type', '');
        $targetDate = date('Y-m-d', strtotime("+{$filterDays} days"));

        // 1. السجلات التجارية للشركات
        $companies = $this->db->fetchAll("
            SELECT co.id, co.name_ar, co.cr_number, co.cr_expiry_date, c.name as client_name,
            DATEDIFF(co.cr_expiry_date, CURDATE()) as days_remaining
            FROM companies co
            LEFT JOIN clients c ON co.client_id = c.id
            WHERE co.deleted_at IS NULL
            AND co.cr_expiry_date IS NOT NULL
            AND co.cr_expiry_date <= ?
            ORDER BY co.cr_expiry_date ASC
        ", [$targetDate]);

        // 2. مستندات الشركات (رخص، تصاريح، شهادات...)
        $documents = $this->db->fetchAll("
            SELECT d.id, d.title, d.document_type, d.expiry_date, d.file_name,
            co.name_ar as company_name, c.name as client_name,
            DATEDIFF(d.expiry_date, CURDATE()) as days_remaining
            FROM company_documents d
            LEFT JOIN companies co ON d.company_id = co.id
            LEFT JOIN clients c ON co.client_id = c.id
            WHERE d.deleted_at IS NULL
            AND d.expiry_date IS NOT NULL
            AND d.expiry_date <= ?
            ORDER BY d.expiry_date ASC
        ", [$targetDate]);

        // 3. عروض الأسعار المنتهية الصلاحية
        $quotations = $this->db->fetchAll("
            SELECT q.id, q.quotation_number, q.validity_date, q.total, q.status,
            c.name as client_name, co.name_ar as company_name,
            DATEDIFF(q.validity_date, CURDATE()) as days_remaining
            FROM quotations q
            LEFT JOIN clients c ON q.client_id = c.id
            LEFT JOIN companies co ON q.company_id = co.id
            WHERE q.deleted_at IS NULL
            AND q.validity_date IS NOT NULL
            AND q.validity_date <= ?
            AND q.status NOT IN ('approved','rejected','cancelled')
            ORDER BY q.validity_date ASC
        ", [$targetDate]);

        // 4. الفواتير المستحقة
        $invoices = $this->db->fetchAll("
            SELECT i.id, i.invoice_number, i.due_date, i.total, i.paid_amount, i.status,
            c.name as client_name,
            DATEDIFF(i.due_date, CURDATE()) as days_remaining
            FROM invoices i
            LEFT JOIN clients c ON i.client_id = c.id
            WHERE i.deleted_at IS NULL
            AND i.due_date IS NOT NULL
            AND i.due_date <= ?
            AND i.status NOT IN ('paid','cancelled')
            ORDER BY i.due_date ASC
        ", [$targetDate]);

        // 5. المطالبات المستحقة
        $claims = $this->db->fetchAll("
            SELECT cl.id, cl.claim_number, cl.due_date, cl.total, cl.paid_amount, cl.status,
            c.name as client_name,
            DATEDIFF(cl.due_date, CURDATE()) as days_remaining
            FROM claims cl
            LEFT JOIN clients c ON cl.client_id = c.id
            WHERE cl.deleted_at IS NULL
            AND cl.due_date IS NOT NULL
            AND cl.due_date <= ?
            AND cl.status NOT IN ('paid','cancelled')
            ORDER BY cl.due_date ASC
        ", [$targetDate]);

        // 6. أوامر الخدمة المستحقة
        $orders = $this->db->fetchAll("
            SELECT so.id, so.order_number, so.due_date, so.status,
            s.name as service_name, co.name_ar as company_name, c.name as client_name,
            DATEDIFF(so.due_date, CURDATE()) as days_remaining
            FROM service_orders so
            LEFT JOIN services s ON so.service_id = s.id
            LEFT JOIN companies co ON so.company_id = co.id
            LEFT JOIN clients c ON so.client_id = c.id
            WHERE so.deleted_at IS NULL
            AND so.due_date IS NOT NULL
            AND so.due_date <= ?
            AND so.status NOT IN ('completed','cancelled','delivered')
            ORDER BY so.due_date ASC
        ", [$targetDate]);

        // 7. المهام المستحقة
        $tasks = $this->db->fetchAll("
            SELECT t.id, t.title, t.due_date, t.priority, t.status,
            u.full_name as assigned_to_name,
            DATEDIFF(t.due_date, CURDATE()) as days_remaining
            FROM tasks t
            LEFT JOIN users u ON t.assigned_to = u.id
            WHERE t.deleted_at IS NULL
            AND t.due_date IS NOT NULL
            AND t.due_date <= ?
            AND t.status NOT IN ('completed','cancelled')
            ORDER BY t.due_date ASC
        ", [$targetDate]);

        // إحصائيات سريعة
        $stats = [
            'expired' => 0,
            'critical' => 0,   // 0-7 أيام
            'warning' => 0,    // 8-30 يوم
            'upcoming' => 0,   // 31+ يوم
            'total' => 0,
        ];

        $allItems = array_merge($companies, $documents, $quotations, $invoices, $claims, $orders, $tasks);
        foreach ($allItems as $item) {
            $days = $item['days_remaining'];
            $stats['total']++;
            if ($days < 0) $stats['expired']++;
            elseif ($days <= 7) $stats['critical']++;
            elseif ($days <= 30) $stats['warning']++;
            else $stats['upcoming']++;
        }

        $pageTitle = 'مركز التجديدات';
        $breadcrumbs = [['title' => 'مركز التجديدات']];
        $this->render('domain/Renewals/Views/index.php', compact(
            'pageTitle', 'breadcrumbs', 'stats', 'filterDays', 'filterType',
            'companies', 'documents', 'quotations', 'invoices', 'claims', 'orders', 'tasks'
        ));
    }
}
