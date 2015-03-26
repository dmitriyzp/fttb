<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Gpo_model extends CI_Model{
    
    public function __construct() {
        parent::__construct();
    }
    
     public function listGpo($where){
        $result=array();
        $this->db->select('id, gpo');
        $this->db->from('gpo');
        if($where) {
            foreach($where as $keys => $val){
                if($val)
                    $this->db->where($keys, $val);
            }
        }
        $query = $this->db->get();
        if ($query->num_rows()>0){
            foreach($query->result() as $item){
                $result[] = array ('id' => $item->id, 'gpo' => $item->gpo);
            }
            return $result;
        }else{
            return false;
        }
    
    }
    
    public function Delete ($uslovie){
        $this->db->query("DELETE FROM gpo WHERE id = {$uslovie}");
        
    }
    public function updateGpo($addData, $uslovie){
        $result = $this->db->query("UPDATE gpo SET gpo='{$addData['gpo']}' WHERE id={$uslovie}");
        if($result)
            return true;
        return false;
    }
    
    public function addGpo($data) {
        $this->db->select();
        $this->db->where('gpo', $data['gpo']);
        $this->db->from('gpo');
        if(!$this->db->count_all_results()){
            $this->db->insert('gpo', $data);
            return true;
        }
        return false;
    }
    
    public function getGpoList(){
        $result = array();
        $this->db->select('id, gpo')->from('gpo');
        $query=$this->db->get();
        foreach($query->result_array() as $item){
            $result[] = $item;
        }
        return $result;
        
    }
}