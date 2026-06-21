<?php
class Quotation extends Model
{
    protected string $table = 'quotations';
    protected bool $softDelete = true;
    protected array $fillable = ['quotation_number','client_id','company_id','quotation_date','validity_date','subtotal','vat_rate','vat_amount','discount','total','payment_terms','status','approved_at','notes','terms_conditions','created_by'];

    public function generateNumber(): string { return $this->getNextNumber('QT'); }

    public function getAllFiltered(int $page = 1, int $perPage = 25, string $search = '', string $status = ''): array
    {
        $where = 'q.deleted_at IS NULL'; $params = [];
        if ($search) { $where .= " AND (q.quotation_number LIKE ? OR c.name LIKE ?)"; $s = "%{$search}%"; $params = [$s,$s]; }
        if ($status) { $where .= " AND q.status = ?"; $params[] = $status; }
        return $this->db->paginate("SELECT q.*, c.name as client_name, co.name_ar as company_name FROM quotations q LEFT JOIN clients c ON q.client_id = c.id LEFT JOIN companies co ON q.company_id = co.id WHERE {$where} ORDER BY q.created_at DESC", $params, $page, $perPage);
    }

    public function getWithItems(int $id): ?array
    {
        $q = $this->find($id);
        if (!$q) return null;
        $q['client'] = $this->db->fetch("SELECT * FROM clients WHERE id = ?", [$q['client_id']]);
        $q['company'] = $q['company_id'] ? $this->db->fetch("SELECT * FROM companies WHERE id = ?", [$q['company_id']]) : null;
        $q['items'] = $this->db->fetchAll("SELECT qi.*, s.name as service_name FROM quotation_items qi LEFT JOIN services s ON qi.service_id = s.id WHERE qi.quotation_id = ? ORDER BY qi.id", [$id]);
        return $q;
    }

    public function saveItems(int $quotationId, array $items): void
    {
        $this->db->query("DELETE FROM quotation_items WHERE quotation_id = ?", [$quotationId]);
        foreach ($items as $item) {
            $this->db->insert('quotation_items', ['quotation_id' => $quotationId, 'service_id' => $item['service_id'] ?: null, 'description' => $item['description'], 'quantity' => (int)$item['quantity'], 'unit_price' => (float)$item['unit_price'], 'total' => (float)$item['total']]);
        }
    }
}
