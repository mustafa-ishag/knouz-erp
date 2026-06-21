<div class="page-header"><div><h1 class="page-title">إضافة فرصة بيعية</h1></div><div class="page-actions"><a href="<?= url('opportunities') ?>" class="btn btn-outline"><i class="fas fa-arrow-right"></i></a></div></div>
<div class="card"><div class="card-body"><form method="POST" action="<?= url('opportunities','store') ?>"><?= csrfField() ?>
    <div class="form-row">
        <div class="form-group"><label class="form-label">العنوان <span class="required">*</span></label><input type="text" name="title" class="form-control" required placeholder="مثال: تأسيس شركة جديدة"></div>
        <div class="form-group"><label class="form-label">العميل <span class="required">*</span></label><select name="client_id" class="form-control" required><option value="">اختر</option><?php foreach($clients as $c): ?><option value="<?= $c['id'] ?>"><?= clean($c['name']) ?></option><?php endforeach; ?></select></div>
    </div>
    <div class="form-row">
        <div class="form-group"><label class="form-label">القيمة المتوقعة</label><input type="number" name="expected_amount" class="form-control" step="0.01" value="0" min="0"></div>
        <div class="form-group"><label class="form-label">الحالة</label><select name="status" class="form-control"><?php foreach(['new'=>'جديد','contacting'=>'تواصل','interested'=>'مهتم','negotiating'=>'تفاوض','quote_sent'=>'عرض مرسل','sold'=>'تم البيع','lost'=>'خسارة'] as $k=>$v): ?><option value="<?= $k ?>"><?= $v ?></option><?php endforeach; ?></select></div>
    </div>
    <div class="form-row">
        <div class="form-group"><label class="form-label">الاحتمالية %</label><input type="number" name="probability" class="form-control" value="50" min="0" max="100"></div>
        <div class="form-group"><label class="form-label">المسؤول</label><select name="assigned_to" class="form-control"><option value="">اختر</option><?php foreach($employees as $e): ?><option value="<?= $e['id'] ?>"><?= clean($e['full_name']) ?></option><?php endforeach; ?></select></div>
    </div>
    <div class="form-group"><label class="form-label">تاريخ الإغلاق المتوقع</label><input type="date" name="expected_close_date" class="form-control"></div>
    <div class="form-group"><label class="form-label">الوصف</label><textarea name="description" class="form-control" rows="2"></textarea></div>
    <div class="form-group"><label class="form-label">ملاحظات</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
</form></div></div>
