<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Roles extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
         if (!$this->session->userdata('loggedin'))
            redirect(base_url() . "start");
    }

    public function showRoles()
    {
        $this->load->model('roles_model');
        $filter = [];
        $data = ['tblarray' => $this->roles_model->listRoles($filter)];
        $this->load->view('header_view');
        $this->load->view('adm/roles_list_view', $data);
        $this->load->view('footer_view');
    }
    
    public function getFormsAjax(){
        $this->load->model('forms_model');
        $result = $this->forms_model->getData('forms','id, description');
        echo json_encode($result);
    }
    
    public function getFormsFieldsAjax(){
        $this->load->model('forms_model');
        if($this->input->get('formid')){
            $result = $this->forms_model->getData('form_fields_list', 'id, description',array('form_id'=>$this->input->get('formid')));
            echo json_encode($result);
        }
    }

    public function delete($id)
    {
        $this->load->model('roles_model');
        $this->roles_model->markDelete($id);
        redirect(base_url() . "adm/roles/showRoles");
    }

    public function addnewfield(){
        $this->load->model('forms_model');
        $role_id = $this->input->get('role_id');
        $islocked = $this->input->get('islocked');
        $fieldid = $this->input->get('fieldid');
        $this->forms_model->addNewField(array('role_id'=>$role_id, 'islocked'=>$islocked, 'field_id'=>$fieldid),array('field_id'=>$fieldid, 'role_id'=>$role_id));
    }
    
    public function edit($id)
    {
        $this->load->model('roles_model');
        $this->load->model('forms_model');
        $filter = [];
        if ($this->input->post('id')) {
            $additionalRole = array();
            $formFields = array();
            if ($this->input->post('update')) {
                foreach ($this->input->post() as $key => $val) {
                    if (substr($key, 0, 3) == "fid")
                        $additionalRole[] = ['id' => substr($key, 3), 'allow' => $val];
                    if (substr($key, 0, 5) == "field")
                        $formFields[] = ['id' => substr($key, 5), 'islocked' => $val];
                }
                
            }
            $updateData = ['role' => $this->input->post('role')];
            $this->forms_model->updateFieldPermission($formFields);
            $res = $this->roles_model->updateRole($updateData, $this->input->post('id'), $additionalRole);
            redirect(base_url() . "adm/roles/showRoles");
        } else {
            if ($id) {
                $filter = ['id' => $id];
                $data = ['tblarray' => $this->roles_model->listRoles($filter), 'id' => $id,
                    'methods' => $this->roles_model->getRoleMethods($id),
                    'formfields' => $this->forms_model->getDataTables($id)];

            }
            $this->load->view('header_view');
            $this->load->view('adm/roles_form_view', $data);
            $this->load->view('footer_view');
        }

    }


    public function add()
    {
        $data['role'] = $this->input->get('newRole');
        $this->load->model('roles_model');
        $this->roles_model->addRole($data);
        
    }
}
