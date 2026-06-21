<!-- تعديل اشتراك حكومي -->
<div class="page-header">
    <div>
        <h1 class="page-title">تعديل اشتراك حكومي</h1>
        <p class="page-subtitle"><?= GovSubscription::platformLabel($subscription['platform']) ?> - <?= clean($subscription['company_name']) ?></p>
    </div>
    <div class="page-actions">
        <a href="<?= url('gov_subscriptions') ?>" class="btn btn-outline">
            <i class="fas fa-arrow-right"></i> رجوع
        </a>
    </div>
</div>

<div class="card">
    <div class="card-body">
        <form method="POST" action="<?= url('gov_subscriptions', 'update') ?>">
            <?= csrfField() ?>
            <input type="hidden" name="id" value="<?= $subscription['id'] ?>">

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">المنصة الحكومية <span class="required">*</span></label>
                    <select name="platform_select" id="platformSelect" class="form-control" onchange="toggleNewPlatform()" required>
                        <option value="">اختر المنصة</option>
                        <?php foreach ($platforms as $key => $label): ?>
                            <option value="<?= $key ?>" <?= $subscription['platform'] === $key ? 'selected' : '' ?>><?= $label ?></option>
                        <?php endforeach; ?>
                        <option value="new">+ أخرى (إضافة منصة جديدة)...</option>
                    </select>
                    <input type="text" name="platform_new" id="platformNew" class="form-control mt-2" placeholder="اكتب اسم المنصة الجديدة" style="display:none;">
                    <input type="hidden" name="platform" id="platformFinal" value="<?= clean($subscription['platform']) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">الشركة</label>
                    <select name="company_id" class="form-control" id="companySelect" onchange="updateCompanyName()">
                        <option value="">اختر الشركة أو اكتب يدوياً</option>
                        <?php foreach ($companies as $c): ?>
                            <option value="<?= $c['id'] ?>" data-name="<?= clean($c['name_ar']) ?>" <?= $subscription['company_id'] == $c['id'] ? 'selected' : '' ?>><?= clean($c['name_ar']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">اسم الشركة <span class="required">*</span></label>
                    <input type="text" name="company_name" id="companyNameInput" class="form-control" required value="<?= clean($subscription['company_name']) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">رقم الاشتراك</label>
                    <input type="text" name="subscription_number" class="form-control" dir="ltr" value="<?= clean($subscription['subscription_number']) ?>">
                </div>
            </div>

            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">تاريخ البداية</label>
                    <input type="date" name="start_date" class="form-control" value="<?= $subscription['start_date'] ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">تاريخ الانتهاء</label>
                    <input type="date" name="end_date" class="form-control" value="<?= $subscription['end_date'] ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">التكلفة (ر.س)</label>
                    <input type="number" name="cost" class="form-control" step="0.01" min="0" value="<?= $subscription['cost'] ?>">
                </div>
            </div>

            <h3 class="mt-3 mb-2" style="font-size:1rem;"><i class="fas fa-key text-gold"></i> بيانات الدخول</h3>
            <div class="form-row">
                <div class="form-group">
                    <label class="form-label">اسم المستخدم في المنصة</label>
                    <input type="text" name="username" class="form-control" dir="ltr" value="<?= clean($subscription['username']) ?>">
                </div>
                <div class="form-group">
                    <label class="form-label">تلميح كلمة المرور</label>
                    <input type="text" name="password_hint" class="form-control" value="<?= clean($subscription['password_hint']) ?>">
                </div>
            </div>

            <div class="form-group">
                <label class="form-label">ملاحظات</label>
                <textarea name="notes" class="form-control" rows="3"><?= clean($subscription['notes']) ?></textarea>
            </div>

            <div class="d-flex gap-1">
                <button type="submit" class="btn btn-primary">
                    <i class="fas fa-save"></i> حفظ التغييرات
                </button>
                <a href="<?= url('gov_subscriptions') ?>" class="btn btn-outline">إلغاء</a>
            </div>
        </form>
    </div>
</div>

<script>
function updateCompanyName() {
    const select = document.getElementById('companySelect');
    const input = document.getElementById('companyNameInput');
    const selected = select.options[select.selectedIndex];
    if (selected && selected.dataset.name) {
        input.value = selected.dataset.name;
    }
}

function toggleNewPlatform() {
    const select = document.getElementById('platformSelect');
    const input = document.getElementById('platformNew');
    if (select.value === 'new') {
        input.style.display = 'block';
        input.required = true;
    } else {
        input.style.display = 'none';
        input.required = false;
    }
}

document.querySelector('form').addEventListener('submit', function(e) {
    const select = document.getElementById('platformSelect');
    const newPlatform = document.getElementById('platformNew');
    const finalPlatform = document.getElementById('platformFinal');
    
    if (select.value === 'new') {
        finalPlatform.value = newPlatform.value;
    } else {
        finalPlatform.value = select.value;
    }
});
</script>
