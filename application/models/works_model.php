<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Works_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getTypeWorks() {
        $result=array();
        $this->db->select('id, tip')->from('works_tip');
        $query=$this->db->get();
        foreach($query->result_array() as $item){
            $result[] = $item;
        }
        return $result;
    }
    
    public function addNewWork($data, $target){
        //$data - массив данных
        //$target - object или switch
        $id_works=0; 
        $id_history=0;
        
        $this->db->insert('works', array('id_tipwork'=>$data['typeWork'],
                                        'status'=>'1',
                                        'srok'=>$data['srok']));
        $id_works=$this->db->insert_id();
        
        $this->db->insert('works_history', array('works_id' => $id_works,
                                                'works_event_id' => '1',
                                                'description'=>$data['description'],
                                                'data_deystviya' => date('Y-m-d'),
                                                'id_user' => $data['uID'],
                                                'currenttime' => time()));
        $id_history = $this->db->insert_id();
        //$this->db->insert('works_history_personal',array('history_id'=>$id_history,
        //                                                'personal_id'=>$data['uID']));
        if ($target == 'object'){
            foreach($data['objects'] as $item){
                $this->db->insert('works_object',array('works_id'=>$id_works,
                                                    'object_id'=>$item));                
            }
        }elseif($target == 'switch'){
            foreach($data['switch'] as $item){
                $this->db->insert('works_switch',array('works_id'=>$id_works,
                                                    'switch_id'=>$item));                
            }            
        }
        
  
    }
    public function getDescrWork($worksID){
        $result='';
        $this->db->select('wh.description, u.fio, wh.data_deystviya')->from('works_history wh')->join('users u','wh.id_user=u.id');
        $this->db->where('wh.works_event_id', '1');
        $this->db->where('wh.works_id', $worksID);
        $query = $this->db->get();
        if($query->num_rows()>0){
            $item = $query->row_object();
            $result = $item->fio . " " . $item->data_deystviya . ": " . $item->description . "<br>";
        }   
        return $result;
        
        
    }
    public function getListWorks($uslovie, $order=false){
        $result = array();
        $status['new'] = '1';
        $status['assigned'] = '2';
        $status['inwork'] = '3';
        $status['closed'] = '4';
        $this->db->select("w.id, w.description, wt.tip, w.groupNum, we.deystvie, date_format(w.srok, '%d-%m-%Y %k:%i') as srok",false);
        $this->db->from('works w');
        $this->db->join('works_tip wt','wt.id=w.id_tipwork');
        $this->db->join('works_events we', 'we.id = w.status');
        $this->db->join('works_history wh', 'wh.works_id=w.id');
        
        if (!empty($uslovie['groupNum']))
            $this->db->where('w.groupNum', $uslovie['groupNum']);
        

        if(empty($uslovie['period'])){
            $this->db->where("w.status !=", '4');
            
        }else{
            $date = new DateTime(date('Y-m-d'));
            
            switch($uslovie['period']){
                case "day":date('Y-m-d'); break;
                case "3day":$date->modify('-3 day');break;
                case "week":$date->modify('-7 day');break;
                case "month":$date->modify('-1 month');break;
            }
            $this->db->where("(w.status !=4 OR (w.status =4 AND wh.data_deystviya between '" . $date->format('Y-m-d') . "' AND '" . date('Y-m-d') . "'))");
        }
       
        if(!empty($uslovie['typeWork'])){
             $this->db->where('w.id_tipwork', $uslovie['typeWork']);
        }
        if(!empty($order))
            $this->db->order_by($order['column'],$order['direction']);
        else
            $this->db->order_by('w.id','ASC');

        $this->db->group_by('w.id');
        
        $query = $this->db->get();
        //echo $this->db->last_query();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $item) {
                $cnt=0; //счетчик для ROWSPAN
                $listObj=array(); // объект, ключ, адрес, доступ
                $listSw=array();
                $listPersonal='';
                $listEmail = '';
                $rayon = '';
                $kluch = '';
                $history ='';
                //Проверка в ТКД
                $this->db->select('o.object_name, st.street, o.house, o.id, o.pod, o.kluch, o.raspologenie, o.dostup, r.rayon')->from('object o');
                $this->db->join('works_object wo', 'o.id=wo.object_id');
                $this->db->join('street st', 'st.id=o.street_id');
                $this->db->join('mdu m','m.id=o.mdu_id');
                $this->db->join('rayon r', 'r.id=m.id_rayon','left');
                $this->db->where('wo.works_id',$item->id);
                $queryObj = $this->db->get();
                if($queryObj->num_rows()>0){
                    foreach($queryObj->result() as $obj){
                        $listObj[] = array('addr'=> $obj->street . " д." . $obj->house . " п." . $obj->pod, 
                                            'name'=>$obj->object_name,
                                            'kluch'=>$obj->kluch,
                                            'id'=>$obj->id,
                                            'dostup'=>$obj->raspologenie . "|" . $obj->dostup);                        
                        $rayon = ($obj->rayon) ? $obj->rayon : '';
                        $kluch = ($obj->kluch) ? $obj->kluch : '';
                    }
                }
                
                //Проверка в коммутаторах
                $this->db->select('s.switch, s.id as swID, o.object_name, o.id, st.street, o.house, o.pod, o.kluch, o.raspologenie, o.dostup, r.rayon')->from('switch s');
                $this->db->join('works_switch ws', 's.id=ws.switch_id');
                $this->db->join('object o','s.object_id=o.id');
                $this->db->join('street st', 'st.id=o.street_id');
                $this->db->join('mdu m','m.id=o.mdu_id');
                $this->db->join('rayon r', 'r.id=m.id_rayon','left');
                $this->db->where('ws.works_id', $item->id);
                $querySw = $this->db->get();
                //echo $this->db->last_query();
                if($querySw->num_rows()>0){
                    foreach($querySw->result() as $sw){
                        $listSw[]= array('switch'=>$sw->switch, 'swID'=>$sw->swID);
                        $rayon = ($sw->rayon) ? $sw->rayon : '';
                        $kluch = ($sw->kluch) ? $sw->kluch : '';
                        $listObj[] = array('addr'=> $sw->street . " д." . $sw->house . " п." . $sw->pod, 
                                            'name'=>$sw->object_name,
                                            'id'=>$sw->id,
                                            'kluch'=>$sw->kluch,
                                            'dostup'=>$sw->raspologenie . " | " . $sw->dostup);
                        
                        
                    }
                }
                
                //проверка бригад исполнителей
                $queryPersonal = $this->db->query('select p.email, p.familiya from works_history wh JOIN works_history_personal whp
                                    on whp.history_id = wh.id join personal p
                                    on p.id = whp.personal_id
                                    WHERE wh.id = (SELECT MAX(id) from works_history where works_id=' . $item->id . ')');
                //$queryPersonal = $this->db->get();
                
                if($queryPersonal->num_rows()!=0){
                    foreach($queryPersonal->result() as $personal){
                        $listPersonal .= $personal->familiya . '<br>';
                        $listEmail .= (strlen($personal->email)>1) ? $personal->email . " | " : "";
                    }
                }
                //-----------------
                $history = $this->getDescrWork($item->id);
                $historyArr = $this->getWorkHistoryString($item->id);
                if(!empty($historyArr)){
                    foreach($historyArr as $historystr){
                        $history .= $historystr;
                    }
                    
                }
                //$listObj = array_unique($listObj);
                $listObj = array_map("unserialize", array_unique(array_map("serialize", $listObj)));
                $cnt = count($listObj);
                $result[] = array('id' => $item->id, 'description'=>$item->description, 'tip'=>$item->tip,
                                'objects'=>$listObj, 'deystvie'=>$item->deystvie, 'switches'=>$listSw, 
                                'personal' => $listPersonal, 'groupNum'=>$item->groupNum,
                                'email' => $listEmail, 'rayon'=>$rayon, 'count'=>$cnt, 'history'=>$history, 'srok'=>$item->srok);
                $cnt=0;
            }
            return $result;
        } else {
            return false;
        }
    }
    
    public function setGroup($where, $data){
        $this->db->where('id',$where);
        $this->db->update('works',$data);
    }
    
    public function getWorkHistoryString($workId){
        $result = array();
        $this->db->select('id, description,data_deystviya')->from('works_history')->where('works_id',$workId)->order_by('id');
        $this->db->where('works_event_id !=', '1');
        $query = $this->db->get();
        if($query->num_rows()>0){
            foreach($query->result() as $item){
                $this->db->select('p.familiya')->from('personal p')->join('works_history_personal whp','p.id=whp.personal_id');
                $this->db->where('whp.history_id',$item->id);
                $qPersonal = $this->db->get();
                $fioStr = '';
                if($qPersonal->num_rows()!=0){
                    $cnt=1;
                    foreach($qPersonal->result() as $fio){
                        $fioStr .=(($cnt) ? "" : " | ").$fio->familiya;$cnt=0;
                    }
                }
                if($item->description)
                    $result[]= $item->data_deystviya . ' ' . $fioStr . ': ' . $item->description . '<br>';
            }
        }
        return $result;
    }
    
    
    public function clearGroup(){
        $this->db->update('works', array('groupNum'=>null));
    }
    
    public function getBaseInfoWork($workId){
        $this->db->select("w.id_tipwork, wt.tip, date_format(w.srok, '%d-%m-%Y %k:%i') as srok, wh.description",false);
        $this->db->from('works w')->join('works_history wh','w.id=wh.works_id')->join('works_tip wt', 'wt.id=w.id_tipwork');
        $this->db->where('wh.works_event_id','1')->where('w.id',$workId);
        $this->db->limit(1);
        $query = $this->db->get();
        if($query->num_rows()){
            return $query->row_array();
        }
            return false;
    }
    
    public function toPrint(){
        $result = array();
        $this->db->select('groupNum')->from('works')->where('groupNum >',0)->group_by('groupNum');
        $query=$this->db->get();
        $result['numPages'] = $query->num_rows();
        //определили количество уникальных групп
        foreach($query->result() as $gNum){
            $result[] = $this->getListWorks(array('groupNum'=>$gNum->groupNum));
        }
        return $result;
        
    }
    
    public function getObjectWorks($id){
        $result = array();
        $this->db->select('w.object_id, o.object_name')->from('works_object w')->join('object o','o.id=w.object_id')->where('w.works_id', $id);
        $query = $this->db->get();
        if($query->num_rows() !=0){
            foreach($query->result() as $item){
                $result[] = array('id'=>$item->object_id, 'object_name'=>$item->object_name);
            }
        }else{
            $this->db->select('o.id, o.object_name')->from('switch s')->distinct();
            $this->db->join('works_switch ws', 's.id=ws.switch_id');
            $this->db->join('object o','s.object_id=o.id');
            $this->db->where('ws.works_id', $id);
            $query = $this->db->get();
            foreach($query->result() as $item){
                $result[] = array('id'=>$item->id, 'object_name'=>$item->object_name);
            }    
        }
        
        return $result ;
    }
    public function updateBaseWorkInfo($workid, $data){
        /*$this->input->get('workid'), array('description'=>$this->input->get('description'),
                                                'srok'=>$this->mylib->date2Msql($this->input->get('srok')),
                                                'id_tipwork'=>$this->input->get('typeWork'))*/
        $this->load->library('MyLib.php');
        $this->db->where('id', $workid)->limit(1);
        $this->db->update('works',array('srok'=>$data['srok'], 'id_tipwork'=>$data['id_tipwork']));
        
        $this->db->where('works_id', $workid)->where('works_event_id','1')->limit(1);
        $this->db->update('works_history',array('description'=>$data['description']));
    }
    public function addHistoryData($data){
        $id_history=0;
        
        $this->db->where('id',$data['works_id']);
        $this->db->update('works', array('status'=>$data['works_event_id']));
        
        $this->db->insert('works_history', array('works_id' => $data['works_id'],
                                                'works_event_id' => $data['works_event_id'],
                                                'data_deystviya' => date('Y-m-d'),
                                                'description' => $data['description'],
                                                'id_user' => $data['uID'],
                                                'currenttime' => time()));
        $id_history = $this->db->insert_id();
        foreach($data['personal'] as $item){
            $this->db->insert('works_history_personal',array('history_id'=>$id_history,
                                                        'personal_id'=>$item));
        }
        if ($this->db->insert_id())
            return true;
        else
            return false;        
        
    }
    
    public function getHistory($uslovie) {
        $result = array();
        $status['new'] = '1';
        $status['inwork'] = '3';
        $status['closed'] = '4';
        $this->db->select('w.id, wh.id as whid, u.fio, w.description as opisanie, wt.tip, wh.description as primechanie, wh.data_deystviya, we.deystvie ');
        $this->db->from('works w');
        $this->db->join('works_tip wt','wt.id=w.id_tipwork');
        $this->db->join('works_history wh','w.id=wh.works_id');
        $this->db->join('works_events we', 'we.id=wh.works_event_id');
        $this->db->join('users u', 'u.id = wh.id_user');
        $this->db->join('works_object wo', 'wo.works_id = w.id', 'left');
        $this->db->join('works_switch ws', 'ws.works_id = w.id', 'left');
        $this->db->join('object o', 'o.id = wo.object_id', 'left');
        $this->db->join('switch s', 's.id = ws.switch_id', 'left');
        $this->db->join('object o1', 'o1.id = s.object_id', 'left');
        $this->db->join('works_history_personal whp', 'whp.history_id = wh.id', 'left');
        if(!empty($uslovie['personal'])) {
            $con = '';
            $cnt = count($uslovie['personal'])-1;
            foreach($uslovie['personal'] as $item){
                if($item)
                    $con .= "personal_id=" . $item . (($cnt) ? " or " : "");
                $cnt--;
            }
            if($con)
                $this->db->where("(" . $con . ")");
        }
        
        if(!empty($uslovie['status'])) {
            $con = '';
            $cnt = count($uslovie['status'])-1;
            foreach($uslovie['status'] as $item){
                if($item)
                    $con .= "wh.works_event_id=" . $item . (($cnt) ? " or " : "");
                $cnt--;
            }
            if($con)
                $this->db->where("(" . $con . ")");
        }
        
        if(!empty($uslovie['tkd'])) {
            $this->db->where("(o.object_name='" . $uslovie['tkd'] . "' or o1.object_name='" . $uslovie['tkd'] . "')");
        }
        if(!empty($uslovie['opisanie'])) {
            $this->db->like('w.description',$uslovie['opisanie']);
        }
        if(!empty($uslovie['primechanie'])) {
            $this->db->like('wh.description',$uslovie['primechanie']);
        }
        
        if(!empty($uslovie['other'])){
            foreach($uslovie['other'] as $key => $val){
                if($val && !is_array($val)){
                    $this->db->where($key,$val);
                }
                    
            }
            
        }
        $this->db->order_by('wh.currenttime');
        $this->db->group_by('wh.id');
            
        $query = $this->db->get();
        //echo( $this->db->last_query());
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $item) {
                $listObj='';
                $listPersonal='';
                //Проверка в ТКД
                $this->db->select('o.object_name, st.street, o.house, o.pod')->from('object o');
                $this->db->join('works_object wo', 'o.id=wo.object_id');
                $this->db->join('street st', 'st.id=o.street_id');
                $this->db->where('wo.works_id',$item->id);
                $queryObj = $this->db->get();
                if($queryObj->num_rows()>0){
                    foreach($queryObj->result() as $obj){
                        $listObj .= $obj->street . " д." . $obj->house . " п." . $obj->pod . " (" . $obj->object_name . ")<br>";
                    }
                }else{
                    //Проверка в коммутаторах
                    $this->db->select('o.object_name, st.street, o.house, o.pod')->from('switch s');
                    $this->db->join('works_switch ws', 's.id=ws.switch_id');
                    $this->db->join('object o','s.object_id=o.id');
                    $this->db->join('street st', 'st.id=o.street_id');
                    $this->db->where('ws.works_id', $item->id);
                    $querySw = $this->db->get();
                    if($querySw->num_rows()>0){
                        foreach($querySw->result() as $sw){
                            $listObj .= $sw->street . " д." . $sw->house . " п." . $sw->pod . " (" . $sw->object_name . ")<br>";
                            //echo($sw->switch) . "<br>";
                        }
                    }
                }
                
                //проверка бригад исполнителей
                $queryPersonal = $this->db->query('select p.familiya from personal p join works_history_personal whp
                                                    on p.id = whp.personal_id
                                                    WHERE whp.history_id = ' . $item->whid);
                //$queryPersonal = $this->db->get();
                if($queryPersonal->num_rows()!=0){
                    foreach($queryPersonal->result() as $personal){
                        $listPersonal .= $personal->familiya . '<br>';
                    }
                }
                //-----------------
                //'w.id, wh.id as whid, u.fio, w.description as opisanie, wt.tip, wh.description as primechanie, wh.data_deystviya, we.deystvie
                $result[] = array('fio'=>$item->fio, 'opisanie'=>$item->opisanie, 'tip'=>$item->tip, 'primechanie'=>$item->primechanie,
                                    'data_deystviya'=>$item->data_deystviya, 'deystvie'=>$item->deystvie, 'objects'=>$listObj, 'personal'=>$listPersonal);
            }
            return $result;
        } else {
            return false;
        }        
    }
}