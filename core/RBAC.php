<?php
/**
 * نظام الصلاحيات (Role Based Access Control)
 */

class RBAC
{
    private Database $db;
    private array $userPermissions = [];

    public function __construct()
    {
        $this->db = Database::getInstance();
        $this->loadPermissions();
    }

    /**
     * تحميل صلاحيات المستخدم الحالي
     */
    private function loadPermissions(): void
    {
        $auth = Auth::getInstance();
        if (!$auth->isLoggedIn()) return;

        // مدير النظام لديه جميع الصلاحيات
        if ($auth->isAdmin()) {
            $permissions = $this->db->fetchAll("SELECT slug FROM permissions");
            $this->userPermissions = array_column($permissions, 'slug');
            return;
        }

        $roleId = $auth->getUser()['role_id'] ?? 0;
        $permissions = $this->db->fetchAll(
            "SELECT p.slug 
             FROM permissions p 
             INNER JOIN role_permissions rp ON p.id = rp.permission_id 
             WHERE rp.role_id = ?",
            [$roleId]
        );
        $this->userPermissions = array_column($permissions, 'slug');
    }

    /**
     * التحقق من صلاحية
     */
    public function hasPermission(string $permission): bool
    {
        $auth = Auth::getInstance();
        if ($auth->isAdmin()) return true;
        return in_array($permission, $this->userPermissions);
    }

    /**
     * التحقق من أي صلاحية من مجموعة
     */
    public function hasAnyPermission(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if ($this->hasPermission($permission)) return true;
        }
        return false;
    }

    /**
     * التحقق من جميع الصلاحيات
     */
    public function hasAllPermissions(array $permissions): bool
    {
        foreach ($permissions as $permission) {
            if (!$this->hasPermission($permission)) return false;
        }
        return true;
    }

    /**
     * الحصول على جميع صلاحيات المستخدم
     */
    public function getPermissions(): array
    {
        return $this->userPermissions;
    }

    /**
     * جلب جميع الأدوار
     */
    public function getAllRoles(): array
    {
        return $this->db->fetchAll("SELECT * FROM roles ORDER BY id");
    }

    /**
     * جلب صلاحيات دور
     */
    public function getRolePermissions(int $roleId): array
    {
        return $this->db->fetchAll(
            "SELECT p.* FROM permissions p 
             INNER JOIN role_permissions rp ON p.id = rp.permission_id 
             WHERE rp.role_id = ?",
            [$roleId]
        );
    }

    /**
     * تحديث صلاحيات دور
     */
    public function updateRolePermissions(int $roleId, array $permissionIds): void
    {
        $this->db->delete('role_permissions', 'role_id = ?', [$roleId]);
        
        foreach ($permissionIds as $permId) {
            $this->db->insert('role_permissions', [
                'role_id' => $roleId,
                'permission_id' => $permId,
            ]);
        }
    }

    /**
     * جلب جميع الصلاحيات المتاحة
     */
    public function getAllPermissions(): array
    {
        return $this->db->fetchAll("SELECT * FROM permissions ORDER BY module, name");
    }

    /**
     * جلب الصلاحيات مجمعة حسب الوحدة
     */
    public function getPermissionsGrouped(): array
    {
        $permissions = $this->getAllPermissions();
        $grouped = [];
        foreach ($permissions as $p) {
            $grouped[$p['module']][] = $p;
        }
        return $grouped;
    }
}
