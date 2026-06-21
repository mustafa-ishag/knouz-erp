<?php
class InvoiceController extends Controller {
    private Invoice $model;
    public function __construct(){parent::__construct();$this->model=new Invoice();}
    public function index(): void {$p=(int)$this->query('page',1);$s=$this->query('search','');$st=$this->query('status','');$r=$this->model->getAllFiltered($p,25,$s,$st);$pageTitle='الفواتير';$breadcrumbs=[['title'=>'الفواتير']];$this->render('domain/Invoices/Views/index.php',['pageTitle'=>$pageTitle,'breadcrumbs'=>$breadcrumbs,'result'=>$r,'search'=>$s,'status'=>$st]);}
    public function create(): void {$clients=$this->db->fetchAll("SELECT id,name FROM clients WHERE deleted_at IS NULL ORDER BY name");$services=$this->db->fetchAll("SELECT id,name,default_price FROM services WHERE is_active=1 AND deleted_at IS NULL ORDER BY name");$pageTitle='إنشاء فاتورة';$breadcrumbs=[['title'=>'الفواتير','url'=>url('invoices')],['title'=>'إنشاء']];$this->render('domain/Invoices/Views/create.php',compact('pageTitle','breadcrumbs','clients','services'));}
    public function store(): void {
        if(!$this->isPost()){$this->redirect('invoices');return;}
        $data=['invoice_number'=>$this->model->generateNumber(),'client_id'=>$this->input('client_id'),'company_id'=>$this->input('company_id')?:null,'claim_id'=>$this->input('claim_id')?:null,'invoice_date'=>$this->input('invoice_date')?:date('Y-m-d'),'due_date'=>$this->input('due_date')?:date('Y-m-d',strtotime('+30 days')),'subtotal'=>(float)$this->input('subtotal',0),'vat_rate'=>(float)$this->input('vat_rate',15),'vat_amount'=>(float)$this->input('vat_amount',0),'discount'=>(float)$this->input('discount',0),'total'=>(float)$this->input('total',0),'paid_amount'=>0,'status'=>'unpaid','notes'=>$this->input('notes'),'created_by'=>$this->currentUser()['id']];
        $id=$this->model->create($data);if(!empty($_POST['items'])){$this->model->saveItems($id,$_POST['items']);}
        $this->logActivity('create','invoices',$id,"فاتورة: {$data['invoice_number']}");$this->setFlash('success','تم إنشاء الفاتورة');$this->redirect('invoices','show',['id'=>$id]);
    }
    public function edit(): void {$id=(int)$this->query('id');$invoice=$this->model->getWithItems($id);if(!$invoice){$this->redirect('invoices');return;}$clients=$this->db->fetchAll("SELECT id,name FROM clients WHERE deleted_at IS NULL ORDER BY name");$companies=$invoice['client_id']?$this->db->fetchAll("SELECT id,name_ar FROM companies WHERE client_id=? AND deleted_at IS NULL",[$invoice['client_id']]):[];$services=$this->db->fetchAll("SELECT id,name,default_price FROM services WHERE is_active=1 AND deleted_at IS NULL ORDER BY name");$pageTitle='تعديل الفاتورة';$breadcrumbs=[['title'=>'الفواتير','url'=>url('invoices')],['title'=>'تعديل']];$this->render('domain/Invoices/Views/edit.php',compact('pageTitle','breadcrumbs','invoice','clients','companies','services'));}
    public function update(): void {
        if(!$this->isPost()){$this->redirect('invoices');return;}$id=(int)$this->input('id');
        $data=['client_id'=>$this->input('client_id'),'company_id'=>$this->input('company_id')?:null,'invoice_date'=>$this->input('invoice_date'),'due_date'=>$this->input('due_date'),'subtotal'=>(float)$this->input('subtotal',0),'vat_rate'=>(float)$this->input('vat_rate',15),'vat_amount'=>(float)$this->input('vat_amount',0),'discount'=>(float)$this->input('discount',0),'total'=>(float)$this->input('total',0),'status'=>$this->input('status'),'notes'=>$this->input('notes')];
        $this->model->update($id,$data);if(!empty($_POST['items'])){$this->model->saveItems($id,$_POST['items']);}$this->setFlash('success','تم التحديث');$this->redirect('invoices','show',['id'=>$id]);
    }
    public function delete(): void {$id=(int)$this->query('id');$i=$this->model->find($id);if($i){$this->model->delete($id);$this->setFlash('success','تم الحذف');}$this->redirect('invoices');}
    public function show(): void {
        $id=(int)$this->query('id');$invoice=$this->model->getWithItems($id);if(!$invoice){$this->redirect('invoices');return;}
        $settings=[];$rows=$this->db->fetchAll("SELECT setting_key,setting_value FROM settings");foreach($rows as $r)$settings[$r['setting_key']]=$r['setting_value'];
        $pageTitle='فاتورة '.$invoice['invoice_number'];$breadcrumbs=[['title'=>'الفواتير','url'=>url('invoices')],['title'=>$invoice['invoice_number']]];$this->render('domain/Invoices/Views/show.php',compact('pageTitle','breadcrumbs','invoice','settings'));
    }
    public function printInvoice(): void { $this->show(); }
}
