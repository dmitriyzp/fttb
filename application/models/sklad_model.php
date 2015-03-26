<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Sklad_model extends CI_Model{
    
    public function __construct() {
        parent::__construct();
    }
    
     public function listSklad($where){
        $result=array();
        $this->db->select('id, sklad');
        $this->db->from('sklad');
        if($where) {
            foreach($where as $keys => $val){
                if($val)
                    $this->db->where($keys, $val);
            }
        }
        $query = $this->db->get();
        if ($query->num_rows()>0){
            foreach($query->result() as $item){
                $result[] = array ('id' => $item->id, 'sklad' => $item->sklad);
            }
            return $result;
        }else{
            return false;
        }
    
    }
    
    public function Delete ($uslovie){
        $this->db->query("DELETE FROM sklad WHERE id = {$uslovie}");
        
    }
    public function updateSklad($addData, $uslovie){
        $result = $this->db->query("UPDATE sklad SET sklad='{$addData['sklad']}' WHERE id={$uslovie}");
        if($result)
            return true;
        return false;
    }
    
    public function addSklad($data) {
        $this->db->select();
        $this->db->where('sklad', $data['sklad']);
        $this->db->from('sklad');
        if(!$this->db->count_all_results()){
            $this->db->insert('sklad', $data);
            return true;
        }
        return false;
    }
}