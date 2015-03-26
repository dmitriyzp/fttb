<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Sfp_model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }
    
    public function getListSfp(){
        $result = array();
        $this->db->select('id, sfp');
        $this->db->from('sfp');
        $query = $this->db->get();
        foreach($query->result() as $item){
            $result[] = array('id'=>$item->id, 'sfp'=>$item->sfp);
        }
        return $result;
       
    }
}