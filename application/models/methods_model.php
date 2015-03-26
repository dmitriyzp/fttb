<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author FANAT
 * @copyright 2014
 */


class Methods_model extends CI_Model{
    
    public function listMethods($where){
        $result=array();
        $this->db->select('id, pathMethod, description');
        $this->db->from('list_functions');
        if($where) {
            foreach($where as $keys => $val){
                if($val)
                    $this->db->where($keys, $val);
            }
        }
        $query = $this->db->get();
        if ($query->num_rows()>0){
            foreach($query->result() as $item){
                $result[] = array ('id' => $item->id, 'pathMethod' => $item->pathMethod, 'description' => $item->description);
            }
            return $result;
        }else{
            return false;
        }
    
    }
    
    public function Delete ($uslovie){
        $this->db->query("DELETE FROM list_functions WHERE id = {$uslovie}");
        $this->load->model('roles_model');
        $this->roles_model->actualizeRoles('delete',false,$uslovie);
        
    }
    public function updateMethod($addData, $uslovie){
        $result = $this->db->query("UPDATE list_functions SET pathMethod='{$addData['pathMethod']}', description='{$addData['description']}' WHERE id={$uslovie}");
        if($result)
            return true;
        return false;
    }
    
    public function addMethod($data) {
        $this->db->select();
        $this->db->where('pathMethod', $data['pathMethod']);
        $this->db->from('list_functions');
        if(!$this->db->count_all_results()){
            $this->db->insert('list_functions', $data);
            $this->load->model('roles_model');
            $this->roles_model->actualizeRoles('add',false,$this->db->insert_id());
            return true;
        }
        return false;
    }
}