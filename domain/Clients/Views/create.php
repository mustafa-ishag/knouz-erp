<!-- إضافة عميل جديد -->
<div class="page-header">
    <div>
        <h1 class="page-title">إضافة عميل جديد</h1>
        <p class="page-subtitle">إدخال بيانات العميل</p>
    </div>
    <div class="page-actions">
        <a href="<?= url('clients') ?>" class="btn btn-outline">
            <i class="fas fa-arrow-right"></i>
            رجوع
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= url('clients', 'store') ?>" id="clientForm">
            <?= csrfField() ?>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">الاسم <span class="required">*</span></label>
                    <input type="text" name="name" class="form-control" required placeholder="اسم العميل الكامل">
                </div>
                <div class="form-group">
                    <label class="form-label">رقم الهوية</label>
                    <input type="text" name="id_number" class="form-control" placeholder="رقم الهوية الوطنية أو الإقامة">
                </div>
            </div>
            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">رقم الجوال</label>
                    <input type="tel" name="phone" class="form-control" placeholder="05XXXXXXXX">
                </div>
                <div class="form-group">
                    <label class="form-label">رقم جوال إضافي</label>
                    <input type="tel" name="phone2" class="form-control" placeholder="05XXXXXXXX">
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">البريد الإلكتروني</label>
                <input type="email" name="email" class="form-control" placeholder="example@domain.com">
            </div>

            <h3 class="mt-3 mb-2" style="font-size:1rem;"><i class="fas fa-map-marker-alt text-gold"></i> العنوان</h3>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">العنوان المختصر</label>
                    <input type="text" name="short_address" class="form-control" placeholder="مثال: RRRD2929">
                </div>
                <div class="form-group">
                    <label class="form-label">رقم المبنى</label>
                    <input type="text" name="building_number" class="form-control" placeholder="رقم المبنى">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">الشارع</label>
                    <input type="text" name="street" class="form-control" placeholder="اسم الشارع">
                </div>
                <div class="form-group">
                    <label class="form-label">الحي</label>
                    <input type="text" name="district" class="form-control" placeholder="اسم الحي">
                </div>
            </div>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">المدينة</label>
                    <select name="city" class="form-control">
                        <option value="">اختر المدينة</option>
                        <option value="الرياض">الرياض</option>
                        <option value="جدة">جدة</option>
                        <option value="مكة المكرمة">مكة المكرمة</option>
                        <option value="المدينة المنورة">المدينة المنورة</option>
                        <option value="الدمام">الدمام</option>
                        <option value="الخبر">الخبر</option>
                        <option value="الأحساء">الأحساء</option>
                        <option value="القطيف">القطيف</option>
                        <option value="الطائف">الطائف</option>
                        <option value="تبوك">تبوك</option>
                        <option value="بريدة">بريدة</option>
                        <option value="خميس مشيط">خميس مشيط</option>
                        <option value="أبها">أبها</option>
                        <option value="حائل">حائل</option>
                        <option value="نجران">نجران</option>
                        <option value="جازان">جازان</option>
                        <option value="ينبع">ينبع</option>
                        <option value="أخرى">أخرى</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">الرمز البريدي</label>
                    <input type="text" name="postal_code" class="form-control" placeholder="مثال: 12345">
                </div>
                <div class="form-group">
                    <label class="form-label">الرقم الإضافي</label>
                    <input type="text" name="additional_number" class="form-control" placeholder="مثال: 7890">
                </div>
            </div>

            
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">مصدر العميل</label>
                    <select name="source" class="form-control">
                        <option value="">اختر المصدر</option>
                        <option value="direct">مباشر</option>
                        <option value="referral">توصية</option>
                        <option value="website">الموقع الإلكتروني</option>
                        <option value="social">وسائل التواصل</option>
                        <option value="advertising">إعلان</option>
                        <option value="other">أخرى</option>
                    </select>
                </div>
                <div class="form-group">
                    <label class="form-label">الموظف المسؤول</label>
                    <select name="assigned_to" class="form-control">
                        <option value="">غير محدد</option>
                        <?php foreach ($employees as $emp): ?>
                            <option value="<?= $emp['id'] ?>"><?= clean($emp['full_name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
            
            <div class="form-group">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" class="form-control" rows="3" placeholder="أي ملاحظات إضافية عن العميل"></textarea>
            </div>
            
            <div class="d-flex gap-1">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i>
                    حفظ العميل
                </button>
                <a href="<?= url('clients') ?>" class="btn btn-outline">إلغاء</a>
            </div>
        </form>
    </div>
</div>
