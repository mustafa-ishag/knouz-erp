<div class="page-header"><div><h1 class="page-title">تسجيل مكالمة</h1></div><div class="page-actions"><a href="<?= url('communications','calls') ?>" class="btn btn-outline"><i class="fas fa-arrow-right"></i> رجوع</a></div></div>
<div class="card"><div class="card-body"><form method="POST" action="<?= url('communications','store_call') ?>"><?= csrfField() ?>
    <div class="form-row">
        <div class="form-group"><label class="form-label">العميل <span class="required">*</span></label><select name="client_id" class="form-control" required><option value="">اختر العميل</option><?php foreach($clients as $c): ?><option value="<?= $c['id'] ?>" <?= $clientId==$c['id']?'selected':'' ?>><?= clean($c['name']) ?> <?= $c['phone']?'('.$c['phone'].')':'' ?></option><?php endforeach; ?></select></div>
        <div class="form-group"><label class="form-label">نوع المكالمة</label><select name="call_type" class="form-control"><option value="outgoing">صادرة</option><option value="incoming">واردة</option></select></div>
    </div>
    <div class="form-row">
        <div class="form-group"><label class="form-label">التاريخ والوقت</label><input type="datetime-local" name="call_date" class="form-control" value="<?= date('Y-m-d\TH:i') ?>"></div>
        <div class="form-group"><label class="form-label">المدة (دقائق)</label><input type="number" name="duration" class="form-control" value="5" min="0"></div>
    </div>
    <div class="form-group"><label class="form-label">النتيجة</label><select name="result" class="form-control"><option value="answered">تم الرد</option><option value="no_answer">لم يرد</option><option value="busy">مشغول</option><option value="callback">طلب معاودة الاتصال</option><option value="interested">مهتم</option><option value="not_interested">غير مهتم</option></select></div>
    <div class="form-group"><label class="form-label">ملاحظات</label><textarea name="notes" class="form-control" rows="3" placeholder="ملخص المكالمة..."></textarea></div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ المكالمة</button>
</form></div></div>
