<div class="page-header">
    <div>
        <h1 class="page-title">تغيير كلمة المرور</h1>
        <p class="page-subtitle">تغيير كلمة المرور الخاصة بحسابك</p>
    </div>
</div>

<div class="card" style="max-width: 500px;">
    <div class="card-body">
        <form method="POST" action="<?= url('auth', 'change_password') ?>">
            <?= csrfField() ?>
            
            <div class="form-group">
                <label class="form-label">كلمة المرور الحالية <span class="required">*</span></label>
                <input type="password" name="current_password" class="form-control" required>
            </div>
            
            <div class="form-group">
                <label class="form-label">كلمة المرور الجديدة <span class="required">*</span></label>
                <input type="password" name="new_password" class="form-control" required minlength="6">
                <div class="form-hint">يجب أن تكون 6 أحرف على الأقل</div>
            </div>
            
            <div class="form-group">
                <label class="form-label">تأكيد كلمة المرور <span class="required">*</span></label>
                <input type="password" name="confirm_password" class="form-control" required>
            </div>
            
            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i>
                حفظ التغييرات
            </button>
        </form>
    </div>
</div>
