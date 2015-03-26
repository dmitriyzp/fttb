<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * @author FANAT
 * @copyright 2014
 */


class Users_model extends CI_Model{
    
    
    public function checkAuth($user, $pass) {
        /*
        Входный параметры: логин и пароль
        Исходящие: true/false и заполненный массив привелегий в сессии 
        */
        $user = $this->db->escape($user);
        $pass =$this->db->escape(md5(md5($pass)));
        $query = $this->db->query("SELECT u.id as uID, u.login, r.role FROM users u join roles r on r.id = u.role_id WHERE u.login={$user} AND u.password={$pass} and u.isactive=1");
        if ($query->num_rows()>0){
            $res = $query->row_array();
            $this->session->set_userdata('role', $res['role']);
            $permissions = $this->db->query("SELECT list_functions.pathMethod,
                                role_functions.allow FROM roles
                                Inner Join role_functions ON roles.id = role_functions.id_role
                                Inner Join list_functions ON role_functions.id_function = list_functions.id
                                WHERE roles.id IN (SELECT role_id FROM users WHERE login = {$user})");
            foreach ($permissions->result() as $items) {
                $this->session->set_userdata($items->pathMethod,$items->allow);
            }
            $this->session->set_userdata('loggedin', '1');
            $this->session->set_userdata('user', $user);
            $row = $query->row();
            $this->session->set_userdata('uID', $row->uID);
            return true;
        }
        return false;
        }
    

    
    public function listUsers($where=false){
        $result=array();
        $this->db->select('u.id, u.fio as FIO, u.login as LOGIN, r.role as ROLE');
        $this->db->from('users u');
        $this->db->join('roles r', 'u.role_id=r.id');
        if($where) {
            foreach($where as $keys => $val){
                if($val)
                    $this->db->where($keys, $val);
            }
        }
        $this->db->where('u.isactive = 1');
        $query = $this->db->get();
        if ($query->num_rows()>0){
            foreach($query->result() as $item){
                $result[] = array ('id' => $item->id, 'fio' => $item->FIO, 'login' => $item->LOGIN, 'role' => $item->ROLE);
            }
            return $result;
        }else{
            return false;
        }
    
    }
    
    public function getUser($id){
        $result = array();
        $this->db->select('u.id, u.fio as FIO, u.login as LOGIN, r.role as ROLE');
        $this->db->from('users u');
        $this->db->join('roles r', 'u.role_id=r.id');
        $this->db->where('u.id', $id);
        $query = $this->db->get();
        if($query->num_rows()>0){
            $row = $query->row();
            $result = array ('id' => $row->id, 'fio' => $row->FIO, 'login' => $row->LOGIN, 'role' => $row->ROLE);
            return $result;
        }
        return false;
    }
    
    
    public function getListRoles(){
        $result = array();
        $this->db->select("id, role");
        $this->db->from('roles');
        $this->db->where('isactive', '1');
        $query = $this->db->get();
        if($query->num_rows()==0)
            return;
        foreach($query->result() as $item){
            $result[$item->id] = $item->role;
        }
        return $result;
    }
    
    
    public function markDelete ($uslovie){
        $this->db->query("UPDATE users SET isactive=0 WHERE id = {$uslovie}");
        
    }
    
    
    public function addUser($data) {
        $this->db->select();
        $this->db->where('login', $data['login']);
        $this->db->where('isactive', '1');
        $this->db->from('users');
        if(!$this->db->count_all_results()){
            $this->db->insert('users', $data);
            return true;
        }
        return false;
    }
    
            
    public function updateUser($addData, $uslovie){
        $pass = '';
        if($addData['password'])
            $pass = "password='{$addData['password']},'";
        $result = $this->db->query("UPDATE users SET fio='{$addData['fio']}', login='{$addData['login']}', {$pass} role_id={$addData['role_id']} WHERE id={$uslovie}");
        if($result)
            return true;
        return false;
    }

}