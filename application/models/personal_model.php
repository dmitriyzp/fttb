<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Personal_model extends CI_Model{
    
    public function __construct() {
        parent::__construct();
    }
    
     public function listPersonal($where=""){
        $result=array();
        $this->db->select('id, familiya, name, otchestvo, email, phone, datebirth, passport');
        $this->db->from('personal');
        $this->db->where('isactive','1');
        if(is_array($where)) {
            foreach($where as $keys => $val){
                if($val)
                    $this->db->where($keys, $val);
            }
        }
        $query = $this->db->get();
        if ($query->num_rows()>0){
            foreach($query->result() as $item){
                $result[] = array ('id' => $item->id, 'familiya' => $item->familiya, 'otchestvo'=>$item->otchestvo, 
                                    'name'=>$item->name,'phone'=>$item->phone,'email'=>$item->email, 'datebirth' => $item->datebirth, 'passport' => $item->passport);
            }
            return $result;
        }else{
            return false;
        }
    
    }
    
    public function Delete ($uslovie){
        $this->db->query("UPDATE personal SET isactive=0 WHERE id = {$uslovie}");
        
    }
    public function updatePersonal($addData, $uslovie){
        $result = $this->db->query("UPDATE personal SET phone='{$addData['phone']}', email='{$addData['email']}', name='{$addData['name']}', familiya='{$addData['familiya']}', otchestvo='{$addData['otchestvo']}', datebirth='{$addData['datebirth']}', passport='{$addData['passport']}' WHERE id={$uslovie}");
        if($result)
            return true;
        return false;
    }
    
    public function addPersonal($data) {
        $this->db->select();
        $this->db->where('familiya', $data['familiya']);
        $this->db->where('name', $data['name']);
        $this->db->from('personal');
        if(!$this->db->count_all_results()){
            $this->db->insert('personal', $data);
            return true;
        }
        return false;
    }
    
    public function getPersonalList(){
        $result = array();
        $this->db->select('id, familiya')->from('personal');
        $query=$this->db->get();
        foreach($query->result_array() as $item){
            $result[] = $item;
        }
        return $result;
        
    }
}