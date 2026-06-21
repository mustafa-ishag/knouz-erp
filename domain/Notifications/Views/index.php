<div class="page-header"><div><h1 class="page-title">الإشعارات</h1></div></div>
<div class="card"><div class="card-body">
    <?php if(empty($notifications)): ?><div class="empty-state"><i class="fas fa-bell-slash"></i><h3>لا توجد إشعارات</h3></div>
    <?php else: foreach($notifications as $n): ?>
        <a href="<?= $n['link']?$n['link']:url('notifications','read',['id'=>$n['id']]) ?>" class="notification-item <?= !$n['is_read']?'unread':'' ?>" style="display:flex;">
            <div class="notification-icon" style="background:var(--<?= $n['type']??'info' ?>-bg);color:var(--<?= $n['type']??'info' ?>);"><i class="fas fa-bell"></i></div>
            <div class="notification-content"><div class="title"><?= clean($n['title']) ?></div><div class="text"><?= clean($n['message']) ?></div><div class="time"><?= timeAgo($n['created_at']) ?></div></div>
        </a>
    <?php endforeach;endif; ?>
</div></div>
