<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
class Personal extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
         if (!$this->session->userdata('loggedin'))
            redirect(base_url() . "start");
    }
    
    public function showPersonal()
    {
        $this->load->model('personal_model');
        $filter = [];
        $data = ['tblarray' => $this->personal_model->listPersonal($filter)];
        $this->load->view('header_view');
        $this->load->view('spr/personal_list_view', $data);
        $this->load->view('footer_view');
    }

    public function delete($id)
    {
        $this->load->model('personal_model');
        $this->personal_model->Delete($id);
        redirect(base_url() . "spr/personal/showPersonal");
    }

    public function edit($id)
    {
        $this->load->model('personal_model');
        $filter = [];
        if ($this->input->post('id')) {
            //familiya, name, otchestvo, email, phone,
            $updateData = ['email' => $this->input->post('email'), 'phone' => $this->input->post('phone'), 'otchestvo' => $this->input->post('otchestvo'), 'name' => $this->input->post('name'), 'familiya' => $this->input->post('familiya'), 'datebirth' => $this->input->post('datebirth'), 'passport' => $this->input->post('passport')];
            $res = $this->personal_model->updatePersonal($updateData, $this->input->post('id'));
            redirect(base_url() . "spr/personal/showPersonal");
        } else {
            if ($id) {
                $filter = ['id' => $id];
                $data = ['tblarray' => $this->personal_model->listPersonal($filter), 'id' => $id];
            }
            $this->load->view('header_view');
            $this->load->view('spr/personal_form_view', $data);
            $this->load->view('footer_view');
        }

    }


    public function add()
    {
        $this->load->model('personal_model');
        if ($this->input->post('save')) {
            $data = array();
            //familiya, name, otchestvo, email, phone,
            $data['familiya'] = $this->input->post('familiya');
            $data['name'] = $this->input->post('name');
            $data['otchestvo'] = $this->input->post('otchestvo');
            $data['email'] = $this->input->post('email');
            $data['phone'] = $this->input->post('phone');
            $data['datebirth'] = $this->input->post('datebirth');
            $data['passport'] = $this->input->post('passport');
            $result = $this->personal_model->addPersonal($data);
            //if($result) TODO: написать обработчик ошибок
            redirect(base_url() . "spr/personal/showPersonal");
        } else {
            $this->load->view('header_view');
            $this->load->view('spr/personal_form_view');
            $this->load->view('footer_view');
        }
    }
    
}