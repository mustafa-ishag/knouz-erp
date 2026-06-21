<div class="page-header"><div><h1 class="page-title">تعديل الفرصة البيعية</h1></div><div class="page-actions"><a href="<?= url('opportunities') ?>" class="btn btn-outline"><i class="fas fa-arrow-right"></i></a></div></div>
<div class="card"><div class="card-body"><form method="POST" action="<?= url('opportunities','update') ?>"><?= csrfField() ?><input type="hidden" name="id" value="<?= $opp['id'] ?>">
    <div class="form-row">
        <div class="form-group"><label class="form-label">العنوان</label><input type="text" name="title" class="form-control" required value="<?= clean($opp['title']) ?>"></div>
        <div class="form-group"><label class="form-label">العميل</label><select name="client_id" class="form-control" required><option value="">اختر</option><?php foreach($clients as $c): ?><option value="<?= $c['id'] ?>" <?= $opp['client_id']==$c['id']?'selected':'' ?>><?= clean($c['name']) ?></option><?php endforeach; ?></select></div>
    </div>
    <div class="form-row">
        <div class="form-group"><label class="form-label">القيمة المتوقعة</label><input type="number" name="expected_amount" class="form-control" step="0.01" value="<?= $opp['expected_amount'] ?>"></div>
        <div class="form-group"><label class="form-label">الحالة</label><select name="status" class="form-control"><?php foreach(['new'=>'جديد','contacting'=>'تواصل','interested'=>'مهتم','negotiating'=>'تفاوض','quote_sent'=>'عرض مرسل','sold'=>'تم البيع','lost'=>'خسارة'] as $k=>$v): ?><option value="<?= $k ?>" <?= $opp['status']===$k?'selected':'' ?>><?= $v ?></option><?php endforeach; ?></select></div>
    </div>
    <div class="form-row">
        <div class="form-group"><label class="form-label">الاحتمالية %</label><input type="number" name="probability" class="form-control" value="<?= $opp['probability'] ?>" min="0" max="100"></div>
        <div class="form-group"><label class="form-label">المسؤول</label><select name="assigned_to" class="form-control"><option value="">اختر</option><?php foreach($employees as $e): ?><option value="<?= $e['id'] ?>" <?= $opp['assigned_to']==$e['id']?'selected':'' ?>><?= clean($e['full_name']) ?></option><?php endforeach; ?></select></div>
    </div>
    <div class="form-group"><label class="form-label">تاريخ الإغلاق المتوقع</label><input type="date" name="expected_close_date" class="form-control" value="<?= $opp['expected_close_date'] ?>"></div>
    <div class="form-group"><label class="form-label">الوصف</label><textarea name="description" class="form-control" rows="2"><?= clean($opp['description']??'') ?></textarea></div>
    <div class="form-group"><label class="form-label">ملاحظات</label><textarea name="notes" class="form-control" rows="2"><?= clean($opp['notes']??'') ?></textarea></div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
</form></div></div>
