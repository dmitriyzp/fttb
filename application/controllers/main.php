<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
class Main extends CI_Controller{
    
    public function __construct(){
        parent::__construct();
        
    }
    
    public function getFormFieldPermissionAjax(){
        $result = array();
        $this->load->model('main_model');
        $result = $this->main_model->getFormFieldPerm($this->input->get('formname'));
        echo json_encode($result);
    }
    
    
}