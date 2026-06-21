<?php
class ReportController extends Controller {
    public function index(): void {
        $pageTitle='التقارير والتحليلات';$breadcrumbs=[['title'=>'التقارير']];
        $this->render('domain/Reports/Views/index.php',compact('pageTitle','breadcrumbs'));
    }
    public function revenue(): void {
        $from=$this->query('from',date('Y-m-01'));$to=$this->query('to',date('Y-m-t'));
        $data=['revenue'=>$this->db->fetchAll("SELECT DATE(payment_date) as date, SUM(amount) as total FROM payments WHERE payment_date BETWEEN ? AND ? AND deleted_at IS NULL GROUP BY DATE(payment_date) ORDER BY date",[$from,$to]),
            'total'=>$this->db->fetchColumn("SELECT COALESCE(SUM(amount),0) FROM payments WHERE payment_date BETWEEN ? AND ? AND deleted_at IS NULL",[$from,$to]),
            'by_method'=>$this->db->fetchAll("SELECT payment_type as payment_method,SUM(amount) as total FROM payments WHERE payment_date BETWEEN ? AND ? AND deleted_at IS NULL GROUP BY payment_type",[$from,$to])];
        $pageTitle='تقرير الإيرادات';$breadcrumbs=[['title'=>'التقارير','url'=>url('reports')],['title'=>'الإيرادات']];
        $this->render('domain/Reports/Views/revenue.php',compact('pageTitle','breadcrumbs','data','from','to'));
    }
    public function clients(): void {
        $data=['total_clients'=>$this->db->count('clients','deleted_at IS NULL'),
            'by_city'=>$this->db->fetchAll("SELECT city,COUNT(*) as count FROM clients WHERE city IS NOT NULL AND city != '' AND deleted_at IS NULL GROUP BY city ORDER BY count DESC"),
            'by_source'=>$this->db->fetchAll("SELECT source,COUNT(*) as count FROM clients WHERE source IS NOT NULL AND source != '' AND deleted_at IS NULL GROUP BY source ORDER BY count DESC"),
            'top_clients'=>$this->db->fetchAll("SELECT c.name,COALESCE(SUM(p.amount),0) as total FROM clients c LEFT JOIN payments p ON p.client_id=c.id AND p.deleted_at IS NULL WHERE c.deleted_at IS NULL GROUP BY c.id ORDER BY total DESC LIMIT 10")];
        $pageTitle='تقرير العملاء';$breadcrumbs=[['title'=>'التقارير','url'=>url('reports')],['title'=>'العملاء']];
        $this->render('domain/Reports/Views/clients.php',compact('pageTitle','breadcrumbs','data'));
    }
    public function services(): void {
        $data=['by_service'=>$this->db->fetchAll("SELECT s.name,COUNT(so.id) as count,SUM(so.price) as revenue,SUM(so.price-so.cost) as profit FROM service_orders so LEFT JOIN services s ON so.service_id=s.id WHERE so.deleted_at IS NULL GROUP BY so.service_id ORDER BY count DESC"),
            'by_status'=>$this->db->fetchAll("SELECT status,COUNT(*) as count FROM service_orders WHERE deleted_at IS NULL GROUP BY status")];
        $pageTitle='تقرير الخدمات';$breadcrumbs=[['title'=>'التقارير','url'=>url('reports')],['title'=>'الخدمات']];
        $this->render('domain/Reports/Views/services.php',compact('pageTitle','breadcrumbs','data'));
    }
    public function financial(): void {
        $year=$this->query('year',date('Y'));
        $data=['monthly'=>$this->db->fetchAll("SELECT MONTH(payment_date) as month,SUM(amount) as total FROM payments WHERE YEAR(payment_date)=? AND deleted_at IS NULL GROUP BY MONTH(payment_date) ORDER BY month",[$year]),
            'total_invoiced'=>$this->db->fetchColumn("SELECT COALESCE(SUM(total),0) FROM invoices WHERE YEAR(invoice_date)=? AND deleted_at IS NULL",[$year]),
            'total_paid'=>$this->db->fetchColumn("SELECT COALESCE(SUM(amount),0) FROM payments WHERE YEAR(payment_date)=? AND deleted_at IS NULL",[$year]),
            'total_outstanding'=>$this->db->fetchColumn("SELECT COALESCE(SUM(total-paid_amount),0) FROM invoices WHERE status!='paid' AND status!='cancelled' AND deleted_at IS NULL")];
        $pageTitle='التقرير المالي';$breadcrumbs=[['title'=>'التقارير','url'=>url('reports')],['title'=>'المالي']];
        $this->render('domain/Reports/Views/financial.php',compact('pageTitle','breadcrumbs','data','year'));
    }
}
