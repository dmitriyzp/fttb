<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Street extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
         if (!$this->session->userdata('loggedin'))
            redirect(base_url() . "start");

    }

    public function showStreet()
    {
        $this->load->model('street_model');
        $filter=[];
        if($this->input->get('filter')){
            $filter = ['town' => $this->input->get('town'), 'street' => $this->input->get('street')];    
        }
        $data= ['tblarray'=>$this->street_model->listStreet($filter), 'town'=>$this->street_model->getListTown()];
        $this->load->view('header_view');
        $this->load->view('spr/street_list_view', $data);
        $this->load->view('footer_view');

        
    }

    public function delete($id)
    {
        $this->load->model('street_model');
        $this->street_model->markDelete($id);
        redirect(base_url() . "spr/street/showStreet");
    }
    
    public function edit($id)
    {
        $this->load->model('street_model');
        $filter=[];
        if($this->input->post('id')){
            $updateData=['street'=>$this->input->post('street'), 'id_town'=>$this->input->post('town'), 'prefix'=>$this->input->post('prefix')];
            $res = $this->street_model->updateStreet($updateData, $this->input->post('id'));
            redirect(base_url() . "spr/street/showStreet");
                
        }else{
            if($id){
                $filter = ['s.id' => $id];
                $this->load->library('myLib');
                $data=['tblarray' => $this->street_model->listStreet($filter), 'town'=>$this->street_model->getListTown(), 'id'=>$id, 'prefix'=>$this->mylib->getPrefixStreet()];
            }
            $this->load->view('header_view');
            $this->load->view('spr/street_form_view', $data);
            $this->load->view('footer_view');
        }
    }


    
    public function add(){
        $this->load->model('street_model');
        if($this->input->post('save')){
            $data = array();
            $data['street'] = $this->input->post('street');
            $data['id_town'] = $this->input->post('town');
            $data['prefix'] = $this->input->post('prefix');
            $result = $this->street_model->addStreet($data);
            //if($result) TODO: написать обработчик ошибок
            redirect(base_url() . "spr/street/showStreet");
        }else{
            $this->load->library('myLib');
            $data = ['town'=>$this->street_model->getListTown(), 'prefix' => $this->mylib->getPrefixStreet()];
            $this->load->view('header_view');
            $this->load->view('spr/street_form_view', $data);
            $this->load->view('footer_view');            
        }
    }


}
