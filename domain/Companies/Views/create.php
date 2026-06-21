<div class="page-header">
    <div><h1 class="page-title">إضافة شركة</h1></div>
    <div class="page-actions"><a href="<?= url('companies') ?>" class="btn btn-outline"><i class="fas fa-arrow-right"></i> رجوع</a></div>
</div>
<div class="card"><div class="card-body">
    <form method="POST" action="<?= url('companies', 'store') ?>">
        <?= csrfField() ?>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">العميل <span class="required">*</span></label>
                <select name="client_id" class="form-control" required>
                    <option value="">اختر العميل</option>
                    <?php foreach ($clients as $c): ?>
                        <option value="<?= $c['id'] ?>" <?= $clientId == $c['id'] ? 'selected' : '' ?>><?= clean($c['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">النشاط</label>
                <input type="text" name="activity" class="form-control" placeholder="نشاط الشركة">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">الاسم العربي <span class="required">*</span></label>
                <input type="text" name="name_ar" class="form-control" required>
            </div>
            <div class="form-group">
                <label class="form-label">الاسم الإنجليزي</label>
                <input type="text" name="name_en" class="form-control" dir="ltr">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">رقم السجل التجاري</label>
                <input type="text" name="cr_number" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">الرقم الموحد</label>
                <input type="text" name="unified_number" class="form-control">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">الرقم المميز</label>
                <input type="text" name="distinctive_number" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">رقم المنشأة في قوى</label>
                <input type="text" name="qiwa_number" class="form-control">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">تاريخ إصدار السجل</label>
                <input type="date" name="cr_issue_date" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">تاريخ انتهاء السجل</label>
                <input type="date" name="cr_expiry_date" class="form-control">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">المدينة</label>
                <select name="city" class="form-control">
                    <option value="">اختر</option>
                    <?php foreach(['الرياض','جدة','مكة المكرمة','المدينة المنورة','الدمام','الخبر','الطائف','تبوك','بريدة','أبها','حائل','نجران','جازان','ينبع','أخرى'] as $c): ?>
                        <option value="<?= $c ?>"><?= $c ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="form-group">
                <label class="form-label">الهاتف</label>
                <input type="tel" name="phone" class="form-control">
            </div>
        </div>
        <div class="form-row">
            <div class="form-group">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control">
            </div>
            <div class="form-group">
                <label class="form-label">العنوان</label>
                <input type="text" name="address" class="form-control">
            </div>
        </div>
        <div class="form-group">
            <label class="form-label">ملاحظات</label>
            <textarea name="notes" class="form-control" rows="2"></textarea>
        </div>
        <div class="d-flex gap-1">
            <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
            <a href="<?= url('companies') ?>" class="btn btn-outline">إلغاء</a>
        </div>
    </form>
</div></div>
