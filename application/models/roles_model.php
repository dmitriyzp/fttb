<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * @author FANAT
 * @copyright 2014
 */


class Roles_model extends CI_Model
{

    public function listRoles($where)
    {
        $result = array();
        $this->db->select('id, role');
        $this->db->from('roles');
        if ($where) {
            foreach ($where as $keys => $val) {
                if ($val)
                    $this->db->where($keys, $val);
            }
        }
        $this->db->where('isactive', '1');
        $query = $this->db->get();
        if ($query->num_rows() > 0) {
            foreach ($query->result() as $item) {
                $result[] = array('id' => $item->id, 'role' => $item->role);
            }
            return $result;
        } else {
            return false;
        }

    }

    public function markDelete($uslovie)
    {
        $this->db->query("UPDATE roles SET isactive=0 WHERE id = {$uslovie}");
        //$this->actualizeRoles('delete', $uslovie);

    }
    public function updateRole($addData, $uslovie, $additional = false)
    {
        $result = $this->db->query("UPDATE roles SET role='{$addData['role']}' WHERE id={$uslovie}");
        foreach ($additional as $item) {
            $result = $this->db->query("UPDATE role_functions SET allow={$item['allow']} WHERE id_function={$item['id']} AND id_role={$uslovie}");
        }
        if ($result)
            return true;
        return false;
    }

    public function addRole($data)
    {
        $this->db->select('role');
        $this->db->where('role', $data['role']);
        $this->db->where('isactive', '1');
        $this->db->from('roles');
        if (!$this->db->count_all_results()) {
            $this->db->insert('roles', $data);
            $this->actualizeRoles($this->db->insert_id());
            return true;
        }
        return false;
    }

    public function actualizeRoles($id_role)
    {
        $this->db->select('id')->from('form_fields_list');
        $fields = $this->db->get();
        foreach($fields->result() as $f){
            $this->db->select('id')->from('form_field_permission')->where('field_id', $f->id)->where('role_id', $id_role);
            if(!$this->db->count_all_results()){
                $this->db->insert('form_field_permission', array('field_id'=>$f->id, 'role_id' => $id_role, 'islocked' =>'1'));
            }
            
        }
        
    }

    public function getRoleMethods($idrole)
    {
        $result = array();
        $this->db->select('lf.id as funct_id, rf.id_role as id_role, lf.pathMethod as pathMethod, lf.description as description, rf.allow as allow');
        $this->db->from('role_functions rf');
        $this->db->join('list_functions lf', 'lf.id=rf.id_function');
        $this->db->where('id_role', $idrole);
        $query = $this->db->get();
        foreach ($query->result() as $item) {
            $result[] = array(
                'funct_id' => $item->funct_id,
                'id_role' => $item->id_role,
                'pathMethod' => $item->pathMethod,
                'description' => $item->description,
                'allow' => $item->allow);
        }
        return $result;

    }

}
