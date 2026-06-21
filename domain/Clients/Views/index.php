<!-- قائمة العملاء -->
<div class="page-header">
    <div>
        <h1 class="page-title">إدارة العملاء</h1>
        <p class="page-subtitle">إجمالي <?= number_format($result['total']) ?> عميل</p>
    </div>
    <div class="page-actions">
        <a href="<?= url('clients', 'create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            إضافة عميل
        </a>
    </div>
</div>

<div class="card">
    <!-- شريط البحث والتصفية -->
    <div class="table-header">
        <div class="table-search">
            <form method="GET" action="<?= url('clients') ?>" style="display:flex; gap:8px; align-items:center;">
                <input type="hidden" name="module" value="clients">
                <input type="hidden" name="action" value="index">
                <input type="text" name="search" value="<?= clean($search) ?>" placeholder="بحث بالاسم، الجوال، البريد..." class="form-control" style="min-width:250px;">
                <select name="city" class="form-control" style="width:150px;">
                    <option value="">كل المدن</option>
                    <?php foreach ($cities as $c): ?>
                        <option value="<?= clean($c['city']) ?>" <?= $city === $c['city'] ? 'selected' : '' ?>><?= clean($c['city']) ?></option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="btn btn-outline btn-sm"><i class="fas fa-search"></i></button>
                <?php if ($search || $city): ?>
                    <a href="<?= url('clients') ?>" class="btn btn-ghost btn-sm"><i class="fas fa-times"></i> مسح</a>
                <?php endif; ?>
            </form>
        </div>
    </div>
    
    <!-- جدول العملاء -->
    <div class="table-wrapper">
        <table class="data-table" id="data-table">
            <thead>
                <tr>
                    <th>رقم العميل</th>
                    <th>الاسم</th>
                    <th>الجوال</th>
                    <th>المدينة</th>
                    <th>الشركات</th>
                    <th>الطلبات</th>
                    <th>المدفوعات</th>
                    <th>تاريخ الإنشاء</th>
                    <th>الإجراءات</th>
                </tr>
            </thead>
            <tbody>
                <?php if (empty($result['data'])): ?>
                    <tr>
                        <td colspan="9">
                            <div class="empty-state">
                                <i class="fas fa-users"></i>
                                <h3>لا يوجد عملاء</h3>
                                <p>ابدأ بإضافة أول عميل</p>
                                <a href="<?= url('clients', 'create') ?>" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> إضافة عميل
                                </a>
                            </div>
                        </td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($result['data'] as $client): ?>
                        <tr>
                            <td><span class="badge badge-primary"><?= clean($client['client_number']) ?></span></td>
                            <td>
                                <a href="<?= url('clients', 'card', ['id' => $client['id']]) ?>" style="color: var(--text-primary); font-weight: 600;">
                                    <?= clean($client['name']) ?>
                                </a>
                            </td>
                            <td class="text-nowrap"><?= formatPhone($client['phone']) ?></td>
                            <td><?= clean($client['city'] ?: '-') ?></td>
                            <td><span class="badge badge-info"><?= $client['companies_count'] ?></span></td>
                            <td><span class="badge badge-warning"><?= $client['orders_count'] ?></span></td>
                            <td class="text-bold"><?= formatMoney($client['total_payments']) ?></td>
                            <td class="text-muted text-sm"><?= formatDate($client['created_at']) ?></td>
                            <td>
                                <div class="table-actions">
                                    <a href="<?= url('clients', 'card', ['id' => $client['id']]) ?>" class="btn btn-ghost btn-icon btn-sm" title="عرض">
                                        <i class="fas fa-eye text-gold"></i>
                                    </a>
                                    <a href="<?= url('clients', 'edit', ['id' => $client['id']]) ?>" class="btn btn-ghost btn-icon btn-sm" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <button onclick="confirmDelete('<?= url('clients', 'delete', ['id' => $client['id']]) ?>', '<?= clean($client['name']) ?>')" class="btn btn-ghost btn-icon btn-sm" title="حذف">
                                        <i class="fas fa-trash text-danger"></i>
                                    </button>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <!-- الترقيم -->
    <?php if ($result['total_pages'] > 1): ?>
        <div class="pagination">
            <?php if ($result['page'] > 1): ?>
                <a href="<?= url('clients', 'index', ['page' => $result['page'] - 1, 'search' => $search, 'city' => $city]) ?>">
                    <i class="fas fa-chevron-right"></i>
                </a>
            <?php endif; ?>
            
            <?php for ($i = 1; $i <= $result['total_pages']; $i++): ?>
                <?php if ($i <= 3 || $i > $result['total_pages'] - 3 || abs($i - $result['page']) <= 1): ?>
                    <a href="<?= url('clients', 'index', ['page' => $i, 'search' => $search, 'city' => $city]) ?>" 
                       class="<?= $i === $result['page'] ? 'active' : '' ?>">
                        <?= $i ?>
                    </a>
                <?php elseif ($i === 4 || $i === $result['total_pages'] - 3): ?>
                    <span>...</span>
                <?php endif; ?>
            <?php endfor; ?>
            
            <?php if ($result['page'] < $result['total_pages']): ?>
                <a href="<?= url('clients', 'index', ['page' => $result['page'] + 1, 'search' => $search, 'city' => $city]) ?>">
                    <i class="fas fa-chevron-left"></i>
                </a>
            <?php endif; ?>
        </div>
        <div class="pagination-info">
            عرض <?= (($result['page'] - 1) * $result['per_page']) + 1 ?> - <?= min($result['page'] * $result['per_page'], $result['total']) ?> من <?= $result['total'] ?> عميل
        </div>
    <?php endif; ?>
</div>
