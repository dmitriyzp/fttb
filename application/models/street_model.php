<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author FANAT
 * @copyright 2014
 */


class Street_model extends CI_Model{
    

    public function __construct(){
        parent::__construct();
    }    

    
    public function listStreet($where=false){
        $result=array();
        $this->db->select('s.id as id, s.street as street, t.town as town, s.prefix as prefix');
        $this->db->from('street s');
        $this->db->join('town t', 's.id_town=t.id');
        if($where) {
            foreach($where as $keys => $val){
                if($val){
                    $typeW = ($keys == 'id') ? "where" : "like";
                    $this->db->$typeW($keys, $val);                    
                }

            }
        }
        $this->db->where('s.isactive = 1');
        $query = $this->db->get();
        if ($query->num_rows()>0){
            foreach($query->result() as $item){
                $result[] = array ('id' => $item->id, 'street' => $item->street, 'town' => $item->town, 'prefix' => $item->prefix);
            }
            return $result;
        }else{
            return false;
        }
    
    }
    
    
    
    public function getListTown(){
        $result = array();
        $this->db->select("id, town");
        $this->db->from('town');
        $query = $this->db->get();
        if($query->num_rows()==0)
            return;
        foreach($query->result() as $item){
            $result[$item->id] = $item->town;
        }
        return $result;
    }
    
    
    public function markDelete ($uslovie){
        $this->db->query("UPDATE street SET isactive=0 WHERE id = {$uslovie}");
        
    }
    
    
    public function addStreet($data) {
        $this->db->select();
        $this->db->where('street', $data['street']);
        $this->db->where('id_town', $data['id_town']);
        $this->db->where('isactive', '1');
        $this->db->from('street');
        if(!$this->db->count_all_results()){
            $this->db->insert('street', $data);
            return true;
        }
        return false;
    }
    
            
    public function updateStreet($addData, $uslovie){
        $result = $this->db->query("UPDATE street SET street='{$addData['street']}', id_town='{$addData['id_town']}', prefix='{$addData['prefix']}' WHERE id={$uslovie}");
        if($result)
            return true;
        return false;
    }
    
    public function getStreetList($id=false){
        $result = array();
        $this->db->select('id, street')->from('street');
        if($id)
            $this->db->where('id_town', $id);
        $query=$this->db->get();
        foreach($query->result_array() as $item){
            $result[] = $item;
        }
        return $result;
        
    }

}