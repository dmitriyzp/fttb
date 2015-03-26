<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Main_model extends CI_Model{
    
    public function getList($table, $field){
        $result = array();
        $this->db->select("id, " . $field);
        $this->db->from($table);
        $query = $this->db->get();
        if($query->num_rows()==0)
            return;
        foreach($query->result() as $item){
            $result[$item->id] = $item->$field;
        }
        return $result;
    }
    
    
    public function getFormFieldPerm($formName)
    {
        $result = array();
        $this->db->select('fl.fieldname, fp.islocked');
        $this->db->from('form_field_permission fp');
        $this->db->join('form_fields_list fl', 'fl.id=fp.field_id');
        $this->db->join('forms f','f.id=fl.form_id');
        $this->db->join('roles r', 'r.id=fp.role_id');
        $this->db->where('islocked','1');
        $this->db->where('f.form',$formName);
        $this->db->where('r.role',$this->session->userdata('role'));
        $query = $this->db->get();
        if($query->num_rows()==0)
            return false;
        foreach($query->result() as $item){
            $result[] = array('fieldname'=>$item->fieldname);
        }
        return $result;
    }
    
    
    public function addWcheck($table, $data, $uslovie) {
        $this->db->select();
        $this->db->where($uslovie['key'], $uslovie['val']);
        $this->db->from($table);
        if(!$this->db->count_all_results()){
            $this->db->insert($table, $data);
            return true;
        }
        return false;
    }
    
    public function deleteRecord ($table, $uslovie){
        $this->db->where($uslovie['key'], $uslovie['val']);
        $this->db->delete($table);
    }
    
    public function markDelete ($table, $uslovie){
        $this->db->query("UPDATE {$table} SET isactive=0 WHERE {$uslovie['key']} = {$uslovie['val']}");
        
    }
}