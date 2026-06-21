<div class="page-header"><div><h1 class="page-title">إضافة طلب خدمة</h1></div><div class="page-actions"><a href="<?= url('orders') ?>" class="btn btn-outline"><i class="fas fa-arrow-right"></i> رجوع</a></div></div>
<div class="card"><div class="card-body">
    <form method="POST" action="<?= url('orders', 'store') ?>"><?= csrfField() ?>
        <div class="form-row">
            <div class="form-group"><label class="form-label">العميل <span class="required">*</span></label>
                <select name="client_id" class="form-control" required id="client_id" onchange="loadCompanies(this.value, 'company_id')">
                    <option value="">اختر العميل</option>
                    <?php foreach ($clients as $c): ?><option value="<?= $c['id'] ?>" <?= $clientId==$c['id']?'selected':'' ?>><?= clean($c['name']) ?></option><?php endforeach; ?>
                </select></div>
            <div class="form-group"><label class="form-label">الشركة</label>
                <select name="company_id" class="form-control" id="company_id">
                    <option value="">اختر الشركة</option>
                    <?php foreach ($companies as $co): ?><option value="<?= $co['id'] ?>" <?= $companyId==$co['id']?'selected':'' ?>><?= clean($co['name_ar']) ?></option><?php endforeach; ?>
                </select></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">الخدمة <span class="required">*</span></label>
                <select name="service_id" class="form-control" required id="service_id" onchange="onServiceChange(this)">
                    <option value="">اختر الخدمة</option>
                    <?php $currentCat = ''; foreach ($services as $s):
                        if ($s['category_name'] !== $currentCat) { if ($currentCat) echo '</optgroup>'; echo '<optgroup label="'.clean($s['category_name']).'">'; $currentCat = $s['category_name']; }
                    ?><option value="<?= $s['id'] ?>" data-price="<?= $s['default_price'] ?>" data-cost="<?= $s['default_cost'] ?>" data-days="<?= $s['execution_days'] ?>"><?= clean($s['name']) ?></option>
                    <?php endforeach; if ($currentCat) echo '</optgroup>'; ?>
                </select></div>
            <div class="form-group"><label class="form-label">الموظف المسؤول</label>
                <select name="assigned_to" class="form-control"><option value="">اختر</option>
                    <?php foreach ($employees as $e): ?><option value="<?= $e['id'] ?>"><?= clean($e['full_name']) ?></option><?php endforeach; ?>
                </select></div>
        </div>
        <div class="form-group"><label class="form-label">الوصف</label><textarea name="description" class="form-control" rows="2" id="description"></textarea></div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">السعر</label><input type="number" name="price" class="form-control" id="price" value="0" step="0.01"></div>
            <div class="form-group"><label class="form-label">التكلفة</label><input type="number" name="cost" class="form-control" id="cost" value="0" step="0.01"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">تاريخ البدء</label><input type="date" name="start_date" class="form-control" value="<?= date('Y-m-d') ?>"></div>
            <div class="form-group"><label class="form-label">تاريخ الاستحقاق</label><input type="date" name="due_date" class="form-control" id="due_date" value="<?= date('Y-m-d', strtotime('+3 days')) ?>"></div>
        </div>
        <div class="form-group"><label class="form-label">ملاحظات</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> إنشاء الطلب</button>
    </form>
</div></div>
<script>
function onServiceChange(el) {
    const opt = el.options[el.selectedIndex];
    if (opt.dataset.price) document.getElementById('price').value = opt.dataset.price;
    if (opt.dataset.cost) document.getElementById('cost').value = opt.dataset.cost;
    if (opt.dataset.days) {
        const d = new Date(); d.setDate(d.getDate() + parseInt(opt.dataset.days));
        document.getElementById('due_date').value = d.toISOString().split('T')[0];
    }
}
</script>
