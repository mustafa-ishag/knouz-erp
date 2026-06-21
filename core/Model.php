<?php
/**
 * Base Model
 * الفئة الأساسية لجميع النماذج
 */

class Model
{
    protected Database $db;
    protected string $table;
    protected string $primaryKey = 'id';
    protected bool $softDelete = true;
    protected array $fillable = [];
    protected array $searchable = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
    }

    /**
     * جلب جميع السجلات
     */
    public function all(string $orderBy = 'id DESC'): array
    {
        $where = $this->softDelete ? "deleted_at IS NULL" : "1=1";
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE {$where} ORDER BY {$orderBy}"
        );
    }

    /**
     * البحث عن سجل بالمعرف
     */
    public function find(int $id): ?array
    {
        $where = $this->softDelete 
            ? "{$this->primaryKey} = ? AND deleted_at IS NULL" 
            : "{$this->primaryKey} = ?";
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$where}",
            [$id]
        );
    }

    /**
     * البحث عن سجل بشرط
     */
    public function findWhere(string $where, array $params = []): ?array
    {
        $softDeleteCondition = $this->softDelete ? " AND deleted_at IS NULL" : "";
        return $this->db->fetch(
            "SELECT * FROM {$this->table} WHERE {$where}{$softDeleteCondition}",
            $params
        );
    }

    /**
     * جلب سجلات بشرط
     */
    public function where(string $where, array $params = [], string $orderBy = 'id DESC'): array
    {
        $softDeleteCondition = $this->softDelete ? " AND deleted_at IS NULL" : "";
        return $this->db->fetchAll(
            "SELECT * FROM {$this->table} WHERE {$where}{$softDeleteCondition} ORDER BY {$orderBy}",
            $params
        );
    }

    /**
     * إنشاء سجل
     */
    public function create(array $data): int
    {
        // تصفية البيانات حسب الحقول المسموحة
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }
        
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->db->insert($this->table, $data);
    }

    /**
     * تحديث سجل
     */
    public function update(int $id, array $data): int
    {
        if (!empty($this->fillable)) {
            $data = array_intersect_key($data, array_flip($this->fillable));
        }
        
        $data['updated_at'] = date('Y-m-d H:i:s');
        
        return $this->db->update(
            $this->table,
            $data,
            "{$this->primaryKey} = ?",
            [$id]
        );
    }

    /**
     * حذف سجل
     */
    public function delete(int $id): int
    {
        if ($this->softDelete) {
            return $this->db->softDelete($this->table, "{$this->primaryKey} = ?", [$id]);
        }
        return $this->db->delete($this->table, "{$this->primaryKey} = ?", [$id]);
    }

    /**
     * حذف نهائي
     */
    public function forceDelete(int $id): int
    {
        return $this->db->delete($this->table, "{$this->primaryKey} = ?", [$id]);
    }

    /**
     * عدد السجلات
     */
    public function count(string $where = '1=1', array $params = []): int
    {
        $softDeleteCondition = $this->softDelete ? " AND deleted_at IS NULL" : "";
        return $this->db->count($this->table, "{$where}{$softDeleteCondition}", $params);
    }

    /**
     * التحقق من وجود سجل
     */
    public function exists(int $id): bool
    {
        return $this->find($id) !== null;
    }

    /**
     * جلب سجلات مع ترقيم
     */
    public function paginate(int $page = 1, int $perPage = 25, string $where = '1=1', array $params = [], string $orderBy = 'id DESC'): array
    {
        $softDeleteCondition = $this->softDelete ? " AND deleted_at IS NULL" : "";
        $sql = "SELECT * FROM {$this->table} WHERE {$where}{$softDeleteCondition} ORDER BY {$orderBy}";
        return $this->db->paginate($sql, $params, $page, $perPage);
    }

    /**
     * البحث
     */
    public function search(string $term, int $page = 1, int $perPage = 25): array
    {
        if (empty($this->searchable) || empty($term)) {
            return $this->paginate($page, $perPage);
        }

        $conditions = [];
        $params = [];
        foreach ($this->searchable as $field) {
            $conditions[] = "{$field} LIKE ?";
            $params[] = "%{$term}%";
        }

        $where = '(' . implode(' OR ', $conditions) . ')';
        return $this->paginate($page, $perPage, $where, $params);
    }

    /**
     * مجموع عمود
     */
    public function sum(string $column, string $where = '1=1', array $params = []): float
    {
        $softDeleteCondition = $this->softDelete ? " AND deleted_at IS NULL" : "";
        $result = $this->db->fetchColumn(
            "SELECT COALESCE(SUM({$column}), 0) FROM {$this->table} WHERE {$where}{$softDeleteCondition}",
            $params
        );
        return (float) $result;
    }

    /**
     * آخر رقم تسلسلي
     */
    public function getNextNumber(string $prefix, ?string $column = null): string
    {
        // Auto-detect number column name
        if (!$column) {
            $numberColumns = [
                'clients' => 'client_number',
                'service_orders' => 'order_number',
                'quotations' => 'quotation_number',
                'claims' => 'claim_number',
                'invoices' => 'invoice_number',
                'payments' => 'payment_number',
            ];
            $column = $numberColumns[$this->table] ?? 'number';
        }

        $year = date('Y');
        $lastNumber = $this->db->fetchColumn(
            "SELECT {$column} FROM {$this->table} WHERE {$column} LIKE ? ORDER BY id DESC LIMIT 1",
            ["{$prefix}-{$year}-%"]
        );

        if ($lastNumber) {
            $parts = explode('-', $lastNumber);
            $seq = (int) end($parts) + 1;
        } else {
            $seq = 1;
        }

        return sprintf('%s-%s-%04d', $prefix, $year, $seq);
    }
}
