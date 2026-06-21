<?php
class PaymentController extends Controller {
    private Payment $model;
    public function __construct(){parent::__construct();$this->model=new Payment();}
    public function index(): void {$p=(int)$this->query('page',1);$s=$this->query('search','');$r=$this->model->getAllFiltered($p,25,$s);$pageTitle='المدفوعات';$breadcrumbs=[['title'=>'المدفوعات']];$this->render('domain/Payments/Views/index.php',['pageTitle'=>$pageTitle,'breadcrumbs'=>$breadcrumbs,'result'=>$r,'search'=>$s]);}
    public function create(): void {
        $claimId=(int)$this->query('claim_id',0);$invoiceId=(int)$this->query('invoice_id',0);
        $clients=$this->db->fetchAll("SELECT id,name FROM clients WHERE deleted_at IS NULL ORDER BY name");
        $claim=$claimId?$this->db->fetch("SELECT cl.*,c.name as client_name FROM claims cl LEFT JOIN clients c ON cl.client_id=c.id WHERE cl.id=?",[$claimId]):null;
        $invoice=$invoiceId?$this->db->fetch("SELECT i.*,c.name as client_name FROM invoices i LEFT JOIN clients c ON i.client_id=c.id WHERE i.id=?",[$invoiceId]):null;
        $pageTitle='تسجيل دفعة';$breadcrumbs=[['title'=>'المدفوعات','url'=>url('payments')],['title'=>'تسجيل دفعة']];
        $this->render('domain/Payments/Views/create.php',compact('pageTitle','breadcrumbs','clients','claim','invoice','claimId','invoiceId'));
    }
    public function store(): void {
        if(!$this->isPost()){$this->redirect('payments');return;}
        $v=new Validator($_POST);$v->required('client_id','العميل')->required('amount','المبلغ');
        if($v->fails()){$this->setFlash('danger',$v->firstError());$this->redirect('payments','create');return;}
        $data=['payment_number'=>$this->model->generateNumber(),'client_id'=>$this->input('client_id'),'claim_id'=>$this->input('claim_id')?:null,'invoice_id'=>$this->input('invoice_id')?:null,'amount'=>(float)$this->input('amount',0),'payment_date'=>$this->input('payment_date')?:date('Y-m-d'),'payment_type'=>$this->input('payment_type','bank_transfer'),'reference_number'=>$this->input('reference_number'),'notes'=>$this->input('notes'),'created_by'=>$this->currentUser()['id']];
        $id=$this->model->create($data);
        if($data['claim_id']){$claimModel=new Claim();$claimModel->updatePaidAmount($data['claim_id']);}
        if($data['invoice_id']){$invModel=new Invoice();$invModel->updatePaidAmount($data['invoice_id']);}
        $this->logActivity('create','payments',$id,"تسجيل دفعة: {$data['payment_number']}");$this->setFlash('success','تم تسجيل الدفعة');$this->redirect('payments');
    }
    public function edit(): void {$id=(int)$this->query('id');$payment=$this->model->find($id);if(!$payment){$this->redirect('payments');return;}$clients=$this->db->fetchAll("SELECT id,name FROM clients WHERE deleted_at IS NULL ORDER BY name");$pageTitle='تعديل الدفعة';$breadcrumbs=[['title'=>'المدفوعات','url'=>url('payments')],['title'=>'تعديل']];$this->render('domain/Payments/Views/edit.php',compact('pageTitle','breadcrumbs','payment','clients'));}
    public function update(): void {if(!$this->isPost()){$this->redirect('payments');return;}$id=(int)$this->input('id');$data=['client_id'=>$this->input('client_id'),'amount'=>(float)$this->input('amount',0),'payment_date'=>$this->input('payment_date'),'payment_type'=>$this->input('payment_type'),'reference_number'=>$this->input('reference_number'),'notes'=>$this->input('notes')];$this->model->update($id,$data);$this->setFlash('success','تم التحديث');$this->redirect('payments');}
    public function delete(): void {$id=(int)$this->query('id');$p=$this->model->find($id);if($p){$this->model->delete($id);if($p['claim_id']){$clm=new Claim();$clm->updatePaidAmount($p['claim_id']);}if($p['invoice_id']){$inv=new Invoice();$inv->updatePaidAmount($p['invoice_id']);}$this->setFlash('success','تم الحذف');}$this->redirect('payments');}
}
