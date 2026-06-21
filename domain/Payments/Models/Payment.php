<?php
class Payment extends Model {
    protected string $table = 'payments'; protected bool $softDelete = true;
    protected array $fillable = ['payment_number','client_id','claim_id','invoice_id','amount','payment_date','payment_type','reference_number','bank_name','notes','attachment','created_by'];
    public function generateNumber(): string { return $this->getNextNumber('PAY'); }
    public function getAllFiltered($page=1,$perPage=25,$search=''): array {
        $where='p.deleted_at IS NULL';$params=[];
        if($search){$where.=" AND (p.payment_number LIKE ? OR c.name LIKE ? OR p.reference_number LIKE ?)";$s="%{$search}%";$params=[$s,$s,$s];}
        return $this->db->paginate("SELECT p.*,c.name as client_name FROM payments p LEFT JOIN clients c ON p.client_id=c.id WHERE {$where} ORDER BY p.payment_date DESC",$params,$page,$perPage);
    }
}
