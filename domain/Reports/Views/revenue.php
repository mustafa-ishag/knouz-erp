<div class="page-header"><div><h1 class="page-title">تقرير الإيرادات</h1></div><div class="page-actions">
    <form method="GET" action="<?= url('reports','revenue') ?>" style="display:flex;gap:8px;"><input type="hidden" name="module" value="reports"><input type="hidden" name="action" value="revenue">
        <input type="date" name="from" value="<?= $from ?>" class="form-control"><input type="date" name="to" value="<?= $to ?>" class="form-control"><button type="submit" class="btn btn-outline"><i class="fas fa-filter"></i></button>
    </form></div></div>
<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon" style="background:var(--success-bg);color:var(--success);"><i class="fas fa-money-bill-wave"></i></div><div class="stat-value text-success"><?= formatMoney($data['total']) ?></div><div class="stat-title">إجمالي الإيرادات</div></div>
</div>
<div class="grid-2 mt-2">
    <div class="card"><div class="card-header"><h3>الإيرادات اليومية</h3></div><div class="card-body"><canvas id="revenueChart" height="250"></canvas></div></div>
    <div class="card"><div class="card-header"><h3>حسب طريقة الدفع</h3></div><div class="card-body">
        <table class="data-table"><thead><tr><th>الطريقة</th><th>المبلغ</th></tr></thead><tbody>
        <?php $methods=['bank_transfer'=>'تحويل بنكي','cash'=>'نقدي','check'=>'شيك','online'=>'إلكتروني','other'=>'أخرى']; foreach($data['by_method'] as $m): ?><tr><td><?= $methods[$m['payment_method']]??$m['payment_method'] ?></td><td class="text-bold"><?= formatMoney($m['total']) ?></td></tr><?php endforeach; ?></tbody></table>
    </div></div>
</div>
<script>
new Chart(document.getElementById('revenueChart'),{type:'bar',data:{labels:<?= json_encode(array_column($data['revenue'],'date')) ?>,datasets:[{label:'الإيرادات',data:<?= json_encode(array_map('floatval',array_column($data['revenue'],'total'))) ?>,backgroundColor:'rgba(212,175,55,0.3)',borderColor:'#d4af37',borderWidth:2}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true}}}});
</script>
