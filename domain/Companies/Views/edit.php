<div class="page-header">
    <div><h1 class="page-title">تعديل الشركة</h1><p class="page-subtitle"><?= clean($company['name_ar']) ?></p></div>
    <div class="page-actions"><a href="<?= url('companies', 'show', ['id' => $company['id']]) ?>" class="btn btn-outline"><i class="fas fa-arrow-right"></i> رجوع</a></div>
</div>
<div class="card"><div class="card-body">
    <form method="POST" action="<?= url('companies', 'update') ?>">
        <?= csrfField() ?>
        <input type="hidden" name="id" value="<?= $company['id'] ?>">
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">العميل <span class="required">*</span></label>
                <select name="client_id" class="form-control" required>
                    <?php foreach ($clients as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $company['client_id'] == $c['id'] ? 'selected' : '' ?>><?= clean($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">النشاط</label>
                <input type="text" name="activity" class="form-control" value="<?= clean($company['activity']) ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">الاسم العربي <span class="required">*</span></label>
                <input type="text" name="name_ar" class="form-control" required value="<?= clean($company['name_ar']) ?>">
            </div>
            <div class="form-group">
                <label class="form-label">الاسم الإنجليزي</label>
                <input type="text" name="name_en" class="form-control" dir="ltr" value="<?= clean($company['name_en']) ?>">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">السجل التجاري</label><input type="text" name="cr_number" class="form-control" value="<?= clean($company['cr_number']) ?>"></div>
            <div class="form-group"><label class="form-label">الرقم الموحد</label><input type="text" name="unified_number" class="form-control" value="<?= clean($company['unified_number']) ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">الرقم المميز</label><input type="text" name="distinctive_number" class="form-control" value="<?= clean($company['distinctive_number']) ?>"></div>
            <div class="form-group"><label class="form-label">رقم قوى</label><input type="text" name="qiwa_number" class="form-control" value="<?= clean($company['qiwa_number']) ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">تاريخ إصدار السجل</label><input type="date" name="cr_issue_date" class="form-control" value="<?= $company['cr_issue_date'] ?>"></div>
            <div class="form-group"><label class="form-label">تاريخ انتهاء السجل</label><input type="date" name="cr_expiry_date" class="form-control" value="<?= $company['cr_expiry_date'] ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">المدينة</label>
                <select name="city" class="form-control">
                    <option value="">اختر</option>
                    <?php foreach(['الرياض','جدة','مكة المكرمة','المدينة المنورة','الدمام','الخبر','الطائف','تبوك','بريدة','أبها','حائل','نجران','جازان','ينبع','أخرى'] as $c): ?>
                        <option value="<?= $c ?>" <?= $company['city'] === $c ? 'selected' : '' ?>><?= $c ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group"><label class="form-label">الهاتف</label><input type="tel" name="phone" class="form-control" value="<?= clean($company['phone']) ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">البريد</label><input type="email" name="email" class="form-control" value="<?= clean($company['email']) ?>"></div>
            <div class="form-group"><label class="form-label">العنوان</label><input type="text" name="address" class="form-control" value="<?= clean($company['address']) ?>"></div>
        </div>
        <div class="form-group"><label class="form-label">ملاحظات</label><textarea name="notes" class="form-control" rows="2"><?= clean($company['notes']) ?></textarea></div>
        <div class="d-flex gap-1">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
            <a href="<?= url('companies', 'show', ['id' => $company['id']]) ?>" class="btn btn-outline">إلغاء</a>
        </div>
    </form>
</div></div>
