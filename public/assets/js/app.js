/**
 * نظام كنوز الإنجاز - JavaScript الرئيسي
 */

// === Sidebar Toggle ===
function toggleSidebar() {
    document.body.classList.toggle('sidebar-collapsed');
    localStorage.setItem('sidebar_collapsed', document.body.classList.contains('sidebar-collapsed'));
}

// تحميل حالة القائمة الجانبية
document.addEventListener('DOMContentLoaded', function() {
    if (localStorage.getItem('sidebar_collapsed') === 'true') {
        document.body.classList.add('sidebar-collapsed');
    }
});

// === Mobile Sidebar ===
function toggleMobileSidebar() {
    document.querySelector('.sidebar').classList.toggle('show');
}

// إغلاق القائمة عند النقر خارجها
document.addEventListener('click', function(e) {
    const sidebar = document.querySelector('.sidebar');
    const mobileToggle = document.querySelector('.mobile-toggle');
    if (sidebar && sidebar.classList.contains('show') && 
        !sidebar.contains(e.target) && 
        (!mobileToggle || !mobileToggle.contains(e.target))) {
        sidebar.classList.remove('show');
    }
});

// === Dropdowns ===
document.addEventListener('click', function(e) {
    const dropdown = e.target.closest('.dropdown');
    
    // إغلاق جميع القوائم المنسدلة
    document.querySelectorAll('.dropdown-menu.show').forEach(menu => {
        if (!dropdown || !dropdown.contains(menu)) {
            menu.classList.remove('show');
        }
    });
    
    // فتح/إغلاق القائمة المنسدلة الحالية
    if (dropdown) {
        const menu = dropdown.querySelector('.dropdown-menu');
        if (menu) {
            menu.classList.toggle('show');
        }
    }
});

// === Notifications Panel ===
function toggleNotifications() {
    const panel = document.querySelector('.notifications-panel');
    if (panel) {
        panel.classList.toggle('show');
    }
}

// === Toast Notifications ===
function showToast(message, type = 'success', duration = 4000) {
    let container = document.querySelector('.toast-container');
    if (!container) {
        container = document.createElement('div');
        container.className = 'toast-container';
        document.body.appendChild(container);
    }
    
    const icons = {
        success: 'fa-check-circle',
        danger: 'fa-exclamation-circle',
        warning: 'fa-exclamation-triangle',
        info: 'fa-info-circle'
    };
    
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = `
        <i class="fas ${icons[type] || icons.info}" style="color: var(--${type}); font-size: 1.2rem;"></i>
        <span style="flex: 1; font-size: 0.875rem; font-weight: 500;">${message}</span>
        <button onclick="this.parentElement.remove()" style="background: none; border: none; cursor: pointer; color: var(--text-light); font-size: 1.2rem;">
            <i class="fas fa-times"></i>
        </button>
    `;
    
    container.appendChild(toast);
    
    setTimeout(() => {
        toast.style.opacity = '0';
        toast.style.transform = 'translateX(-20px)';
        toast.style.transition = 'all 0.3s ease';
        setTimeout(() => toast.remove(), 300);
    }, duration);
}

// === Modal ===
function openModal(modalId) {
    const overlay = document.getElementById(modalId);
    if (overlay) {
        overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
}

function closeModal(modalId) {
    const overlay = document.getElementById(modalId);
    if (overlay) {
        overlay.classList.remove('show');
        document.body.style.overflow = '';
    }
}

// إغلاق المودال بالنقر على الخلفية
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('modal-overlay')) {
        e.target.classList.remove('show');
        document.body.style.overflow = '';
    }
});

// إغلاق المودال بمفتاح Escape
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        document.querySelectorAll('.modal-overlay.show').forEach(modal => {
            modal.classList.remove('show');
        });
        document.body.style.overflow = '';
    }
});

// === Alerts ===
document.addEventListener('click', function(e) {
    if (e.target.closest('.alert-close')) {
        const alert = e.target.closest('.alert');
        if (alert) {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        }
    }
});

