<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Town_model extends CI_Model{
    
    public function __construct() {
        parent::__construct();
    }
    
     public function listTown($where=false){
        $result=array();
        $this->db->select('id, town');
        $this->db->from('town');
        if($where) {
            foreach($where as $keys => $val){
                if($val)
                    $this->db->where($keys, $val);
            }
        }
        $query = $this->db->get();
        if ($query->num_rows()>0){
            foreach($query->result() as $item){
                $result[] = array ('id' => $item->id, 'town' => $item->town);
            }
            return $result;
        }else{
            return false;
        }
    
    }
    
    public function Delete ($uslovie){
        $this->db->query("DELETE FROM town WHERE id = {$uslovie}");
        
    }
    public function updateTown($addData, $uslovie){
        $result = $this->db->query("UPDATE town SET town='{$addData['town']}' WHERE id={$uslovie}");
        if($result)
            return true;
        return false;
    }
    
    public function addTown($data) {
        $this->db->select();
        $this->db->where('town', $data['town']);
        $this->db->from('town');
        if(!$this->db->count_all_results()){
            $this->db->insert('town', $data);
            return true;
        }
        return false;
    }
}