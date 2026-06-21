<div class="page-header"><div><h1 class="page-title">إنشاء فاتورة جديدة</h1></div><div class="page-actions"><a href="<?= url('invoices') ?>" class="btn btn-outline"><i class="fas fa-arrow-right"></i> رجوع</a></div></div>

<?php
// تمرير الخدمات كـ JSON للـ JavaScript
$servicesJson = json_encode(array_map(fn($s) => ['id'=>$s['id'],'name'=>$s['name'],'price'=>(float)$s['default_price']], $services ?? []), JSON_UNESCAPED_UNICODE);
?>
<script>window.KN_SERVICES = <?= $servicesJson ?>;</script>

<div class="card"><div class="card-body">
<form method="POST" action="<?= url('invoices','store') ?>"><?= csrfField() ?>
    <div class="form-row">
        <div class="form-group"><label class="form-label">العميل <span class="required">*</span></label>
            <select name="client_id" class="form-control" required onchange="loadCompanies(this.value,'company_id')">
                <option value="">اختر العميل</option>
                <?php foreach($clients as $c): ?>
                <option value="<?= $c['id'] ?>"><?= clean($c['name']) ?></option>
                <?php endforeach; ?>
            </select>
        </div>
        <div class="form-group"><label class="form-label">الشركة</label>
            <select name="company_id" class="form-control" id="company_id"><option value="">اختر الشركة</option></select>
        </div>
    </div>
    <div class="form-row">
        <div class="form-group"><label class="form-label">تاريخ الفاتورة</label><input type="date" name="invoice_date" class="form-control" value="<?= date('Y-m-d') ?>"></div>
        <div class="form-group"><label class="form-label">تاريخ الاستحقاق</label><input type="date" name="due_date" class="form-control" value="<?= date('Y-m-d', strtotime('+30 days')) ?>"></div>
    </div>

    <h3 class="mt-3 mb-2" style="font-size:1rem;"><i class="fas fa-list text-gold"></i> بنود الفاتورة</h3>
    <div class="table-wrapper">
    <table class="data-table" id="items-table">
        <thead><tr><th style="width:30%">الخدمة / الوصف</th><th style="width:12%">الكمية</th><th style="width:18%">السعر</th><th style="width:18%">الإجمالي</th><th style="width:8%"></th></tr></thead>
        <tbody id="items-tbody">
            <tr id="item-row-0">
                <td>
                    <select name="items[0][service_id]" class="form-control mb-1" onchange="onServiceSelect(this, 0)" style="margin-bottom:4px;">
                        <option value="">-- اختر الخدمة --</option>
                    </select>
                    <input type="text" name="items[0][description]" class="form-control" placeholder="أو أدخل وصفاً مباشرة" required>
                </td>
                <td><input type="number" name="items[0][quantity]" class="form-control" value="1" min="1" onchange="calcItemTotal(0)"></td>
                <td><input type="number" name="items[0][unit_price]" class="form-control" value="0.00" step="0.01" onchange="calcItemTotal(0)"></td>
                <td><input type="text" name="items[0][total]" class="form-control" value="0.00" readonly style="background:var(--bg-input);"></td>
                <td><button type="button" class="btn btn-ghost btn-icon btn-sm" onclick="removeItem(0)"><i class="fas fa-trash text-danger"></i></button></td>
            </tr>
        </tbody>
    </table>
    </div>
    <button type="button" class="btn btn-outline btn-sm mt-2" onclick="addInvoiceItem()"><i class="fas fa-plus"></i> إضافة بند</button>

    <div class="grid-2 mt-3">
        <div>
            <div class="form-group"><label class="form-label">ملاحظات</label><textarea name="notes" class="form-control" rows="3"></textarea></div>
        </div>
        <div class="card" style="background:var(--bg-input);">
            <div class="card-body">
                <div class="quick-stat"><span>المجموع الفرعي</span><input type="number" name="subtotal" id="subtotal" class="form-control" value="0.00" readonly style="width:140px;text-align:left;direction:ltr;"></div>
                <div class="quick-stat"><span>نسبة الضريبة %</span><input type="number" name="vat_rate" id="vat_rate" class="form-control" value="15" style="width:80px;" onchange="recalcSubtotal()"></div>
                <div class="quick-stat"><span>قيمة الضريبة</span><input type="number" name="vat_amount" id="vat_amount" class="form-control" value="0.00" readonly style="width:140px;text-align:left;direction:ltr;"></div>
                <div class="quick-stat"><span>الخصم</span><input type="number" name="discount" id="discount" class="form-control" value="0.00" step="0.01" style="width:140px;" onchange="recalcSubtotal()"></div>
                <div class="quick-stat" style="border-top:2px solid var(--gold);padding-top:12px;">
                    <span class="text-bold text-gold" style="font-size:1.1rem;">الإجمالي</span>
                    <input type="number" name="total" id="total" class="form-control text-bold" value="0.00" readonly style="width:140px;text-align:left;direction:ltr;color:var(--gold);font-size:1.1rem;">
                </div>
            </div>
        </div>
    </div>
    <div class="mt-3"><button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> حفظ الفاتورة</button></div>
