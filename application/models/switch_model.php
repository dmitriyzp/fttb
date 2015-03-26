<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Switch_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
    }

    public function getIpList($where)
    {
        $result = array();
        $this->db->select('INET_NTOA(sw.ip) as ip');
        $this->db->from('switch sw');
        $this->db->join('object o', 'sw.object_id = o.id');
        $this->db->join('mdu m', 'o.mdu_id=m.id');
        if ($where)
            $this->db->like($where);
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $item) {
                $result[] = array('ip' => $item->ip);
            }
            return $result;
        } else {
            return false;
        }

    }

    public function getTable($where, $order=false)
    {
        $result = array();
        $this->db->select('sw.id as swId, sw.switch, sw.nbr, o.id as oID, o.prefix, st.street, o.house, o.kluch, o.pod, o.object_name, INET_NTOA(sw.ip) as ip, r.ring, e.equipment, sw.serial, sw.inservice, sw.vlan');
        $this->db->from('switch sw');
        $this->db->join('object o', 'o.id=sw.object_id');
        $this->db->join('ring r', 'sw.ring_id = r.id', 'left');
        $this->db->join('equipment e', 'e.id=sw.equipment_id');
        $this->db->join('street st', 'o.street_id=st.id');
        $this->db->join('mdu m', 'm.id=o.mdu_id');
        $this->db->where('sw.isactive','1');
        if ($where) {
            foreach ($where as $numArr) {
                foreach ($numArr as $key => $val) {
                    if ($val) {
                        if ($key == 'ip')
                            $this->db->where($key, "INET_ATON('" . $val . "')", false);
                        else
                            $this->db->like($key, $val);
                    }
                }
            }
        }
         if(!empty($order))
            $this->db->order_by($order['column'],$order['direction']);
         else
            $this->db->order_by('sw.switch', 'ASC');
            
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $item) {
                $result[] = array(
                    'id' => $item->swId,
                    'switch_name' => $item->switch,
                    'nbr' => $item->nbr,
                    'prefix' => $item->prefix,
                    'street' => $item->street,
                    'house' => $item->house,
                    'pod' => $item->pod,
                    'object_name' => $item->object_name,
                    'ip' => $item->ip,
                    'ring' => $item->ring,
                    'equipment' => $item->equipment,
                    'serial' => $item->serial,
                    'vlan' => $item->vlan,
                    'inservice' => $item->inservice,
                    'kluch'=> $item->kluch,
                    'oID'=>$item->oID);
            }
            return $result;
        } else {
            return false;
        }


    }

    public function getAllInfo($swId)
    {
        $this->db->select('sw.id, sw.switch, sw.nbr, o.object_name, INET_NTOA(sw.ip) as ip, r.ring, e.equipment, 
                        sw.serial, sw.inservice, sw.vlan, (sf1.sfp) as sfp25, (sf2.sfp) as sfp26, 
                        (tp1.tipport) as tipport25, (tp2.tipport) as tipport26, sw.switch_lldp_25, sw.switch_lldp_26, 
                        sw.proverka_nameswitch, sw.data_oprosa_lldp, sw.nomer_v_kolce, sw.port_na_ds, sw.port_ds_des');
        $this->db->from('switch sw');
        $this->db->join('object o', 'o.id=sw.object_id');
        $this->db->join('ring r', 'sw.ring_id = r.id', 'left');
        $this->db->join('equipment e', 'e.id=sw.equipment_id');
        $this->db->join('mdu m', 'm.id=o.mdu_id');
        $this->db->join('sfp sf1', 'sf1.id=sw.id_sfp_25', 'left');
        $this->db->join('sfp sf2', 'sf2.id=sw.id_sfp_26', 'left');
        $this->db->join('tipport tp1', 'tp1.id=sw.id_tipport_25', 'left');
        $this->db->join('tipport tp2', 'tp2.id=sw.id_tipport_26', 'left');
        $this->db->where('sw.id', $swId);
        $query = $this->db->get();
        if ($query->num_rows() == 1)
            return $query->row_array();
        else
            return false;


    }


    
    public function changeStatus($id, $status){
        $this->db->where('id',$id);
        $result = $this->db->update('switch',array('inservice'=>$status));
        return $result;
    }
    
    public function updateSwitch($switch, $data){
        $this->db->where('switch', $switch);
         $result = $this->db->update('switch', $data);
         return $result;
    }
    
    public function getNextParamsSwitch($tkd){
        
        /* query1 - VLAN
        select INET_NTOA(s.ip) as swIP, s.ring_id, s.switch, s.vlan from switch s 
INNER JOIN object o ON s.object_id = o.id
where s.ip BETWEEN (INET_ATON('10.207.128.1')) AND (INET_ATON('10.207.128.254')) and  o.object_name = 'MDU_BRD00128_1021'
        */
        $result = array();//конечный результат
        $temp = array();//временное хранилище результатов
        $this->db->select('INET_NTOA(s.ip) as swIP, s.ring_id, s.object_id, s.switch, s.vlan, m.mdu');
        $this->db->from('switch s');
        $this->db->join('object o','s.object_id = o.id');
        $this->db->join('mdu m', 'm.id = o.mdu_id');
        $this->db->where('o.object_name',$tkd);
        
        $query = $this->db->get();
        for($cnt=1;$cnt<50;$cnt++){
            $temp['switchNum'][$cnt]=$cnt;
            $temp['vlan'][$cnt]=$cnt;
        }
        //Определяем следующие номера vlan и switchName
        if($query->num_rows()>0){
               foreach($query->result() as $item){
                    //unset($temp['vlan'][$item->vlan]);
                    $swName = $item->switch;
                    unset($temp['switchNum'][$swName{strlen($swName)-1}]);
                    $temp['curIp']= $item->swIP;
                    $temp['ring_id'] = $item->ring_id;
                    $temp['mdu'] = $item->mdu;
                    $temp['object_id'] = $item->object_id;
               }
        }
        
        //VLAN
        
        $this->db->select('INET_NTOA(s.ip) as swIP, s.ring_id, s.object_id, s.switch, s.vlan, m.mdu');
        $this->db->from('switch s');
        $this->db->join('object o','s.object_id = o.id');
        $this->db->join('mdu m', 'm.id = o.mdu_id');
        $this->db->where('s.ring_id',$temp['ring_id']);
        $this->db->where('m.mdu',$temp['mdu']);
        
        $query = $this->db->get();
        //Определяем следующие номера vlan и switchName
        if($query->num_rows()>0){
               foreach($query->result() as $item){
                    unset($temp['vlan'][$item->vlan]);
                    
               }
        }
        
        //vlan 
        $pattIp = "/\\d{1,3}.\\d{1,3}.\\d{1,3}./";
        preg_match($pattIp,$temp['curIp'],$temp['subNet']);
        for($cnt=1;$cnt<254;$cnt++){
            $temp['ip'][$cnt]=$temp['subNet'][0] . $cnt;
        }        
        $this->db->select('INET_NTOA(s.ip) as swIP, s.ring_id, s.object_id, s.switch, s.vlan');
        $this->db->from('switch s');
        $this->db->join('object o','s.object_id = o.id');
        $this->db->where("s.ip BETWEEN (INET_ATON('" . $temp['subNet'][0] . "1')) AND (INET_ATON('" . $temp['subNet'][0] . "254'))");
        $query = $this->db->get();
        foreach($query->result() as $item){
            $numArr = substr($item->swIP,strlen($temp['subNet'][0]));
            unset($temp['ip'][$numArr]);
        }
        
        $result['newSwitch'] = $tkd . '_' . array_shift($temp['switchNum']);
        $result['newVlan'] = array_shift($temp['vlan']);
        $result['newIp'] = array_shift($temp['ip']);
        $result['object_id'] = $temp['object_id'];
        $result['ring_id'] = $temp['ring_id'];
        
        return $result;
        
        /*
            
        */
    }
    
    public function addSwitch($data){
        $this->load->library('MyLib.php');
        $data['ip'] = $this->mylib->inet_aton($data['ip']);
        $data['inservice'] = 'plan';
        $this->db->select('ip,switch')->from('switch');
        $this->db->where('ip', $data['ip']);
        $this->db->or_where('switch',$data['switch']);
        $query=$this->db->get();
        if($query->num_rows() == 0){
            $this->db->insert('switch', $data);
        }
    }

}