// === AJAX Helper ===
async function fetchAPI(url, options = {}) {
    try {
        const defaults = {
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
            },
        };
        
        if (options.body && !(options.body instanceof FormData)) {
            defaults.headers['Content-Type'] = 'application/json';
            options.body = JSON.stringify(options.body);
        }
        
        const response = await fetch(url, { ...defaults, ...options });
        const data = await response.json();
        
        return data;
    } catch (error) {
        console.error('API Error:', error);
        showToast('حدث خطأ في الاتصال', 'danger');
        throw error;
    }
}

// === Confirm Delete ===
function confirmDelete(url, itemName = 'هذا العنصر') {
    if (confirm(`هل أنت متأكد من حذف ${itemName}؟\nلا يمكن التراجع عن هذا الإجراء.`)) {
        window.location.href = url;
    }
}

// === Form Validation ===
function validateForm(formId) {
    const form = document.getElementById(formId);
    if (!form) return true;
    
    let isValid = true;
    
    // إزالة الأخطاء السابقة
    form.querySelectorAll('.is-invalid').forEach(el => el.classList.remove('is-invalid'));
    form.querySelectorAll('.invalid-feedback').forEach(el => el.remove());
    
    // التحقق من الحقول المطلوبة
    form.querySelectorAll('[required]').forEach(field => {
        if (!field.value.trim()) {
            field.classList.add('is-invalid');
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = 'هذا الحقل مطلوب';
            field.parentNode.appendChild(feedback);
            isValid = false;
        }
    });
    
    // التحقق من البريد الإلكتروني
    form.querySelectorAll('input[type="email"]').forEach(field => {
        if (field.value && !field.value.match(/^[^\s@]+@[^\s@]+\.[^\s@]+$/)) {
            field.classList.add('is-invalid');
            const feedback = document.createElement('div');
            feedback.className = 'invalid-feedback';
            feedback.textContent = 'البريد الإلكتروني غير صالح';
            field.parentNode.appendChild(feedback);
            isValid = false;
        }
    });
    
    return isValid;
}

// === Table Search ===
function tableSearch(inputId, tableId) {
    const input = document.getElementById(inputId);
    const table = document.getElementById(tableId);
    if (!input || !table) return;
    
    input.addEventListener('input', function() {
        const term = this.value.toLowerCase();
        const rows = table.querySelectorAll('tbody tr');
        
        rows.forEach(row => {
            const text = row.textContent.toLowerCase();
            row.style.display = text.includes(term) ? '' : 'none';
        });
    });
}

// === Number Formatting ===
function formatNumber(num) {
    return new Intl.NumberFormat('ar-SA').format(num);
}

function formatCurrency(amount) {
    return new Intl.NumberFormat('ar-SA', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2,
    }).format(amount) + ' ر.س';
}

// === Dynamic Select ===
function loadCompanies(clientId, selectId) {
    const select = document.getElementById(selectId);
    if (!select || !clientId) return;
    
    select.innerHTML = '<option value="">جاري التحميل...</option>';
    
    fetchAPI(`/kn/public/?module=companies&action=by_client&client_id=${clientId}`)
        .then(data => {
            select.innerHTML = '<option value="">اختر الشركة</option>';
            if (data.companies) {
                data.companies.forEach(company => {
                    select.innerHTML += `<option value="${company.id}">${company.name_ar}</option>`;
                });
            }
        })
        .catch(() => {
            select.innerHTML = '<option value="">خطأ في التحميل</option>';
        });
}

// === Print ===
function printPage() {
    window.print();
}

// === Tabs ===
function switchTab(tabGroup, tabName) {
    // تعطيل جميع التبويبات
    document.querySelectorAll(`[data-tab-group="${tabGroup}"]`).forEach(tab => {
        tab.classList.remove('active');
    });
    document.querySelectorAll(`[data-tab-content="${tabGroup}"]`).forEach(content => {
        content.classList.remove('active');
    });
    
    // تفعيل التبويب المحدد
    document.querySelector(`[data-tab-group="${tabGroup}"][data-tab="${tabName}"]`)?.classList.add('active');
    document.getElementById(`tab-${tabGroup}-${tabName}`)?.classList.add('active');
}

