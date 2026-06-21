<!-- تعديل العميل -->
<div class="page-header">
    <div>
        <h1 class="page-title">تعديل العميل</h1>
        <p class="page-subtitle"><?= clean($client['name']) ?> - <?= clean($client['client_number']) ?></p>
    </div>
    <div class="page-actions">
        <a href="<?= url('clients', 'card', ['id' => $client['id']]) ?>" class="btn btn-outline">
            <i class="fas fa-arrow-right"></i>
            رجوع
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= url('clients', 'update') ?>">
            <?= csrfField() ?>
            <input type="hidden" name="id" value="<?= $client['id'] ?>">
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">الاسم <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" required value="<?= clean($client['name']) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">رقم الهوية</label>
                    <input type="text" name="id_number" class="form-control" value="<?= clean($client['id_number']) ?>">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">رقم الجوال</label>
                    <input type="tel" name="phone" class="form-control" value="<?= clean($client['phone']) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">رقم جوال إضافي</label>
                    <input type="tel" name="phone2" class="form-control" value="<?= clean($client['phone2']) ?>">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" value="<?= clean($client['email']) ?>">
            </div>

            <h3 class="mt-3 mb-2" style="font-size:1rem;"><i class="fas fa-map-marker-alt text-gold"></i> العنوان</h3>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">العنوان المختصر</label>
                    <input type="text" name="short_address" class="form-control" value="<?= clean($client['short_address'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">رقم المبنى</label>
                    <input type="text" name="building_number" class="form-control" value="<?= clean($client['building_number'] ?? '') ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">الشارع</label>
                    <input type="text" name="street" class="form-control" value="<?= clean($client['street'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">الحي</label>
                    <input type="text" name="district" class="form-control" value="<?= clean($client['district'] ?? '') ?>">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">المدينة</label>
                    <select name="city" class="form-control">
                        <option value="">اختر المدينة</option>
                        <?php
                        $saudiCities = ['الرياض','جدة','مكة المكرمة','المدينة المنورة','الدمام','الخبر','الأحساء','القطيف','الطائف','تبوك','بريدة','خميس مشيط','أبها','حائل','نجران','جازان','ينبع','أخرى'];
                        foreach ($saudiCities as $c): ?>
                            <option value="<?= $c ?>" <?= $client['city'] === $c ? 'selected' : '' ?>><?= $c ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">الرمز البريدي</label>
                    <input type="text" name="postal_code" class="form-control" value="<?= clean($client['postal_code'] ?? '') ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">الرقم الإضافي</label>
                    <input type="text" name="additional_number" class="form-control" value="<?= clean($client['additional_number'] ?? '') ?>">
                </div>
            </div>

            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">مصدر العميل</label>
                    <select name="source" class="form-control">
                        <option value="">اختر المصدر</option>
                        <?php
                        $sources = ['direct'=>'مباشر','referral'=>'توصية','website'=>'الموقع الإلكتروني','social'=>'وسائل التواصل','advertising'=>'إعلان','other'=>'أخرى'];
                        foreach ($sources as $k => $v): ?>
                            <option value="<?= $k ?>" <?= $client['source'] === $k ? 'selected' : '' ?>><?= $v ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">الموظف المسؤول</label>
                    <select name="assigned_to" class="form-control">
                        <option value="">غير محدد</option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?= $emp['id'] ?>" <?= $client['assigned_to'] == $emp['id'] ? 'selected' : '' ?>><?= clean($emp['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" class="form-control" rows="3"><?= clean($client['notes']) ?></textarea>
            </div>
            
            <div class="d-flex gap-1">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    حفظ التغييرات
                </button>
                <a href="<?= url('clients', 'card', ['id' => $client['id']]) ?>" class="btn btn-outline">إلغاء</a>
            </div>
        </form>
    </div>
</div>
