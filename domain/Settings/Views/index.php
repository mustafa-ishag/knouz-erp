<div class="page-header"><div><h1 class="page-title">إعدادات النظام</h1></div></div>
<div class="card"><div class="card-body">
    <form method="POST" action="<?= url('settings', 'update') ?>"><?= csrfField() ?>
        <h3 class="mb-2" style="font-size:1rem;"><i class="fas fa-building text-gold"></i> بيانات الشركة</h3>
        <div class="form-row">
            <div class="form-group"><label class="form-label">اسم الشركة (عربي)</label><input type="text" name="company_name_ar" class="form-control" value="<?= clean($settings['company_name_ar'] ?? '') ?>"></div>
            <div class="form-group"><label class="form-label">اسم الشركة (إنجليزي)</label><input type="text" name="company_name_en" class="form-control" dir="ltr" value="<?= clean($settings['company_name_en'] ?? '') ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">رقم السجل التجاري</label><input type="text" name="company_cr" class="form-control" value="<?= clean($settings['company_cr'] ?? '') ?>"></div>
            <div class="form-group"><label class="form-label">الرقم الضريبي</label><input type="text" name="company_vat_number" class="form-control" value="<?= clean($settings['company_vat_number'] ?? '') ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">الهاتف</label><input type="tel" name="company_phone" class="form-control" value="<?= clean($settings['company_phone'] ?? '') ?>"></div>
            <div class="form-group"><label class="form-label">البريد الإلكتروني</label><input type="email" name="company_email" class="form-control" value="<?= clean($settings['company_email'] ?? '') ?>"></div>
        </div>
        <div class="form-row">
            <div class="form-group"><label class="form-label">المدينة</label><input type="text" name="company_city" class="form-control" value="<?= clean($settings['company_city'] ?? '') ?>"></div>
            <div class="form-group"><label class="form-label">العنوان</label><input type="text" name="company_address" class="form-control" value="<?= clean($settings['company_address'] ?? '') ?>"></div>
        </div>

        <hr class="my-3">
        <h3 class="mb-2" style="font-size:1rem;"><i class="fas fa-cog text-gold"></i> إعدادات مالية</h3>
        <div class="form-row">
            <div class="form-group"><label class="form-label">نسبة ضريبة القيمة المضافة (%)</label><input type="number" name="vat_rate" class="form-control" value="<?= clean($settings['vat_rate'] ?? '15') ?>" step="0.01"></div>
            <div class="form-group"><label class="form-label">العملة</label><input type="text" name="currency" class="form-control" value="<?= clean($settings['currency'] ?? 'SAR') ?>"></div>
        </div>

        <hr class="my-3">
        <h3 class="mb-2" style="font-size:1rem;"><i class="fas fa-file-invoice text-gold"></i> إعدادات عروض الأسعار</h3>
        <div class="form-row">
            <div class="form-group"><label class="form-label">صلاحية عرض السعر (أيام)</label><input type="number" name="quotation_validity_days" class="form-control" value="<?= clean($settings['quotation_validity_days'] ?? '30') ?>"></div>
            <div class="form-group"><label class="form-label">بادئة رقم العرض</label><input type="text" name="quotation_prefix" class="form-control" value="<?= clean($settings['quotation_prefix'] ?? 'QT') ?>"></div>
        </div>
        <div class="form-group"><label class="form-label">الشروط والأحكام الافتراضية</label><textarea name="quotation_terms" class="form-control" rows="4"><?= clean($settings['quotation_terms'] ?? '') ?></textarea></div>

        <hr class="my-3">
        <h3 class="mb-2" style="font-size:1rem;"><i class="fas fa-bell text-gold"></i> إعدادات التنبيهات</h3>
        <div class="form-row">
            <div class="form-group"><label class="form-label">تنبيه انتهاء السجل (أيام قبل)</label><input type="number" name="cr_expiry_alert_days" class="form-control" value="<?= clean($settings['cr_expiry_alert_days'] ?? '30') ?>"></div>
            <div class="form-group"><label class="form-label">تنبيه استحقاق الفاتورة (أيام قبل)</label><input type="number" name="invoice_due_alert_days" class="form-control" value="<?= clean($settings['invoice_due_alert_days'] ?? '7') ?>"></div>
        </div>

        <div class="mt-3"><button type="submit" class="btn btn-primary btn-lg"><i class="fas fa-save"></i> حفظ الإعدادات</button></div>
    </form>
</div></div>

<div class="card mt-2">
    <div class="card-header"><h3><i class="fas fa-link text-gold mr-1"></i> روابط سريعة</h3></div>
    <div class="card-body">
        <div class="d-flex gap-2" style="flex-wrap:wrap;">
            <a href="<?= url('settings', 'users') ?>" class="btn btn-outline"><i class="fas fa-users-cog"></i> إدارة المستخدمين</a>
        </div>
    </div>
</div>
