<div class="page-header"><div><h1 class="page-title">رفع مستند</h1></div><div class="page-actions"><a href="<?= url('documents') ?>" class="btn btn-outline"><i class="fas fa-arrow-right"></i></a></div></div>
<div class="card"><div class="card-body"><form method="POST" action="<?= url('documents','store') ?>" enctype="multipart/form-data"><?= csrfField() ?>
    <div class="form-row">
        <div class="form-group"><label class="form-label">الشركة <span class="required">*</span></label><select name="company_id" class="form-control" required><option value="">اختر</option><?php foreach($companies as $co): ?><option value="<?= $co['id'] ?>"><?= clean($co['name_ar']) ?> (<?= clean($co['client_name']) ?>)</option><?php endforeach; ?></select></div>
        <div class="form-group"><label class="form-label">نوع المستند</label><select name="document_type" class="form-control"><option value="cr">سجل تجاري</option><option value="vat">شهادة ضريبية</option><option value="gosi">شهادة تأمينات</option><option value="license">ترخيص</option><option value="contract">عقد</option><option value="id">هوية</option><option value="other">أخرى</option></select></div>
    </div>
    <div class="form-group"><label class="form-label">عنوان المستند</label><input type="text" name="title" class="form-control" placeholder="اختياري - سيُستخدم اسم الملف"></div>
    <div class="form-row">
        <div class="form-group"><label class="form-label">الملف <span class="required">*</span></label><input type="file" name="file" class="form-control" required accept=".pdf,.jpg,.jpeg,.png,.doc,.docx,.xls,.xlsx"></div>
        <div class="form-group"><label class="form-label">تاريخ الانتهاء</label><input type="date" name="expiry_date" class="form-control"></div>
    </div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-upload"></i> رفع المستند</button>
</form></div></div>
