<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Tipport_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getListTipport(){
        $result = array();
        $this->db->select('id, tipport');
        $this->db->from('tipport');
        $query = $this->db->get();
        foreach($query->result() as $item){
            $result[] = array('id'=>$item->id, 'tipport'=>$item->tipport);
        }
        return $result;
       
    }
}