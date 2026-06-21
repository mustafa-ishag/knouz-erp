<?php
class SalesOpportunityController extends Controller {
    public function index(): void {
        $result=$this->db->paginate("SELECT so.*,c.name as client_name,u.full_name as assigned_name FROM sales_opportunities so LEFT JOIN clients c ON so.client_id=c.id LEFT JOIN users u ON so.assigned_to=u.id WHERE so.deleted_at IS NULL ORDER BY so.created_at DESC",[],(int)$this->query('page',1),25);
        $pageTitle='الفرص البيعية'; $breadcrumbs=[['title'=>'الفرص البيعية']];
        $this->render('domain/SalesOpportunities/Views/index.php',compact('pageTitle','breadcrumbs','result'));
    }
    public function create(): void {
        $clients=$this->db->fetchAll("SELECT id,name FROM clients WHERE deleted_at IS NULL ORDER BY name");
        $employees=$this->db->fetchAll("SELECT id,full_name FROM users WHERE is_active=1 AND deleted_at IS NULL");
        $pageTitle='إضافة فرصة'; $breadcrumbs=[['title'=>'الفرص البيعية','url'=>url('opportunities')],['title'=>'إضافة']];
        $this->render('domain/SalesOpportunities/Views/create.php',compact('pageTitle','breadcrumbs','clients','employees'));
    }
    public function store(): void {
        if(!$this->isPost()){$this->redirect('opportunities');return;}
        $this->db->insert('sales_opportunities',[
            'client_id'=>$this->input('client_id'),
            'title'=>$this->input('title'),
            'expected_amount'=>(float)$this->input('expected_amount',0),
            'status'=>$this->input('status','new'),
            'probability'=>(int)$this->input('probability',10),
            'assigned_to'=>$this->input('assigned_to')?:null,
            'expected_close_date'=>$this->input('expected_close_date'),
            'description'=>$this->input('description'),
            'notes'=>$this->input('notes'),
            'created_by'=>$this->currentUser()['id']
        ]);
        $this->setFlash('success','تم إضافة الفرصة'); $this->redirect('opportunities');
    }
    public function edit(): void {
        $id=(int)$this->query('id');
        $opp=$this->db->fetch("SELECT * FROM sales_opportunities WHERE id=? AND deleted_at IS NULL",[$id]);
        if(!$opp){$this->redirect('opportunities');return;}
        $clients=$this->db->fetchAll("SELECT id,name FROM clients WHERE deleted_at IS NULL ORDER BY name");
        $employees=$this->db->fetchAll("SELECT id,full_name FROM users WHERE is_active=1 AND deleted_at IS NULL");
        $pageTitle='تعديل الفرصة'; $breadcrumbs=[['title'=>'الفرص البيعية','url'=>url('opportunities')],['title'=>'تعديل']];
        $this->render('domain/SalesOpportunities/Views/edit.php',compact('pageTitle','breadcrumbs','opp','clients','employees'));
    }
    public function update(): void {
        if(!$this->isPost()){$this->redirect('opportunities');return;}
        $id=(int)$this->input('id');
        $this->db->update('sales_opportunities',[
            'client_id'=>$this->input('client_id'),
            'title'=>$this->input('title'),
            'expected_amount'=>(float)$this->input('expected_amount',0),
            'status'=>$this->input('status'),
            'probability'=>(int)$this->input('probability'),
            'assigned_to'=>$this->input('assigned_to')?:null,
            'expected_close_date'=>$this->input('expected_close_date'),
            'description'=>$this->input('description'),
            'notes'=>$this->input('notes')
        ],'id=?',[$id]);
        $this->setFlash('success','تم التحديث'); $this->redirect('opportunities');
    }
    public function delete(): void {
        $id=(int)$this->query('id');
        $this->db->update('sales_opportunities',['deleted_at'=>date('Y-m-d H:i:s')],'id=?',[$id]);
        $this->setFlash('success','تم الحذف');$this->redirect('opportunities');
    }
}
