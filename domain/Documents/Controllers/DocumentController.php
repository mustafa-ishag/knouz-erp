<?php
class DocumentController extends Controller {
    public function index(): void {$pageTitle='المستندات';$breadcrumbs=[['title'=>'المستندات']];$docs=$this->db->fetchAll("SELECT d.*,c.name as client_name,co.name_ar as company_name FROM company_documents d LEFT JOIN companies co ON d.company_id=co.id LEFT JOIN clients c ON co.client_id=c.id WHERE d.deleted_at IS NULL ORDER BY d.created_at DESC LIMIT 100");$this->render('domain/Documents/Views/index.php',compact('pageTitle','breadcrumbs','docs'));}
    public function upload(): void {$companies=$this->db->fetchAll("SELECT co.id,co.name_ar,c.name as client_name FROM companies co LEFT JOIN clients c ON co.client_id=c.id WHERE co.deleted_at IS NULL ORDER BY co.name_ar");$pageTitle='رفع مستند';$breadcrumbs=[['title'=>'المستندات','url'=>url('documents')],['title'=>'رفع']];$this->render('domain/Documents/Views/upload.php',compact('pageTitle','breadcrumbs','companies'));}
    public function store(): void {
        if(!$this->isPost()||empty($_FILES['file'])){$this->redirect('documents');return;}
        $uploader=new FileUploader();$path=$uploader->upload($_FILES['file'],'documents');
        if(!$path){$this->setFlash('danger',$uploader->getErrors()[0] ?? 'فشل الرفع');$this->redirect('documents','upload');return;}
        $this->db->insert('company_documents',['company_id'=>$this->input('company_id'),'document_type'=>$this->input('document_type'),'title'=>$this->input('title')?:$_FILES['file']['name'],'file_path'=>$path,'file_name'=>basename($path),'file_size'=>$_FILES['file']['size'],'expiry_date'=>$this->input('expiry_date')?:null,'uploaded_by'=>$this->currentUser()['id']]);
        $this->setFlash('success','تم رفع المستند');$this->redirect('documents');
    }
    public function deleteDoc(): void {$id=(int)$this->query('id');$this->db->update('company_documents',['deleted_at'=>date('Y-m-d H:i:s')],'id=?',[$id]);$this->setFlash('success','تم الحذف');$this->redirect('documents');}

    /**
     * تحميل المستند
     */
    public function download(): void {
        $id = (int)$this->query('id');
        $doc = $this->db->fetch("SELECT * FROM company_documents WHERE id=?", [$id]);
        if (!$doc) { $this->setFlash('danger', 'المستند غير موجود'); $this->redirect('documents'); return; }

        $filePath = BASE_PATH . '/public/uploads/' . $doc['file_path'];
        if (!file_exists($filePath)) {
            $this->setFlash('danger', 'الملف غير موجود على السيرفر');
            $this->redirect('documents');
            return;
        }

        $originalName = $doc['title'];
        $ext = pathinfo($doc['file_name'], PATHINFO_EXTENSION);
        if ($ext && !str_ends_with($originalName, '.' . $ext)) {
            $originalName .= '.' . $ext;
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . $originalName . '"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);
        exit;
    }

    /**
     * استعراض المستند في المتصفح
     */
    public function preview(): void {
        $id = (int)$this->query('id');
        $doc = $this->db->fetch("SELECT * FROM company_documents WHERE id=?", [$id]);
        if (!$doc) { $this->setFlash('danger', 'المستند غير موجود'); $this->redirect('documents'); return; }

        $filePath = BASE_PATH . '/public/uploads/' . $doc['file_path'];
        if (!file_exists($filePath)) {
            $this->setFlash('danger', 'الملف غير موجود على السيرفر');
            $this->redirect('documents');
            return;
        }

        $ext = strtolower(pathinfo($doc['file_name'], PATHINFO_EXTENSION));
        $mimeTypes = [
            'pdf' => 'application/pdf',
            'jpg' => 'image/jpeg', 'jpeg' => 'image/jpeg',
            'png' => 'image/png', 'gif' => 'image/gif', 'webp' => 'image/webp',
            'doc' => 'application/msword',
            'docx' => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
            'xls' => 'application/vnd.ms-excel',
            'xlsx' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
        ];

        $mimeType = $mimeTypes[$ext] ?? 'application/octet-stream';

        header('Content-Type: ' . $mimeType);
        header('Content-Disposition: inline; filename="' . $doc['file_name'] . '"');
        header('Content-Length: ' . filesize($filePath));
        header('Cache-Control: public, max-age=3600');
        readfile($filePath);
        exit;
    }
}
