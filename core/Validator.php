<?php
/**
 * نظام التحقق من صحة البيانات
 */

class Validator
{
    private array $errors = [];
    private array $data = [];

    public function __construct(array $data)
    {
        $this->data = $data;
    }

    /**
     * التحقق من حقل مطلوب
     */
    public function required(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (empty($this->data[$field]) && $this->data[$field] !== '0') {
            $this->errors[$field] = "حقل {$label} مطلوب";
        }
        return $this;
    }

    /**
     * التحقق من البريد الإلكتروني
     */
    public function email(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (!empty($this->data[$field]) && !filter_var($this->data[$field], FILTER_VALIDATE_EMAIL)) {
            $this->errors[$field] = "البريد الإلكتروني غير صالح";
        }
        return $this;
    }

    /**
     * التحقق من الحد الأدنى للطول
     */
    public function minLength(string $field, int $min, string $label = ''): self
    {
        $label = $label ?: $field;
        if (!empty($this->data[$field]) && mb_strlen($this->data[$field]) < $min) {
            $this->errors[$field] = "يجب أن يكون {$label} {$min} أحرف على الأقل";
        }
        return $this;
    }

    /**
     * التحقق من الحد الأقصى للطول
     */
    public function maxLength(string $field, int $max, string $label = ''): self
    {
        $label = $label ?: $field;
        if (!empty($this->data[$field]) && mb_strlen($this->data[$field]) > $max) {
            $this->errors[$field] = "يجب أن لا يتجاوز {$label} {$max} حرفاً";
        }
        return $this;
    }

    /**
     * التحقق من الرقم
     */
    public function numeric(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (!empty($this->data[$field]) && !is_numeric($this->data[$field])) {
            $this->errors[$field] = "يجب أن يكون {$label} رقماً";
        }
        return $this;
    }

    /**
     * التحقق من الحد الأدنى للقيمة
     */
    public function min(string $field, float $min, string $label = ''): self
    {
        $label = $label ?: $field;
        if (!empty($this->data[$field]) && (float)$this->data[$field] < $min) {
            $this->errors[$field] = "يجب أن يكون {$label} أكبر من أو يساوي {$min}";
        }
        return $this;
    }

    /**
     * التحقق من رقم الجوال السعودي
     */
    public function saudiMobile(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (!empty($this->data[$field])) {
            $phone = preg_replace('/[^0-9]/', '', $this->data[$field]);
            if (!preg_match('/^(05|5|9665)\d{8}$/', $phone)) {
                $this->errors[$field] = "رقم الجوال غير صالح";
            }
        }
        return $this;
    }

    /**
     * التحقق من التاريخ
     */
    public function date(string $field, string $label = ''): self
    {
        $label = $label ?: $field;
        if (!empty($this->data[$field])) {
            $d = DateTime::createFromFormat('Y-m-d', $this->data[$field]);
            if (!$d || $d->format('Y-m-d') !== $this->data[$field]) {
                $this->errors[$field] = "التاريخ غير صالح";
            }
        }
        return $this;
    }

    /**
     * التحقق من القيمة ضمن قائمة
     */
    public function in(string $field, array $values, string $label = ''): self
    {
        $label = $label ?: $field;
        if (!empty($this->data[$field]) && !in_array($this->data[$field], $values)) {
            $this->errors[$field] = "قيمة {$label} غير صالحة";
        }
        return $this;
    }

    /**
     * التحقق من تفرد القيمة في قاعدة البيانات
     */
    public function unique(string $field, string $table, ?string $column = null, ?int $excludeId = null, string $label = ''): self
    {
        $label = $label ?: $field;
        $column = $column ?: $field;
        
        if (!empty($this->data[$field])) {
            $db = Database::getInstance();
            $where = "{$column} = ? AND deleted_at IS NULL";
            $params = [$this->data[$field]];
            
            if ($excludeId) {
                $where .= " AND id != ?";
                $params[] = $excludeId;
            }
            
            if ($db->exists($table, $where, $params)) {
                $this->errors[$field] = "قيمة {$label} مستخدمة مسبقاً";
            }
        }
        return $this;
    }

    /**
     * التحقق من تطابق حقلين
     */
    public function confirm(string $field, string $confirmField, string $label = ''): self
    {
        $label = $label ?: $field;
        if (($this->data[$field] ?? '') !== ($this->data[$confirmField] ?? '')) {
            $this->errors[$field] = "حقل {$label} غير متطابق";
        }
        return $this;
    }

    /**
     * هل التحقق ناجح؟
     */
    public function passes(): bool
    {
        return empty($this->errors);
    }

    /**
     * هل فشل التحقق؟
     */
    public function fails(): bool
    {
        return !empty($this->errors);
    }

    /**
     * الحصول على الأخطاء
     */
    public function errors(): array
    {
        return $this->errors;
    }

    /**
     * أول خطأ
     */
    public function firstError(): string
    {
        return reset($this->errors) ?: '';
    }

    /**
     * خطأ حقل معين
     */
    public function error(string $field): string
    {
        return $this->errors[$field] ?? '';
    }
}
