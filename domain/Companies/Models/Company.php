<?php
class Company extends Model
{
    protected string $table = 'companies';
    protected bool $softDelete = true;
    protected array $fillable = [
        'client_id', 'name_ar', 'name_en', 'cr_number', 'unified_number',
        'distinctive_number', 'qiwa_number', 'activity', 'city', 'address',
        'email', 'phone', 'cr_issue_date', 'cr_expiry_date', 'notes', 'created_by'
    ];
    protected array $searchable = ['name_ar', 'name_en', 'cr_number', 'unified_number'];

    public function getAllWithClient(int $page = 1, int $perPage = 25, string $search = '', int $clientId = 0): array
    {
        $where = 'co.deleted_at IS NULL';
        $params = [];
        if ($search) {
            $where .= " AND (co.name_ar LIKE ? OR co.name_en LIKE ? OR co.cr_number LIKE ? OR c.name LIKE ?)";
            $s = "%{$search}%";
            $params = [$s, $s, $s, $s];
        }
        if ($clientId) {
            $where .= " AND co.client_id = ?";
            $params[] = $clientId;
        }
        $sql = "SELECT co.*, c.name as client_name FROM companies co LEFT JOIN clients c ON co.client_id = c.id WHERE {$where} ORDER BY co.created_at DESC";
        return $this->db->paginate($sql, $params, $page, $perPage);
    }

    public function getByClient(int $clientId): array
    {
        return $this->where('client_id = ?', [$clientId], 'name_ar ASC');
    }

    public function getDocuments(int $companyId): array
    {
        return $this->db->fetchAll(
            "SELECT * FROM company_documents WHERE company_id = ? AND deleted_at IS NULL ORDER BY created_at DESC",
            [$companyId]
        );
    }

    public function getExpiring(int $days = 30): array
    {
        return $this->db->fetchAll(
            "SELECT co.*, c.name as client_name FROM companies co LEFT JOIN clients c ON co.client_id = c.id
             WHERE co.cr_expiry_date BETWEEN CURDATE() AND DATE_ADD(CURDATE(), INTERVAL ? DAY) AND co.deleted_at IS NULL
             ORDER BY co.cr_expiry_date ASC", [$days]
        );
    }
}
