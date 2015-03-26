<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Mdu_model extends CI_Model{
    
    public function __construct() {
        parent::__construct();
    }
    
     public function listMdu($where, $type = 'where'){
        $result=array();
        $this->db->select('m.id, m.mdu, r.rayon');
        $this->db->from('mdu m');
        $this->db->join('rayon r','m.id_rayon=r.id','left');
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
                $result[] = array ('id' => $item->id, 'mdu' => $item->mdu, 'rayon'=>$item->rayon);
            }
            return $result;
        }else{
            return false;
        }
    
    }
    
    public function Delete ($uslovie){
        $this->db->query("DELETE FROM mdu WHERE id = {$uslovie}");
        
    }
    public function updateMdu($addData, $uslovie){
        $result = $this->db->query("UPDATE mdu SET mdu='{$addData['mdu']}', id_rayon={$addData['id_rayon']} WHERE id={$uslovie}");
        if($result)
            return true;
        return false;
    }
    
    public function addMdu($data) {
        $this->db->select();
        $this->db->where('mdu', $data['mdu']);
        $this->db->from('mdu');
        if(!$this->db->count_all_results()){
            $this->db->insert('mdu', $data);
            return true;
        }
        return false;
    }
    
    public function getMduList(){
        $result = array();
        $this->db->select('id, mdu')->from('mdu');
        $query=$this->db->get();
        foreach($query->result_array() as $item){
            $result[] = $item;
        }
        return $result;
        
    }
}