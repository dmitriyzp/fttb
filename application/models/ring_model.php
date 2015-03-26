<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    

class Ring_model extends CI_Model {
    
    public function __construct(){
        parent::__construct();
    }
    
    public function getRingList(){
        $result = array();
        $this->db->select('ring');
        $this->db->from('ring');
        $this->db->distinct();
        $query = $this->db->get();
        foreach($query->result() as $item){
            $result[] = array('ring'=>$item->ring);
        }
        return $result;
    }
    
}