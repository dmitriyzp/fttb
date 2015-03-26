<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Switches extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
         if (!$this->session->userdata('loggedin'))
            redirect(base_url() . "start");
    }

    public function getDataTable()
    {
        if ($this->input->get('find')) {
            $result = array();
            $uslovie = array();
            $order = array();
            $uslovie[] = array('inservice' => $this->input->get('inservice'));
            $uslovie[] = array('mdu' => $this->input->get('mduName'));
            $uslovie[] = array('object_name' => $this->input->get('tkd'));
            $uslovie[] = array('ip' => $this->input->get('ip'));
            $uslovie[] = array('ring' => $this->input->get('ring'));
            $uslovie[] = array('equipment' => $this->input->get('equipment'));
            if($this->input->get('column')){
                $order['column'] = $this->input->get('column');
                $order['direction'] = $this->input->get('direction');
            }
            $this->load->model('switch_model');
            $result = $this->switch_model->getTable($uslovie, $order);
            echo json_encode($result);
//            $this->load->view('header_view');
//            $this->load->view('ved/switch_list_view', $data);
//            $this->load->view('footer_view');
        }
    }

    public function showSwitches()
    {
        $data = array();
        $this->load->library('MyLib.php');
        $this->load->model('works_model');
        $data['typeWork'] = $this->works_model->getTypeWorks();
        $data['mostUseProblem'] = $this->mylib->mostUseProblem();
        $this->load->view('header_view');
        $this->load->view('ved/switch_list_view', $data);
        $this->load->view('footer_view');

    }

    public function getTkdList()
    {
        $result = array();
        $myTkdArray = array();
        $this->load->model('object_model');
        $uslovie = array('m.mdu' => $this->input->get('mdu'));
        $result = $this->object_model->listTkd($uslovie);
        echo json_encode($result);
    }

    public function getMduList()
    {
        $result = array();
        $uslovie = array('mdu' => 'MDU');
        $this->load->model('mdu_model');
        $result = $this->mdu_model->listMdu($uslovie, 'like');
        echo json_encode($result);
    }

    public function getRingList()
    {
        $result = array();
        $this->load->model('ring_model');
        $result = $this->ring_model->getRingList();
        echo json_encode($result);
    }
    
    public function getSfpList()
    {
        $result = array();
        $this->load->model('sfp_model');
        $result = $this->sfp_model->getListSfp();
        echo json_encode($result);
    }
    
    public function getTipportList()
    {
        $result = array();
        $this->load->model('tipport_model');
        $result = $this->tipport_model->getListTipport();
        echo json_encode($result);
    }
    public function getIpList()
    {
        $result = array();
        $this->load->model('switch_model');
        $result = $this->switch_model->getIpList(array('m.mdu' => $this->input->get('mdu')));
        echo json_encode($result);
    }

    public function getEquipmentList()
    {
        $result = array();
        $this->load->model('equipment_model');
        $result = $this->equipment_model->getListEquipment();
        echo json_encode($result);
    }
    
    public function switchInfo($swId, $title='') {
        if($swId){
            $data = array();
            $this->load->model('switch_model');
            $data['result'] = $this->switch_model->getAllInfo($swId);
            $this->load->view('e_header_view', array('title'=>$title));
            $this->load->view('ved/switch_allinfo_view', $data);
            $this->load->view('e_footer_view');
        }
    }


   
    public function changeStatus(){
        $this->load->model('switch_model');
        if($this->input->get('id')){
            $result = $this->switch_model->changeStatus($this->input->get('id'), $this->input->get('status'));
            echo $result;
        }
    }
    
    public function switchUpdate(){
  
        $this->load->model('switch_model');
        $updatedata = array('nbr'=>$this->input->post('nbr'), 'ip' =>$this->inet_aton($this->input->post('ip')),
                            'ring_id' => $this->input->post('ring'), 'equipment_id' => $this->input->post('equipment'), 'serial' => $this->input->post('serial'), 
                            'vlan' => $this->input->post('vlan'), 'id_sfp_25' =>($this->input->post('sfp25')) ? $this->input->post('sfp25') : '0', 'id_sfp_26' => ($this->input->post('sfp26')) ? $this->input->post('sfp26') : '0', 
                            'id_tipport_25' => $this->input->post('tipport25'), 'id_tipport_26' => $this->input->post('tipport26'), 'switch_lldp_25' => $this->input->post('switch_lldp_25'), 
                            'switch_lldp_26' => $this->input->post('switch_lldp_26'), 'data_oprosa_lldp' => $this->input->post('data_oprosa_lldp'), 'nomer_v_kolce' => $this->input->post('nomer_v_kolce'), 
                            'port_na_ds' => $this->input->post('port_na_ds'), 'port_ds_des' => $this->input->post('port_ds_des'), 'proverka_nameswitch'=>($this->input->post('proverka_nameswitch')) ? '1' : '0');
//        foreach($updatedata as $key=>$val){
//            if(strlen($val) == 0)
//                unset($updatedata[$key]);
//        }
        $ret = $this->switch_model->updateSwitch($this->input->post('switch'), $updatedata);
        $this->load->view('header_view');
        $this->load->view('ved/switch_list_view');
        $this->load->view('footer_view');
  
    }
    
    function inet_aton($ip){
      $ip = ip2long($ip);
      ($ip < 0) ? $ip+=4294967296 : true;
      return $ip;
    }
    
    function inet_ntoa($int){
      return long2ip($int);
    }
    
    public function add2Work() {
        $this->load->library('MyLib.php');
        $data['switch'] = $this->input->post('sw');
        $data['typeWork'] = $this->input->post('typeWork');
        $data['uID'] = $this->session->userdata('uID');
        $data['description'] = $this->input->post('description');
        $data['srok'] = $this->mylib->date2Msql($this->input->post('srok'));
        $this->load->model('works_model');
        $this->works_model->addNewWork($data, 'switch');
        
        //var_dump($data) ;
    }
}
