<!-- لوحة التحكم الرئيسية -->
<div class="page-header">
    <div>
        <h1 class="page-title">لوحة التحكم</h1>
        <p class="page-subtitle">مرحباً <?= clean($this->currentUser()['full_name']) ?>، نظرة عامة على أعمال كنوز الإنجاز</p>
    </div>
    <div class="page-actions">
        <a href="<?= url('clients', 'create') ?>" class="btn btn-primary">
            <i class="fas fa-plus"></i>
            عميل جديد
        </a>
        <a href="<?= url('orders', 'create') ?>" class="btn btn-outline">
            <i class="fas fa-plus"></i>
            طلب جديد
        </a>
    </div>
</div>

<!-- بطاقات الإحصائيات -->
<div class="stat-cards">
    <div class="stat-card">
        <div class="stat-card-icon gold"><i class="fas fa-users"></i></div>
        <div class="stat-card-info">
            <div class="stat-card-value"><?= number_format($stats['total_clients']) ?></div>
            <div class="stat-card-label">إجمالي العملاء</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon blue"><i class="fas fa-building"></i></div>
        <div class="stat-card-info">
            <div class="stat-card-value"><?= number_format($stats['total_companies']) ?></div>
            <div class="stat-card-label">إجمالي الشركات</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon orange"><i class="fas fa-clipboard-list"></i></div>
        <div class="stat-card-info">
            <div class="stat-card-value"><?= number_format($stats['total_orders']) ?></div>
            <div class="stat-card-label">إجمالي الطلبات</div>
            <div class="stat-card-trend"><i class="fas fa-spinner"></i> <?= number_format($stats['open_orders']) ?> مفتوحة</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon green"><i class="fas fa-check-circle"></i></div>
        <div class="stat-card-info">
            <div class="stat-card-value"><?= number_format($stats['completed_orders']) ?></div>
            <div class="stat-card-label">طلبات منجزة</div>
            <div class="stat-card-trend up"><?= formatMoney($stats['completed_orders_value'], false) ?> ر.س</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon" style="background:rgba(23,108,180,0.1);color:#176cb4;"><i class="fas fa-file-invoice-dollar"></i></div>
        <div class="stat-card-info">
            <div class="stat-card-value"><?= formatMoney($stats['open_orders_value'], false) ?></div>
            <div class="stat-card-label">قيمة الطلبات المفتوحة</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon green"><i class="fas fa-coins"></i></div>
        <div class="stat-card-info">
            <div class="stat-card-value"><?= formatMoney($stats['total_revenue'], false) ?></div>
            <div class="stat-card-label">إجمالي الإيرادات</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon blue"><i class="fas fa-calendar-check"></i></div>
        <div class="stat-card-info">
            <div class="stat-card-value"><?= formatMoney($stats['monthly_revenue'], false) ?></div>
            <div class="stat-card-label">إيرادات الشهر</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon red"><i class="fas fa-file-invoice-dollar"></i></div>
        <div class="stat-card-info">
            <div class="stat-card-value"><?= number_format($stats['unpaid_invoices']) ?></div>
            <div class="stat-card-label">فواتير غير مسددة</div>
            <div class="stat-card-trend down"><?= formatMoney($stats['unpaid_invoices_amount'], false) ?> ر.س</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-card-icon orange"><i class="fas fa-hand-holding-dollar"></i></div>
        <div class="stat-card-info">
            <div class="stat-card-value"><?= number_format($stats['due_claims']) ?></div>
            <div class="stat-card-label">مطالبات مستحقة</div>
            <div class="stat-card-trend down"><?= formatMoney($stats['due_claims_amount'], false) ?> ر.س</div>
        </div>
    </div>
</div>

