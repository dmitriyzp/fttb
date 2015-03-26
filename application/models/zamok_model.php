<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Zamok_model extends CI_Model{
    
    public function __construct(){
        parent::__construct();
    }
    
    public function getZamokList(){
        $result = array();
        $this->db->select('id, zamok')->from('zamok')->where_not_in('ustanovlen','yes')->order_by('zamok', 'ASC');
        $query=$this->db->get();
        foreach($query->result_array() as $item){
            $result[] = $item;
        }
        return $result;
        
    }
    
    public function getSeriyaList(){
        $result = array();
        $this->db->select('seriya')->distinct()->from('zamok')->order_by('seriya');
        $query = $this->db->get();
        foreach($query->result_array() as $item){
            $result[]=$item;
        }
        return $result;
    }
    
    public function setupZamok($id,$data){
        $this->db->where('id',$id);
        $ret = $this->db->update('zamok',$data);
        return $ret;
    }
    
    public function getZamokListFull($uslovie, $order=false){
        $result=array();
        $this->db->select('z.id, z.zamok, z.seriya, z.datavidachi, z.dataustanovki, z.ustanovlen,
			o.object_name, o.id as objID, o.prefix, s.street, o.house, o.pod, p.familiya');
        $this->db->from('zamok z');
        $this->db->join('object o','o.zamok_id=z.id','left');
        $this->db->join('street s','o.street_id=s.id','left');
        $this->db->join('personal p','z.personal_id=p.id','left');
        if(!empty($uslovie)){
            foreach($uslovie as $key =>$val){
                if(!empty($val))
                    $this->db->where($key, $val);
            }
        }
        if(!empty($order))
            $this->db->order_by($order['column'],$order['direction']);
        else
            $this->db->order_by('z.zamok');
        $query = $this->db->get();
        foreach($query->result_array() as $item){
            $result[]=$item;
        }
        if(!empty($result))
            return $result;
        return false;
        
        
    }
}