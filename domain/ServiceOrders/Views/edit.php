<div class="page-header"><div><h1 class="page-title">تعديل الطلب <?= clean($order['order_number']) ?></h1></div><div class="page-actions"><a href="<?= url('orders', 'show', ['id'=>$order['id']]) ?>" class="btn btn-outline"><i class="fas fa-arrow-right"></i> رجوع</a></div></div>
<div class="card"><div class="card-body">
    <form method="POST" action="<?= url('orders', 'update') ?>"><?= csrfField() ?><input type="hidden" name="id" value="<?= $order['id'] ?>">
        <div class="form-row">
            <div class="form-group"><label class="form-label">العميل <span class="required">*</span></label>
                <select name="client_id" class="form-control" required onchange="loadCompanies(this.value,'company_id')">
                    <?php foreach ($clients as $c): ?><option value="<?= $c['id'] ?>" <?= $order['client_id']==$c['id']?'selected':'' ?>><?= clean($c['name']) ?></option><?php endforeach; ?>
                </select></div>
            <div class="form-group"><label class="form-label">الشركة</label>
                <select name="company_id" class="form-control" id="company_id"><option value="">اختر</option>
                    <?php foreach ($companies as $co): ?><option value="<?= $co['id'] ?>" <?= $order['company_id']==$co['id']?'selected':'' ?>><?= clean($co['name_ar']) ?></option><?php endforeach; ?>
                </select></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">الخدمة <span class="required">*</span></label>
                <select name="service_id" class="form-control" required>
                    <?php foreach ($services as $s): ?><option value="<?= $s['id'] ?>" <?= $order['service_id']==$s['id']?'selected':'' ?>><?= clean($s['name']) ?></option><?php endforeach; ?>
                </select></div>
            <div class="form-group"><label class="form-label">الحالة</label>
                <select name="status" class="form-control">
                    <?php foreach(['new'=>'جديد','in_progress'=>'قيد التنفيذ','pending'=>'معلق','completed'=>'مكتمل','cancelled'=>'ملغي'] as $k=>$v): ?>
                        <option value="<?= $k ?>" <?= $order['status']===$k?'selected':'' ?>><?= $v ?></option>
                    <?php endforeach; ?>
                </select></div>
        </div>
        <div class="form-group"><label class="form-label">الوصف</label><textarea name="description" class="form-control" rows="2"><?= clean($order['description']) ?></textarea></div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">السعر</label><input type="number" name="price" class="form-control" value="<?= $order['price'] ?>" step="0.01"></div>
            <div class="form-group"><label class="form-label">التكلفة</label><input type="number" name="cost" class="form-control" value="<?= $order['cost'] ?>" step="0.01"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">الموظف المسؤول</label>
                <select name="assigned_to" class="form-control"><option value="">اختر</option>
                    <?php foreach ($employees as $e): ?><option value="<?= $e['id'] ?>" <?= $order['assigned_to']==$e['id']?'selected':'' ?>><?= clean($e['full_name']) ?></option><?php endforeach; ?>
                </select></div>
            <div class="form-group"><label class="form-label">مرجع المنصة</label><input type="text" name="platform_ref" class="form-control" value="<?= clean($order['platform_ref']) ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">تاريخ البدء</label><input type="date" name="start_date" class="form-control" value="<?= $order['start_date'] ?>"></div>
            <div class="form-group"><label class="form-label">تاريخ الاستحقاق</label><input type="date" name="due_date" class="form-control" value="<?= $order['due_date'] ?>"></div>
        </div>
        <div class="form-group"><label class="form-label">ملاحظة التحديث</label><input type="text" name="status_notes" class="form-control" placeholder="سبب التغيير (اختياري)"></div>
        <div class="form-group"><label class="form-label">ملاحظات</label><textarea name="notes" class="form-control" rows="2"><?= clean($order['notes']) ?></textarea></div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
    </form>
</div></div>
