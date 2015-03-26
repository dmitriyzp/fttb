<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
class Parser_model extends CI_Model{
    
    public function __construct() {
        parent::__construct();
    }
    
    
    public function clearSwitch(){
        $this->db->update('switch',array('isactive'=>0));
        
    }
    
    public function parseHistory($data){
/**
 *     $data['type'] =$val[''];
 *             $data['object'] =$val[''];
 *             $data['opisanie'] =$val[''];
 *             $data['status'] =$val[''];
 *             $data['datastatusa'] =$val[''];
 *             $data['comment'] =$val[''];
 *             $data['ispolniteli'] =$val[''];
 *             $data['operator'] =$val[''];
 */
    $id_type = 0;
    $id_object = 0;
    $id_switch = 0;
    $id_work = 0;
    $id_ispolniteley = array();
    $id_operator = 0;
    $id_history = 0;
    
    //Выяснить ID типа работы
    $this->db->select('id')->from('works_tip')->where('tip', $data['type']);
    $query=$this->db->get();
    if($query->num_rows()>0){
        $res = $query->row();
        $id_type = $res->id;
        unset($res);
    }
    unset($query);
    //Выяснить ID объекта (свитч или ТКД)
    $this->db->select('id')->from('switch')->where('switch', $data['object']);
    $query=$this->db->get();
    if($query->num_rows()>0){
        $res = $query->row();
        $id_switch = $res->id;
        unset($res);
    }else{
        $this->db->select('id')->from('object')->where('object_name', $data['object']);
        $query=$this->db->get();
        if($query->num_rows()>0){
            $res = $query->row();
            $id_object = $res->id;
            unset($res);
        }
    }  
    
        //Выяснить ID ispolniteley
        
    $tehnari = (strlen(trim($data['ispolniteli']))>1) ? explode('|',trim($data['ispolniteli'])) : false;
    if($tehnari){
        foreach($tehnari as $val){
            $this->db->select('id')->from('personal')->where('familiya', $val);
            $query = $this->db->get();
            if($query->num_rows()>0){
                $tehn = $query->row();
                $id_ispolniteley[] = $tehn->id;
            }else{
                $id_ispolniteley[] = $this->selUpdateReturn('personal', array('familiya'=>$val));
            }
        }
        
    }
    
    //Выяснить ID оператора
    $this->db->select('id')->from('users')->like('fio', $data['operator']);
    $query=$this->db->get();
    if($query->num_rows()>0){
        $res = $query->row();
        $id_operator = $res->id;
        unset($res);
    }
    
    //Добавление записи в works
    $arrWorks = array('id_tipwork' => $id_type, 'description' => $data['opisanie'], 'status' => ($data['status'] == 'Новая') ? '1' : '4');
    $this->db->insert('works', $arrWorks);
    $id_work = $this->db->insert_id();
 
     //Добавление записи в object or switch
    if($id_switch){
        $arrSW = array('works_id' => $id_work, 'switch_id' => $id_switch);
        $this->db->insert('works_switch', $arrSW);
    }elseif($id_object){
        $arrObj = array('works_id' => $id_work, 'object_id' => $id_object);
        $this->db->insert('works_object', $arrObj);        
    }else{
        $data['comment'] = $data['comment'] . "(отсутствует в БД " . $data['object'] . ")";
    }
       
        //Добавление записи в works_history
    $arrWH = array('works_id' => $id_work, 'works_event_id' => '4', 'description' => $data['comment'], 
                'data_deystviya' => $data['datastatusa'], 'id_user'=>$id_operator);
    $this->db->insert('works_history', $arrWH);
    $id_history = $this->db->insert_id();
    

    
    if(count($id_ispolniteley)>0){
        foreach($id_ispolniteley as $val){
            $arrTeh = array('history_id' => $id_history, 'personal_id' => $val);
            $this->db->insert('works_history_personal', $arrTeh);
        }
    }
    }
    
    public function selUpdateReturn($table, $where, $tmp = ''){
        $result = array();
        $this->db->select('id')->from($table);
        if($where) {
            foreach($where as $keys => $val){
                if($val)
                    $this->db->where($keys, $val);
            }
        }
        $this->db->limit(1);
        $query=$this->db->get();
        if($query->num_rows() == 1){
            foreach($query->result() as $item){
                return $item->id;
            }
        }else{
            $this->db->insert($table, $where);
            return $this->db->insert_id();
        }
    }
    
    public function selReturn($table, $where){
        $result = array();
        $this->db->select('id')->from($table);
        if($where) {
            foreach($where as $keys => $val){
                if($val)
                    $this->db->where($keys, $val);
            }
        }
        $this->db->limit(1);
        $query=$this->db->get();
        if($query->num_rows() == 1){
            foreach($query->result() as $item){
                return $item->id;
            }
        }
        return false;
    }
    
    public function selUpdateObject($table, $where, $data){
        $result = array();
        $this->db->select('id')->from($table);
        if($where) {
            foreach($where as $keys => $val){
                if($val)
                    $this->db->where($keys, $val);
            }
        }
        $this->db->limit(1);
        $query=$this->db->get();
        if($query->num_rows() == 1){
            foreach($query->result() as $item){
                return $item->id;
            }
        }else{
            
            $ret = $this->db->insert($table, $data);
            return $ret;
        }
    }
    
        public function selUpdateSwitch($table, $where, $data){
        $result = array();
        $this->db->select('id')->from($table);
        if($where) {
            foreach($where as $keys => $val){
                if($val)
                    $this->db->where($keys, $val);
            }
        }
        $this->db->limit(1);
        $query=$this->db->get();
        if($query->num_rows() == 1){
            $this->db->where('ip', $data['ip']);
            $ret = $this->db->update('switch', $data);
            return $ret;
        }else{
            
            $ret = $this->db->insert($table, $data);
            return $ret;
        }
    }
    
        public function selUpdateZamok($table, $where, $data){
        $result = array();
        $this->db->select('id')->from($table);
        if($where) {
            foreach($where as $keys => $val){
                if($val)
                    $this->db->where($keys, $val);
            }
        }
        $this->db->limit(1);
        $query=$this->db->get();
        if($query->num_rows() == 1){
            $this->db->where('zamok', $data['zamok']);
            $ret = $this->db->update('zamok', $data);
            return $ret;
        }else{
            
            $ret = $this->db->insert($table, $data);
            return $ret;
        }
    }
    
}