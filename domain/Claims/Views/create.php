<?php
$servicesJson = json_encode(array_map(fn($s) => ['id'=>$s['id'],'name'=>$s['name'],'price'=>(float)$s['default_price']], $services ?? []), JSON_UNESCAPED_UNICODE);
$initIdx = ($quotation && !empty($quotation['items'])) ? count($quotation['items']) : 1;
?>
<script>window.KN_SERVICES = <?= $servicesJson ?>;</script>

<div class="page-header"><div><h1 class="page-title">إنشاء مطالبة مالية</h1></div><div class="page-actions"><a href="<?= url('claims') ?>" class="btn btn-outline"><i class="fas fa-arrow-right"></i> رجوع</a></div></div>
<div class="card"><div class="card-body">
<form method="POST" action="<?= url('claims','store') ?>"><?= csrfField() ?>
    <?php if ($quotation): ?><input type="hidden" name="quotation_id" value="<?= $quotation['id'] ?>">
    <div class="alert alert-info"><i class="fas fa-link"></i> مرتبطة بعرض السعر رقم <strong><?= clean($quotation['quotation_number']) ?></strong></div>
    <?php endif; ?>
    <div class="form-row">
        <div class="form-group"><label class="form-label">العميل <span class="required">*</span></label>
            <select name="client_id" class="form-control" required onchange="loadCompanies(this.value,'company_id')">
                <option value="">اختر العميل</option>
                <?php foreach($clients as $c): ?>
                <option value="<?= $c['id'] ?>" <?= ($quotation && $quotation['client_id']==$c['id'])?'selected':'' ?>><?= clean($c['name']) ?></option>
                <?php endforeach; ?>
            </select></div>
        <div class="form-group"><label class="form-label">الشركة / الشركات</label>
            <select name="company_ids[]" class="form-control" id="company_id" multiple style="min-height:80px;">
                <option value="" disabled>اختر شركة أو أكثر</option>
            </select>
            <small class="text-muted">يمكنك اختيار أكثر من شركة بالضغط على Ctrl</small></div>
    </div>
    <div class="form-row">
        <div class="form-group"><label class="form-label">تاريخ الاستحقاق</label><input type="date" name="due_date" class="form-control" value="<?= date('Y-m-d', strtotime('+30 days')) ?>"></div>
        <div class="form-group"><label class="form-label">نسبة المطالبة (%)</label><input type="number" name="claim_percentage" class="form-control" value="100" min="0" max="100" step="0.01"></div>
    </div>
    <h3 class="mt-3 mb-2" style="font-size:1rem;"><i class="fas fa-list text-gold"></i> البنود</h3>
    <div class="table-wrapper"><table class="data-table"><thead><tr><th style="width:35%;">الخدمة / الوصف</th><th style="width:12%;">الكمية</th><th style="width:18%;">السعر</th><th style="width:18%;">الإجمالي</th><th></th></tr></thead>
    <tbody id="items-tbody">
        <?php if ($quotation && !empty($quotation['items'])): ?>
            <?php foreach ($quotation['items'] as $i => $item): ?>
            <tr id="item-row-<?= $i ?>">
                <td>
                    <select name="items[<?= $i ?>][service_id]" class="form-control" style="margin-bottom:4px;" onchange="onServiceSelect(this,<?= $i ?>)">
                        <option value="">-- اختر الخدمة --</option>
                        <?php foreach($services as $s): ?><option value="<?= $s['id'] ?>" data-price="<?= $s['default_price'] ?>" <?= $item['service_id']==$s['id']?'selected':'' ?>><?= clean($s['name']) ?></option><?php endforeach; ?>
                    </select>
                    <input type="text" name="items[<?= $i ?>][description]" class="form-control" value="<?= clean($item['description']) ?>" required>
                </td>
                <td><input type="number" name="items[<?= $i ?>][quantity]" class="form-control" value="<?= $item['quantity'] ?>" min="1" onchange="calcItemTotal(<?= $i ?>)"></td>
                <td><input type="number" name="items[<?= $i ?>][unit_price]" class="form-control" value="<?= $item['unit_price'] ?>" step="0.01" onchange="calcItemTotal(<?= $i ?>)"></td>
                <td><input type="text" name="items[<?= $i ?>][total]" class="form-control" value="<?= $item['total'] ?>" readonly style="background:var(--bg-input);"></td>
                <td><button type="button" class="btn btn-ghost btn-icon btn-sm" onclick="removeItem(<?= $i ?>)"><i class="fas fa-trash text-danger"></i></button></td>
            </tr>
            <?php endforeach; ?>
        <?php else: ?>
            <tr id="item-row-0">
                <td>
                    <select name="items[0][service_id]" class="form-control" style="margin-bottom:4px;" onchange="onServiceSelect(this,0)">
                        <option value="">-- اختر الخدمة (اختياري) --</option>
                        <?php foreach($services as $s): ?><option value="<?= $s['id'] ?>" data-price="<?= $s['default_price'] ?>"><?= clean($s['name']) ?></option><?php endforeach; ?>
                    </select>
                    <input type="text" name="items[0][description]" class="form-control" placeholder="الوصف" required>
                </td>
                <td><input type="number" name="items[0][quantity]" class="form-control" value="1" min="1" onchange="calcItemTotal(0)"></td>
                <td><input type="number" name="items[0][unit_price]" class="form-control" value="0.00" step="0.01" onchange="calcItemTotal(0)"></td>
                <td><input type="text" name="items[0][total]" class="form-control" value="0.00" readonly style="background:var(--bg-input);"></td>
                <td><button type="button" class="btn btn-ghost btn-icon btn-sm" onclick="removeItem(0)"><i class="fas fa-trash text-danger"></i></button></td>
            </tr>
        <?php endif; ?>
    </tbody></table></div>
    <button type="button" class="btn btn-outline btn-sm mt-2" onclick="addClaimItem()"><i class="fas fa-plus"></i> إضافة بند</button>

    <div class="grid-2 mt-3"><div>
        <div class="form-group"><label class="form-label">ملاحظات</label><textarea name="notes" class="form-control" rows="3"></textarea></div>
    </div><div class="card" style="background:var(--bg-input);"><div class="card-body">
        <div class="quick-stat"><span>الفرعي</span><input type="number" name="subtotal" id="subtotal" class="form-control" value="<?= $quotation['subtotal']??0 ?>" readonly style="width:140px;text-align:left;direction:ltr;"></div>
        <div class="quick-stat"><span>الضريبة (%)</span><input type="number" name="vat_rate" id="vat_rate" class="form-control" value="<?= $quotation['vat_rate']??15 ?>" style="width:80px;" onchange="recalcSubtotal()"></div>
        <div class="quick-stat"><span>قيمة الضريبة</span><input type="number" name="vat_amount" id="vat_amount" class="form-control" value="<?= $quotation['vat_amount']??0 ?>" readonly style="width:140px;text-align:left;direction:ltr;"></div>

        <div class="quick-stat" style="border-top:2px solid var(--gold);padding-top:12px;"><span class="text-bold text-gold">الإجمالي</span><input type="number" name="total" id="total" class="form-control text-bold" value="<?= $quotation['total']??0 ?>" readonly style="width:140px;text-align:left;direction:ltr;color:var(--gold);"></div>
    </div></div></div>
    <div class="mt-3"><button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> حفظ المطالبة</button></div>
