<?php
/**
 * متحكم الاشتراكات الحكومية
 */

class GovSubscriptionController extends Controller
{
    private GovSubscription $model;

    public function __construct()
    {
        parent::__construct();
        $this->model = new GovSubscription();
    }

    /**
     * قائمة الاشتراكات
     */
    public function index(): void
    {
        $platform = $this->query('platform', '');
        $search = $this->query('search', '');

        $subscriptions = $this->model->getAllWithCompany($platform, $search);
        $platforms = GovSubscription::platforms();
        $stats = $this->model->getPlatformStats();

        // حساب إجمالي المنصات الفريدة
        $totalPlatforms = count($stats);

        $pageTitle = 'الاشتراكات الحكومية';
        $breadcrumbs = [
            ['title' => 'الخدمات الحكومية'],
            ['title' => 'الاشتراكات الحكومية']
        ];

        $this->render('domain/GovSubscriptions/Views/index.php', compact(
            'pageTitle', 'breadcrumbs', 'subscriptions', 'platforms',
            'platform', 'search', 'stats', 'totalPlatforms'
        ));
    }

    /**
     * صفحة إضافة اشتراك
     */
    public function create(): void
    {
        $platforms = GovSubscription::platforms();
        $companies = $this->db->fetchAll(
            "SELECT id, name_ar FROM companies WHERE deleted_at IS NULL ORDER BY name_ar"
        );

        $pageTitle = 'إضافة اشتراك حكومي';
        $breadcrumbs = [
            ['title' => 'الخدمات الحكومية'],
            ['title' => 'الاشتراكات الحكومية', 'url' => url('gov_subscriptions')],
            ['title' => 'إضافة اشتراك']
        ];

        $this->render('domain/GovSubscriptions/Views/create.php', compact(
            'pageTitle', 'breadcrumbs', 'platforms', 'companies'
        ));
    }

    /**
     * حفظ اشتراك جديد
     */
    public function store(): void
    {
        if (!$this->isPost()) {
            $this->redirect('gov_subscriptions');
            return;
        }

        $validator = new Validator($_POST);
        $validator->required('platform', 'المنصة')
                  ->required('company_name', 'الشركة');

        if ($validator->fails()) {
            $this->setFlash('danger', $validator->firstError());
            $this->redirect('gov_subscriptions', 'create');
            return;
        }

        $data = [
            'platform' => $this->input('platform'),
            'company_id' => $this->input('company_id') ?: null,
            'company_name' => $this->input('company_name'),
            'subscription_number' => $this->input('subscription_number'),
            'start_date' => $this->input('start_date') ?: null,
            'end_date' => $this->input('end_date') ?: null,
            'cost' => $this->input('cost') ?: 0,
            'username' => $this->input('username'),
            'password_hint' => $this->input('password_hint'),
            'notes' => $this->input('notes'),
            'created_by' => $this->currentUser()['id'],
        ];

        $id = $this->model->create($data);
        $this->logActivity('create', 'gov_subscriptions', $id, "إضافة اشتراك حكومي: {$data['platform']} - {$data['company_name']}");

        $this->setFlash('success', 'تم إضافة الاشتراك بنجاح');
        $this->redirect('gov_subscriptions');
    }

    /**
     * صفحة تعديل اشتراك
     */
    public function edit(): void
    {
        $id = (int)$this->query('id');
        $subscription = $this->model->find($id);

        if (!$subscription) {
            $this->setFlash('danger', 'الاشتراك غير موجود');
            $this->redirect('gov_subscriptions');
            return;
        }

        $platforms = GovSubscription::platforms();
        $companies = $this->db->fetchAll(
            "SELECT id, name_ar FROM companies WHERE deleted_at IS NULL ORDER BY name_ar"
        );

        $pageTitle = 'تعديل اشتراك حكومي';
        $breadcrumbs = [
            ['title' => 'الخدمات الحكومية'],
            ['title' => 'الاشتراكات الحكومية', 'url' => url('gov_subscriptions')],
            ['title' => 'تعديل']
        ];

        $this->render('domain/GovSubscriptions/Views/edit.php', compact(
            'pageTitle', 'breadcrumbs', 'subscription', 'platforms', 'companies'
        ));
    }

    /**
     * تحديث اشتراك
     */
    public function update(): void
    {
        if (!$this->isPost()) {
            $this->redirect('gov_subscriptions');
            return;
        }

        $id = (int)$this->input('id');
        $subscription = $this->model->find($id);

        if (!$subscription) {
            $this->setFlash('danger', 'الاشتراك غير موجود');
            $this->redirect('gov_subscriptions');
            return;
        }

        $data = [
            'platform' => $this->input('platform'),
            'company_id' => $this->input('company_id') ?: null,
            'company_name' => $this->input('company_name'),
            'subscription_number' => $this->input('subscription_number'),
            'start_date' => $this->input('start_date') ?: null,
            'end_date' => $this->input('end_date') ?: null,
            'cost' => $this->input('cost') ?: 0,
            'username' => $this->input('username'),
            'password_hint' => $this->input('password_hint'),
            'notes' => $this->input('notes'),
        ];

        $this->model->update($id, $data);
        $this->logActivity('update', 'gov_subscriptions', $id, "تعديل اشتراك حكومي: {$data['platform']}");

        $this->setFlash('success', 'تم تحديث الاشتراك بنجاح');
        $this->redirect('gov_subscriptions');
    }

    /**
     * حذف اشتراك
     */
    public function delete(): void
    {
        $id = (int)$this->query('id');
        $subscription = $this->model->find($id);

        if ($subscription) {
            $this->model->delete($id);
            $this->logActivity('delete', 'gov_subscriptions', $id, "حذف اشتراك حكومي: {$subscription['platform']}");
            $this->setFlash('success', 'تم حذف الاشتراك بنجاح');
        }

        $this->redirect('gov_subscriptions');
    }
}
