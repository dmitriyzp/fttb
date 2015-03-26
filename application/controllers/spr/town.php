<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
class Town extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
         if (!$this->session->userdata('loggedin'))
            redirect(base_url() . "start");
    }
    
    public function showTown()
    {
        $this->load->model('town_model');
        $filter = [];
        $data = ['tblarray' => $this->town_model->listTown($filter)];
        $this->load->view('header_view');
        $this->load->view('spr/town_list_view', $data);
        $this->load->view('footer_view');
    }

    public function delete($id)
    {
        $this->load->model('town_model');
        $this->town_model->Delete($id);
        redirect(base_url() . "spr/town/showTown");
    }

    public function edit($id)
    {
        $this->load->model('town_model');
        $filter = [];
        if ($this->input->post('id')) {

            $updateData = ['town' => $this->input->post('town')];
            $res = $this->town_model->updateTown($updateData, $this->input->post('id'));
            redirect(base_url() . "spr/town/showTown");
        } else {
            if ($id) {
                $filter = ['id' => $id];
                $data = ['tblarray' => $this->town_model->listTown($filter), 'id' => $id];
            }
            $this->load->view('header_view');
            $this->load->view('spr/town_form_view', $data);
            $this->load->view('footer_view');
        }

    }


    public function add()
    {
        $this->load->model('town_model');
        if ($this->input->post('save')) {
            $data = array();
            $data['town'] = $this->input->post('town');
            $result = $this->town_model->addTown($data);
            //if($result) TODO: написать обработчик ошибок
            redirect(base_url() . "spr/town/showTown");
        } else {
            $this->load->view('header_view');
            $this->load->view('spr/town_form_view');
            $this->load->view('footer_view');
        }
    }
    
}