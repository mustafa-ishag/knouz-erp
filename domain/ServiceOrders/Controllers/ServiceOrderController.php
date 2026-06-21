<?php
class ServiceOrderController extends Controller
{
    private ServiceOrder $model;
    public function __construct() { parent::__construct(); $this->model = new ServiceOrder(); }

    public function index(): void
    {
        $page = (int)$this->query('page', 1); $search = $this->query('search', ''); $status = $this->query('status', ''); $clientId = (int)$this->query('client_id', 0);
        $result = $this->model->getAllFiltered($page, 25, $search, $status, $clientId);
        $pageTitle = 'طلبات الخدمات'; $breadcrumbs = [['title' => 'الطلبات']];
        $this->render('domain/ServiceOrders/Views/index.php', compact('pageTitle', 'breadcrumbs', 'result', 'search', 'status', 'clientId'));
    }

    public function create(): void
    {
        $clientId = (int)$this->query('client_id', 0); $companyId = (int)$this->query('company_id', 0);
        $clients = $this->db->fetchAll("SELECT id, name FROM clients WHERE deleted_at IS NULL ORDER BY name");
        $services = (new Service())->getActive();
        $employees = $this->db->fetchAll("SELECT id, full_name FROM users WHERE is_active = 1 AND deleted_at IS NULL ORDER BY full_name");
        $companies = $clientId ? $this->db->fetchAll("SELECT id, name_ar FROM companies WHERE client_id = ? AND deleted_at IS NULL", [$clientId]) : [];
        $pageTitle = 'إضافة طلب خدمة'; $breadcrumbs = [['title' => 'الطلبات', 'url' => url('orders')], ['title' => 'إضافة']];
        $this->render('domain/ServiceOrders/Views/create.php', compact('pageTitle', 'breadcrumbs', 'clients', 'services', 'employees', 'companies', 'clientId', 'companyId'));
    }

    public function store(): void
    {
        if (!$this->isPost()) { $this->redirect('orders'); return; }
        $v = new Validator($_POST); $v->required('client_id', 'العميل')->required('service_id', 'الخدمة');
        if ($v->fails()) { $this->setFlash('danger', $v->firstError()); $this->redirect('orders', 'create'); return; }
        $service = $this->db->fetch("SELECT * FROM services WHERE id = ?", [$this->input('service_id')]);
        $data = ['order_number' => $this->model->generateNumber(), 'client_id' => $this->input('client_id'), 'company_id' => $this->input('company_id') ?: null, 'service_id' => $this->input('service_id'), 'description' => $this->input('description') ?: ($service['name'] ?? ''), 'price' => (float)$this->input('price', $service['default_price'] ?? 0), 'cost' => (float)$this->input('cost', $service['default_cost'] ?? 0), 'status' => 'new', 'assigned_to' => $this->input('assigned_to') ?: null, 'start_date' => $this->input('start_date') ?: date('Y-m-d'), 'due_date' => $this->input('due_date') ?: date('Y-m-d', strtotime('+' . ($service['execution_days'] ?? 3) . ' days')), 'notes' => $this->input('notes'), 'created_by' => $this->currentUser()['id']];
        $id = $this->model->create($data);
        $this->model->addStatusHistory($id, '', 'new', 'إنشاء الطلب', $this->currentUser()['id']);
        $this->logActivity('create', 'orders', $id, "إنشاء طلب خدمة: {$data['order_number']}");
        $this->setFlash('success', 'تم إنشاء طلب الخدمة بنجاح'); $this->redirect('orders', 'show', ['id' => $id]);
    }

    public function edit(): void
    {
        $id = (int)$this->query('id'); $order = $this->model->find($id);
        if (!$order) { $this->setFlash('danger', 'الطلب غير موجود'); $this->redirect('orders'); return; }
        $clients = $this->db->fetchAll("SELECT id, name FROM clients WHERE deleted_at IS NULL ORDER BY name");
        $services = (new Service())->getActive();
        $employees = $this->db->fetchAll("SELECT id, full_name FROM users WHERE is_active = 1 AND deleted_at IS NULL");
        $companies = $order['client_id'] ? $this->db->fetchAll("SELECT id, name_ar FROM companies WHERE client_id = ? AND deleted_at IS NULL", [$order['client_id']]) : [];
        $pageTitle = 'تعديل الطلب'; $breadcrumbs = [['title' => 'الطلبات', 'url' => url('orders')], ['title' => 'تعديل']];
        $this->render('domain/ServiceOrders/Views/edit.php', compact('pageTitle', 'breadcrumbs', 'order', 'clients', 'services', 'employees', 'companies'));
    }

    public function update(): void
    {
        if (!$this->isPost()) { $this->redirect('orders'); return; }
        $id = (int)$this->input('id'); $order = $this->model->find($id);
        if (!$order) { $this->setFlash('danger', 'الطلب غير موجود'); $this->redirect('orders'); return; }
        $oldStatus = $order['status']; $newStatus = $this->input('status', $oldStatus);
        $data = ['client_id'=>$this->input('client_id'), 'company_id'=>$this->input('company_id')?:null, 'service_id'=>$this->input('service_id'), 'description'=>$this->input('description'), 'price'=>(float)$this->input('price',0), 'cost'=>(float)$this->input('cost',0), 'status'=>$newStatus, 'assigned_to'=>$this->input('assigned_to')?:null, 'start_date'=>$this->input('start_date'), 'due_date'=>$this->input('due_date'), 'platform_ref'=>$this->input('platform_ref'), 'notes'=>$this->input('notes')];
        if ($newStatus === 'completed' && $oldStatus !== 'completed') { $data['completed_date'] = date('Y-m-d H:i:s'); }
        $this->model->update($id, $data);
        if ($oldStatus !== $newStatus) { $this->model->addStatusHistory($id, $oldStatus, $newStatus, $this->input('status_notes'), $this->currentUser()['id']); }
        $this->logActivity('update', 'orders', $id, "تحديث طلب: {$order['order_number']}");
        $this->setFlash('success', 'تم تحديث الطلب'); $this->redirect('orders', 'show', ['id' => $id]);
    }

    public function delete(): void
    {
        $id = (int)$this->query('id'); $o = $this->model->find($id);
        if ($o) { $this->model->delete($id); $this->logActivity('delete', 'orders', $id, "حذف طلب: {$o['order_number']}"); $this->setFlash('success', 'تم حذف الطلب'); }
        $this->redirect('orders');
    }

    public function show(): void
    {
        $id = (int)$this->query('id'); $order = $this->model->getWithDetails($id);
        if (!$order) { $this->setFlash('danger', 'الطلب غير موجود'); $this->redirect('orders'); return; }
        $pageTitle = 'طلب ' . $order['order_number']; $breadcrumbs = [['title' => 'الطلبات', 'url' => url('orders')], ['title' => $order['order_number']]];
        $this->render('domain/ServiceOrders/Views/show.php', compact('pageTitle', 'breadcrumbs', 'order'));
    }

    public function history(): void { $this->show(); }
    public function import(): void { $this->setFlash('info', 'ميزة الاستيراد قيد التطوير'); $this->redirect('orders'); }
}