// === Auto-calculate VAT ===
function calculateVAT(subtotalId, vatRateId, vatAmountId, totalId) {
    const subtotal = parseFloat(document.getElementById(subtotalId)?.value) || 0;
    const vatRate = parseFloat(document.getElementById(vatRateId)?.value) || 15;
    const vatAmount = subtotal * vatRate / 100;
    const total = subtotal + vatAmount;
    
    if (document.getElementById(vatAmountId)) {
        document.getElementById(vatAmountId).value = vatAmount.toFixed(2);
    }
    if (document.getElementById(totalId)) {
        document.getElementById(totalId).value = total.toFixed(2);
    }
}

// === Item Management (Fallback - each view has its own inline addXxxItem function) ===
// This is a safe fallback in case addQuotationItem is called from an older context
function addQuotationItem() {
    // Try to call the page-specific function if available
    if (typeof addQuotItem === 'function') { addQuotItem(); return; }
    if (typeof addClaimItem === 'function') { addClaimItem(); return; }
    if (typeof addInvoiceItem === 'function') { addInvoiceItem(); return; }
    if (typeof addInvItem === 'function') { addInvItem(); return; }

    // Generic fallback with no service select
    const tbody = document.getElementById('items-tbody');
    if (!tbody) return;
    const idx = tbody.querySelectorAll('tr').length;
    const row = document.createElement('tr');
    row.id = `item-row-${idx}`;
    row.innerHTML = `
        <td><input type="text" name="items[${idx}][description]" class="form-control" required><input type="hidden" name="items[${idx}][service_id]" value=""></td>
        <td><input type="number" name="items[${idx}][quantity]" class="form-control" value="1" min="1" onchange="calcItemTotal(${idx})"></td>
        <td><input type="number" name="items[${idx}][unit_price]" class="form-control" value="0" step="0.01" onchange="calcItemTotal(${idx})"></td>
        <td><input type="text" name="items[${idx}][total]" class="form-control" value="0.00" readonly></td>
        <td><button type="button" class="btn btn-ghost btn-icon btn-sm" onclick="removeItem(${idx})"><i class="fas fa-trash text-danger"></i></button></td>
    `;
    tbody.appendChild(row);
}

function removeItem(idx) {
    document.getElementById(`item-row-${idx}`)?.remove();
    recalcSubtotal();
}

function calcItemTotal(idx) {
    const row = document.getElementById(`item-row-${idx}`);
    if (!row) return;
    
    const qty = parseFloat(row.querySelector(`[name="items[${idx}][quantity]"]`)?.value) || 0;
    const price = parseFloat(row.querySelector(`[name="items[${idx}][unit_price]"]`)?.value) || 0;
    const total = qty * price;
    
    row.querySelector(`[name="items[${idx}][total]"]`).value = total.toFixed(2);
    recalcSubtotal();
}

function recalcSubtotal() {
    let subtotal = 0;
    document.querySelectorAll('[name$="[total]"]').forEach(input => {
        if (input.name.startsWith('items[')) {
            subtotal += parseFloat(input.value) || 0;
        }
    });
    
    const subtotalEl = document.getElementById('subtotal');
    if (subtotalEl) subtotalEl.value = subtotal.toFixed(2);
    
    calculateVAT('subtotal', 'vat_rate', 'vat_amount', 'total');
}

// === Notifications Polling ===
function pollNotifications() {
    if (!document.querySelector('.header-icon[onclick*="toggleNotifications"]')) return;
    
    fetchAPI('/kn/public/?module=notifications&action=get')
        .then(data => {
            if (data.unread_count > 0) {
                const badge = document.querySelector('.notification-badge-count');
                if (badge) badge.textContent = data.unread_count;
                document.querySelector('.badge-dot')?.style.setProperty('display', 'block');
            }
        })
        .catch(() => {});
}

// Poll every 60 seconds
setInterval(pollNotifications, 60000);

// === Initialize ===
document.addEventListener('DOMContentLoaded', function() {
    // Auto-hide flash messages after 5 seconds
    document.querySelectorAll('.alert[data-auto-hide]').forEach(alert => {
        setTimeout(() => {
            alert.style.opacity = '0';
            alert.style.transform = 'translateY(-10px)';
            setTimeout(() => alert.remove(), 300);
        }, 5000);
    });
    
    // Initialize table search if exists
    const searchInput = document.getElementById('table-search');
    const dataTable = document.getElementById('data-table');
    if (searchInput && dataTable) {
        tableSearch('table-search', 'data-table');
    }
});
