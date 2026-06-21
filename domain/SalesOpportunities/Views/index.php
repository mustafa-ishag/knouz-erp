<div class="page-header"><div><h1 class="page-title">الفرص البيعية</h1><p class="page-subtitle"><?= number_format($result['total']) ?> فرصة</p></div><div class="page-actions"><a href="<?= url('opportunities','create') ?>" class="btn btn-primary"><i class="fas fa-plus"></i> إضافة فرصة</a></div></div>
<div class="card"><div class="table-wrapper"><table class="data-table"><thead><tr><th>العنوان</th><th>العميل</th><th>القيمة المتوقعة</th><th>المرحلة</th><th>الاحتمالية</th><th>تاريخ الإغلاق</th><th></th></tr></thead>
<tbody><?php if(empty($result['data'])): ?><tr><td colspan="7"><div class="empty-state"><i class="fas fa-chart-line"></i><h3>لا توجد فرص بيعية</h3></div></td></tr>
<?php else: foreach($result['data'] as $o): ?>
    <?php $stages=['new'=>'جديد','contacting'=>'تواصل','interested'=>'مهتم','negotiating'=>'تفاوض','quote_sent'=>'عرض مرسل','sold'=>'تم البيع','lost'=>'خسارة']; ?>
    <tr><td class="text-bold"><?= clean($o['title']) ?></td><td><?= clean($o['client_name']??'-') ?></td><td class="text-gold text-bold"><?= formatMoney($o['expected_amount']) ?></td>
    <td><?= statusBadge($o['status']) ?></td><td><div class="progress" style="width:80px;"><div class="progress-bar" style="width:<?= $o['probability'] ?>%;"></div></div><span class="text-xs"><?= $o['probability'] ?>%</span></td>
    <td class="text-sm"><?= formatDate($o['expected_close_date']) ?></td>
    <td><div class="table-actions"><a href="<?= url('opportunities','edit',['id'=>$o['id']]) ?>" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-edit"></i></a><button onclick="confirmDelete('<?= url('opportunities','delete',['id'=>$o['id']]) ?>')" class="btn btn-ghost btn-icon btn-sm"><i class="fas fa-trash text-danger"></i></button></div></td></tr>
<?php endforeach;endif; ?></tbody></table></div></div>
