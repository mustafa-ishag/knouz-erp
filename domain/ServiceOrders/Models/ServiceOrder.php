<?php
class ServiceOrder extends Model
{
    protected string $table = 'service_orders';
    protected bool $softDelete = true;
    protected array $fillable = ['order_number','client_id','company_id','service_id','description','price','cost','status','assigned_to','start_date','due_date','completed_date','platform_ref','notes','created_by'];

    public function generateNumber(): string { return $this->getNextNumber('SO'); }

    public function getAllFiltered(int $page = 1, int $perPage = 25, string $search = '', string $status = '', int $clientId = 0): array
    {
        $where = 'so.deleted_at IS NULL'; $params = [];
        if ($search) { $where .= " AND (so.order_number LIKE ? OR c.name LIKE ? OR s.name LIKE ?)"; $t = "%{$search}%"; $params = [$t,$t,$t]; }
        if ($status) { $where .= " AND so.status = ?"; $params[] = $status; }
        if ($clientId) { $where .= " AND so.client_id = ?"; $params[] = $clientId; }
        return $this->db->paginate("SELECT so.*, c.name as client_name, co.name_ar as company_name, s.name as service_name, u.full_name as assigned_name FROM service_orders so LEFT JOIN clients c ON so.client_id = c.id LEFT JOIN companies co ON so.company_id = co.id LEFT JOIN services s ON so.service_id = s.id LEFT JOIN users u ON so.assigned_to = u.id WHERE {$where} ORDER BY so.created_at DESC", $params, $page, $perPage);
    }

    public function getWithDetails(int $id): ?array
    {
        $order = $this->find($id);
        if (!$order) return null;
        $order['client'] = $this->db->fetch("SELECT * FROM clients WHERE id = ?", [$order['client_id']]);
        $order['company'] = $order['company_id'] ? $this->db->fetch("SELECT * FROM companies WHERE id = ?", [$order['company_id']]) : null;
        $order['service'] = $order['service_id'] ? $this->db->fetch("SELECT * FROM services WHERE id = ?", [$order['service_id']]) : null;
        $order['history'] = $this->db->fetchAll("SELECT h.*, u.full_name as user_name FROM order_status_history h LEFT JOIN users u ON h.changed_by = u.id WHERE h.order_id = ? ORDER BY h.created_at DESC", [$id]);
        return $order;
    }

    public function addStatusHistory(int $orderId, string $oldStatus, string $newStatus, ?string $notes = null, int $userId = 0): void
    {
        $this->db->insert('order_status_history', ['order_id' => $orderId, 'old_status' => $oldStatus, 'new_status' => $newStatus, 'notes' => $notes, 'changed_by' => $userId]);
    }
}
