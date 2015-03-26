<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Zamok extends CI_Controller {
    
    function __construct() {
        parent::__construct();
        if (!$this->session->userdata('loggedin'))
            redirect(base_url() . "start");
    }
    
    function showZamok(){
        $data = array();
        $this->load->model('zamok_model');
        $this->load->model('personal_model');
        $data['seriya'] = $this->zamok_model->getSeriyaList();
        $data['personal'] = $this->personal_model->listPersonal();
        $this->load->view('header_view');
        $this->load->view('ved/zamok_list_view', $data);
        $this->load->view('footer_view');
    }
    
    function getDataTable(){
        $order = array();
        $uslovie = array('z.seriya'=>$this->input->get('seriya'),
                        'z.ustanovlen'=>$this->input->get('ustanovlen'));
        $result = array();
        $this->load->model('zamok_model');
        if($this->input->get('column')){
            $order['column'] = $this->input->get('column');
            $order['direction'] = $this->input->get('direction');
        }        
        $result = $this->zamok_model->getZamokListFull($uslovie, $order);
        echo json_encode($result);
        
    }
    
    function giveZamok(){
        $data = array();
        $this->load->model('zamok_model');
        $data['id'] = $this->input->get('idZamok');
        $data['personal_id'] = $this->input->get('idPersonal');
        $data['datavidachi'] = date('Y-m-d');
        $this->zamok_model->setupZamok($data['id'], $data);
    }
    
}