<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Administrator extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->load->helper('url');

        if (!$this->session->userdata('loggedin'))
            redirect(base_url() . "start");
    }

    public function index()
    {
        $this->load->view('header_view.php');
        $this->load->view('admin_view.php');
        $this->load->view('footer_view.php');
    }

    public function logout()
    {
        $this->session->sess_destroy();
        redirect(base_url() . "start");
    }

    public function showUsers($op = 'show', $id = false)
    {
        // TODO: формирование списков для листбокса
        $this->load->model('main_model');
        if ($op == 'delete') {
            $delarray = ['key' => 'id', 'val' => $id];
            $this->main_model->markDelete('users', $delarray);
        }
        $captions = ['ID', 'Ф.И.О', 'Логин', 'Права'];
        $data[] = ['field' => 'id', 'type' => 'none'];
        $data[] = ['field' => 'fio', 'type' => 'select', 'list' => $this->main_model->
            getList('users', 'fio')];
        $data[] = ['field' => 'login', 'type' => 'select', 'list' => $this->main_model->
            getList('users', 'login')];
        $data[] = ['field' => 'role', 'type' => 'select', 'list' => $this->main_model->
            getList('roles', 'role')];
        $addCont = 'addUsers';
        if ($this->input->get('sendfilter')) {
            $myfilter = $this->input->get();
            array_pop($myfilter);
            $this->_showGrid('users_model', 'listUsers', $captions, $myfilter, $data, $addCont);
        }


        $this->_showGrid('users_model', 'listUsers', $captions, false, $data, $addCont);
    }

    public function showMethods($op = 'show', $id = false)
    {
        // TODO: формирование списков для листбокса
        $this->load->model('main_model');
        if ($op == 'delete') {
            $delarray = ['key' => 'id', 'val' => $id];
            $this->main_model->deleteRecord('list_functions', $delarray);
        }
        $captions = ['ID', 'Название метода', 'Описание'];
        $data[] = ['field' => 'id', 'type' => 'none'];
        $data[] = ['field' => 'pathMethod', 'type' => 'text'];
        $data[] = ['field' => 'description', 'type' => 'text'];
        $addCont = 'addMethods';
        if ($this->input->get('sendfilter')) {
            $myfilter = $this->input->get();
            array_pop($myfilter);
            $this->_showGrid('methods_model', 'listMethods', $captions, $myfilter, $data, $addCont);
        }


        $this->_showGrid('methods_model', 'listMethods', $captions, false, $data, $addCont);
    }


    public function showRoles($op = 'show', $id = false)
    {
        //        if (!$this->session->userdata(__METHOD__)){
        //            echo "<script>alert('Permission denied');</script>";
        //            $this->index();
        //
        //        }
        $this->load->model('main_model');
        $captions = ['ID', 'Роль'];
        $data[] = ['field' => 'id', 'type' => 'none'];
        $data[] = ['field' => 'role', 'type' => 'select', 'list' => $this->main_model->
            getList('roles', 'role')];
        $addCont = 'addRoles';
        //        $data[] = ['field' =>'q2', 'type' => 'text'];
        //        $data[] = ['field' =>'q3', 'type' => 'checkbox'];

        $this->_showGrid('roles_model', 'listRoles', $captions, false, false, $addCont);
    }


    public function addRoles()
    {

    }


    public function edit($id)
    {
        $this->load->model('users_model');
        $this->load->model('main_model');
        $editarray = array();
        if ($this->input->post('edit')) {
            $addData['fio'] = $this->input->post('fio');
            $addData['login'] = $this->input->post('login');
            $addData['password'] = $this->input->post('password');
            $addData['role_id'] = $this->input->post('role_id');
            $uslovie = $this->input->post('id');

            $addData['password'] = md5(md5($addData['password']));
            $result = $this->users_model->updateUser($addData, $uslovie);
            if ($result) {
                $mydata = ['tblarray' => "<h1>Запись успешно обновлена</h1>"];
                $this->load->view('administrator_view', $mydata);
            } else {
                $mydata = ['tblarray' =>
                    "<h1>Произошла ошибка! Проверьте правильность введенных данных</h1>"];
                $this->load->view('administrator_view', $mydata);
            }

        }

        $editarray = $this->users_model->getUser($id);
        $data['action'] = __function__;
        $data['input'][] = ['name' => 'fio', 'type' => 'text', 'value' => $editarray['fio'],
            'placeholder' => 'Введите Ф.И.О', 'label' => 'Ф.И.О', 'required' => true];
        $data['input'][] = ['name' => 'id', 'type' => 'hidden', 'label' => '', 'value' =>
            $id];
        $data['input'][] = ['name' => 'login', 'type' => 'text', 'value' => $editarray['login'],
            'placeholder' => 'Введите логин', 'label' => 'Логин', 'required' => true];
        $data['input'][] = ['name' => 'password', 'type' => 'password', 'value' => '',
            'placeholder' => '', 'label' => 'Пароль', 'required' => true];
        $data['input'][] = ['name' => 'role_id', 'type' => 'select', 'value' => '',
            'placeholder' => '', 'label' => 'Права', 'list' => $this->main_model->getList('roles',
            'role')];
        $data['submit'] = ['name' => __function__, 'value' => 'Сохранить'];
        $this->_showAddForm($data);
    }

    public function addUsers()
    {
        $this->load->model('main_model');
        if ($this->input->post('addUsers')) {
            $addData = $this->input->post();
            array_pop($addData); //удаляем элемент submit
            $uslovie = ['key' => 'login', 'val' => $this->input->post('login')];
            $addData['password'] = md5(md5($addData['password']));
            $result = $this->main_model->addWcheck('users', $addData, $uslovie);
            if ($result) {
                $mydata = ['tblarray' => "<h1>Запись успешно добавлена</h1>"];
                $this->load->view('administrator_view', $mydata);
            } else {
                $mydata = ['tblarray' =>
                    "<h1>Произошла ошибка! Проверьте правильность введенных данных</h1>"];
                $this->load->view('administrator_view', $mydata);
            }

        }


        $data['action'] = __function__;
        $data['input'][] = ['name' => 'fio', 'type' => 'text', 'value' => '',
            'placeholder' => 'Введите Ф.И.О', 'label' => 'Ф.И.О', 'required' => true];
        $data['input'][] = ['name' => 'login', 'type' => 'text', 'value' => '',
            'placeholder' => 'Введите логин', 'label' => 'Логин', 'required' => true];
        $data['input'][] = ['name' => 'password', 'type' => 'password', 'value' => '',
            'placeholder' => '', 'label' => 'Пароль', 'required' => true];

        $data['input'][] = ['name' => 'role_id', 'type' => 'select', 'value' => '',
            'placeholder' => '', 'label' => 'Права', 'list' => $this->main_model->getList('roles',
            'role')];
        $data['submit'] = ['name' => __function__, 'value' => 'Сохранить'];

        $this->_showAddForm($data);
    }

    public function _showAddForm($params)
    {
        $data = "<form action=\"{$params['action']}\" method=\"POST\">\n";
        foreach ($params['input'] as $key => $val) {
            $data .= ($val['label']) ? "<p><label for=\"{$val['name']}\">{$val['label']}</label></p>" :
                "";
            if ($val['type'] == 'select') {
                $data .= "<p><select size='1' name={$val['name']} class=\"form-input\"></p>";
                foreach ($val['list'] as $key => $val) {
                    $data .= "<option value = \"{$key}\">{$val}</option>";
                }
                $data .= "</select>";
            } elseif ($val['type'] == 'hidden') {
                $data .= "<p><input type=\"hidden\" name=\"{$val['name']}\" value=\"{$val['value']}\"\n";
            } else {

                $data .= "<p><input type=\"{$val['type']}\" name=\"{$val['name']}\" value=\"{$val['value']}\" placeholder=\"{$val['placeholder']}\" class=\"form-input\"><p>\n";
            }

        }
        $data .= "<p><input type=\"submit\" name=\"{$params['submit']['name']}\" value=\"{$params['submit']['value']}\" class=\"small-button\" > </p>\n";
        $data .= "</form>";
        $mydata = array('tblarray' => $data);
        $this->load->view('administrator_view', $mydata);
    }

    public function _showGrid($mymodel, $myclass, $captions, $where = false, $filter = false,
        $addCont)
    {
        //функция отображения данных
        $even = 0;
        $data = "<p><a class = \"small-button form-button\" href=\"{$addCont}\">Добавить</a></p>";
        $data .= "<table cellspacing='0' align='left'>\n";
        $result = array();
        $this->load->model($mymodel);
        $result = $this->$mymodel->$myclass($where);
        $data .= "<tr>\n";
        foreach ($captions as $val) {
            $data .= "<th>{$val}</th>\n";
        }
        $data .= "<th>Операции</th>";
        $data .= "</tr>\n";
        //Фильтр по колонкам
        if ($filter) {
            $data .= "<tr>\n";
            $data .= "<form action=\"\" method=\"GET\">";
            foreach ($filter as $field) {
                if ($field['type'] == 'select') {
                    $data .= "<td><select size='1' name={$field['field']}>";
                    $data .= "<option value = \"\"></option>";
                    foreach ($field['list'] as $val) {
                        $defvalue = (isset($_GET[$field['field']]) && $_GET[$field['field']] == $val) ?
                            "selected" : "";
                        $data .= "<option {$defvalue} value = \"{$val}\">{$val}</option>";
                    }
                    $data .= "</select></td>";
                } else
                    if ($field['type'] == 'checkbox') {
                        $defvalue = (isset($_GET[$field['field']])) ? "checked" : "";
                        $data .= "<td><input type=\"{$field['type']}\" name=\"{$field['field']}\" {$defvalue} /></td>";
                    } else
                        if ($field['type'] == 'text') {
                            $defvalue = (isset($_GET[$field['field']])) ? $_GET[$field['field']] : "";
                            $data .= "<td><input type=\"{$field['type']}\" name=\"{$field['field']}\" value=\"{$defvalue}\" /></td>";
                        } else {
                            $data .= "<td></td>";
                        }

            }
            $data .= "<td><input type=\"submit\" name=\"sendfilter\" value=\"Фильтр\" class=\"small-button\" /></td>";
            $data .= "</form>";
            $data .= "</tr>\n";
        }

        //Фильтр по колонкам
        //Вывод грида
        if ($result) {
            foreach ($result as $key => $val) {
                if ($even) {
                    $even = 0;
                    $data .= "<tr>\n";
                } else {
                    $even = 1;
                    $data .= "<tr class=\"even\">\n";
                }

                foreach ($val as $td) {
                    $data .= "<td>{$td}</td>\n";
                }
                $data .= "<td><a onclick=\"confirmUrl('Вы уверены', '" . current_url() .
                    "/delete/'+{$val['id']});\" href=\"#\">Удалить</a> || <a href=\"edit/{$val['id']}\">Изменить</a></td>";
                $data .= "</tr>";
            }
        }


        $data .= "</table>\n";
        $mydata = array('tblarray' => $data);
        $this->load->view('administrator_view', $mydata);
    }

}
