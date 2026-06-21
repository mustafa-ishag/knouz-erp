<?php
class EmployeeController extends Controller {
    public function index(): void {$users=$this->db->fetchAll("SELECT u.*,r.name as role_name FROM users u LEFT JOIN roles r ON u.role_id=r.id WHERE u.deleted_at IS NULL ORDER BY u.full_name");$pageTitle='الموظفون';$breadcrumbs=[['title'=>'الموظفون']];$this->render('domain/Employees/Views/index.php',compact('pageTitle','breadcrumbs','users'));}
    public function create(): void {$roles=$this->db->fetchAll("SELECT * FROM roles ORDER BY name");$pageTitle='إضافة موظف';$breadcrumbs=[['title'=>'الموظفون','url'=>url('employees')],['title'=>'إضافة']];$this->render('domain/Employees/Views/create.php',compact('pageTitle','breadcrumbs','roles'));}
    public function store(): void {
        if(!$this->isPost()){$this->redirect('employees');return;}
        $v=new Validator($_POST);$v->required('username','اسم المستخدم')->required('full_name','الاسم')->required('password','كلمة المرور');
        if($v->fails()){$this->setFlash('danger',$v->firstError());$this->redirect('employees','create');return;}
        $exists=$this->db->fetch("SELECT id FROM users WHERE username=?",[$this->input('username')]);
        if($exists){$this->setFlash('danger','اسم المستخدم مستخدم');$this->redirect('employees','create');return;}
        $this->db->insert('users',['username'=>$this->input('username'),'password'=>password_hash($this->input('password'),PASSWORD_DEFAULT),'full_name'=>$this->input('full_name'),'email'=>$this->input('email'),'phone'=>$this->input('phone'),'role_id'=>(int)$this->input('role_id',2),'is_active'=>1]);
        $this->setFlash('success','تم إضافة الموظف');$this->redirect('employees');
    }
    public function edit(): void {$id=(int)$this->query('id');$user=$this->db->fetch("SELECT * FROM users WHERE id=? AND deleted_at IS NULL",[$id]);if(!$user){$this->redirect('employees');return;}$roles=$this->db->fetchAll("SELECT * FROM roles ORDER BY name");$pageTitle='تعديل الموظف';$breadcrumbs=[['title'=>'الموظفون','url'=>url('employees')],['title'=>'تعديل']];$this->render('domain/Employees/Views/edit.php',compact('pageTitle','breadcrumbs','user','roles'));}
    public function update(): void {
        if(!$this->isPost()){$this->redirect('employees');return;}$id=(int)$this->input('id');
        $data=['full_name'=>$this->input('full_name'),'email'=>$this->input('email'),'phone'=>$this->input('phone'),'role_id'=>(int)$this->input('role_id'),'is_active'=>$this->input('is_active',0)?1:0];
        if($this->input('password')){$data['password']=password_hash($this->input('password'),PASSWORD_DEFAULT);}
        $this->db->update('users',$data,'id=?',[$id]);$this->setFlash('success','تم التحديث');$this->redirect('employees');
    }
    public function delete(): void {$id=(int)$this->query('id');$this->db->update('users',['deleted_at'=>date('Y-m-d H:i:s')],'id=?',[$id]);$this->setFlash('success','تم الحذف');$this->redirect('employees');}
    public function show(): void {$this->edit();}
}
