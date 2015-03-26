<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
class Forms_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getData($table, $sel, $uslovie=false) {
        $this->db->select($sel);
        $this->db->from($table);
        if($uslovie)
            $this->db->where($uslovie);
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        foreach($query->result_array() as $item){
            $result[]=$item;
        }
        return $result;
    }
    
    public function getDataTables($id){
        $result = array();
        $this->db->select('f.description as formname, ffl.description as fieldname, ffp.islocked, ffp.id as fieldid');
        $this->db->from('form_field_permission ffp');
        $this->db->join('form_fields_list ffl','ffl.id=ffp.field_id');
        $this->db->join('forms f','f.id=ffl.form_id');
        $this->db->where('ffp.role_id',$id);
        $query = $this->db->get();
        if($query->num_rows() == 0)
            return false;
        foreach($query->result_array() as $item){
            $result[]=$item;
        }
        return $result;
        
    }
    
    public function addNewField($data, $uslovie){
        $this->db->select('id');
        $this->db->from('form_field_permission');
        $this->db->where('field_id',$uslovie['field_id']);
        $this->db->where('role_id',$uslovie['role_id']);
        $query=$this->db->get();
        if($query->num_rows()>0)
            return false;
        $this->db->insert('form_field_permission',$data);
    }
    
    public function updateFieldPermission($update)
    {     
        foreach ($update as $item) {
            $result = $this->db->query("UPDATE form_field_permission SET islocked={$item['islocked']} WHERE id={$item['id']}");
        }
    }
    
}