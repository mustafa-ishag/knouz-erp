<div class="page-header"><div><h1 class="page-title">المستندات</h1></div><div class="page-actions"><a href="<?= url('documents','upload') ?>" class="btn btn-primary"><i class="fas fa-upload"></i> رفع مستند</a></div></div>
<div class="card"><div class="table-wrapper"><table class="data-table"><thead><tr><th>المستند</th><th>النوع</th><th>الشركة</th><th>العميل</th><th>الحجم</th><th>انتهاء الصلاحية</th><th>التاريخ</th><th></th></tr></thead>
<tbody><?php if(empty($docs)): ?><tr><td colspan="8"><div class="empty-state"><i class="fas fa-folder-open"></i><h3>لا توجد مستندات</h3></div></td></tr>
<?php else: foreach($docs as $d): ?><tr>
    <td><i class="fas fa-file-pdf text-danger mr-1"></i> <strong><?= clean($d['title']) ?></strong></td>
    <td><span class="badge badge-info"><?= clean($d['document_type']??'-') ?></span></td>
    <td><?= clean($d['company_name']??'-') ?></td><td><?= clean($d['client_name']??'-') ?></td>
    <td class="text-sm"><?= isset($d['file_size'])?formatFileSize($d['file_size']):'-' ?></td>
    <td><?php if($d['expiry_date']): ?><span class="badge badge-<?= strtotime($d['expiry_date'])<time()?'danger':'success' ?>"><?= formatDate($d['expiry_date']) ?></span><?php else: ?>-<?php endif; ?></td>
    <td class="text-sm text-muted"><?= formatDate($d['created_at']) ?></td>
    <td><div class="table-actions"><a href="<?= url('documents','preview',['id'=>$d['id']]) ?>" target="_blank" class="btn btn-ghost btn-icon btn-sm" title="استعراض"><i class="fas fa-eye text-info"></i></a><a href="<?= url('documents','download',['id'=>$d['id']]) ?>" class="btn btn-ghost btn-icon btn-sm" title="تحميل"><i class="fas fa-download text-gold"></i></a><button onclick="confirmDelete('<?= url('documents','delete',['id'=>$d['id']]) ?>')" class="btn btn-ghost btn-icon btn-sm" title="حذف"><i class="fas fa-trash text-danger"></i></button></div></td>
</tr><?php endforeach;endif; ?></tbody></table></div></div>
