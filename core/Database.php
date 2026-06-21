<?php
/**
 * فئة قاعدة البيانات - PDO Wrapper
 * توفر اتصال آمن وعمليات CRUD
 */

class Database
{
    private static ?Database $instance = null;
    private PDO $pdo;

    private function __construct()
    {
        $config = require dirname(__DIR__) . '/config/database.php';
        
        $dsn = sprintf(
            'mysql:host=%s;port=%d;dbname=%s;charset=%s',
            $config['host'],
            $config['port'],
            $config['database'],
            $config['charset']
        );

        try {
            $this->pdo = new PDO($dsn, $config['username'], $config['password'], $config['options']);
        } catch (PDOException $e) {
            error_log("Database Connection Error: " . $e->getMessage());
            die('خطأ في الاتصال بقاعدة البيانات. يرجى التحقق من الإعدادات.');
        }
    }

    /**
     * Singleton pattern
     */
    public static function getInstance(): self
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    /**
     * الحصول على كائن PDO
     */
    public function getPdo(): PDO
    {
        return $this->pdo;
    }

    /**
     * تنفيذ استعلام مع parameters
     */
    public function query(string $sql, array $params = []): PDOStatement
    {
        $stmt = $this->pdo->prepare($sql);
        $stmt->execute($params);
        return $stmt;
    }

    /**
     * جلب صف واحد
     */
    public function fetch(string $sql, array $params = []): ?array
    {
        $stmt = $this->query($sql, $params);
        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * جلب جميع الصفوف
     */
    public function fetchAll(string $sql, array $params = []): array
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchAll();
    }

    /**
     * جلب عمود واحد
     */
    public function fetchColumn(string $sql, array $params = [])
    {
        $stmt = $this->query($sql, $params);
        return $stmt->fetchColumn();
    }

    /**
     * إدراج سجل
     */
    public function insert(string $table, array $data): int
    {
        $columns = implode(', ', array_keys($data));
        $placeholders = implode(', ', array_fill(0, count($data), '?'));
        
        $sql = "INSERT INTO {$table} ({$columns}) VALUES ({$placeholders})";
        $this->query($sql, array_values($data));
        
        return (int) $this->pdo->lastInsertId();
    }

    /**
     * تحديث سجل
     */
    public function update(string $table, array $data, string $where, array $whereParams = []): int
    {
        $sets = [];
        $values = [];
        
        foreach ($data as $column => $value) {
            $sets[] = "{$column} = ?";
            $values[] = $value;
        }
        
        $sql = "UPDATE {$table} SET " . implode(', ', $sets) . " WHERE {$where}";
        $stmt = $this->query($sql, array_merge($values, $whereParams));
        
        return $stmt->rowCount();
    }

    /**
     * حذف سجل
     */
    public function delete(string $table, string $where, array $params = []): int
    {
        $sql = "DELETE FROM {$table} WHERE {$where}";
        $stmt = $this->query($sql, $params);
        return $stmt->rowCount();
    }

    /**
     * حذف ناعم (Soft Delete)
     */
    public function softDelete(string $table, string $where, array $params = []): int
    {
        return $this->update($table, ['deleted_at' => date('Y-m-d H:i:s')], $where, $params);
    }

    /**
     * بدء معاملة
     */
    public function beginTransaction(): bool
    {
        return $this->pdo->beginTransaction();
    }

    /**
     * تأكيد المعاملة
     */
    public function commit(): bool
    {
        return $this->pdo->commit();
    }

    /**
     * التراجع عن المعاملة
     */
    public function rollBack(): bool
    {
        return $this->pdo->rollBack();
    }

    /**
     * عدد الصفوف
     */
    public function count(string $table, string $where = '1=1', array $params = []): int
    {
        $sql = "SELECT COUNT(*) FROM {$table} WHERE {$where}";
        return (int) $this->fetchColumn($sql, $params);
    }

    /**
     * التحقق من وجود سجل
     */
    public function exists(string $table, string $where, array $params = []): bool
    {
        return $this->count($table, $where, $params) > 0;
    }

    /**
     * جلب سجلات مع ترقيم الصفحات
     */
    public function paginate(string $sql, array $params, int $page, int $perPage): array
    {
        // عدد السجلات الكلي
        $countSql = "SELECT COUNT(*) FROM ({$sql}) AS count_table";
        $total = (int) $this->fetchColumn($countSql, $params);
        
        // حساب الإزاحة
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT {$perPage} OFFSET {$offset}";
        
        $data = $this->fetchAll($sql, $params);
        
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage),
        ];
    }

    /**
     * منع النسخ
     */
    private function __clone() {}
    public function __wakeup() { throw new \Exception("Cannot unserialize singleton"); }
}
