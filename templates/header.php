<?php
$auth = Auth::getInstance();
$user = $auth->getUser();
$notificationSystem = new NotificationSystem();
$unreadCount = $notificationSystem->getUnreadCount($user['id']);
$notifications = $notificationSystem->getUserNotifications($user['id'], 10);
$userInitials = mb_substr($user['full_name'], 0, 2);
?>
<!-- الهيدر -->
<header class="header">
    <div class="header-right">
        <!-- زر القائمة للجوال -->
        <button class="mobile-toggle" onclick="toggleMobileSidebar()">
            <i class="fas fa-bars"></i>
        </button>
        
        <!-- مسار التنقل -->
        <div class="breadcrumb">
            <a href="<?= url('dashboard') ?>"><i class="fas fa-home"></i></a>
            <?php if (isset($breadcrumbs) && is_array($breadcrumbs)): ?>
                <?php foreach ($breadcrumbs as $crumb): ?>
                    <span class="separator">/</span>
                    <?php if (isset($crumb['url'])): ?>
                        <a href="<?= $crumb['url'] ?>"><?= clean($crumb['title']) ?></a>
                    <?php else: ?>
                        <span class="current"><?= clean($crumb['title']) ?></span>
                    <?php endif; ?>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        
        <!-- البحث -->
        <div class="header-search">
            <input type="text" placeholder="بحث..." id="global-search">
            <i class="fas fa-search"></i>
        </div>
    </div>
    
    <div class="header-left">
        <!-- الإشعارات -->
        <div class="dropdown">
            <button class="header-icon" onclick="toggleNotifications()">
                <i class="fas fa-bell"></i>
                <?php if ($unreadCount > 0): ?>
                    <span class="badge-dot"></span>
                <?php endif; ?>
            </button>
            
            <div class="notifications-panel dropdown-menu dropdown-right">
                <div class="notifications-header">
                    <h4 style="font-size: 0.9375rem; font-weight: 700;">الإشعارات</h4>
                    <?php if ($unreadCount > 0): ?>
                        <a href="<?= url('notifications', 'read_all') ?>" style="font-size: 0.75rem;">
                            تحديد الكل كمقروء
                        </a>
                    <?php endif; ?>
                </div>
                <div class="notifications-list">
                    <?php if (empty($notifications)): ?>
                        <div class="empty-state" style="padding: 2rem;">
                            <i class="fas fa-bell-slash" style="font-size: 2rem;"></i>
                            <p style="margin-top: 0.5rem;">لا توجد إشعارات</p>
                        </div>
                    <?php else: ?>
                        <?php foreach ($notifications as $notif): ?>
                            <a href="<?= $notif['link'] ? $notif['link'] : url('notifications', 'read', ['id' => $notif['id']]) ?>" 
                               class="notification-item <?= !$notif['is_read'] ? 'unread' : '' ?>">
                                <div class="notification-icon" style="background: var(--<?= $notif['type'] ?>-bg); color: var(--<?= $notif['type'] ?>);">
                                    <i class="fas fa-<?= $notif['type'] === 'warning' ? 'exclamation-triangle' : ($notif['type'] === 'danger' ? 'exclamation-circle' : ($notif['type'] === 'success' ? 'check-circle' : 'info-circle')) ?>"></i>
                                </div>
                                <div class="notification-content">
                                    <div class="title"><?= clean($notif['title']) ?></div>
                                    <div class="text"><?= clean(truncate($notif['message'], 80)) ?></div>
                                    <div class="time"><?= timeAgo($notif['created_at']) ?></div>
                                </div>
                            </a>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        
        <!-- قائمة المستخدم -->
        <div class="dropdown">
            <div class="user-menu">
                <div class="user-avatar"><?= $userInitials ?></div>
                <div class="user-info">
                    <span class="name"><?= clean($user['full_name']) ?></span>
                    <span class="role"><?= clean($user['role_name'] ?? '') ?></span>
                </div>
                <i class="fas fa-chevron-down" style="font-size: 0.7rem; color: var(--text-light);"></i>
            </div>
            
            <div class="dropdown-menu dropdown-right">
                <a href="<?= url('auth', 'change_password') ?>" class="dropdown-item">
                    <i class="fas fa-key"></i>
                    <span>تغيير كلمة المرور</span>
                </a>
                <div class="dropdown-divider"></div>
                <a href="<?= url('auth', 'logout') ?>" class="dropdown-item" style="color: var(--danger);">
                    <i class="fas fa-sign-out-alt"></i>
                    <span>تسجيل الخروج</span>
                </a>
            </div>
        </div>
    </div>
</header>
