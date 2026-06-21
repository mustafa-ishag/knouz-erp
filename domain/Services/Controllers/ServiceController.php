<?php
class ServiceController extends Controller
{
    private Service $serviceModel;
    public function __construct() { parent::__construct(); $this->serviceModel = new Service(); }

    public function index(): void
    {
        $page = (int)$this->query('page', 1); $search = $this->query('search', ''); $catId = (int)$this->query('category_id', 0);
        $result = $this->serviceModel->getAllWithCategory($page, 25, $search, $catId);
        $categories = $this->serviceModel->getCategories();
        $pageTitle = 'مكتبة الخدمات'; $breadcrumbs = [['title' => 'الخدمات']];
        $this->render('domain/Services/Views/index.php', compact('pageTitle', 'breadcrumbs', 'result', 'search', 'catId', 'categories'));
    }

    public function create(): void
    {
        $categories = $this->serviceModel->getCategories();
        $pageTitle = 'إضافة خدمة'; $breadcrumbs = [['title' => 'الخدمات', 'url' => url('services')], ['title' => 'إضافة']];
        $this->render('domain/Services/Views/create.php', compact('pageTitle', 'breadcrumbs', 'categories'));
    }

    public function store(): void
    {
        if (!$this->isPost()) { $this->redirect('services'); return; }
        $v = new Validator($_POST); $v->required('name', 'الاسم')->required('category_id', 'التصنيف');
        if ($v->fails()) { $this->setFlash('danger', $v->firstError()); $this->redirect('services', 'create'); return; }
        $data = ['category_id'=>$this->input('category_id'),'name'=>$this->input('name'),'description'=>$this->input('description'),'platform'=>$this->input('platform'),'execution_days'=>(int)$this->input('execution_days',3),'default_price'=>(float)$this->input('default_price',0),'default_cost'=>(float)$this->input('default_cost',0),'is_active'=>$this->input('is_active',1)?1:0,'requirements'=>$this->input('requirements')];
        $id = $this->serviceModel->create($data);
        $this->logActivity('create', 'services', $id, "إضافة خدمة: {$data['name']}");
        $this->setFlash('success', 'تم إضافة الخدمة بنجاح'); $this->redirect('services');
    }

    public function edit(): void
    {
        $id = (int)$this->query('id'); $service = $this->serviceModel->find($id);
        if (!$service) { $this->setFlash('danger', 'الخدمة غير موجودة'); $this->redirect('services'); return; }
        $categories = $this->serviceModel->getCategories();
        $pageTitle = 'تعديل الخدمة'; $breadcrumbs = [['title' => 'الخدمات', 'url' => url('services')], ['title' => 'تعديل']];
        $this->render('domain/Services/Views/edit.php', compact('pageTitle', 'breadcrumbs', 'service', 'categories'));
    }

    public function update(): void
    {
        if (!$this->isPost()) { $this->redirect('services'); return; }
        $id = (int)$this->input('id');
        $data = ['category_id'=>$this->input('category_id'),'name'=>$this->input('name'),'description'=>$this->input('description'),'platform'=>$this->input('platform'),'execution_days'=>(int)$this->input('execution_days',3),'default_price'=>(float)$this->input('default_price',0),'default_cost'=>(float)$this->input('default_cost',0),'is_active'=>$this->input('is_active',0)?1:0,'requirements'=>$this->input('requirements')];
        $this->serviceModel->update($id, $data);
        $this->logActivity('update', 'services', $id, "تعديل خدمة: {$data['name']}");
        $this->setFlash('success', 'تم تحديث الخدمة'); $this->redirect('services');
    }

    public function delete(): void
    {
        $id = (int)$this->query('id'); $s = $this->serviceModel->find($id);
        if ($s) { $this->serviceModel->delete($id); $this->logActivity('delete', 'services', $id, "حذف خدمة: {$s['name']}"); $this->setFlash('success', 'تم حذف الخدمة'); }
        $this->redirect('services');
    }
}
