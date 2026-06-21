<?php
/**
 * رفع الملفات بأمان
 */

class FileUploader
{
    private array $config;
    private array $errors = [];

    public function __construct()
    {
        $appConfig = require dirname(__DIR__) . '/config/app.php';
        $this->config = $appConfig['upload'];
    }

    /**
     * رفع ملف
     */
    public function upload(array $file, string $directory = '', ?string $customName = null): ?string
    {
        // التحقق من وجود الملف
        if (empty($file['tmp_name']) || $file['error'] !== UPLOAD_ERR_OK) {
            $this->errors[] = 'لم يتم رفع الملف بشكل صحيح';
            return null;
        }

        // التحقق من الحجم
        if ($file['size'] > $this->config['max_size']) {
            $maxMb = $this->config['max_size'] / (1024 * 1024);
            $this->errors[] = "حجم الملف يتجاوز الحد الأقصى ({$maxMb} ميجابايت)";
            return null;
        }

        // التحقق من النوع
        $extension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        if (!in_array($extension, $this->config['allowed_types'])) {
            $this->errors[] = 'نوع الملف غير مسموح به';
            return null;
        }

        // إنشاء المجلد
        $uploadDir = dirname(__DIR__) . '/public/' . $this->config['upload_path'];
        if ($directory) {
            $uploadDir .= $directory . '/';
        }

        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }

        // إنشاء اسم فريد
        if ($customName) {
            $fileName = $customName . '.' . $extension;
        } else {
            $fileName = date('Y-m-d_') . bin2hex(random_bytes(8)) . '.' . $extension;
        }

        $fullPath = $uploadDir . $fileName;

        // رفع الملف
        if (move_uploaded_file($file['tmp_name'], $fullPath)) {
            return ($directory ? $directory . '/' : '') . $fileName;
        }

        $this->errors[] = 'فشل في رفع الملف';
        return null;
    }

    /**
     * رفع عدة ملفات
     */
    public function uploadMultiple(array $files, string $directory = ''): array
    {
        $uploaded = [];
        
        // تحويل مصفوفة PHP المعقدة للملفات
        $fileCount = count($files['name']);
        for ($i = 0; $i < $fileCount; $i++) {
            $file = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i],
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i],
            ];

            $result = $this->upload($file, $directory);
            if ($result) {
                $uploaded[] = $result;
            }
        }

        return $uploaded;
    }

    /**
     * حذف ملف
     */
    public function delete(string $path): bool
    {
        $fullPath = dirname(__DIR__) . '/public/' . $this->config['upload_path'] . $path;
        if (file_exists($fullPath)) {
            return unlink($fullPath);
        }
        return false;
    }

    /**
     * الحصول على رابط الملف
     */
    public function getUrl(string $path): string
    {
        return '/kn/public/' . $this->config['upload_path'] . $path;
    }

    /**
     * الأخطاء
     */
    public function getErrors(): array
    {
        return $this->errors;
    }

    public function hasErrors(): bool
    {
        return !empty($this->errors);
    }
}
