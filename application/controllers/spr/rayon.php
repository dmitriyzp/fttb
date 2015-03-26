<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
class Rayon extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
         if (!$this->session->userdata('loggedin'))
            redirect(base_url() . "start");
    }
    
    public function showRayon()
    {
        $this->load->model('rayon_model');
        $filter = [];
        $data = ['tblarray' => $this->rayon_model->listRayon($filter)];
        $this->load->view('header_view');
        $this->load->view('spr/rayon_list_view', $data);
        $this->load->view('footer_view');
    }

    public function delete($id)
    {
        $this->load->model('rayon_model');
        $this->rayon_model->Delete($id);
        redirect(base_url() . "spr/rayon/showRayon");
    }

    public function edit($id)
    {
        $this->load->model('rayon_model');
        $filter = [];
        if ($this->input->post('id')) {

            $updateData = ['rayon' => $this->input->post('rayon')];
            $res = $this->rayon_model->updateRayon($updateData, $this->input->post('id'));
            redirect(base_url() . "spr/rayon/showRayon");
        } else {
            if ($id) {
                $filter = ['id' => $id];
                $data = ['tblarray' => $this->rayon_model->listRayon($filter), 'id' => $id];
            }
            $this->load->view('header_view');
            $this->load->view('spr/rayon_form_view', $data);
            $this->load->view('footer_view');
        }

    }


    public function add()
    {
        $this->load->model('rayon_model');
        if ($this->input->post('save')) {
            $data = array();
            $data['rayon'] = $this->input->post('rayon');
            $result = $this->rayon_model->addRayon($data);
            //if($result) TODO: написать обработчик ошибок
            redirect(base_url() . "spr/rayon/showRayon");
        } else {
            $this->load->view('header_view');
            $this->load->view('spr/rayon_form_view');
            $this->load->view('footer_view');
        }
    }
    
}