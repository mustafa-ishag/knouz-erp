<?php
class CompanyController extends Controller
{
    private Company $companyModel;

    public function __construct()
    {
        parent::__construct();
        $this->companyModel = new Company();
    }

    public function index(): void
    {
        $page = (int)$this->query('page', 1);
        $search = $this->query('search', '');
        $clientId = (int)$this->query('client_id', 0);
        $result = $this->companyModel->getAllWithClient($page, 25, $search, $clientId);
        $pageTitle = 'إدارة الشركات';
        $breadcrumbs = [['title' => 'الشركات']];
        $this->render('domain/Companies/Views/index.php', compact('pageTitle', 'breadcrumbs', 'result', 'search', 'clientId'));
    }

    public function create(): void
    {
        $clientId = (int)$this->query('client_id', 0);
        $clients = $this->db->fetchAll("SELECT id, name FROM clients WHERE deleted_at IS NULL ORDER BY name");
        $pageTitle = 'إضافة شركة';
        $breadcrumbs = [['title' => 'الشركات', 'url' => url('companies')], ['title' => 'إضافة شركة']];
        $this->render('domain/Companies/Views/create.php', compact('pageTitle', 'breadcrumbs', 'clients', 'clientId'));
    }

    public function store(): void
    {
        if (!$this->isPost()) { $this->redirect('companies'); return; }
        $v = new Validator($_POST);
        $v->required('client_id', 'العميل')->required('name_ar', 'الاسم العربي');
        if ($v->fails()) { $this->setFlash('danger', $v->firstError()); $this->redirect('companies', 'create'); return; }

        $data = [];
        foreach (['client_id','name_ar','name_en','cr_number','unified_number','distinctive_number','qiwa_number','activity','city','address','email','phone','cr_issue_date','cr_expiry_date','notes'] as $f) {
            $val = $this->input($f);
            $data[$f] = ($val !== '' && $val !== null) ? $val : null;
        }
        $data['created_by'] = $this->currentUser()['id'];
        $id = $this->companyModel->create($data);
        $this->logActivity('create', 'companies', $id, "إضافة شركة: {$data['name_ar']}");
        $this->setFlash('success', 'تم إضافة الشركة بنجاح');
        $this->redirect('companies', 'show', ['id' => $id]);
    }

    public function edit(): void
    {
        $id = (int)$this->query('id');
        $company = $this->companyModel->find($id);
        if (!$company) { $this->setFlash('danger', 'الشركة غير موجودة'); $this->redirect('companies'); return; }
        $clients = $this->db->fetchAll("SELECT id, name FROM clients WHERE deleted_at IS NULL ORDER BY name");
        $pageTitle = 'تعديل الشركة: ' . $company['name_ar'];
        $breadcrumbs = [['title' => 'الشركات', 'url' => url('companies')], ['title' => 'تعديل']];
        $this->render('domain/Companies/Views/edit.php', compact('pageTitle', 'breadcrumbs', 'company', 'clients'));
    }

    public function update(): void
    {
        if (!$this->isPost()) { $this->redirect('companies'); return; }
        $id = (int)$this->input('id');
        $company = $this->companyModel->find($id);
        if (!$company) { $this->setFlash('danger', 'الشركة غير موجودة'); $this->redirect('companies'); return; }

        $data = [];
        foreach (['client_id','name_ar','name_en','cr_number','unified_number','distinctive_number','qiwa_number','activity','city','address','email','phone','cr_issue_date','cr_expiry_date','notes'] as $f) {
            $val = $this->input($f);
            $data[$f] = ($val !== '' && $val !== null) ? $val : null;
        }
        $this->companyModel->update($id, $data);
        $this->logActivity('update', 'companies', $id, "تعديل شركة: {$data['name_ar']}");
        $this->setFlash('success', 'تم تحديث بيانات الشركة');
        $this->redirect('companies', 'show', ['id' => $id]);
    }

    public function delete(): void
    {
        $id = (int)$this->query('id');
        $company = $this->companyModel->find($id);
        if ($company) {
            $this->companyModel->delete($id);
            $this->logActivity('delete', 'companies', $id, "حذف شركة: {$company['name_ar']}");
            $this->setFlash('success', 'تم حذف الشركة');
        }
        $this->redirect('companies');
    }

    public function show(): void
    {
        $id = (int)$this->query('id');
        $company = $this->companyModel->find($id);
        if (!$company) { $this->setFlash('danger', 'الشركة غير موجودة'); $this->redirect('companies'); return; }
        $company['client'] = $this->db->fetch("SELECT * FROM clients WHERE id = ?", [$company['client_id']]);
        $company['documents'] = $this->companyModel->getDocuments($id);
        $company['orders'] = $this->db->fetchAll("SELECT so.*, s.name as service_name FROM service_orders so LEFT JOIN services s ON so.service_id = s.id WHERE so.company_id = ? AND so.deleted_at IS NULL ORDER BY so.created_at DESC LIMIT 10", [$id]);
        $pageTitle = $company['name_ar'];
        $breadcrumbs = [['title' => 'الشركات', 'url' => url('companies')], ['title' => $company['name_ar']]];
        $this->render('domain/Companies/Views/show.php', compact('pageTitle', 'breadcrumbs', 'company'));
    }

    // API endpoint for loading companies by client
    public function byClient(): void
    {
        $clientId = (int)$this->query('client_id');
        $companies = $this->companyModel->getByClient($clientId);
        $this->json(['companies' => $companies]);
    }
}
