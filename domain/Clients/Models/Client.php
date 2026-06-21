<?php
/**
 * نموذج العميل
 */

class Client extends Model
{
    protected string $table = 'clients';
    protected bool $softDelete = true;
    protected array $fillable = [
        'client_number', 'name', 'phone', 'phone2', 'email',
        'city', 'address', 'short_address', 'building_number', 'street',
        'district', 'postal_code', 'additional_number',
        'id_number', 'notes', 'source',
        'assigned_to', 'last_contact_date', 'user_id', 'created_by'
    ];
    protected array $searchable = ['name', 'phone', 'email', 'client_number', 'id_number', 'city'];

    /**
     * توليد رقم عميل جديد
     */
    public function generateNumber(): string
    {
        return $this->getNextNumber('CLI');
    }

    /**
     * جلب العملاء مع الإحصائيات
     */
    public function getAllWithStats(int $page = 1, int $perPage = 25, string $search = '', string $city = ''): array
    {
        $where = 'c.deleted_at IS NULL';
        $params = [];

        if ($search) {
            $where .= " AND (c.name LIKE ? OR c.phone LIKE ? OR c.email LIKE ? OR c.client_number LIKE ?)";
            $searchTerm = "%{$search}%";
            $params = array_merge($params, [$searchTerm, $searchTerm, $searchTerm, $searchTerm]);
        }

        if ($city) {
            $where .= " AND c.city = ?";
            $params[] = $city;
        }

        $sql = "SELECT c.*, 
                    (SELECT COUNT(*) FROM companies co WHERE co.client_id = c.id AND co.deleted_at IS NULL) as companies_count,
                    (SELECT COUNT(*) FROM service_orders so WHERE so.client_id = c.id AND so.deleted_at IS NULL) as orders_count,
                    (SELECT COALESCE(SUM(p.amount), 0) FROM payments p WHERE p.client_id = c.id AND p.deleted_at IS NULL) as total_payments
                FROM clients c 
                WHERE {$where}
                ORDER BY c.created_at DESC";

        return $this->db->paginate($sql, $params, $page, $perPage);
    }

    /**
     * بطاقة العميل الشاملة
     */
    public function getClientCard(int $clientId): ?array
    {
        $client = $this->find($clientId);
        if (!$client) return null;

        $db = $this->db;

        // الشركات
        $client['companies'] = $db->fetchAll(
            "SELECT * FROM companies WHERE client_id = ? AND deleted_at IS NULL ORDER BY name_ar",
            [$clientId]
        );

        // إحصائيات
        $client['stats'] = [
            'companies_count' => count($client['companies']),
            'services_count' => $db->count('service_orders', 'client_id = ? AND deleted_at IS NULL', [$clientId]),
            'quotations_count' => $db->count('quotations', 'client_id = ? AND deleted_at IS NULL', [$clientId]),
            'claims_count' => $db->count('claims', 'client_id = ? AND deleted_at IS NULL', [$clientId]),
            'invoices_count' => $db->count('invoices', 'client_id = ? AND deleted_at IS NULL', [$clientId]),
            'total_revenue' => $db->fetchColumn("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE client_id = ? AND deleted_at IS NULL", [$clientId]),
            'total_invoiced' => $db->fetchColumn("SELECT COALESCE(SUM(total), 0) FROM invoices WHERE client_id = ? AND deleted_at IS NULL", [$clientId]),
            'total_paid' => $db->fetchColumn("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE client_id = ? AND deleted_at IS NULL", [$clientId]),
            'balance_due' => 0,
        ];
        $client['stats']['balance_due'] = $client['stats']['total_invoiced'] - $client['stats']['total_paid'];

        // آخر الطلبات
        $client['recent_orders'] = $db->fetchAll(
            "SELECT so.*, s.name as service_name 
             FROM service_orders so 
             LEFT JOIN services s ON so.service_id = s.id 
             WHERE so.client_id = ? AND so.deleted_at IS NULL 
             ORDER BY so.created_at DESC LIMIT 10",
            [$clientId]
        );

        // آخر المكالمات
        $client['calls'] = $db->fetchAll(
            "SELECT cl.*, u.full_name as user_name 
             FROM calls cl 
             LEFT JOIN users u ON cl.user_id = u.id 
             WHERE cl.client_id = ? 
             ORDER BY cl.call_date DESC LIMIT 10",
            [$clientId]
        );

        // عروض الأسعار
        $client['quotations'] = $db->fetchAll(
            "SELECT * FROM quotations WHERE client_id = ? AND deleted_at IS NULL ORDER BY created_at DESC LIMIT 10",
            [$clientId]
        );

        // الفواتير
        $client['invoices'] = $db->fetchAll(
            "SELECT * FROM invoices WHERE client_id = ? AND deleted_at IS NULL ORDER BY created_at DESC LIMIT 10",
            [$clientId]
        );

        return $client;
    }

    /**
     * جلب المدن المتاحة
     */
    public function getCities(): array
    {
        return $this->db->fetchAll(
            "SELECT DISTINCT city FROM clients WHERE city IS NOT NULL AND city != '' AND deleted_at IS NULL ORDER BY city"
        );
    }
}
