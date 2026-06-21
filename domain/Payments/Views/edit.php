<div class="page-header"><div><h1 class="page-title">تعديل الدفعة <?= clean($payment['payment_number']) ?></h1></div><div class="page-actions"><a href="<?= url('payments') ?>" class="btn btn-outline"><i class="fas fa-arrow-right"></i></a></div></div>
<div class="card"><div class="card-body">
    <form method="POST" action="<?= url('payments','update') ?>"><?= csrfField() ?><input type="hidden" name="id" value="<?= $payment['id'] ?>">
        <div class="form-group"><label class="form-label">العميل</label><select name="client_id" class="form-control" required><?php foreach($clients as $c): ?><option value="<?= $c['id'] ?>" <?= $payment['client_id']==$c['id']?'selected':'' ?>><?= clean($c['name']) ?></option><?php endforeach; ?></select></div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">المبلغ</label><input type="number" name="amount" class="form-control" value="<?= $payment['amount'] ?>" step="0.01" required></div>
            <div class="form-group"><label class="form-label">التاريخ</label><input type="date" name="payment_date" class="form-control" value="<?= $payment['payment_date'] ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">الطريقة</label><select name="payment_type" class="form-control"><?php foreach(['bank_transfer'=>'تحويل بنكي','cash'=>'نقدي','check'=>'شيك','online'=>'إلكتروني','other'=>'أخرى'] as $k=>$v): ?><option value="<?= $k ?>" <?= $payment['payment_type']===$k?'selected':'' ?>><?= $v ?></option><?php endforeach; ?></select></div>
            <div class="form-group"><label class="form-label">المرجع</label><input type="text" name="reference_number" class="form-control" value="<?= clean($payment['reference_number']) ?>"></div>
        </div>
        <div class="form-group"><label class="form-label">ملاحظات</label><textarea name="notes" class="form-control" rows="2"><?= clean($payment['notes']) ?></textarea></div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
    </form>
</div></div>
