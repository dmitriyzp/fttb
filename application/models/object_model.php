<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Object_model extends CI_Model
{

    public function listTkd($where="")
    {
        $result = array();
        $this->db->select('o.object_name as tkd');
        $this->db->from('object o');
        $this->db->join('mdu m', 'o.mdu_id = m.id');
        $this->db->join('town t', 'o.gorod_id=t.id');
        if ($where)
            $this->db->like($where);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $item) {
                $result[] = array('tkd' => $item->tkd);
            }
            return $result;
        } else {
            return false;
        }

    }

    public function getTable($uslovie, $order=false)
    {
        $result = array();
        $this->db->select('o.id, o.object_name, m.mdu, o.prefix, s.street, o.house, o.pod, g.gpo, o.kluch, t.town');
        $this->db->from('object o');
        $this->db->join('mdu m', 'm.id=o.mdu_id');
        $this->db->join('street s', 's.id=o.street_id');
        $this->db->join('gpo g', 'g.id=o.gpo_id');
        $this->db->join('town t', 't.id=o.gorod_id');
        foreach ($uslovie as $valArr) {
            foreach ($valArr as $key => $val) {
                if ($val) {
                    if ($key == 'house')
                        $this->db->where($key, $val);
                    else
                        $this->db->like($key, $val);
                }
            }

        }
        if(!empty($order))
            $this->db->order_by($order['column'],$order['direction']);
        else
            $this->db->order_by('o.object_name','ASC');
        
        $query = $this->db->get();
        if ($query->num_rows() == 0)
            return false;
        foreach ($query->result() as $item) {
            $result[] = array(
                'id' => $item->id,
                'object_name' => $item->object_name,
                'mdu' => $item->mdu,
                'prefix' => $item->prefix,
                'street' => $item->street,
                'house' => $item->house,
                'pod' => $item->pod,
                'kluch' => $item->kluch,
                'town' => $item->town,
                'gpo' => $item->gpo);
        }
        return $result;
    }

    public function getAllData($objId)
    {
        $result = array();
        $this->db->select('o.object_name, t.town, o.prefix, s.street, o.house, o.pod, m.mdu, g.gpo, o.kluch, o.prenadleg_pod, 
                        z.zamok, o.raspologenie, o.dostup, o.nakl_vlasnist, o.nakl_molniya, o.nakl_shema, 
                        o.kreplenie_switch, o.dop_oborud, o.avtomat_tkd, o.avtomat_yashik, o.birka_shitok, 
                        o.podkl_pitanie, o.tip_kabel_pitaniya, o.optika1_dest, o.optika1_birka_in, o.optika1_sost, 
                        o.optika2_dest, o.optika2_birka_in, o.optika2_sost, o.optika3_dest, o.optika3_birka_in, 
                        o.optika3_sost, o.optika4_dest, o.optika4_birka_in, o.optika4_sost, o.optika1_birka_out, 
                        o.optika2_birka_out, o.optika3_birka_out, o.optika4_birka_out, o.address_proveren, o.month_ppo, 
                        o.percent_work_ppo, o.foto, o.sost_zamka');
        $this->db->from('object o');
        $this->db->join('mdu m', 'm.id=o.mdu_id');
        $this->db->join('zamok z', 'o.zamok_id=z.id', 'left');
        $this->db->join('street s', 's.id=o.street_id');
        $this->db->join('gpo g', 'g.id=o.gpo_id');
        $this->db->join('town t', 't.id=o.gorod_id');
        $this->db->where('o.id',$objId);
        $query = $this->db->get();
        if($query->num_rows() == 1)
            return $query->row_array();
        else
            return false;
        
    }
    
    public function updateObject($where, $data){
        $this->db->where($where['field'], $where['val']);
         $result = $this->db->update('object', $data);
         return $result;
    }
}
