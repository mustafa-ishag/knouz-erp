<?php
class Invoice extends Model {
    protected string $table = 'invoices'; protected bool $softDelete = true;
    protected array $fillable = ['invoice_number','client_id','company_id','claim_id','invoice_date','due_date','subtotal','vat_rate','vat_amount','discount','total','paid_amount','status','notes','created_by'];
    public function generateNumber(): string { return $this->getNextNumber('INV'); }
    public function getAllFiltered($page=1,$perPage=25,$search='',$status=''): array {
        $where='i.deleted_at IS NULL';$params=[];
        if($search){$where.=" AND (i.invoice_number LIKE ? OR c.name LIKE ?)";$s="%{$search}%";$params=[$s,$s];}
        if($status){$where.=" AND i.status = ?";$params[]=$status;}
        return $this->db->paginate("SELECT i.*,c.name as client_name,co.name_ar as company_name FROM invoices i LEFT JOIN clients c ON i.client_id=c.id LEFT JOIN companies co ON i.company_id=co.id WHERE {$where} ORDER BY i.created_at DESC",$params,$page,$perPage);
    }
    public function getWithItems($id): ?array {
        $inv=$this->find($id);if(!$inv)return null;
        $inv['client']=$this->db->fetch("SELECT * FROM clients WHERE id=?",[$inv['client_id']]);
        $inv['company']=$inv['company_id']?$this->db->fetch("SELECT * FROM companies WHERE id=?",[$inv['company_id']]):null;
        $inv['items']=$this->db->fetchAll("SELECT ii.*,s.name as service_name FROM invoice_items ii LEFT JOIN services s ON ii.service_id=s.id WHERE ii.invoice_id=? ORDER BY ii.id",[$id]);
        $inv['payments']=$this->db->fetchAll("SELECT * FROM payments WHERE invoice_id=? AND deleted_at IS NULL ORDER BY payment_date DESC",[$id]);
        return $inv;
    }
    public function saveItems($id,$items): void {
        $this->db->query("DELETE FROM invoice_items WHERE invoice_id=?",[$id]);
        foreach($items as $item){$this->db->insert('invoice_items',['invoice_id'=>$id,'service_id'=>$item['service_id']?:null,'description'=>$item['description'],'quantity'=>(int)$item['quantity'],'unit_price'=>(float)$item['unit_price'],'total'=>(float)$item['total']]);}
    }
    public function updatePaidAmount($id): void {
        $paid=$this->db->fetchColumn("SELECT COALESCE(SUM(amount),0) FROM payments WHERE invoice_id=? AND deleted_at IS NULL",[$id]);
        $inv=$this->find($id);$status=$paid>=$inv['total']?'paid':($paid>0?'partially_paid':$inv['status']);
        $this->update($id,['paid_amount'=>$paid,'status'=>$status]);
    }
}
