<?php
class SettingsController extends Controller {
    public function index(): void {
        $settings=[];$rows=$this->db->fetchAll("SELECT * FROM settings ORDER BY setting_key");foreach($rows as $r)$settings[$r['setting_key']]=$r['setting_value'];
        $pageTitle='إعدادات النظام';$breadcrumbs=[['title'=>'الإعدادات']];
        $this->render('domain/Settings/Views/index.php',compact('pageTitle','breadcrumbs','settings'));
    }
    public function update(): void {
        if(!$this->isPost()){$this->redirect('settings');return;}
        foreach($_POST as $key=>$value){if($key==='_csrf_token')continue;
            $exists=$this->db->fetch("SELECT id FROM settings WHERE setting_key=?",[$key]);
            if($exists){$this->db->update('settings',['setting_value'=>$value],'setting_key=?',[$key]);}else{$this->db->insert('settings',['setting_key'=>$key,'setting_value'=>$value]);}}
        $this->logActivity('update','settings',0,'تحديث إعدادات النظام');$this->setFlash('success','تم حفظ الإعدادات');$this->redirect('settings');
    }
    public function users(): void {
        $users=$this->db->fetchAll("SELECT u.*,r.name as role_name FROM users u LEFT JOIN roles r ON u.role_id=r.id WHERE u.deleted_at IS NULL ORDER BY u.full_name");
        $roles=$this->db->fetchAll("SELECT * FROM roles ORDER BY name");
        $pageTitle='إدارة المستخدمين';$breadcrumbs=[['title'=>'الإعدادات','url'=>url('settings')],['title'=>'المستخدمين']];
        $this->render('domain/Settings/Views/users.php',compact('pageTitle','breadcrumbs','users','roles'));
    }
    public function createUser(): void {
        $roles=$this->db->fetchAll("SELECT * FROM roles ORDER BY name");
        $pageTitle='إضافة مستخدم';$breadcrumbs=[['title'=>'الإعدادات','url'=>url('settings')],['title'=>'إضافة مستخدم']];
        $this->render('domain/Settings/Views/create_user.php',compact('pageTitle','breadcrumbs','roles'));
    }
    public function storeUser(): void {
        if(!$this->isPost()){$this->redirect('settings','users');return;}
        $v=new Validator($_POST);$v->required('username','اسم المستخدم')->required('full_name','الاسم الكامل')->required('password','كلمة المرور');
        if($v->fails()){$this->setFlash('danger',$v->firstError());$this->redirect('settings','create_user');return;}
        $exists=$this->db->fetch("SELECT id FROM users WHERE username=?",[$this->input('username')]);
        if($exists){$this->setFlash('danger','اسم المستخدم مستخدم مسبقاً');$this->redirect('settings','create_user');return;}
        $this->db->insert('users',['username'=>$this->input('username'),'password'=>password_hash($this->input('password'),PASSWORD_DEFAULT),'full_name'=>$this->input('full_name'),'email'=>$this->input('email'),'phone'=>$this->input('phone'),'role_id'=>(int)$this->input('role_id',2),'is_active'=>1]);
        $this->setFlash('success','تم إضافة المستخدم');$this->redirect('settings','users');
    }
    public function toggleUser(): void {
        $id=(int)$this->query('id');$user=$this->db->fetch("SELECT * FROM users WHERE id=?",[$id]);if($user){$this->db->update('users',['is_active'=>$user['is_active']?0:1],'id=?',[$id]);}
        $this->redirect('settings','users');
    }
}
