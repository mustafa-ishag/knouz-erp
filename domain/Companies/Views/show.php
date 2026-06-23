<!-- بطاقة الشركة الشاملة (360 درجة) -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= clean($company['name_ar']) ?></h1>
        <p class="page-subtitle">بطاقة الشركة - <?= clean($company['cr_number'] ?: 'بدون سجل') ?></p>
    </div>
    <div class="page-actions">
        <a href="<?= url('companies', 'edit', ['id' => $company['id']]) ?>" class="btn btn-outline"><i class="fas fa-edit"></i> تعديل</a>
        <a href="<?= url('orders', 'create', ['client_id' => $company['client_id'], 'company_id' => $company['id']]) ?>" class="btn btn-primary"><i class="fas fa-plus"></i> طلب جديد</a>
    </div>
</div>

<div class="client-card-view">
    <!-- العمود الأيمن - معلومات الشركة -->
    <div>
        <div class="card">
            <div class="card-body client-profile">
                <div class="client-avatar-large" style="background:linear-gradient(135deg,#176cb4,#22ae82);"><?= mb_substr($company['name_ar'], 0, 2) ?></div>
                <h2 style="font-size: 1.375rem; font-weight: 800; margin-bottom: 4px;"><?= clean($company['name_ar']) ?></h2>
                <?php if ($company['name_en']): ?><p class="text-muted text-sm" dir="ltr"><?= clean($company['name_en']) ?></p><?php endif; ?>
                <p class="text-muted text-sm">العميل: <a href="<?= url('clients', 'card', ['id' => $company['client_id']]) ?>"><?= clean($company['client']['name'] ?? '-') ?></a></p>

                <div style="text-align: right; margin-top: 1.5rem;">
                    <?php if ($company['phone']): ?>
                        <div class="d-flex align-center gap-1 mb-1"><i class="fas fa-phone text-gold" style="width:20px;"></i><span dir="ltr"><?= clean($company['phone']) ?></span></div>
                    <?php endif; ?>
                    <?php if ($company['email']): ?>
                        <div class="d-flex align-center gap-1 mb-1"><i class="fas fa-envelope text-gold" style="width:20px;"></i><span><?= clean($company['email']) ?></span></div>
                    <?php endif; ?>
                    <?php if ($company['city']): ?>
                        <div class="d-flex align-center gap-1 mb-1"><i class="fas fa-map-marker-alt text-gold" style="width:20px;"></i><span><?= clean($company['city']) ?></span></div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- بطاقات إحصائية -->
        <div class="card mt-2">
            <div class="card-body">
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;">
                    <div style="text-align:center;padding:12px;background:var(--bg-input);border-radius:10px;">
                        <div class="text-bold text-lg" style="color:var(--primary);"><?= number_format($company['stats']['total_orders']) ?></div>
                        <div class="text-xs text-muted">إجمالي الطلبات</div>
                    </div>
                    <div style="text-align:center;padding:12px;background:var(--bg-input);border-radius:10px;">
                        <div class="text-bold text-lg" style="color:#22ae82;"><?= number_format($company['stats']['completed_orders']) ?></div>
                        <div class="text-xs text-muted">طلبات منجزة</div>
                    </div>
                    <div style="text-align:center;padding:12px;background:var(--bg-input);border-radius:10px;">
                        <div class="text-bold text-lg" style="color:#176cb4;"><?= formatMoney($company['stats']['total_revenue'], false) ?></div>
                        <div class="text-xs text-muted">الإيرادات</div>
                    </div>
                    <div style="text-align:center;padding:12px;background:var(--bg-input);border-radius:10px;">
                        <div class="text-bold text-lg" style="color:var(--danger);"><?= formatMoney($company['stats']['pending_amount'], false) ?></div>
                        <div class="text-xs text-muted">مبالغ مستحقة</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- بيانات الشركة -->
        <div class="card mt-2">
            <div class="card-header"><h3><i class="fas fa-info-circle text-gold mr-1"></i> البيانات الأساسية</h3></div>
            <div class="card-body">
                <div class="quick-stat"><span class="quick-stat-label">السجل التجاري</span><span class="quick-stat-value"><?= clean($company['cr_number'] ?: '-') ?></span></div>
                <div class="quick-stat"><span class="quick-stat-label">الرقم الموحد</span><span class="quick-stat-value"><?= clean($company['unified_number'] ?: '-') ?></span></div>
                <div class="quick-stat"><span class="quick-stat-label">الرقم المميز</span><span class="quick-stat-value"><?= clean($company['distinctive_number'] ?: '-') ?></span></div>
                <div class="quick-stat"><span class="quick-stat-label">رقم قوى</span><span class="quick-stat-value"><?= clean($company['qiwa_number'] ?: '-') ?></span></div>
                <div class="quick-stat"><span class="quick-stat-label">النشاط</span><span class="quick-stat-value"><?= clean($company['activity'] ?: '-') ?></span></div>
                <?php if ($company['cr_issue_date']): ?>
                    <div class="quick-stat"><span class="quick-stat-label">تاريخ إصدار السجل</span><span class="quick-stat-value"><?= formatDate($company['cr_issue_date']) ?></span></div>
                <?php endif; ?>
                <?php if ($company['cr_expiry_date']): ?>
                    <div class="quick-stat"><span class="quick-stat-label">انتهاء السجل</span>
                        <span class="badge badge-<?= strtotime($company['cr_expiry_date']) < time() ? 'danger' : (strtotime($company['cr_expiry_date']) < strtotime('+30 days') ? 'warning' : 'success') ?>"><?= formatDate($company['cr_expiry_date']) ?></span>
                    </div>
                <?php endif; ?>
                <?php if ($company['notes']): ?>
                    <div class="quick-stat"><span class="quick-stat-label">ملاحظات</span><span class="quick-stat-value"><?= clean($company['notes']) ?></span></div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- العمود الأيسر - التبويبات -->
    <div>
        <!-- تبويبات -->
        <div class="card">
            <div class="card-header" style="border-bottom:none;padding-bottom:0;">
                <div class="tab-nav" style="display:flex;gap:0;flex-wrap:wrap;">
                    <button class="tab-btn active" onclick="switchTab(this,'orders-tab')"><i class="fas fa-clipboard-list"></i> الطلبات</button>
                    <button class="tab-btn" onclick="switchTab(this,'claims-tab')"><i class="fas fa-file-invoice-dollar"></i> المطالبات</button>
                    <button class="tab-btn" onclick="switchTab(this,'employees-tab')"><i class="fas fa-users"></i> الموظفين (<?= $company['stats']['employees_count'] ?>)</button>
                    <button class="tab-btn" onclick="switchTab(this,'gov-tab')"><i class="fas fa-landmark"></i> الاشتراكات الحكومية</button>
                    <button class="tab-btn" onclick="switchTab(this,'docs-tab')"><i class="fas fa-folder-open"></i> المستندات</button>
                </div>
            </div>

            <!-- تبويب الطلبات -->
            <div class="tab-content active" id="orders-tab">
                <div class="card-body">
                    <?php if (empty($company['orders'])): ?>
                        <div class="empty-state"><i class="fas fa-clipboard-list"></i><h3>لا توجد طلبات</h3></div>
                    <?php else: ?>
                        <table class="data-table"><thead><tr><th>رقم الطلب</th><th>الخدمة</th><th>الحالة</th><th>المبلغ</th><th>التاريخ</th></tr></thead>
                        <tbody>
                            <?php foreach ($company['orders'] as $o): ?>
                                <tr>
                                    <td><a href="<?= url('orders', 'show', ['id' => $o['id']]) ?>"><?= clean($o['order_number']) ?></a></td>
                                    <td><?= clean($o['service_name'] ?? '-') ?></td>
                                    <td><?= statusBadge($o['status']) ?></td>
                                    <td><?= formatMoney($o['price']) ?></td>
                                    <td class="text-muted text-sm"><?= formatDate($o['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody></table>
                    <?php endif; ?>
                </div>
            </div>

            <!-- تبويب المطالبات -->
            <div class="tab-content" id="claims-tab" style="display:none;">
                <div class="card-body">
                    <?php if (empty($company['claims'])): ?>
                        <div class="empty-state"><i class="fas fa-file-invoice-dollar"></i><h3>لا توجد مطالبات</h3></div>
                    <?php else: ?>
                        <table class="data-table"><thead><tr><th>رقم المطالبة</th><th>المبلغ</th><th>المدفوع</th><th>الحالة</th><th>التاريخ</th></tr></thead>
                        <tbody>
                            <?php foreach ($company['claims'] as $cl): ?>
                                <tr>
                                    <td><a href="<?= url('claims', 'show', ['id' => $cl['id']]) ?>"><?= clean($cl['claim_number']) ?></a></td>
                                    <td class="text-bold"><?= formatMoney($cl['total']) ?></td>
                                    <td><?= formatMoney($cl['paid_amount']) ?></td>
                                    <td><?= statusBadge($cl['status']) ?></td>
                                    <td class="text-muted text-sm"><?= formatDate($cl['created_at']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody></table>
                    <?php endif; ?>
                </div>
            </div>

            <!-- تبويب الموظفين -->
            <div class="tab-content" id="employees-tab" style="display:none;">
                <div class="card-body">
                    <!-- نموذج إضافة موظف -->
                    <form method="POST" action="<?= url('companies', 'store_employee') ?>" style="background:var(--bg-input);padding:16px;border-radius:12px;margin-bottom:16px;">
                        <input type="hidden" name="company_id" value="<?= $company['id'] ?>">
                        <h4 style="margin-bottom:12px;"><i class="fas fa-user-plus text-gold"></i> إضافة موظف جديد</h4>
                        <div class="form-row">
                            <div class="form-group"><label class="form-label">الاسم <span class="required">*</span></label><input type="text" name="name" class="form-control" required></div>
                            <div class="form-group"><label class="form-label">المنصب</label><input type="text" name="position" class="form-control"></div>
                        </div>
                        <div class="form-row">
                            <div class="form-group"><label class="form-label">الهاتف</label><input type="text" name="phone" class="form-control" dir="ltr"></div>
                            <div class="form-group"><label class="form-label">البريد</label><input type="email" name="email" class="form-control" dir="ltr"></div>
                            <div class="form-group"><label class="form-label">رقم الهوية</label><input type="text" name="id_number" class="form-control" dir="ltr"></div>
                        </div>
                        <button type="submit" class="btn btn-primary btn-sm"><i class="fas fa-plus"></i> إضافة</button>
                    </form>

                    <?php if (empty($company['employees'])): ?>
                        <div class="empty-state"><i class="fas fa-users"></i><h3>لا يوجد موظفين مسجلين</h3></div>
                    <?php else: ?>
                        <table class="data-table"><thead><tr><th>الاسم</th><th>المنصب</th><th>الهاتف</th><th>البريد</th><th>رقم الهوية</th><th></th></tr></thead>
                        <tbody>
                            <?php foreach ($company['employees'] as $emp): ?>
                                <tr>
                                    <td class="text-bold"><?= clean($emp['name']) ?></td>
                                    <td><?= clean($emp['position'] ?: '-') ?></td>
                                    <td dir="ltr"><?= clean($emp['phone'] ?: '-') ?></td>
                                    <td dir="ltr"><?= clean($emp['email'] ?: '-') ?></td>
                                    <td dir="ltr"><?= clean($emp['id_number'] ?: '-') ?></td>
                                    <td>
                                        <button onclick="confirmDelete('<?= url('companies', 'delete_employee', ['id' => $emp['id']]) ?>')" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-trash text-danger"></i></button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody></table>
                    <?php endif; ?>
                </div>
            </div>

            <!-- تبويب الاشتراكات الحكومية -->
            <div class="tab-content" id="gov-tab" style="display:none;">
                <div class="card-body">
                    <?php if (empty($company['gov_subscriptions'])): ?>
                        <div class="empty-state"><i class="fas fa-landmark"></i><h3>لا توجد اشتراكات</h3><a href="<?= url('gov_subscriptions', 'create') ?>" class="btn btn-outline btn-sm mt-1">إضافة اشتراك</a></div>
                    <?php else: ?>
                        <table class="data-table"><thead><tr><th>المنصة</th><th>اسم المستخدم</th><th>الحالة</th><th>تاريخ الانتهاء</th></tr></thead>
                        <tbody>
                            <?php foreach ($company['gov_subscriptions'] as $gs): ?>
                                <tr>
                                    <td class="text-bold"><?= clean($gs['platform_name']) ?></td>
                                    <td dir="ltr"><?= clean($gs['username'] ?? '-') ?></td>
                                    <td><?= statusBadge($gs['status'] ?? 'active') ?></td>
                                    <td class="text-muted text-sm"><?= formatDate($gs['expiry_date'] ?? null) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody></table>
                    <?php endif; ?>
                </div>
            </div>

            <!-- تبويب المستندات -->
            <div class="tab-content" id="docs-tab" style="display:none;">
                <div class="card-body">
                    <?php if (empty($company['documents'])): ?>
                        <div class="empty-state"><i class="fas fa-folder-open"></i><h3>لا توجد مستندات</h3></div>
                    <?php else: ?>
                        <?php foreach ($company['documents'] as $doc): ?>
                            <div class="d-flex align-center gap-2 mb-2" style="padding:10px;background:var(--bg-input);border-radius:10px;">
                                <i class="fas fa-file-pdf" style="font-size:1.5rem;color:var(--danger);"></i>
                                <div class="flex-1">
                                    <div class="text-bold text-sm"><?= clean($doc['title']) ?></div>
                                    <div class="text-xs text-muted"><?= clean($doc['document_type']) ?> • <?= formatDate($doc['created_at']) ?></div>
                                </div>
                                <?php if (!empty($doc['license_number'])): ?>
                                    <span class="badge badge-info">رقم: <?= clean($doc['license_number']) ?></span>
                                <?php endif; ?>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.tab-nav { display: flex; gap: 0; border-bottom: 2px solid var(--border); overflow-x: auto; }
.tab-btn { padding: 10px 16px; border: none; background: none; font-family: var(--font-family); font-size: 0.85rem; cursor: pointer; color: var(--text-muted); border-bottom: 2px solid transparent; margin-bottom: -2px; white-space: nowrap; transition: all 0.3s ease; }
.tab-btn:hover { color: var(--text-primary); }
.tab-btn.active { color: var(--primary); border-bottom-color: var(--primary); font-weight: 600; }
.tab-btn i { margin-left: 4px; }
</style>

<script>
function switchTab(btn, tabId) {
    document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
    document.querySelectorAll('.tab-content').forEach(t => t.style.display = 'none');
    btn.classList.add('active');
    document.getElementById(tabId).style.display = 'block';
}
</script>
