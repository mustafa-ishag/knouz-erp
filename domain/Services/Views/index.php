<div class="page-header">
    <div><h1 class="page-title">مكتبة الخدمات</h1><p class="page-subtitle"><?= number_format($result['total']) ?> خدمة</p></div>
    <div class="page-actions"><a href="<?= url('services', 'create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة خدمة</a></div>
</div>
<div class="card">
    <div class="table-header">
        <form method="GET" action="<?= url('services') ?>" style="display:flex;gap:8px;">
            <input type="hidden" name="module" value="services"><input type="hidden" name="action" value="index">
            <input type="text" name="search" value="<?= clean($search) ?>" placeholder="بحث..." class="form-control" style="min-width:200px;">
            <select name="category_id" class="form-control" style="width:180px;">
                <option value="">كل التصنيفات</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= $catId == $cat['id'] ? 'selected' : '' ?>><?= clean($cat['name']) ?></option>
                <?php endforeach; ?>
            </select>
            <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-search"></i></button>
        </form>
    </div>
    <div class="table-wrapper">
        <table class="data-table"><thead><tr><th>الخدمة</th><th>التصنيف</th><th>المنصة</th><th>المدة</th><th>السعر</th><th>التكلفة</th><th>الربح</th><th>الحالة</th><th>الإجراءات</th></tr></thead>
        <tbody>
            <?php if (empty($result['data'])): ?>
                <tr><td colspan="9"><div class="empty-state"><i class="fas fa-cogs"></i><h3>لا توجد خدمات</h3></div></td></tr>
            <?php else: ?>
                <?php foreach ($result['data'] as $s): ?>
                    <tr>
                        <td><strong><?= clean($s['name']) ?></strong><?php if($s['description']): ?><br><small class="text-muted"><?= clean(truncate($s['description'],60)) ?></small><?php endif; ?></td>
                        <td><span class="tag"><i class="fas <?= clean($s['category_icon'] ?? 'fa-cog') ?>" style="color:<?= clean($s['category_color'] ?? '#999') ?>;"></i> <?= clean($s['category_name'] ?? '-') ?></span></td>
                        <td><?= clean($s['platform'] ?: '-') ?></td>
                        <td><?= $s['execution_days'] ?> يوم</td>
                        <td class="text-bold"><?= formatMoney($s['default_price']) ?></td>
                        <td><?= formatMoney($s['default_cost']) ?></td>
                        <td class="text-success text-bold"><?= formatMoney($s['default_price'] - $s['default_cost']) ?></td>
                        <td><span class="badge badge-<?= $s['is_active'] ? 'success' : 'secondary' ?>"><?= $s['is_active'] ? 'نشطة' : 'معطلة' ?></span></td>
                        <td>
                            <div class="table-actions">
                                <a href="<?= url('services', 'edit', ['id' => $s['id']]) ?>" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-edit"></i></a>
                                <button onclick="confirmDelete('<?= url('services', 'delete', ['id' => $s['id']]) ?>')" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-trash text-danger"></i></button>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody></table>
    </div>
</div>
