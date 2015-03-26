<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
class Sklad extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
         if (!$this->session->userdata('loggedin'))
            redirect(base_url() . "start");
    }
    
    public function showSklad()
    {
        $this->load->model('sklad_model');
        $filter = [];
        $data = ['tblarray' => $this->sklad_model->listSklad($filter)];
        $this->load->view('header_view');
        $this->load->view('spr/sklad_list_view', $data);
        $this->load->view('footer_view');
    }

    public function delete($id)
    {
        $this->load->model('sklad_model');
        $this->sklad_model->Delete($id);
        redirect(base_url() . "spr/sklad/showSklad");
    }

    public function edit($id)
    {
        $this->load->model('sklad_model');
        $filter = [];
        if ($this->input->post('id')) {

            $updateData = ['sklad' => $this->input->post('sklad')];
            $res = $this->sklad_model->updateSklad($updateData, $this->input->post('id'));
            redirect(base_url() . "spr/sklad/showSklad");
        } else {
            if ($id) {
                $filter = ['id' => $id];
                $data = ['tblarray' => $this->sklad_model->listSklad($filter), 'id' => $id];
            }
            $this->load->view('header_view');
            $this->load->view('spr/sklad_form_view', $data);
            $this->load->view('footer_view');
        }

    }


    public function add()
    {
        $this->load->model('sklad_model');
        if ($this->input->post('save')) {
            $data = array();
            $data['sklad'] = $this->input->post('sklad');
            $result = $this->sklad_model->addSklad($data);
            //if($result) TODO: написать обработчик ошибок
            redirect(base_url() . "spr/sklad/showSklad");
        } else {
            $this->load->view('header_view');
            $this->load->view('spr/sklad_form_view');
            $this->load->view('footer_view');
        }
    }
    
}