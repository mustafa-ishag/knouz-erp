<?php
/**
 * متحكم العملاء
 */

class ClientController extends Controller
{
    private Client $clientModel;

    public function __construct()
    {
        parent::__construct();
        $this->clientModel = new Client();
    }

    /**
     * قائمة العملاء
     */
    public function index(): void
    {
        $page = (int)$this->query('page', 1);
        $search = $this->query('search', '');
        $city = $this->query('city', '');
        
        $result = $this->clientModel->getAllWithStats($page, 25, $search, $city);
        $cities = $this->clientModel->getCities();
        
        $pageTitle = 'إدارة العملاء';
        $breadcrumbs = [['title' => 'العملاء']];
        
        $this->render('domain/Clients/Views/index.php', compact(
            'pageTitle', 'breadcrumbs', 'result', 'search', 'city', 'cities'
        ));
    }

    /**
     * صفحة إضافة عميل
     */
    public function create(): void
    {
        $pageTitle = 'إضافة عميل جديد';
        $breadcrumbs = [
            ['title' => 'العملاء', 'url' => url('clients')],
            ['title' => 'إضافة عميل']
        ];
        $employees = $this->db->fetchAll("SELECT id, full_name FROM users WHERE is_active = 1 AND deleted_at IS NULL ORDER BY full_name");
        
        $this->render('domain/Clients/Views/create.php', compact('pageTitle', 'breadcrumbs', 'employees'));
    }

    /**
     * حفظ عميل جديد
     */
    public function store(): void
    {
        if (!$this->isPost()) {
            $this->redirect('clients');
            return;
        }

        $validator = new Validator($_POST);
        $validator->required('name', 'الاسم')
                  ->maxLength('name', 200, 'الاسم')
                  ->email('email', 'البريد الإلكتروني');

        if ($validator->fails()) {
            $this->setFlash('danger', $validator->firstError());
            $this->redirect('clients', 'create');
            return;
        }

        $data = [
            'client_number' => $this->clientModel->generateNumber(),
            'name' => $this->input('name'),
            'phone' => $this->input('phone'),
            'phone2' => $this->input('phone2'),
            'email' => $this->input('email'),
            'city' => $this->input('city'),
            'address' => $this->input('address'),
            'short_address' => $this->input('short_address'),
            'building_number' => $this->input('building_number'),
            'street' => $this->input('street'),
            'district' => $this->input('district'),
            'postal_code' => $this->input('postal_code'),
            'additional_number' => $this->input('additional_number'),
            'id_number' => $this->input('id_number'),
            'notes' => $this->input('notes'),
            'source' => $this->input('source'),
            'assigned_to' => $this->input('assigned_to') ?: null,
            'created_by' => $this->currentUser()['id'],
        ];

        $id = $this->clientModel->create($data);
        $this->logActivity('create', 'clients', $id, "إضافة عميل: {$data['name']}");
        
        $this->setFlash('success', 'تم إضافة العميل بنجاح');
        $this->redirect('clients', 'card', ['id' => $id]);
    }

    /**
     * صفحة تعديل عميل
     */
    public function edit(): void
    {
        $id = (int)$this->query('id');
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            $this->setFlash('danger', 'العميل غير موجود');
            $this->redirect('clients');
            return;
        }

        $pageTitle = 'تعديل العميل: ' . $client['name'];
        $breadcrumbs = [
            ['title' => 'العملاء', 'url' => url('clients')],
            ['title' => 'تعديل العميل']
        ];
        $employees = $this->db->fetchAll("SELECT id, full_name FROM users WHERE is_active = 1 AND deleted_at IS NULL ORDER BY full_name");
        
        $this->render('domain/Clients/Views/edit.php', compact('pageTitle', 'breadcrumbs', 'client', 'employees'));
    }

    /**
     * تحديث عميل
     */
    public function update(): void
    {
        if (!$this->isPost()) {
            $this->redirect('clients');
            return;
        }

        $id = (int)$this->input('id');
        $client = $this->clientModel->find($id);
        
        if (!$client) {
            $this->setFlash('danger', 'العميل غير موجود');
            $this->redirect('clients');
            return;
        }

        $validator = new Validator($_POST);
        $validator->required('name', 'الاسم');

        if ($validator->fails()) {
            $this->setFlash('danger', $validator->firstError());
            $this->redirect('clients', 'edit', ['id' => $id]);
            return;
        }

        $data = [
            'name' => $this->input('name'),
            'phone' => $this->input('phone'),
            'phone2' => $this->input('phone2'),
            'email' => $this->input('email'),
            'city' => $this->input('city'),
            'address' => $this->input('address'),
            'short_address' => $this->input('short_address'),
            'building_number' => $this->input('building_number'),
            'street' => $this->input('street'),
            'district' => $this->input('district'),
            'postal_code' => $this->input('postal_code'),
            'additional_number' => $this->input('additional_number'),
            'id_number' => $this->input('id_number'),
            'notes' => $this->input('notes'),
            'source' => $this->input('source'),
            'assigned_to' => $this->input('assigned_to') ?: null,
        ];

        $this->clientModel->update($id, $data);
        $this->logActivity('update', 'clients', $id, "تعديل عميل: {$data['name']}");
        
        $this->setFlash('success', 'تم تحديث بيانات العميل بنجاح');
        $this->redirect('clients', 'card', ['id' => $id]);
    }

    /**
     * حذف عميل
     */
    public function delete(): void
    {
        $id = (int)$this->query('id');
        $client = $this->clientModel->find($id);
        
        if ($client) {
            $this->clientModel->delete($id);
            $this->logActivity('delete', 'clients', $id, "حذف عميل: {$client['name']}");
            $this->setFlash('success', 'تم حذف العميل بنجاح');
        }
        
        $this->redirect('clients');
    }

    /**
     * بطاقة العميل الشاملة
     */
    public function card(): void
    {
        $id = (int)$this->query('id');
        $client = $this->clientModel->getClientCard($id);
        
        if (!$client) {
            $this->setFlash('danger', 'العميل غير موجود');
            $this->redirect('clients');
            return;
        }

        $pageTitle = 'بطاقة العميل: ' . $client['name'];
        $breadcrumbs = [
            ['title' => 'العملاء', 'url' => url('clients')],
            ['title' => $client['name']]
        ];
        
        $this->render('domain/Clients/Views/card.php', compact('pageTitle', 'breadcrumbs', 'client'));
    }

    /**
     * عرض تفاصيل العميل (alias for card)
     */
    public function show(): void
    {
        $this->card();
    }
}
