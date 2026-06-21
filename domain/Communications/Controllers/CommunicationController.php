<?php
class CommunicationController extends Controller {
    public function calls(): void {
        $page=(int)$this->query('page',1);$search=$this->query('search','');
        $where='1=1';$params=[];
        if($search){$where.=" AND (c.name LIKE ? OR cl.notes LIKE ?)";$s="%{$search}%";$params=[$s,$s];}
        $result=$this->db->paginate("SELECT cl.*,c.name as client_name,u.full_name as user_name FROM calls cl LEFT JOIN clients c ON cl.client_id=c.id LEFT JOIN users u ON cl.user_id=u.id WHERE {$where} ORDER BY cl.call_date DESC",$params,$page,25);
        $pageTitle='سجل المكالمات';$breadcrumbs=[['title'=>'التواصل'],['title'=>'المكالمات']];
        $this->render('domain/Communications/Views/calls.php',compact('pageTitle','breadcrumbs','result','search'));
    }
    public function logCall(): void {
        $clientId=(int)$this->query('client_id',0);
        $clients=$this->db->fetchAll("SELECT id,name,phone FROM clients WHERE deleted_at IS NULL ORDER BY name");
        $pageTitle='تسجيل مكالمة';$breadcrumbs=[['title'=>'التواصل'],['title'=>'تسجيل مكالمة']];
        $this->render('domain/Communications/Views/log_call.php',compact('pageTitle','breadcrumbs','clients','clientId'));
    }
    public function storeCall(): void {
        if(!$this->isPost()){$this->redirect('communications','calls');return;}
        $this->db->insert('calls',['client_id'=>$this->input('client_id'),'user_id'=>$this->currentUser()['id'],'call_type'=>$this->input('call_type','outgoing'),'call_date'=>$this->input('call_date')?:date('Y-m-d H:i:s'),'duration'=>(int)$this->input('duration',0),'result'=>$this->input('result'),'notes'=>$this->input('notes')]);
        $this->db->update('clients',['last_contact_date'=>date('Y-m-d H:i:s')],'id=?',[$this->input('client_id')]);
        $this->logActivity('create','calls',0,'تسجيل مكالمة');$this->setFlash('success','تم تسجيل المكالمة');$this->redirect('communications','calls');
    }
    public function activities(): void {
        $result=$this->db->paginate("SELECT a.*,u.full_name as user_name FROM activity_log a LEFT JOIN users u ON a.user_id=u.id ORDER BY a.created_at DESC",[],(int)$this->query('page',1),50);
        $pageTitle='سجل النشاطات';$breadcrumbs=[['title'=>'التواصل'],['title'=>'النشاطات']];
        $this->render('domain/Communications/Views/activities.php',compact('pageTitle','breadcrumbs','result'));
    }
}
