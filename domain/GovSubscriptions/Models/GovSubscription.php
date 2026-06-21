<?php
/**
 * نموذج الاشتراكات الحكومية
 */

class GovSubscription extends Model
{
    protected string $table = 'gov_subscriptions';
    protected bool $softDelete = true;
    protected array $fillable = [
        'platform', 'company_id', 'company_name', 'subscription_number',
        'start_date', 'end_date', 'cost', 'username', 'password_hint',
        'notes', 'created_by'
    ];
    protected array $searchable = ['platform', 'company_name', 'subscription_number'];

    /**
     * المنصات الحكومية المتاحة (ثابتة + مضافة مسبقاً)
     */
    public static function platforms(): array
    {
        $defaults = [
            'قوى (Qiwa)', 'هيئة الزكاة (ZATCA)', 'مقيم (Muqeem)',
            'مدد (Mudad)', 'بلدي (Balady)', 'التأمينات (GOSI)',
            'سلامة', 'وزارة التجارة', 'الغرفة التجارية', 'هيئة النقل'
        ];

        try {
            $db = Database::getInstance();
            $existing = $db->fetchAll("SELECT DISTINCT platform FROM gov_subscriptions WHERE deleted_at IS NULL AND platform != ''");
            $dbPlatforms = array_column($existing, 'platform');
            $all = array_unique(array_merge($defaults, $dbPlatforms));
        } catch (Exception $e) {
            $all = $defaults;
        }

        $result = [];
        foreach ($all as $p) {
            // استبعاد الخيارات القديمة المكتوبة بالانجليزي اذا وجدت
            if (in_array($p, ['qiwa', 'zatca', 'muqeem', 'mudad', 'balady', 'gosi', 'salamah', 'mol', 'chamber', 'transport', 'other'])) continue;
            $result[$p] = $p;
        }
        return $result;
    }

    /**
     * الحصول على اسم المنصة
     */
    public static function platformLabel(string $key): string
    {
        $oldMap = [
            'qiwa' => 'قوى (Qiwa)', 'zatca' => 'هيئة الزكاة (ZATCA)', 'muqeem' => 'مقيم (Muqeem)',
            'mudad' => 'مدد (Mudad)', 'balady' => 'بلدي (Balady)', 'gosi' => 'التأمينات (GOSI)',
            'salamah' => 'سلامة', 'mol' => 'وزارة التجارة', 'chamber' => 'الغرفة التجارية',
            'transport' => 'هيئة النقل', 'other' => 'أخرى'
        ];
        return $oldMap[$key] ?? $key;
    }

    /**
     * جلب جميع الاشتراكات مع بيانات الشركة
     */
    public function getAllWithCompany(string $platform = '', string $search = ''): array
    {
        $where = 'gs.deleted_at IS NULL';
        $params = [];

        if ($platform) {
            $where .= ' AND gs.platform = ?';
            $params[] = $platform;
        }

        if ($search) {
            $where .= ' AND (gs.company_name LIKE ? OR gs.subscription_number LIKE ? OR gs.platform LIKE ?)';
            $s = "%{$search}%";
            $params = array_merge($params, [$s, $s, $s]);
        }

        return $this->db->fetchAll(
            "SELECT gs.*, c.name_ar as linked_company_name
             FROM gov_subscriptions gs
             LEFT JOIN companies c ON gs.company_id = c.id
             WHERE {$where}
             ORDER BY gs.end_date ASC",
            $params
        );
    }

    /**
     * إحصائيات المنصات
     */
    public function getPlatformStats(): array
    {
        return $this->db->fetchAll(
            "SELECT platform, COUNT(*) as total,
                    SUM(CASE WHEN end_date < CURDATE() THEN 1 ELSE 0 END) as expired,
                    SUM(CASE WHEN end_date >= CURDATE() AND end_date <= DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as expiring,
                    SUM(CASE WHEN end_date > DATE_ADD(CURDATE(), INTERVAL 30 DAY) THEN 1 ELSE 0 END) as active
             FROM gov_subscriptions
             WHERE deleted_at IS NULL
             GROUP BY platform
             ORDER BY platform"
        );
    }
}
