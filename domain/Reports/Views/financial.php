<div class="page-header"><div><h1 class="page-title">التقرير المالي <?= $year ?></h1></div><div class="page-actions">
    <form method="GET" action="<?= url('reports','financial') ?>" style="display:flex;gap:8px;"><input type="hidden" name="module" value="reports"><input type="hidden" name="action" value="financial"><select name="year" class="form-control" style="width:120px;"><?php for($y=date('Y');$y>=2020;$y--): ?><option value="<?= $y ?>" <?= $year==$y?'selected':'' ?>><?= $y ?></option><?php endfor; ?></select><button type="submit" class="btn btn-outline"><i class="fas fa-filter"></i></button></form></div></div>
<div class="stats-grid">
    <div class="stat-card"><div class="stat-icon" style="background:var(--primary-bg);color:var(--gold);"><i class="fas fa-file-invoice-dollar"></i></div><div class="stat-value"><?= formatMoney($data['total_invoiced']) ?></div><div class="stat-title">إجمالي الفوترة</div></div>
    <div class="stat-card"><div class="stat-icon" style="background:var(--success-bg);color:var(--success);"><i class="fas fa-money-bill-wave"></i></div><div class="stat-value text-success"><?= formatMoney($data['total_paid']) ?></div><div class="stat-title">إجمالي التحصيل</div></div>
    <div class="stat-card"><div class="stat-icon" style="background:var(--danger-bg);color:var(--danger);"><i class="fas fa-exclamation-triangle"></i></div><div class="stat-value text-danger"><?= formatMoney($data['total_outstanding']) ?></div><div class="stat-title">المبالغ المعلقة</div></div>
</div>
<div class="card mt-2"><div class="card-header"><h3>الإيرادات الشهرية</h3></div><div class="card-body"><canvas id="monthlyChart" height="300"></canvas></div></div>
<script>
<?php $months=['','يناير','فبراير','مارس','أبريل','مايو','يونيو','يوليو','أغسطس','سبتمبر','أكتوبر','نوفمبر','ديسمبر'];$labels=[];$vals=array_fill(1,12,0);foreach($data['monthly'] as $m)$vals[$m['month']]=$m['total'];for($i=1;$i<=12;$i++){$labels[]=$months[$i];} ?>
new Chart(document.getElementById('monthlyChart'),{type:'bar',data:{labels:<?= json_encode($labels) ?>,datasets:[{label:'الإيرادات',data:<?= json_encode(array_values($vals)) ?>,backgroundColor:'rgba(212,175,55,0.3)',borderColor:'#d4af37',borderWidth:2}]},options:{responsive:true,scales:{y:{beginAtZero:true}}}});
</script>
