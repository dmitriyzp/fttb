<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Methods extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
         if (!$this->session->userdata('loggedin'))
            redirect(base_url() . "start");
    }

    public function showMethods()
    {
        $this->load->model('methods_model');
        $filter = [];
        $data = ['tblarray' => $this->methods_model->listMethods($filter)];
        $this->load->view('header_view');
        $this->load->view('adm/methods_list_view', $data);
        $this->load->view('footer_view');
    }

    public function delete($id)
    {
        $this->load->model('methods_model');
        $this->methods_model->Delete($id);
        redirect(base_url() . "adm/methods/showMethods");
    }

    public function edit($id)
    {
        $this->load->model('methods_model');
        $filter = [];
        if ($this->input->post('id')) {

            $updateData = ['pathMethod' => $this->input->post('pathMethod'), 'description' =>
                $this->input->post('description')];
            $res = $this->methods_model->updateMethod($updateData, $this->input->post('id'));
            redirect(base_url() . "adm/methods/showMethods");
        } else {
            if ($id) {
                $filter = ['id' => $id];
                $data = ['tblarray' => $this->methods_model->listMethods($filter), 'id' => $id];
            }
            $this->load->view('header_view');
            $this->load->view('adm/methods_form_view', $data);
            $this->load->view('footer_view');
        }

    }


    public function add()
    {
        $this->load->model('methods_model');
        if ($this->input->post('save')) {
            $data = array();
            $data['pathMethod'] = $this->input->post('pathMethod');
            $data['description'] = $this->input->post('description');
            $result = $this->methods_model->addMethod($data);
            //if($result) TODO: написать обработчик ошибок
            redirect(base_url() . "adm/methods/showMethods");
        } else {
            $this->load->view('header_view');
            $this->load->view('adm/methods_form_view');
            $this->load->view('footer_view');
        }
    }
}
