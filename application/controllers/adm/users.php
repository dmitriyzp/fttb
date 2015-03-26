<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Users extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
         if (!$this->session->userdata('loggedin'))
            redirect(base_url() . "start");

    }

    public function showUsers()
    {
        $this->load->model('users_model');
        $filter=[];
        if($this->input->get('filter')){
            $filter = ['role' => $this->input->get('roles')];    
        }
        $data= ['tblarray'=>$this->users_model->listUsers($filter), 'role'=>$this->users_model->getListRoles()];
        $this->load->view('header_view');
        $this->load->view('adm/users_list_view', $data);
        $this->load->view('footer_view');

        
    }

    public function delete($id)
    {
        $this->load->model('users_model');
        $this->users_model->markDelete($id);
        redirect(base_url() . "adm/users/showUsers");
    }
    
    public function edit($id)
    {
        $this->load->model('users_model');
        $filter=[];
        if($id){
            $filter = ['u.id' => $id];
            $data=['tblarray' => $this->users_model->listUsers($filter), 'role'=>$this->users_model->getListRoles(), 'id'=>$id, 'action'=>'update'];
        }
        $this->load->view('header_view');
        $this->load->view('adm/users_form_view', $data);
        $this->load->view('footer_view');
    }
    
    public function update(){
        $this->load->model('users_model');
        $filter=[];
        if($this->input->post('id')){
            $updateData=['fio'=>$this->input->post('fio'), 'login'=>$this->input->post('login'), 'password'=>($this->input->post('password') ? md5(md5($this->input->post('password'))) : ''), 'role_id'=>$this->input->post('roles')];
            $res = $this->users_model->updateUser($updateData, $this->input->post('id'));
            redirect(base_url() . "adm/users/showUsers");
                
        }
    }
    
    public function add(){
        $this->load->model('users_model');
        if($this->input->post('save')){
            $data = array();
            $data['fio'] = $this->input->post('fio');
            $data['login'] = $this->input->post('login');
            $data['password'] = md5(md5($this->input->post('password')));
            $data['role_id'] = $this->input->post('roles');
            $result = $this->users_model->addUser($data);
            //if($result) TODO: написать обработчик ошибок
            redirect(base_url() . "adm/users/showUsers");
        }else{
            $data = ['role'=>$this->users_model->getListRoles()];
            $this->load->view('header_view');
            $this->load->view('adm/users_form_view', $data);
            $this->load->view('footer_view');            
        }
    }


}