</form>
</div></div>

<script>
let invoiceItemIndex = 0;

// تحميل الخدمات في select الأول عند تحميل الصفحة
document.addEventListener('DOMContentLoaded', function() {
    populateServiceSelect(document.querySelector('[name="items[0][service_id]"]'));
});

function populateServiceSelect(selectEl) {
    if (!selectEl || !window.KN_SERVICES) return;
    const current = selectEl.value;
    selectEl.innerHTML = '<option value="">-- اختر الخدمة --</option>';
    window.KN_SERVICES.forEach(s => {
        const opt = document.createElement('option');
        opt.value = s.id;
        opt.textContent = s.name;
        opt.dataset.price = s.price;
        selectEl.appendChild(opt);
    });
    if (current) selectEl.value = current;
}

function onServiceSelect(selectEl, idx) {
    const selected = selectEl.options[selectEl.selectedIndex];
    if (!selected || !selected.value) return;
    const row = document.getElementById('item-row-' + idx);
    if (!row) return;
    // ملء الوصف والسعر تلقائياً
    const descInput = row.querySelector('[name="items[' + idx + '][description]"]');
    const priceInput = row.querySelector('[name="items[' + idx + '][unit_price]"]');
    if (descInput && !descInput.value) descInput.value = selected.textContent.trim();
    if (priceInput) { priceInput.value = parseFloat(selected.dataset.price || 0).toFixed(2); calcItemTotal(idx); }
}

function addInvoiceItem() {
    invoiceItemIndex++;
    const idx = invoiceItemIndex;
    const tbody = document.getElementById('items-tbody');
    if (!tbody) return;
    const row = document.createElement('tr');
    row.id = 'item-row-' + idx;
    row.innerHTML = `
        <td>
            <select name="items[${idx}][service_id]" class="form-control mb-1" onchange="onServiceSelect(this, ${idx})" style="margin-bottom:4px;"></select>
            <input type="text" name="items[${idx}][description]" class="form-control" placeholder="أو أدخل وصفاً مباشرة" required>
        </td>
        <td><input type="number" name="items[${idx}][quantity]" class="form-control" value="1" min="1" onchange="calcItemTotal(${idx})"></td>
        <td><input type="number" name="items[${idx}][unit_price]" class="form-control" value="0.00" step="0.01" onchange="calcItemTotal(${idx})"></td>
        <td><input type="text" name="items[${idx}][total]" class="form-control" value="0.00" readonly style="background:var(--bg-input);"></td>
        <td><button type="button" class="btn btn-ghost btn-icon btn-sm" onclick="removeItem(${idx})"><i class="fas fa-trash text-danger"></i></button></td>
    `;
    tbody.appendChild(row);
    populateServiceSelect(row.querySelector('select'));
}
</script>
