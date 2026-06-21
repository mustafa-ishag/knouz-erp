<?php
class Claim extends Model {
    protected string $table = 'claims'; protected bool $softDelete = true;
    protected array $fillable = ['claim_number','client_id','company_id','quotation_id','claim_percentage','due_date','subtotal','vat_rate','vat_amount','total','paid_amount','status','notes','created_by'];
    public function generateNumber(): string { return $this->getNextNumber('CL'); }
    public function getAllFiltered(int $page = 1, int $perPage = 25, string $search = '', string $status = ''): array {
        $where = 'cl.deleted_at IS NULL'; $params = [];
        if ($search) { $where .= " AND (cl.claim_number LIKE ? OR c.name LIKE ?)"; $s = "%{$search}%"; $params = [$s,$s]; }
        if ($status) { $where .= " AND cl.status = ?"; $params[] = $status; }
        return $this->db->paginate("SELECT cl.*, c.name as client_name, co.name_ar as company_name FROM claims cl LEFT JOIN clients c ON cl.client_id = c.id LEFT JOIN companies co ON cl.company_id = co.id WHERE {$where} ORDER BY cl.created_at DESC", $params, $page, $perPage);
    }
    public function getWithItems(int $id): ?array {
        $c = $this->find($id); if (!$c) return null;
        $c['client'] = $this->db->fetch("SELECT * FROM clients WHERE id = ?", [$c['client_id']]);
        $c['company'] = $c['company_id'] ? $this->db->fetch("SELECT * FROM companies WHERE id = ?", [$c['company_id']]) : null;
        $c['items'] = $this->db->fetchAll("SELECT ci.*, s.name as service_name FROM claim_items ci LEFT JOIN services s ON ci.service_id = s.id WHERE ci.claim_id = ? ORDER BY ci.id", [$id]);
        $c['payments'] = $this->db->fetchAll("SELECT * FROM payments WHERE claim_id = ? AND deleted_at IS NULL ORDER BY payment_date DESC", [$id]);
        return $c;
    }
    public function saveItems(int $claimId, array $items): void {
        $this->db->query("DELETE FROM claim_items WHERE claim_id = ?", [$claimId]);
        foreach ($items as $item) { $this->db->insert('claim_items', ['claim_id'=>$claimId, 'service_id'=>$item['service_id']?:null, 'description'=>$item['description'], 'quantity'=>(int)$item['quantity'], 'unit_price'=>(float)$item['unit_price'], 'total'=>(float)$item['total']]); }
    }
    public function updatePaidAmount(int $id): void {
        $paid = $this->db->fetchColumn("SELECT COALESCE(SUM(amount), 0) FROM payments WHERE claim_id = ? AND deleted_at IS NULL", [$id]);
        $claim = $this->find($id);
        $status = $paid >= $claim['total'] ? 'paid' : ($paid > 0 ? 'partially_paid' : $claim['status']);
        $this->update($id, ['paid_amount' => $paid, 'status' => $status]);
    }
}
