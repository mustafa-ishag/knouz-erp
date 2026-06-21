<?php
class QuotationController extends Controller
{
    private Quotation $model;
    public function __construct() { parent::__construct(); $this->model = new Quotation(); }

    public function index(): void
    {
        $page = (int)$this->query('page', 1); $search = $this->query('search', ''); $status = $this->query('status', '');
        $result = $this->model->getAllFiltered($page, 25, $search, $status);
        $pageTitle = 'عروض الأسعار'; $breadcrumbs = [['title' => 'عروض الأسعار']];
        $this->render('domain/Quotations/Views/index.php', compact('pageTitle', 'breadcrumbs', 'result', 'search', 'status'));
    }

    public function create(): void
    {
        $clients = $this->db->fetchAll("SELECT id, name FROM clients WHERE deleted_at IS NULL ORDER BY name");
        $services = (new Service())->getActive();
        $settings = $this->getSettings();
        $pageTitle = 'إنشاء عرض سعر'; $breadcrumbs = [['title' => 'عروض الأسعار', 'url' => url('quotations')], ['title' => 'إنشاء']];
        $this->render('domain/Quotations/Views/create.php', compact('pageTitle', 'breadcrumbs', 'clients', 'services', 'settings'));
    }

    public function store(): void
    {
        if (!$this->isPost()) { $this->redirect('quotations'); return; }
        $v = new Validator($_POST); $v->required('client_id', 'العميل');
        if ($v->fails()) { $this->setFlash('danger', $v->firstError()); $this->redirect('quotations', 'create'); return; }

        $data = ['quotation_number' => $this->model->generateNumber(), 'client_id' => $this->input('client_id'), 'company_id' => $this->input('company_id') ?: null, 'quotation_date' => $this->input('quotation_date') ?: date('Y-m-d'), 'validity_date' => $this->input('validity_date') ?: date('Y-m-d', strtotime('+30 days')), 'subtotal' => (float)$this->input('subtotal', 0), 'vat_rate' => (float)$this->input('vat_rate', 15), 'vat_amount' => (float)$this->input('vat_amount', 0), 'discount' => (float)$this->input('discount', 0), 'total' => (float)$this->input('total', 0), 'payment_terms' => $this->input('payment_terms'), 'status' => 'draft', 'notes' => $this->input('notes'), 'created_by' => $this->currentUser()['id']];
        $id = $this->model->create($data);
        if (!empty($_POST['items'])) { $this->model->saveItems($id, $_POST['items']); }
        $this->logActivity('create', 'quotations', $id, "إنشاء عرض سعر: {$data['quotation_number']}");
        $this->setFlash('success', 'تم إنشاء عرض السعر'); $this->redirect('quotations', 'show', ['id' => $id]);
    }

    public function edit(): void
    {
        $id = (int)$this->query('id'); $quotation = $this->model->getWithItems($id);
        if (!$quotation) { $this->setFlash('danger', 'غير موجود'); $this->redirect('quotations'); return; }
        $clients = $this->db->fetchAll("SELECT id, name FROM clients WHERE deleted_at IS NULL ORDER BY name");
        $services = (new Service())->getActive();
        $companies = $quotation['client_id'] ? $this->db->fetchAll("SELECT id, name_ar FROM companies WHERE client_id = ? AND deleted_at IS NULL", [$quotation['client_id']]) : [];
        $pageTitle = 'تعديل عرض السعر'; $breadcrumbs = [['title' => 'عروض الأسعار', 'url' => url('quotations')], ['title' => 'تعديل']];
        $this->render('domain/Quotations/Views/edit.php', compact('pageTitle', 'breadcrumbs', 'quotation', 'clients', 'services', 'companies'));
    }

    public function update(): void
    {
        if (!$this->isPost()) { $this->redirect('quotations'); return; }
        $id = (int)$this->input('id');
        $data = ['client_id'=>$this->input('client_id'), 'company_id'=>$this->input('company_id')?:null, 'quotation_date'=>$this->input('quotation_date'), 'validity_date'=>$this->input('validity_date'), 'subtotal'=>(float)$this->input('subtotal',0), 'vat_rate'=>(float)$this->input('vat_rate',15), 'vat_amount'=>(float)$this->input('vat_amount',0), 'discount'=>(float)$this->input('discount',0), 'total'=>(float)$this->input('total',0), 'payment_terms'=>$this->input('payment_terms'), 'notes'=>$this->input('notes')];
        $this->model->update($id, $data);
        if (!empty($_POST['items'])) { $this->model->saveItems($id, $_POST['items']); }
        $this->logActivity('update', 'quotations', $id, "تعديل عرض سعر");
        $this->setFlash('success', 'تم التحديث'); $this->redirect('quotations', 'show', ['id' => $id]);
    }

    public function delete(): void
    {
        $id = (int)$this->query('id'); $q = $this->model->find($id);
        if ($q) { $this->model->delete($id); $this->setFlash('success', 'تم الحذف'); }
        $this->redirect('quotations');
    }

    public function show(): void
    {
        $id = (int)$this->query('id'); $quotation = $this->model->getWithItems($id);
        if (!$quotation) { $this->setFlash('danger', 'غير موجود'); $this->redirect('quotations'); return; }
        $settings = $this->getSettings();
        $pageTitle = 'عرض سعر ' . $quotation['quotation_number']; $breadcrumbs = [['title' => 'عروض الأسعار', 'url' => url('quotations')], ['title' => $quotation['quotation_number']]];
        $this->render('domain/Quotations/Views/show.php', compact('pageTitle', 'breadcrumbs', 'quotation', 'settings'));
    }

    public function approve(): void
    {
        $id = (int)$this->query('id');
        $this->model->update($id, ['status' => 'approved', 'approved_at' => date('Y-m-d H:i:s')]);
        $this->logActivity('update', 'quotations', $id, 'اعتماد عرض سعر');
        $this->setFlash('success', 'تم اعتماد عرض السعر'); $this->redirect('quotations', 'show', ['id' => $id]);
    }

    public function printQuotation(): void { $this->show(); }

    public function toClaim(): void
    {
        $id = (int)$this->query('id'); $q = $this->model->getWithItems($id);
        if (!$q) { $this->redirect('quotations'); return; }
        header('Location: ' . url('claims', 'create', ['from_quotation' => $id])); exit;
    }

    private function getSettings(): array
    {
        $settings = [];
        $rows = $this->db->fetchAll("SELECT setting_key, setting_value FROM settings");
        foreach ($rows as $r) $settings[$r['setting_key']] = $r['setting_value'];
        return $settings;
    }
}
