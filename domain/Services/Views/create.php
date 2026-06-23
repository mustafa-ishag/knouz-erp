<div class="page-header"><div><h1 class="page-title">إضافة خدمة</h1></div><div class="page-actions"><a href="<?= url('services') ?>" class="btn btn-outline"><i class="fas fa-arrow-right"></i> رجوع</a></div></div>
<div class="card"><div class="card-body">
    <form method="POST" action="<?= url('services', 'store') ?>"><?= csrfField() ?>
        <div class="form-row">
            <div class="form-group"><label class="form-label">الاسم <span class="required">*</span></label><input type="text" name="name" class="form-control" required></div>
            <div class="form-group"><label class="form-label">التصنيف <span class="required">*</span></label>
                <select name="category_id" class="form-control" required><option value="">اختر التصنيف</option>
                    <?php foreach ($categories as $cat): ?><option value="<?= $cat['id'] ?>"><?= clean($cat['name']) ?></option><?php endforeach; ?>
                </select></div>
        </div>
        <div class="form-group"><label class="form-label">الوصف</label><textarea name="description" class="form-control" rows="2"></textarea></div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">المنصة</label>
                <select name="platform" class="form-control"><option value="">اختر</option>
                    <?php foreach(['moc'=>'وزارة التجارة','qiwa'=>'قوى','gosi'=>'التأمينات','mudad'=>'مدد','muqeem'=>'مقيم','zatca'=>'الزكاة والضريبة','balady'=>'بلدي','other'=>'أخرى'] as $k=>$v): ?>
                        <option value="<?= $k ?>"><?= $v ?></option>
                    <?php endforeach; ?>
                </select></div>
            <div class="form-group"><label class="form-label">مدة التنفيذ (أيام)</label><input type="number" name="execution_days" class="form-control" value="3" min="1"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">السعر الافتراضي</label><input type="number" name="default_price" class="form-control" value="0" step="0.01"></div>
            <div class="form-group"><label class="form-label">التكلفة</label><input type="number" name="default_cost" class="form-control" value="0" step="0.01"></div>
        </div>
        <div class="form-group"><label class="form-label"><i class="fas fa-link"></i> رابط موقع الخدمة</label><input type="url" name="url" class="form-control" dir="ltr" placeholder="https://..."></div>
        <div class="form-group"><label class="form-label">المتطلبات</label><textarea name="requirements" class="form-control" rows="2" placeholder="المستندات أو المتطلبات اللازمة"></textarea></div>
        <div class="form-check mb-2"><input type="checkbox" name="is_active" value="1" checked id="is_active"><label for="is_active">خدمة نشطة</label></div>
        <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
    </form>
</div></div>
