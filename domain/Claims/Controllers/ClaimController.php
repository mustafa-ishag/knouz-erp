<?php
class ClaimController extends Controller {
    private Claim $model;
    public function __construct() { parent::__construct(); $this->model = new Claim(); }

    public function index(): void { $page=(int)$this->query('page',1);$search=$this->query('search','');$status=$this->query('status','');$result=$this->model->getAllFiltered($page,25,$search,$status);$pageTitle='المطالبات المالية';$breadcrumbs=[['title'=>'المطالبات']];$this->render('domain/Claims/Views/index.php',compact('pageTitle','breadcrumbs','result','search','status')); }

    public function create(): void {
        $fromQuotation = (int)$this->query('from_quotation', 0); $quotation = null;
        if ($fromQuotation) { $qModel = new Quotation(); $quotation = $qModel->getWithItems($fromQuotation); }
        $clients = $this->db->fetchAll("SELECT id, name FROM clients WHERE deleted_at IS NULL ORDER BY name");
        $services = $this->db->fetchAll("SELECT id,name,default_price FROM services WHERE is_active=1 AND deleted_at IS NULL ORDER BY name");
        $pageTitle = 'إنشاء مطالبة'; $breadcrumbs = [['title'=>'المطالبات','url'=>url('claims')],['title'=>'إنشاء']];
        $this->render('domain/Claims/Views/create.php', compact('pageTitle','breadcrumbs','clients','quotation','services'));
    }

    public function store(): void {
        if (!$this->isPost()) { $this->redirect('claims'); return; }
        $data = ['claim_number'=>$this->model->generateNumber(),'client_id'=>$this->input('client_id'),'company_id'=>$this->input('company_id')?:null,'quotation_id'=>$this->input('quotation_id')?:null,'due_date'=>$this->input('due_date')?:date('Y-m-d',strtotime('+30 days')),'claim_percentage'=>(float)$this->input('claim_percentage',100),'subtotal'=>(float)$this->input('subtotal',0),'vat_rate'=>(float)$this->input('vat_rate',15),'vat_amount'=>(float)$this->input('vat_amount',0),'total'=>(float)$this->input('total',0),'paid_amount'=>0,'status'=>'draft','notes'=>$this->input('notes'),'created_by'=>$this->currentUser()['id']];
        $id = $this->model->create($data);
        if (!empty($_POST['items'])) { $this->model->saveItems($id, $_POST['items']); }
        $this->logActivity('create','claims',$id,"إنشاء مطالبة: {$data['claim_number']}");
        $this->setFlash('success','تم إنشاء المطالبة');$this->redirect('claims','show',['id'=>$id]);
    }

    public function edit(): void {
        $id=(int)$this->query('id');$claim=$this->model->getWithItems($id);if(!$claim){$this->setFlash('danger','غير موجودة');$this->redirect('claims');return;}
        $clients=$this->db->fetchAll("SELECT id,name FROM clients WHERE deleted_at IS NULL ORDER BY name");
        $companies=$claim['client_id']?$this->db->fetchAll("SELECT id,name_ar FROM companies WHERE client_id=? AND deleted_at IS NULL",[$claim['client_id']]):[]; 
        $services=$this->db->fetchAll("SELECT id,name,default_price FROM services WHERE is_active=1 AND deleted_at IS NULL ORDER BY name");
        $pageTitle='تعديل المطالبة';$breadcrumbs=[['title'=>'المطالبات','url'=>url('claims')],['title'=>'تعديل']];
        $this->render('domain/Claims/Views/edit.php',compact('pageTitle','breadcrumbs','claim','clients','companies','services'));
    }

    public function update(): void {
        if(!$this->isPost()){$this->redirect('claims');return;}$id=(int)$this->input('id');
        $data=['client_id'=>$this->input('client_id'),'company_id'=>$this->input('company_id')?:null,'due_date'=>$this->input('due_date'),'claim_percentage'=>(float)$this->input('claim_percentage',100),'subtotal'=>(float)$this->input('subtotal',0),'vat_rate'=>(float)$this->input('vat_rate',15),'vat_amount'=>(float)$this->input('vat_amount',0),'total'=>(float)$this->input('total',0),'status'=>$this->input('status','draft'),'notes'=>$this->input('notes')];
        $this->model->update($id,$data);if(!empty($_POST['items'])){$this->model->saveItems($id,$_POST['items']);}
        $this->setFlash('success','تم التحديث');$this->redirect('claims','show',['id'=>$id]);
    }

    public function delete(): void { $id=(int)$this->query('id');$c=$this->model->find($id);if($c){$this->model->delete($id);$this->setFlash('success','تم الحذف');}$this->redirect('claims'); }

    public function show(): void {
        $id=(int)$this->query('id');$claim=$this->model->getWithItems($id);if(!$claim){$this->setFlash('danger','غير موجودة');$this->redirect('claims');return;}
        $settings=[];$rows=$this->db->fetchAll("SELECT setting_key,setting_value FROM settings");foreach($rows as $r)$settings[$r['setting_key']]=$r['setting_value'];
        $pageTitle='مطالبة '.$claim['claim_number'];$breadcrumbs=[['title'=>'المطالبات','url'=>url('claims')],['title'=>$claim['claim_number']]];
        $this->render('domain/Claims/Views/show.php',compact('pageTitle','breadcrumbs','claim','settings'));
    }

    public function printClaim(): void { $this->show(); }
}
