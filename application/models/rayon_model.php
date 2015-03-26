<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Rayon_model extends CI_Model{
    
    public function __construct() {
        parent::__construct();
    }
    
     public function listRayon($where=false, $type = 'where'){
        $result=array();
        $this->db->select('id, rayon');
        $this->db->from('rayon');
        if($where) {
            foreach($where as $keys => $val){
                if($val)
                    if($type=='where')
                        $this->db->where($keys, $val);
                    else
                        $this->db->like($keys, $val);
            }
        }
        $query = $this->db->get();
        if ($query->num_rows()>0){
            foreach($query->result() as $item){
                $result[] = array ('id' => $item->id, 'rayon' => $item->rayon);
            }
            return $result;
        }else{
            return false;
        }
    
    }
    
    public function Delete ($uslovie){
        $this->db->query("DELETE FROM rayon WHERE id = {$uslovie}");
        
    }
    public function updateRayon($addData, $uslovie){
        $result = $this->db->query("UPDATE rayon SET rayon='{$addData['rayon']}' WHERE id={$uslovie}");
        if($result)
            return true;
        return false;
    }
    
    public function addRayon($data) {
        $this->db->select();
        $this->db->where('rayon', $data['rayon']);
        $this->db->from('rayon');
        if(!$this->db->count_all_results()){
            $this->db->insert('rayon', $data);
            return true;
        }
        return false;
    }
    
    public function getRayonList(){
        $result = array();
        $this->db->select('id, rayon')->from('rayon');
        $query=$this->db->get();
        foreach($query->result_array() as $item){
            $result[] = $item;
        }
        return $result;
        
    }
}