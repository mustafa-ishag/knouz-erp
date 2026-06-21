<?php
class NotificationController extends Controller {
    public function index(): void { $notifications = (new NotificationSystem())->getUserNotifications($this->currentUser()['id'], 50); $pageTitle='الإشعارات'; $breadcrumbs=[['title'=>'الإشعارات']]; $this->render('domain/Notifications/Views/index.php', compact('pageTitle','breadcrumbs','notifications')); }
    public function markRead(): void { $id=(int)$this->query('id'); $this->db->update('notifications', ['is_read'=>1], 'id=? AND user_id=?', [$id, $this->currentUser()['id']]); $notif=$this->db->fetch("SELECT link FROM notifications WHERE id=?",[$id]); header('Location: '.($notif['link']?:url('notifications'))); exit; }
    public function markAllRead(): void { $this->db->execute("UPDATE notifications SET is_read=1 WHERE user_id=? AND is_read=0", [$this->currentUser()['id']]); $this->setFlash('success','تم تحديد جميع الإشعارات كمقروءة'); $this->redirect('notifications'); }
    public function getNotifications(): void { $n=new NotificationSystem();$this->json(['unread_count'=>$n->getUnreadCount($this->currentUser()['id'])]); }
}
