<?php
class TaskController extends Controller {
    public function index(): void {
        $page=(int)$this->query('page',1);$status=$this->query('status','');
        $where='t.deleted_at IS NULL';$params=[];
        if($status){$where.=" AND t.status=?";$params[]=$status;}
        $userId=$this->currentUser()['id'];$role=$this->currentUser()['role_id']??2;
        if($role>1){$where.=" AND (t.assigned_to=? OR t.created_by=?)";$params[]=$userId;$params[]=$userId;}
        $result=$this->db->paginate("SELECT t.*,u.full_name as assigned_name,c.name as client_name FROM tasks t LEFT JOIN users u ON t.assigned_to=u.id LEFT JOIN clients c ON t.client_id=c.id WHERE {$where} ORDER BY t.due_date ASC",$params,$page,25);
        $pageTitle='المهام';$breadcrumbs=[['title'=>'المهام']];$this->render('domain/Tasks/Views/index.php',compact('pageTitle','breadcrumbs','result','status'));
    }
    public function create(): void {
        $clients=$this->db->fetchAll("SELECT id,name FROM clients WHERE deleted_at IS NULL ORDER BY name");
        $employees=$this->db->fetchAll("SELECT id,full_name FROM users WHERE is_active=1 AND deleted_at IS NULL ORDER BY full_name");
        $pageTitle='إضافة مهمة';$breadcrumbs=[['title'=>'المهام','url'=>url('tasks')],['title'=>'إضافة']];
        $this->render('domain/Tasks/Views/create.php',compact('pageTitle','breadcrumbs','clients','employees'));
    }
    public function store(): void {
        if(!$this->isPost()){$this->redirect('tasks');return;}
        $this->db->insert('tasks',['title'=>$this->input('title'),'description'=>$this->input('description'),'client_id'=>$this->input('client_id')?:null,'assigned_to'=>$this->input('assigned_to')?:null,'priority'=>$this->input('priority','medium'),'status'=>'pending','due_date'=>$this->input('due_date'),'created_by'=>$this->currentUser()['id']]);
        $this->setFlash('success','تم إضافة المهمة');$this->redirect('tasks');
    }
    public function edit(): void {
        $id=(int)$this->query('id');$task=$this->db->fetch("SELECT * FROM tasks WHERE id=? AND deleted_at IS NULL",[$id]);
        if(!$task){$this->redirect('tasks');return;}
        $clients=$this->db->fetchAll("SELECT id,name FROM clients WHERE deleted_at IS NULL ORDER BY name");
        $employees=$this->db->fetchAll("SELECT id,full_name FROM users WHERE is_active=1 AND deleted_at IS NULL ORDER BY full_name");
        $pageTitle='تعديل المهمة';$breadcrumbs=[['title'=>'المهام','url'=>url('tasks')],['title'=>'تعديل']];
        $this->render('domain/Tasks/Views/edit.php',compact('pageTitle','breadcrumbs','task','clients','employees'));
    }
    public function update(): void {
        if(!$this->isPost()){$this->redirect('tasks');return;}$id=(int)$this->input('id');
        $this->db->update('tasks',['title'=>$this->input('title'),'description'=>$this->input('description'),'client_id'=>$this->input('client_id')?:null,'assigned_to'=>$this->input('assigned_to')?:null,'priority'=>$this->input('priority'),'status'=>$this->input('status'),'due_date'=>$this->input('due_date'),'completed_at'=>$this->input('status')==='completed'?date('Y-m-d H:i:s'):null],'id=?',[$id]);
        $this->setFlash('success','تم التحديث');$this->redirect('tasks');
    }
    public function delete(): void {$id=(int)$this->query('id');$this->db->update('tasks',['deleted_at'=>date('Y-m-d H:i:s')],'id=?',[$id]);$this->setFlash('success','تم الحذف');$this->redirect('tasks');}
}
