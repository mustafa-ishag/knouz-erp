<div class="page-header"><div><h1 class="page-title">تعديل المهمة</h1></div><div class="page-actions"><a href="<?= url('tasks') ?>" class="btn btn-outline"><i class="fas fa-arrow-right"></i></a></div></div>
<div class="card"><div class="card-body"><form method="POST" action="<?= url('tasks','update') ?>"><?= csrfField() ?><input type="hidden" name="id" value="<?= $task['id'] ?>">
    <div class="form-group"><label class="form-label">العنوان</label><input type="text" name="title" class="form-control" required value="<?= clean($task['title']) ?>"></div>
    <div class="form-group"><label class="form-label">الوصف</label><textarea name="description" class="form-control" rows="3"><?= clean($task['description']) ?></textarea></div>
    <div class="form-row">
        <div class="form-group"><label class="form-label">العميل</label><select name="client_id" class="form-control"><option value="">اختياري</option><?php foreach($clients as $c): ?><option value="<?= $c['id'] ?>" <?= $task['client_id']==$c['id']?'selected':'' ?>><?= clean($c['name']) ?></option><?php endforeach; ?></select></div>
        <div class="form-group"><label class="form-label">المسؤول</label><select name="assigned_to" class="form-control"><option value="">اختر</option><?php foreach($employees as $e): ?><option value="<?= $e['id'] ?>" <?= $task['assigned_to']==$e['id']?'selected':'' ?>><?= clean($e['full_name']) ?></option><?php endforeach; ?></select></div>
    </div>
    <div class="form-row">
        <div class="form-group"><label class="form-label">الأولوية</label><select name="priority" class="form-control"><?php foreach(['high'=>'عالية','medium'=>'متوسطة','low'=>'منخفضة'] as $k=>$v): ?><option value="<?= $k ?>" <?= $task['priority']===$k?'selected':'' ?>><?= $v ?></option><?php endforeach; ?></select></div>
        <div class="form-group"><label class="form-label">الحالة</label><select name="status" class="form-control"><?php foreach(['pending'=>'معلقة','in_progress'=>'قيد التنفيذ','completed'=>'مكتملة','cancelled'=>'ملغية'] as $k=>$v): ?><option value="<?= $k ?>" <?= $task['status']===$k?'selected':'' ?>><?= $v ?></option><?php endforeach; ?></select></div>
    </div>
    <div class="form-group"><label class="form-label">الموعد</label><input type="date" name="due_date" class="form-control" value="<?= $task['due_date'] ?>"></div>
    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> حفظ</button>
</form></div></div>