<!-- الصف الثاني -->
<div class="dashboard-grid">
    <!-- رسم بياني - الإيرادات الشهرية -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-chart-area text-gold mr-1"></i> الإيرادات الشهرية</h3>
        </div>
        <div class="card-body">
            <div class="chart-container" style="height: 300px;">
                <canvas id="revenueChart"></canvas>
            </div>
        </div>
    </div>
    
    <!-- أكثر الخدمات طلباً -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-chart-pie text-gold mr-1"></i> أكثر الخدمات طلباً</h3>
        </div>
        <div class="card-body">
            <?php if (empty($topServices)): ?>
                <div class="empty-state">
                    <i class="fas fa-chart-pie"></i>
                    <p>لا توجد بيانات بعد</p>
                </div>
            <?php else: ?>
                <div class="chart-container" style="height: 300px;">
                    <canvas id="servicesChart"></canvas>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- تنبيهات -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-exclamation-triangle text-warning mr-1"></i> التنبيهات</h3>
        </div>
        <div class="card-body">
            <?php if (empty($expiringCR) && empty($overdueClaims)): ?>
                <div class="empty-state">
                    <i class="fas fa-check-circle" style="color: var(--success);"></i>
                    <h3>لا توجد تنبيهات</h3>
                    <p>كل شيء على ما يرام!</p>
                </div>
            <?php else: ?>
                <?php foreach ($expiringCR as $cr): ?>
                    <div class="alert-card warning">
                        <div class="alert-card-icon" style="background: var(--warning-bg); color: var(--warning);">
                            <i class="fas fa-clock"></i>
                        </div>
                        <div class="alert-card-content">
                            <div class="title" style="color: var(--warning);">سجل تجاري ينتهي قريباً</div>
                            <div class="desc"><?= clean($cr['name_ar']) ?> - <?= clean($cr['client_name']) ?> (<?= formatDate($cr['cr_expiry_date']) ?>)</div>
                        </div>
                    </div>
                <?php endforeach; ?>
                
                <?php foreach ($overdueClaims as $claim): ?>
                    <div class="alert-card danger">
                        <div class="alert-card-icon" style="background: var(--danger-bg); color: var(--danger);">
                            <i class="fas fa-exclamation-circle"></i>
                        </div>
                        <div class="alert-card-content">
                            <div class="title" style="color: var(--danger);">مطالبة متأخرة</div>
                            <div class="desc"><?= clean($claim['claim_number']) ?> - <?= clean($claim['client_name']) ?> (<?= formatMoney($claim['total']) ?>)</div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- آخر الأنشطة -->
    <div class="card">
        <div class="card-header">
            <h3><i class="fas fa-history text-gold mr-1"></i> آخر الأنشطة</h3>
        </div>
        <div class="card-body">
            <?php if (empty($recentActivities)): ?>
                <div class="empty-state">
                    <i class="fas fa-clock"></i>
                    <p>لا توجد أنشطة بعد</p>
                </div>
            <?php else: ?>
                <?php foreach ($recentActivities as $activity): ?>
                    <div class="activity-item">
                        <div class="activity-icon" style="background: var(--primary-bg); color: var(--gold);">
                            <i class="fas fa-<?= $activity['action'] === 'login' ? 'sign-in-alt' : ($activity['action'] === 'create' ? 'plus' : ($activity['action'] === 'update' ? 'edit' : 'trash')) ?>"></i>
                        </div>
                        <div class="activity-content">
                            <div class="title"><?= clean($activity['user_name'] ?? 'النظام') ?></div>
                            <div class="desc"><?= clean($activity['details'] ?? $activity['action'] . ' - ' . $activity['module']) ?></div>
                            <div class="time"><?= timeAgo($activity['created_at']) ?></div>
                        </div>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
    </div>
    
    <!-- أفضل العملاء -->
    <div class="card full-width">
        <div class="card-header">
            <h3><i class="fas fa-trophy text-gold mr-1"></i> أفضل العملاء</h3>
        </div>
        <div class="card-body">
            <?php if (empty($topClients)): ?>
                <div class="empty-state">
                    <i class="fas fa-trophy"></i>
                    <p>لا توجد بيانات بعد</p>
                </div>
            <?php else: ?>
                <div class="table-wrapper">
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>العميل</th>
                                <th>إجمالي المدفوعات</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($topClients as $i => $client): ?>
                                <tr>
                                    <td>
                                        <span class="badge badge-<?= $i === 0 ? 'warning' : ($i === 1 ? 'secondary' : 'primary') ?>">
                                            <?= $i + 1 ?>
                                        </span>
                                    </td>
                                    <td><strong><?= clean($client['name']) ?></strong></td>
                                    <td class="text-bold text-gold"><?= formatMoney($client['total_paid']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- الرسوم البيانية -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    // رسم بياني الإيرادات
    const revenueData = <?= json_encode($monthlyRevenue) ?>;
    const revenueCtx = document.getElementById('revenueChart');
    
    if (revenueCtx) {
        const months = {
            '01': 'يناير', '02': 'فبراير', '03': 'مارس', '04': 'أبريل',
            '05': 'مايو', '06': 'يونيو', '07': 'يوليو', '08': 'أغسطس',
            '09': 'سبتمبر', '10': 'أكتوبر', '11': 'نوفمبر', '12': 'ديسمبر'
        };
        
        new Chart(revenueCtx, {
            type: 'line',
            data: {
                labels: revenueData.map(r => months[r.month.split('-')[1]] || r.month),
                datasets: [{
                    label: 'الإيرادات',
                    data: revenueData.map(r => parseFloat(r.total)),
                    borderColor: '#D4A853',
                    backgroundColor: 'rgba(212, 168, 83, 0.1)',
                    borderWidth: 3,
                    fill: true,
                    tension: 0.4,
                    pointBackgroundColor: '#D4A853',
                    pointBorderColor: '#fff',
                    pointBorderWidth: 2,
                    pointRadius: 5,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: { display: false },
                    tooltip: {
                        rtl: true,
                        textDirection: 'rtl',
                        callbacks: {
                            label: ctx => ctx.parsed.y.toLocaleString('ar-SA') + ' ر.س'
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        grid: { color: 'rgba(0,0,0,0.04)' },
                        ticks: {
                            callback: v => v.toLocaleString('ar-SA'),
                            font: { family: 'Tajawal' }
                        }
                    },
                    x: {
                        grid: { display: false },
                        ticks: { font: { family: 'Tajawal' } }
                    }
                }
            }
        });
    }
    
    // رسم بياني الخدمات
    const servicesData = <?= json_encode($topServices) ?>;
    const servicesCtx = document.getElementById('servicesChart');
    
    if (servicesCtx && servicesData.length > 0) {
        const colors = ['#D4A853', '#3498DB', '#27AE60', '#E74C3C', '#9B59B6'];
        
        new Chart(servicesCtx, {
            type: 'doughnut',
            data: {
                labels: servicesData.map(s => s.name),
                datasets: [{
                    data: servicesData.map(s => s.count),
                    backgroundColor: colors,
                    borderWidth: 0,
                    hoverOffset: 8,
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom',
                        rtl: true,
                        labels: {
                            font: { family: 'Tajawal', size: 12 },
                            padding: 15,
                            usePointStyle: true,
                        }
                    }
                },
                cutout: '65%',
            }
        });
    }
});
</script>
