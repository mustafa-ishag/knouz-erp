<div class="page-header"><div><h1 class="page-title">تقرير الخدمات</h1></div><div class="page-actions"><a href="<?= url('reports') ?>" class="btn btn-outline"><i class="fas fa-arrow-right"></i></a></div></div>
<div class="grid-2">
    <div class="card"><div class="card-header"><h3>أداء الخدمات</h3></div><div class="card-body"><table class="data-table"><thead><tr><th>الخدمة</th><th>الطلبات</th><th>الإيرادات</th><th>الأرباح</th></tr></thead><tbody>
    <?php foreach($data['by_service'] as $s): ?><tr><td class="text-bold"><?= clean($s['name']??'-') ?></td><td><span class="badge badge-primary"><?= $s['count'] ?></span></td><td class="text-gold"><?= formatMoney($s['revenue']) ?></td><td class="text-success text-bold"><?= formatMoney($s['profit']) ?></td></tr><?php endforeach; ?></tbody></table></div></div>
    <div class="card"><div class="card-header"><h3>حالات الطلبات</h3></div><div class="card-body"><canvas id="statusChart" height="250"></canvas></div></div>
</div>
<script>
new Chart(document.getElementById('statusChart'),{type:'doughnut',data:{labels:<?= json_encode(array_column($data['by_status'],'status')) ?>,datasets:[{data:<?= json_encode(array_map('intval',array_column($data['by_status'],'count'))) ?>,backgroundColor:['#d4af37','#3b82f6','#f59e0b','#10b981','#ef4444','#8b5cf6']}]},options:{responsive:true}});
</script>
