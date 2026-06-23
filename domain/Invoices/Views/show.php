<?php /* عرض الفاتورة - مطابق لعرض المطالبة */ ?>
<div class="page-header"><div><h1 class="page-title">فاتورة <?= clean($invoice['invoice_number']) ?></h1><p class="page-subtitle"><?= statusBadge($invoice['status']) ?></p></div>
    <div class="page-actions"><a href="<?= url('invoices','edit',['id'=>$invoice['id']]) ?>" class="btn btn-outline"><i class="fas fa-edit"></i> تعديل</a><button onclick="printPage()" class="btn btn-outline"><i class="fas fa-print"></i> طباعة</button><button onclick="exportPDF()" class="btn btn-outline" style="background:#c0392b;color:#fff;border-color:#c0392b;"><i class="fas fa-file-pdf"></i> تصدير PDF</button>
    <a href="<?= url('payments','create',['invoice_id'=>$invoice['id']]) ?>" class="btn btn-success"><i class="fas fa-money-bill-wave"></i> تسجيل دفعة</a></div></div>
<div class="card"><div class="card-body">
    <div class="d-flex justify-between mb-3" style="flex-wrap:wrap;gap:16px;"><div><h2 style="font-size:1.75rem;font-weight:800;color:var(--gold);"><?= clean($settings['company_name_ar']??'كنوز الإنجاز') ?></h2></div><div style="text-align:left;"><h3 style="font-size:1.25rem;font-weight:700;">فاتورة ضريبية</h3><p class="text-sm"><?= clean($invoice['invoice_number']) ?></p></div></div>
    <div class="grid-2 mb-3">
        <div style="padding:16px;background:var(--bg-input);border-radius:8px;"><strong class="text-sm">العميل</strong><p class="mt-1"><?= clean($invoice['client']['name']??'') ?></p><?php if($invoice['company']): ?><p class="text-sm text-muted"><?= clean($invoice['company']['name_ar']) ?></p><?php endif; ?></div>
        <div style="padding:16px;background:var(--bg-input);border-radius:8px;">
            <div class="quick-stat"><span class="text-sm">التاريخ</span><span class="text-bold"><?= formatDate($invoice['invoice_date']) ?></span></div>
            <div class="quick-stat"><span class="text-sm">الاستحقاق</span><span class="text-bold"><?= formatDate($invoice['due_date']) ?></span></div>
        </div>
    </div>
    <table class="data-table"><thead><tr><th>#</th><th>الوصف</th><th>الكمية</th><th>السعر</th><th>الإجمالي</th></tr></thead>
    <tbody><?php foreach($invoice['items'] as $i=>$item): ?><tr><td><?= $i+1 ?></td><td><?= clean($item['description']) ?></td><td><?= $item['quantity'] ?></td><td><?= formatMoney($item['unit_price']) ?></td><td class="text-bold"><?= formatMoney($item['total']) ?></td></tr><?php endforeach; ?></tbody></table>
    <div style="max-width:350px;margin-right:auto;margin-top:24px;">
        <div class="quick-stat"><span>الفرعي</span><span class="text-bold"><?= formatMoney($invoice['subtotal']) ?></span></div>
        <?php if($invoice['discount']>0): ?><div class="quick-stat"><span>الخصم</span><span class="text-danger">- <?= formatMoney($invoice['discount']) ?></span></div><?php endif; ?>
        <div class="quick-stat"><span>الضريبة (<?= $invoice['vat_rate'] ?>%)</span><span><?= formatMoney($invoice['vat_amount']) ?></span></div>
        <div class="quick-stat" style="border-top:2px solid var(--gold);padding-top:12px;"><span class="text-bold text-gold" style="font-size:1.1rem;">الإجمالي</span><span class="text-bold text-gold" style="font-size:1.25rem;"><?= formatMoney($invoice['total']) ?></span></div>
        <div class="quick-stat"><span class="text-success">المدفوع</span><span class="text-success text-bold"><?= formatMoney($invoice['paid_amount']) ?></span></div>
        <div class="quick-stat"><span class="text-danger">المتبقي</span><span class="text-danger text-bold"><?= formatMoney($invoice['total']-$invoice['paid_amount']) ?></span></div>
    </div>
</div></div>
<?php if(!empty($invoice['payments'])): ?><div class="card mt-2"><div class="card-header"><h3><i class="fas fa-money-bill-wave text-gold mr-1"></i> المدفوعات</h3></div><div class="card-body"><table class="data-table"><thead><tr><th>الرقم</th><th>التاريخ</th><th>المبلغ</th><th>الطريقة</th></tr></thead><tbody><?php foreach($invoice['payments'] as $p): ?><tr><td><?= clean($p['payment_number']) ?></td><td><?= formatDate($p['payment_date']) ?></td><td class="text-success text-bold"><?= formatMoney($p['amount']) ?></td><td><?= clean($p['payment_method']) ?></td></tr><?php endforeach; ?></tbody></table></div></div><?php endif; ?>
