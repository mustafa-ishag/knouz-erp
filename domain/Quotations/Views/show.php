<div class="page-header"><div><h1 class="page-title">عرض سعر <?= clean($quotation['quotation_number']) ?></h1><p class="page-subtitle"><?= statusBadge($quotation['status']) ?></p></div>
    <div class="page-actions">
        <?php if ($quotation['status'] === 'draft'): ?><a href="<?= url('quotations','approve',['id'=>$quotation['id']]) ?>" class="btn btn-success" onclick="return confirm('اعتماد عرض السعر؟')"><i class="fas fa-check"></i> اعتماد</a><?php endif; ?>
        <a href="<?= url('quotations','edit',['id'=>$quotation['id']]) ?>" class="btn btn-outline"><i class="fas fa-edit"></i> تعديل</a>
        <button onclick="printPage()" class="btn btn-outline"><i class="fas fa-print"></i> طباعة</button>
        <?php if ($quotation['status'] === 'approved'): ?><a href="<?= url('quotations','to_claim',['id'=>$quotation['id']]) ?>" class="btn btn-primary"><i class="fas fa-hand-holding-dollar"></i> تحويل لمطالبة</a><?php endif; ?>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <!-- هيدر العرض -->
        <div class="d-flex justify-between align-center mb-3" style="flex-wrap:wrap;gap:16px;">
            <div>
                <h2 style="font-size:1.75rem;font-weight:800;color:var(--gold);"><?= clean($settings['company_name_ar'] ?? 'كنوز الإنجاز') ?></h2>
                <p class="text-sm text-muted"><?= clean($settings['company_name_en'] ?? '') ?></p>
            </div>
            <div style="text-align:left;">
                <h3 style="font-size:1.25rem;font-weight:700;">عرض سعر</h3>
                <p class="text-sm"><?= clean($quotation['quotation_number']) ?></p>
            </div>
        </div>

        <div class="grid-2 mb-3">
            <div style="padding:16px;background:var(--bg-input);border-radius:8px;">
                <strong class="text-sm">بيانات العميل</strong>
                <p class="mt-1"><?= clean($quotation['client']['name'] ?? '') ?></p>
                <?php if ($quotation['company']): ?><p class="text-sm text-muted"><?= clean($quotation['company']['name_ar']) ?></p><?php endif; ?>
                <?php if ($quotation['client']['phone'] ?? null): ?><p class="text-sm text-muted"><?= formatPhone($quotation['client']['phone']) ?></p><?php endif; ?>
            </div>
            <div style="padding:16px;background:var(--bg-input);border-radius:8px;">
                <div class="quick-stat"><span class="text-sm">تاريخ العرض</span><span class="text-bold"><?= formatDate($quotation['quotation_date']) ?></span></div>
                <div class="quick-stat"><span class="text-sm">صالح حتى</span><span class="text-bold"><?= formatDate($quotation['validity_date']) ?></span></div>
                <div class="quick-stat"><span class="text-sm">الحالة</span><?= statusBadge($quotation['status']) ?></div>
            </div>
        </div>

        <!-- البنود -->
        <table class="data-table"><thead><tr><th>#</th><th>الوصف</th><th>الكمية</th><th>سعر الوحدة</th><th>الإجمالي</th></tr></thead>
        <tbody>
            <?php foreach ($quotation['items'] as $i => $item): ?><tr>
                <td><?= $i+1 ?></td><td><?= clean($item['description']) ?><?php if ($item['service_name']): ?><br><small class="text-muted"><?= clean($item['service_name']) ?></small><?php endif; ?></td>
                <td><?= $item['quantity'] ?></td><td><?= formatMoney($item['unit_price']) ?></td><td class="text-bold"><?= formatMoney($item['total']) ?></td>
            </tr><?php endforeach; ?>
        </tbody></table>

        <!-- الملخص المالي -->
        <div style="max-width:350px;margin-right:auto;margin-top:24px;">
            <div class="quick-stat"><span>المجموع الفرعي</span><span class="text-bold"><?= formatMoney($quotation['subtotal']) ?></span></div>
            <?php if ($quotation['discount'] > 0): ?><div class="quick-stat"><span>الخصم</span><span class="text-danger">- <?= formatMoney($quotation['discount']) ?></span></div><?php endif; ?>
            <div class="quick-stat"><span>ضريبة القيمة المضافة (<?= $quotation['vat_rate'] ?>%)</span><span><?= formatMoney($quotation['vat_amount']) ?></span></div>
            <div class="quick-stat" style="border-top:2px solid var(--gold);padding-top:12px;"><span class="text-bold text-gold" style="font-size:1.1rem;">الإجمالي</span><span class="text-bold text-gold" style="font-size:1.25rem;"><?= formatMoney($quotation['total']) ?></span></div>
        </div>

        <?php if ($quotation['payment_terms']): ?><div class="mt-3"><h4 class="text-sm text-bold mb-1">الشروط والأحكام</h4><p class="text-sm text-muted" style="white-space:pre-line;"><?= clean($quotation['payment_terms']) ?></p></div><?php endif; ?>
    </div>
</div>