</form>
</div></div>

<script>
let claimItemIndex = <?= $initIdx ?>;

function onServiceSelect(sel, idx) {
    const opt = sel.options[sel.selectedIndex];
    if (!opt || !opt.value) return;
    const row = document.getElementById('item-row-' + idx);
    if (!row) return;
    const desc = row.querySelector('[name="items[' + idx + '][description]"]');
    const price = row.querySelector('[name="items[' + idx + '][unit_price]"]');
    if (desc && !desc.value) desc.value = opt.textContent.trim();
    if (price) { price.value = parseFloat(opt.dataset.price || 0).toFixed(2); calcItemTotal(idx); }
}

function addClaimItem() {
    const idx = claimItemIndex++;
    const tbody = document.getElementById('items-tbody');
    const opts = (window.KN_SERVICES || []).map(s =>
        `<option value="${s.id}" data-price="${s.price}">${s.name}</option>`
    ).join('');
    const row = document.createElement('tr');
    row.id = 'item-row-' + idx;
    row.innerHTML = `
        <td>
            <select name="items[${idx}][service_id]" class="form-control" style="margin-bottom:4px;" onchange="onServiceSelect(this,${idx})">
                <option value="">-- اختر الخدمة --</option>${opts}
            </select>
            <input type="text" name="items[${idx}][description]" class="form-control" required>
        </td>
        <td><input type="number" name="items[${idx}][quantity]" class="form-control" value="1" min="1" onchange="calcItemTotal(${idx})"></td>
        <td><input type="number" name="items[${idx}][unit_price]" class="form-control" value="0" step="0.01" onchange="calcItemTotal(${idx})"></td>
        <td><input type="text" name="items[${idx}][total]" class="form-control" value="0.00" readonly style="background:var(--bg-input);"></td>
        <td><button type="button" class="btn btn-ghost btn-icon btn-sm" onclick="removeItem(${idx})"><i class="fas fa-trash text-danger"></i></button></td>
    `;
    tbody.appendChild(row);
}
</script>

<?php if ($quotation && $quotation['client_id']): ?>
<script>
document.addEventListener('DOMContentLoaded', function() {
    const clientSelect = document.querySelector('[name="client_id"]');
    if (clientSelect && clientSelect.value) {
        const companySelect = document.getElementById('company_id');
        companySelect.innerHTML = '<option value="" disabled>جاري التحميل...</option>';
        fetch(BASE_URL + '/?module=companies&action=by_client&client_id=' + clientSelect.value, {
            headers: {'X-Requested-With': 'XMLHttpRequest'}
        })
        .then(r => r.json())
        .then(data => {
            companySelect.innerHTML = '';
            if (data.companies) {
                data.companies.forEach(c => {
                    const opt = document.createElement('option');
                    opt.value = c.id;
                    opt.textContent = c.name_ar;
                    <?php if (!empty($quotation['company_id'])): ?>
                    if (c.id == <?= (int)$quotation['company_id'] ?>) opt.selected = true;
                    <?php endif; ?>
                    companySelect.appendChild(opt);
                });
            }
        })
        .catch(() => { companySelect.innerHTML = '<option value="" disabled>اختر شركة أو أكثر</option>'; });
    }
});
</script>
<?php endif; ?>
