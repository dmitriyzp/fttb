<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
class Mdu extends CI_Controller{
    
    public function __construct() {
        parent::__construct();
         if (!$this->session->userdata('loggedin'))
            redirect(base_url() . "start");
    }
    
    public function showMdu()
    {
        $this->load->model('mdu_model');
        $filter = [];
        $data = ['tblarray' => $this->mdu_model->listMdu($filter)];
        $this->load->view('header_view');
        $this->load->view('spr/mdu_list_view', $data);
        $this->load->view('footer_view');
    }

    public function delete($id)
    {
        $this->load->model('mdu_model');
        $this->mdu_model->Delete($id);
        redirect(base_url() . "spr/mdu/showMdu");
    }

    public function edit($id)
    {
        $this->load->model('mdu_model');
        $this->load->model('rayon_model');
        $filter = [];
        if ($this->input->post('id')) {

            $updateData = ['mdu' => $this->input->post('mdu'),
                            'id_rayon' => $this->input->post('rayon')];
            $res = $this->mdu_model->updateMdu($updateData, $this->input->post('id'));
            redirect(base_url() . "spr/mdu/showMdu");
        } else {
            if ($id) {
                $filter = ['m.id' => $id];
                $data = ['tblarray' => $this->mdu_model->listMdu($filter), 'id' => $id,
                        'listRayon'=>$this->rayon_model->getRayonList()];
            }
            $this->load->view('header_view');
            $this->load->view('spr/mdu_form_view', $data);
            $this->load->view('footer_view');
        }

    }


    public function add()
    {
        $this->load->model('mdu_model');
        if ($this->input->post('save')) {
            $data = array();
            $data['mdu'] = $this->input->post('mdu');
            $result = $this->mdu_model->addMdu($data);
            //if($result) TODO: написать обработчик ошибок
            redirect(base_url() . "spr/mdu/showMdu");
        } else {
            $this->load->view('header_view');
            $this->load->view('spr/mdu_form_view');
            $this->load->view('footer_view');
        }
    }
    
}