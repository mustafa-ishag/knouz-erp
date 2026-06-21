<div class="page-header"><div><h1 class="page-title">تسجيل دفعة</h1></div><div class="page-actions"><a href="<?= url('payments') ?>" class="btn btn-outline"><i class="fas fa-arrow-right"></i> رجوع</a></div></div>
<div class="card"><div class="card-body">
    <form method="POST" action="<?= url('payments','store') ?>"><?= csrfField() ?>
        <?php if($claim): ?><div class="alert alert-info mb-3"><i class="fas fa-info-circle"></i> دفعة مرتبطة بالمطالبة: <?= clean($claim['claim_number']) ?> - الإجمالي: <?= formatMoney($claim['total']) ?> - المتبقي: <?= formatMoney($claim['total']-$claim['paid_amount']) ?></div>
            <input type="hidden" name="claim_id" value="<?= $claim['id'] ?>"><input type="hidden" name="client_id" value="<?= $claim['client_id'] ?>">
        <?php elseif($invoice): ?><div class="alert alert-info mb-3"><i class="fas fa-info-circle"></i> دفعة مرتبطة بالفاتورة: <?= clean($invoice['invoice_number']) ?> - المتبقي: <?= formatMoney($invoice['total']-$invoice['paid_amount']) ?></div>
            <input type="hidden" name="invoice_id" value="<?= $invoice['id'] ?>"><input type="hidden" name="client_id" value="<?= $invoice['client_id'] ?>">
        <?php endif; ?>
        
        <?php if(!$claim && !$invoice): ?>
        <div class="form-group"><label class="form-label">العميل <span class="required">*</span></label>
            <select name="client_id" class="form-control" required><option value="">اختر</option><?php foreach($clients as $c): ?><option value="<?= $c['id'] ?>"><?= clean($c['name']) ?></option><?php endforeach; ?></select></div>
        <?php endif; ?>
        
        <div class="form-row">
            <div class="form-group"><label class="form-label">المبلغ <span class="required">*</span></label>
                <input type="number" name="amount" class="form-control" required step="0.01" min="0.01" value="<?= $claim?($claim['total']-$claim['paid_amount']):($invoice?($invoice['total']-$invoice['paid_amount']):'') ?>"></div>
            <div class="form-group"><label class="form-label">تاريخ الدفع</label><input type="date" name="payment_date" class="form-control" value="<?= date('Y-m-d') ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">طريقة الدفع</label>
                <select name="payment_type" class="form-control"><option value="bank_transfer">تحويل بنكي</option><option value="cash">نقدي</option><option value="check">شيك</option><option value="online">دفع إلكتروني</option><option value="other">أخرى</option></select></div>
            <div class="form-group"><label class="form-label">رقم المرجع / الحوالة</label><input type="text" name="reference_number" class="form-control" placeholder="رقم الحوالة أو الشيك"></div>
        </div>
        <div class="form-group"><label class="form-label">ملاحظات</label><textarea name="notes" class="form-control" rows="2"></textarea></div>
        <button type="submit" class="btn btn-success btn-lg"><i class="fas fa-save"></i> تسجيل الدفعة</button>
    </form>
</div></div>
