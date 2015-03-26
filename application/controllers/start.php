<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Start extends CI_Controller {
    
    public function __construct(){
        parent::__construct();
        $this->load->helper('url');
        $this->load->model('users_model');
    }
    
    public function index (){
    if($this->session->userdata('loggedin') == '1')
        redirect(base_url() . "administrator");
    else
        $this->load->view('start_view');   
    }
    
    public function auth(){
        if ($this->input->post('signin')){
            if ($this->users_model->checkAuth($this->input->post('login'), $this->input->post('password'))){
                redirect(base_url() . "administrator");
            }
            redirect(base_url());
        }
    }

    
}
