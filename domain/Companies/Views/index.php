<div class="page-header">
    <div>
        <h1 class="page-title">إدارة الشركات</h1>
        <p class="page-subtitle">إجمالي <?= number_format($result['total']) ?> شركة</p>
    </div>
    <div class="page-actions">
        <a href="<?= url('companies', 'create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة شركة</a>
    </div>
</div>
<div class="card">
    <div class="table-header">
        <form method="GET" action="<?= url('companies') ?>" style="display:flex; gap:8px;">
            <input type="hidden" name="module" value="companies"><input type="hidden" name="action" value="index">
            <input type="text" name="search" value="<?= clean($search) ?>" placeholder="بحث..." class="form-control" style="min-width:250px;">
            <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-search"></i></button>
            <?php if ($search): ?><a href="<?= url('companies') ?>" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i></a><?php endif; ?>
        </form>
    </div>
    <div class="table-wrapper">
        <table class="data-table">
            <thead><tr><th>الشركة</th><th>العميل</th><th>السجل التجاري</th><th>الرقم الموحد</th><th>المدينة</th><th>انتهاء السجل</th><th>الإجراءات</th></tr></thead>
            <tbody>
                <?php if (empty($result['data'])): ?>
                    <tr><td colspan="7"><div class="empty-state"><i class="fas fa-building"></i><h3>لا توجد شركات</h3><a href="<?= url('companies', 'create') ?>" class="btn btn-primary btn-sm mt-2"><i class="fas fa-plus"></i> إضافة شركة</a></div></td></tr>
                <?php else: ?>
                    <?php foreach ($result['data'] as $co): ?>
                        <tr>
                            <td><a href="<?= url('companies', 'show', ['id' => $co['id']]) ?>" style="color:var(--text-primary);font-weight:600;"><?= clean($co['name_ar']) ?></a></td>
                            <td><a href="<?= url('clients', 'card', ['id' => $co['client_id']]) ?>"><?= clean($co['client_name'] ?? '-') ?></a></td>
                            <td><?= clean($co['cr_number'] ?: '-') ?></td>
                            <td><?= clean($co['unified_number'] ?: '-') ?></td>
                            <td><?= clean($co['city'] ?: '-') ?></td>
                            <td>
                                <?php if ($co['cr_expiry_date']): ?>
                                    <span class="badge badge-<?= strtotime($co['cr_expiry_date']) < time() ? 'danger' : (strtotime($co['cr_expiry_date']) < strtotime('+30 days') ? 'warning' : 'success') ?>">
                                        <?= formatDate($co['cr_expiry_date']) ?>
                                    </span>
                                <?php else: ?>-<?php endif; ?>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="<?= url('companies', 'show', ['id' => $co['id']]) ?>" class="btn btn-ghost btn-icon btn-sm" title="عرض"><i class="fas fa-eye text-gold"></i></a>
                                    <a href="<?= url('companies', 'edit', ['id' => $co['id']]) ?>" class="btn btn-ghost btn-icon btn-sm" title="تعديل"><i class="fas fa-edit"></i></a>
                                    <button onclick="confirmDelete('<?= url('companies', 'delete', ['id' => $co['id']]) ?>', '<?= clean($co['name_ar']) ?>')" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-trash text-danger"></i></button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    <?php if ($result['total_pages'] > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $result['total_pages']; $i++): ?>
                <a href="<?= url('companies', 'index', ['page' => $i, 'search' => $search]) ?>" class="<?= $i === $result['page'] ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
    <?php endif; ?>
</div>
