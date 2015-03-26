<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class History extends CI_Controller {
    
    public function __construct() {
        parent::__construct();
        if (!$this->session->userdata('loggedin'))
            redirect(base_url() . "start");
    }
    
    public function showHistory() {
        $data = array();
        $this->load->model('works_model');
        $this->load->model('personal_model');
        $this->load->model('users_model');
        $data['operator'] = $this->users_model->listUsers();
        $data['typeWork'] = $this->works_model->getTypeWorks();
        $data['personal'] = $this->personal_model->listPersonal();
        $this->load->view('header_view');
        $this->load->view('ved/history_view', $data);
        $this->load->view('footer_view');
        
    }
    
    public function getTkdList()
    {
            $result = array();
            $myTkdArray = array();
            $this->load->model('object_model');
            $myTkdArray = $this->object_model->listTkd();
            foreach ($myTkdArray as $val) {
                $result[] = $val['tkd'];
            }
            echo json_encode($result);
    }
    
    public function getDataTable(){
        $result = array();
        $uslovie = array('personal'=>$this->input->get('personal'), 'other'=>array(
                        
                        'w.id_tipwork'=>$this->input->get('typeWork'),
                        'wh.id_user'=>$this->input->get('operator'),
                        'wh.data_deystviya'=>$this->input->get('srok')),
                        'opisanie'=>$this->input->get('opisanie'),
                        'primechanie'=>$this->input->get('primechanie'),
                        'tkd'=>$this->input->get('tkd'),
                        'status'=>$this->input->get('status'));
        $this->load->model('works_model');
        $result = $this->works_model->getHistory($uslovie);
        echo json_encode($result);
    }
}