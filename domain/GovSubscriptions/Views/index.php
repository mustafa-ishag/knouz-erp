<!-- الاشتراكات الحكومية -->
<div class="page-header">
    <div>
        <h1 class="page-title">الاشتراكات الحكومية</h1>
        <p class="page-subtitle">متابعة <?= count($subscriptions) ?> اشتراك في <?= $totalPlatforms ?> منصة حكومية وجهة رسمية</p>
    </div>
    <div class="page-actions">
        <a href="<?= url('gov_subscriptions', 'create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            اشتراك جديد
        </a>
    </div>
</div>

<!-- فلاتر المنصات -->
<div class="card mb-2">
    <div class="card-body" style="padding: 12px 16px;">
        <div class="d-flex gap-1" style="flex-wrap: wrap;">
            <a href="<?= url('gov_subscriptions') ?>"
               class="btn btn-sm <?= !$platform ? 'btn-primary' : 'btn-outline' ?>"
               style="border-radius: 20px; font-size: 0.8rem;">
                الكل
            </a>
            <?php foreach ($platforms as $key => $label): ?>
                <?php
                // حساب عدد الاشتراكات لهذه المنصة
                $count = 0;
                foreach ($subscriptions as $s) {
                    if ($s['platform'] === $key) $count++;
                }
                // عرض فقط المنصات التي لها اشتراكات أو كانت محددة
                // نعرض كل المنصات
                ?>
                <a href="<?= url('gov_subscriptions', 'index', ['platform' => $key]) ?>"
                   class="btn btn-sm <?= $platform === $key ? 'btn-primary' : 'btn-outline' ?>"
                   style="border-radius: 20px; font-size: 0.8rem;">
                    <?= $label ?>
                    <?php if ($count > 0): ?>
                        <span class="badge badge-gold" style="font-size:0.65rem; margin-right:4px; padding:2px 6px;"><?= $count ?></span>
                    <?php endif; ?>
                </a>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<!-- جدول الاشتراكات -->
<div class="card">
    <?php if (empty($subscriptions)): ?>
        <div class="card-body">
            <div class="empty-state">
                <i class="fas fa-landmark"></i>
                <h3>لا توجد اشتراكات حكومية</h3>
                <p>ابدأ بإضافة اشتراكاتك في المنصات الحكومية لمتابعتها</p>
                <a href="<?= url('gov_subscriptions', 'create') ?>" class="btn btn-primary mt-2">
                    <i class="fas fa-plus"></i> اشتراك جديد
                </a>
            </div>
        </div>
    <?php else: ?>
        <div class="table-wrapper">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>المنصة</th>
                        <th>الشركة</th>
                        <th>رقم الاشتراك</th>
                        <th>تاريخ البداية</th>
                        <th>تاريخ الانتهاء</th>
                        <th>الأيام المتبقية</th>
                        <th>الحالة</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($subscriptions as $sub): ?>
                        <?php
                        $companyDisplay = $sub['linked_company_name'] ?? $sub['company_name'];
                        $daysLeft = null;
                        $status = 'unknown';
                        $statusLabel = '-';
                        $statusClass = 'secondary';

                        if ($sub['end_date']) {
                            $endTime = strtotime($sub['end_date']);
                            $now = time();
                            $daysLeft = (int)ceil(($endTime - $now) / 86400);

                            if ($daysLeft < 0) {
                                $status = 'expired';
                                $statusLabel = 'منتهية';
                                $statusClass = 'danger';
                            } elseif ($daysLeft <= 30) {
                                $status = 'expiring';
                                $statusLabel = 'قاربت الانتهاء';
                                $statusClass = 'warning';
                            } else {
                                $status = 'active';
                                $statusLabel = 'سارية';
                                $statusClass = 'success';
                            }
                        }
                        ?>
                        <tr>
                            <td>
                                <strong><?= GovSubscription::platformLabel($sub['platform']) ?></strong>
                            </td>
                            <td><?= clean($companyDisplay ?: '-') ?></td>
                            <td>
                                <span dir="ltr" style="direction:ltr;unicode-bidi:embed;display:inline-block;">
                                    <?= clean($sub['subscription_number'] ?: '-') ?>
                                </span>
                            </td>
                            <td><?= $sub['start_date'] ? formatDate($sub['start_date']) : '-' ?></td>
                            <td><?= $sub['end_date'] ? formatDate($sub['end_date']) : '-' ?></td>
                            <td>
                                <?php if ($daysLeft !== null): ?>
                                    <span class="text-<?= $statusClass ?>" style="font-weight:700;">
                                        <?= $daysLeft < 0 ? abs($daysLeft) . ' يوم متأخر' : $daysLeft . ' يوم' ?>
                                    </span>
                                <?php else: ?>
                                    -
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-<?= $statusClass ?>"><?= $statusLabel ?></span>
                            </td>
                            <td>
                                <div class="table-actions">
                                    <a href="<?= url('gov_subscriptions', 'edit', ['id' => $sub['id']]) ?>" class="btn btn-ghost btn-icon btn-sm" title="تعديل">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <a href="<?= url('gov_subscriptions', 'delete', ['id' => $sub['id']]) ?>" class="btn btn-ghost btn-icon btn-sm text-danger" onclick="return confirm('هل تريد حذف هذا الاشتراك؟')" title="حذف">
                                        <i class="fas fa-trash"></i>
                                    </a>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
