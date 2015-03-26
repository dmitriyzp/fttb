<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Equipment_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getListEquipment(){
        $result = array();
        $this->db->select('id, equipment');
        $this->db->from('equipment');
        $query = $this->db->get();
        foreach($query->result() as $item){
            $result[] = array('id'=>$item->id, 'equipment'=>$item->equipment);
        }
        return $result;
       
    }
}