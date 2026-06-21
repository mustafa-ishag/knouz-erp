<?php
class Service extends Model
{
    protected string $table = 'services';
    protected bool $softDelete = true;
    protected array $fillable = ['category_id','name','description','platform','execution_days','default_price','default_cost','is_active','sort_order','requirements'];

    public function getAllWithCategory(int $page = 1, int $perPage = 25, string $search = '', int $categoryId = 0): array
    {
        $where = 's.deleted_at IS NULL';
        $params = [];
        if ($search) { $where .= " AND (s.name LIKE ? OR s.description LIKE ?)"; $s = "%{$search}%"; $params = [$s, $s]; }
        if ($categoryId) { $where .= " AND s.category_id = ?"; $params[] = $categoryId; }
        return $this->db->paginate("SELECT s.*, sc.name as category_name, sc.icon as category_icon, sc.color as category_color FROM services s LEFT JOIN service_categories sc ON s.category_id = sc.id WHERE {$where} ORDER BY s.sort_order, s.name", $params, $page, $perPage);
    }

    public function getActive(): array
    {
        return $this->db->fetchAll("SELECT s.*, sc.name as category_name FROM services s LEFT JOIN service_categories sc ON s.category_id = sc.id WHERE s.is_active = 1 AND s.deleted_at IS NULL ORDER BY sc.sort_order, s.sort_order, s.name");
    }

    public function getCategories(): array
    {
        return $this->db->fetchAll("SELECT * FROM service_categories ORDER BY sort_order");
    }
}
