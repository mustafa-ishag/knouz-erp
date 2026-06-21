<!-- بطاقة العميل الشاملة (360 درجة) -->
<div class="page-header">
    <div>
        <h1 class="page-title"><?= clean($client['name']) ?></h1>
        <p class="page-subtitle">بطاقة العميل - <?= clean($client['client_number']) ?></p>
    </div>
    <div class="page-actions">
        <a href="<?= url('clients', 'edit', ['id' => $client['id']]) ?>" class="btn btn-outline">
            <i class="fas fa-edit"></i>
            تعديل
        </a>
        <a href="<?= url('companies', 'create', ['client_id' => $client['id']]) ?>" class="btn btn-outline">
            <i class="fas fa-building"></i>
            إضافة شركة
        </a>
        <a href="<?= url('orders', 'create', ['client_id' => $client['id']]) ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            طلب جديد
        </a>
    </div>
</div>

<div class="client-card-view">
    <!-- العمود الأيسر - معلومات العميل -->
    <div>
        <div class="card">
            <div class="card-body client-profile">
                <div class="client-avatar-large"><?= mb_substr($client['name'], 0, 2) ?></div>
                <h2 style="font-size: 1.375rem; font-weight: 800; margin-bottom: 4px;"><?= clean($client['name']) ?></h2>
                <p class="text-muted text-sm"><?= clean($client['client_number']) ?></p>
                
                <!-- معلومات التواصل -->
                <div style="text-align: right; margin-top: 1.5rem;">
                    <?php if ($client['phone']): ?>
                        <div class="d-flex align-center gap-1 mb-1">
                            <i class="fas fa-phone text-gold" style="width: 20px;"></i>
                            <span><?= formatPhone($client['phone']) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($client['phone2']): ?>
                        <div class="d-flex align-center gap-1 mb-1">
                            <i class="fas fa-phone text-muted" style="width: 20px;"></i>
                            <span><?= formatPhone($client['phone2']) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($client['email']): ?>
                        <div class="d-flex align-center gap-1 mb-1">
                            <i class="fas fa-envelope text-gold" style="width: 20px;"></i>
                            <span><?= clean($client['email']) ?></span>
                        </div>
                    <?php endif; ?>
                    <?php if ($client['city'] || $client['short_address'] || $client['street']): ?>
                        <div style="margin-top:12px; padding:10px; background:var(--bg-input); border-radius:8px;">
                            <div class="d-flex align-center gap-1 mb-1" style="font-weight:700;">
                                <i class="fas fa-map-marker-alt text-gold" style="width: 20px;"></i>
                                <span>العنوان</span>
                                <?php if ($client['short_address']): ?>
                                    <span class="badge badge-gold" style="margin-right:auto;"><?= clean($client['short_address']) ?></span>
                                <?php endif; ?>
                            </div>
                            <?php
                            $addressParts = array_filter([
                                $client['building_number'] ? 'مبنى ' . clean($client['building_number']) : '',
                                clean($client['street'] ?? ''),
                                clean($client['district'] ?? ''),
                                clean($client['city'] ?? ''),
                            ]);
                            if ($addressParts): ?>
                                <div class="text-sm" style="padding-right:28px;"><?= implode('، ', $addressParts) ?></div>
                            <?php endif; ?>
                            <?php if ($client['postal_code'] || $client['additional_number']): ?>
                                <div class="text-sm text-muted" style="padding-right:28px;">
                                    <?php if ($client['postal_code']): ?>الرمز البريدي: <?= clean($client['postal_code']) ?><?php endif; ?>
                                    <?php if ($client['postal_code'] && $client['additional_number']): ?> | <?php endif; ?>
                                    <?php if ($client['additional_number']): ?>الرقم الإضافي: <?= clean($client['additional_number']) ?><?php endif; ?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                    <?php if ($client['id_number']): ?>
                        <div class="d-flex align-center gap-1 mb-1">
                            <i class="fas fa-id-card text-gold" style="width: 20px;"></i>
                            <span><?= clean($client['id_number']) ?></span>
                        </div>
                    <?php endif; ?>
                </div>
                
                <!-- الإحصائيات -->
                <div class="client-stats-grid">
                    <div class="client-stat-item">
                        <div class="value"><?= $client['stats']['companies_count'] ?></div>
                        <div class="label">الشركات</div>
                    </div>
                    <div class="client-stat-item">
                        <div class="value"><?= $client['stats']['services_count'] ?></div>
                        <div class="label">الخدمات</div>
                    </div>
                    <div class="client-stat-item">
                        <div class="value"><?= $client['stats']['quotations_count'] ?></div>
                        <div class="label">عروض الأسعار</div>
                    </div>
                    <div class="client-stat-item">
                        <div class="value"><?= $client['stats']['invoices_count'] ?></div>
                        <div class="label">الفواتير</div>
                    </div>
                </div>
                
                <!-- ملخص مالي -->
                <div style="margin-top: 1.5rem; text-align: right;">
                    <div class="quick-stat">
                        <span class="quick-stat-label">إجمالي الإيرادات</span>
                        <span class="quick-stat-value text-gold"><?= formatMoney($client['stats']['total_revenue']) ?></span>
                    </div>
                    <div class="quick-stat">
                        <span class="quick-stat-label">إجمالي المدفوعات</span>
                        <span class="quick-stat-value text-success"><?= formatMoney($client['stats']['total_paid']) ?></span>
                    </div>
                    <div class="quick-stat">
                        <span class="quick-stat-label">الرصيد المستحق</span>
                        <span class="quick-stat-value <?= $client['stats']['balance_due'] > 0 ? 'text-danger' : 'text-success' ?>">
                            <?= formatMoney($client['stats']['balance_due']) ?>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- الملاحظات -->
        <?php if ($client['notes']): ?>
            <div class="card mt-2">
                <div class="card-header"><h3>ملاحظات</h3></div>
                <div class="card-body">
                    <p class="text-sm"><?= nl2br(clean($client['notes'])) ?></p>
                </div>
            </div>
        <?php endif; ?>
        
        <div class="card mt-2">
            <div class="card-body" style="text-align: center; padding: 1rem;">
                <small class="text-muted">تاريخ الإنشاء: <?= formatDate($client['created_at']) ?></small>
            </div>
        </div>
    </div>
    
    <!-- العمود الأيمن - التفاصيل -->
    <div>
        <!-- التبويبات -->
        <div class="card">
            <div class="tabs">
                <button class="tab-item active" data-tab-group="client" data-tab="companies" onclick="switchTab('client','companies')">
                    <i class="fas fa-building"></i> الشركات (<?= $client['stats']['companies_count'] ?>)
                </button>
                <button class="tab-item" data-tab-group="client" data-tab="orders" onclick="switchTab('client','orders')">
                    <i class="fas fa-clipboard-list"></i> الطلبات (<?= $client['stats']['services_count'] ?>)
                </button>
                <button class="tab-item" data-tab-group="client" data-tab="quotations" onclick="switchTab('client','quotations')">
                    <i class="fas fa-file-invoice"></i> عروض الأسعار (<?= $client['stats']['quotations_count'] ?>)
                </button>
                <button class="tab-item" data-tab-group="client" data-tab="invoices" onclick="switchTab('client','invoices')">
                    <i class="fas fa-file-invoice-dollar"></i> الفواتير (<?= $client['stats']['invoices_count'] ?>)
                </button>
                <button class="tab-item" data-tab-group="client" data-tab="calls" onclick="switchTab('client','calls')">
                    <i class="fas fa-phone"></i> المكالمات
                </button>
            </div>
            
            <!-- تبويب الشركات -->
            <div id="tab-client-companies" data-tab-content="client" class="tab-content active">
                <div class="card-body">
                    <?php if (empty($client['companies'])): ?>
                        <div class="empty-state">
                            <i class="fas fa-building"></i>
                            <h3>لا توجد شركات</h3>
                            <a href="<?= url('companies', 'create', ['client_id' => $client['id']]) ?>" class="btn btn-primary btn-sm mt-2">
                                <i class="fas fa-plus"></i> إضافة شركة
                            </a>
                        </div>
                    <?php else: ?>
                        <div class="table-wrapper">
                            <table class="data-table">
                                <thead>
                                    <tr>
                                        <th>الشركة</th>
                                        <th>السجل التجاري</th>
                                        <th>انتهاء السجل</th>
                                        <th>المدينة</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($client['companies'] as $company): ?>
                                        <tr>
                                            <td><strong><?= clean($company['name_ar']) ?></strong></td>
                                            <td><?= clean($company['cr_number'] ?: '-') ?></td>
                                            <td>
                                                <?php if ($company['cr_expiry_date']): ?>
                                                    <?php
                                                    $isExpiring = strtotime($company['cr_expiry_date']) < strtotime('+30 days');
                                                    $isExpired = strtotime($company['cr_expiry_date']) < time();
                                                    ?>
                                                    <span class="badge badge-<?= $isExpired ? 'danger' : ($isExpiring ? 'warning' : 'success') ?>">
                                                        <?= formatDate($company['cr_expiry_date']) ?>
                                                    </span>
                                                <?php else: ?>
                                                    -
                                                <?php endif; ?>
                                            </td>
                                            <td><?= clean($company['city'] ?: '-') ?></td>
                                            <td>
                                                <a href="<?= url('companies', 'show', ['id' => $company['id']]) ?>" class="btn btn-ghost btn-icon btn-sm">
                                                    <i class="fas fa-eye text-gold"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- تبويب الطلبات -->
            <div id="tab-client-orders" data-tab-content="client" class="tab-content">
                <div class="card-body">
                    <?php if (empty($client['recent_orders'])): ?>
                        <div class="empty-state">
                            <i class="fas fa-clipboard-list"></i>
                            <h3>لا توجد طلبات</h3>
                        </div>
                    <?php else: ?>
                        <div class="table-wrapper">
                            <table class="data-table">
                                <thead>
                                    <tr><th>رقم الطلب</th><th>الخدمة</th><th>الحالة</th><th>المبلغ</th><th>التاريخ</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($client['recent_orders'] as $order): ?>
                                        <tr>
                                            <td><a href="<?= url('orders', 'show', ['id' => $order['id']]) ?>"><?= clean($order['order_number']) ?></a></td>
                                            <td><?= clean($order['service_name'] ?? '-') ?></td>
                                            <td><?= statusBadge($order['status']) ?></td>
                                            <td><?= formatMoney($order['price']) ?></td>
                                            <td class="text-sm text-muted"><?= formatDate($order['created_at']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- تبويب عروض الأسعار -->
            <div id="tab-client-quotations" data-tab-content="client" class="tab-content">
                <div class="card-body">
                    <?php if (empty($client['quotations'])): ?>
                        <div class="empty-state">
                            <i class="fas fa-file-invoice"></i>
                            <h3>لا توجد عروض أسعار</h3>
                        </div>
                    <?php else: ?>
                        <div class="table-wrapper">
                            <table class="data-table">
                                <thead>
                                    <tr><th>رقم العرض</th><th>الحالة</th><th>الإجمالي</th><th>التاريخ</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($client['quotations'] as $q): ?>
                                        <tr>
                                            <td><a href="<?= url('quotations', 'show', ['id' => $q['id']]) ?>"><?= clean($q['quotation_number']) ?></a></td>
                                            <td><?= statusBadge($q['status']) ?></td>
                                            <td class="text-bold"><?= formatMoney($q['total']) ?></td>
                                            <td class="text-sm text-muted"><?= formatDate($q['quotation_date']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- تبويب الفواتير -->
            <div id="tab-client-invoices" data-tab-content="client" class="tab-content">
                <div class="card-body">
                    <?php if (empty($client['invoices'])): ?>
                        <div class="empty-state">
                            <i class="fas fa-file-invoice-dollar"></i>
                            <h3>لا توجد فواتير</h3>
                        </div>
                    <?php else: ?>
                        <div class="table-wrapper">
                            <table class="data-table">
                                <thead>
                                    <tr><th>رقم الفاتورة</th><th>الحالة</th><th>الإجمالي</th><th>المدفوع</th><th>التاريخ</th></tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($client['invoices'] as $inv): ?>
                                        <tr>
                                            <td><a href="<?= url('invoices', 'show', ['id' => $inv['id']]) ?>"><?= clean($inv['invoice_number']) ?></a></td>
                                            <td><?= statusBadge($inv['status']) ?></td>
                                            <td class="text-bold"><?= formatMoney($inv['total']) ?></td>
                                            <td class="text-success"><?= formatMoney($inv['paid_amount']) ?></td>
                                            <td class="text-sm text-muted"><?= formatDate($inv['invoice_date']) ?></td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- تبويب المكالمات -->
            <div id="tab-client-calls" data-tab-content="client" class="tab-content">
                <div class="card-body">
                    <?php if (empty($client['calls'])): ?>
                        <div class="empty-state">
                            <i class="fas fa-phone"></i>
                            <h3>لا توجد مكالمات مسجلة</h3>
                            <a href="<?= url('communications', 'calls', ['client_id' => $client['id']]) ?>" class="btn btn-primary btn-sm mt-2">
                                <i class="fas fa-plus"></i> تسجيل مكالمة
                            </a>
                        </div>
                    <?php else: ?>
                        <?php foreach ($client['calls'] as $call): ?>
                            <div class="activity-item">
                                <div class="activity-icon" style="background: <?= $call['call_type'] === 'incoming' ? 'var(--info-bg)' : 'var(--success-bg)' ?>; color: <?= $call['call_type'] === 'incoming' ? 'var(--info)' : 'var(--success)' ?>;">
                                    <i class="fas fa-phone-<?= $call['call_type'] === 'incoming' ? 'arrow-down-left' : 'arrow-up-right' ?>"></i>
                                </div>
                                <div class="activity-content">
                                    <div class="title">
                                        <?= $call['call_type'] === 'incoming' ? 'مكالمة واردة' : 'مكالمة صادرة' ?>
                                        <?php if ($call['result']): ?>
                                            - <?= statusBadge($call['result']) ?>
                                        <?php endif; ?>
                                    </div>
                                    <?php if ($call['notes']): ?>
                                        <div class="desc"><?= clean(truncate($call['notes'], 100)) ?></div>
                                    <?php endif; ?>
                                    <div class="time">
                                        <?= clean($call['user_name'] ?? '') ?> - <?= formatDateTime($call['call_date']) ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
</div>
