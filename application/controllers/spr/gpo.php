<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
class Gpo extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
         if (!$this->session->userdata('loggedin'))
            redirect(base_url() . "start");
    }
    
    public function showGpo()
    {
        $this->load->model('gpo_model');
        $filter = [];
        $data = ['tblarray' => $this->gpo_model->listGpo($filter)];
        $this->load->view('header_view');
        $this->load->view('spr/gpo_list_view', $data);
        $this->load->view('footer_view');
    }

    public function delete($id)
    {
        $this->load->model('gpo_model');
        $this->gpo_model->Delete($id);
        redirect(base_url() . "spr/gpo/showGpo");
    }

    public function edit($id)
    {
        $this->load->model('gpo_model');
        $filter = [];
        if ($this->input->post('id')) {

            $updateData = ['gpo' => $this->input->post('gpo')];
            $res = $this->gpo_model->updateGpo($updateData, $this->input->post('id'));
            redirect(base_url() . "spr/gpo/showGpo");
        } else {
            if ($id) {
                $filter = ['id' => $id];
                $data = ['tblarray' => $this->gpo_model->listGpo($filter), 'id' => $id];
            }
            $this->load->view('header_view');
            $this->load->view('spr/gpo_form_view', $data);
            $this->load->view('footer_view');
        }

    }


    public function add()
    {
        $this->load->model('gpo_model');
        if ($this->input->post('save')) {
            $data = array();
            $data['gpo'] = $this->input->post('gpo');
            $result = $this->gpo_model->addGpo($data);
            //if($result) TODO: написать обработчик ошибок
            redirect(base_url() . "spr/gpo/showGpo");
        } else {
            $this->load->view('header_view');
            $this->load->view('spr/gpo_form_view');
            $this->load->view('footer_view');
        }
    }
    
}