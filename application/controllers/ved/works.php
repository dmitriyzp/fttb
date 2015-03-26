<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');

class Works extends CI_Controller {
    
    function __construct()
    {
        parent::__construct();
         if (!$this->session->userdata('loggedin'))
            redirect(base_url() . "start");
    }
    
    public function showWorks() {
        $data = array();
        $this->load->model('works_model');
        $this->load->model('personal_model');
        $this->load->model('rayon_model');
        $data['typeWork'] = $this->works_model->getTypeWorks();
        $data['personal'] = $this->personal_model->listPersonal();
        $data['rayon'] = $this->rayon_model->listRayon();
        $this->load->view('header_view');
        $this->load->view('ved/works_list_view', $data);
        $this->load->view('footer_view');
    }
    
    public function getDataTable() {
        $result = array();
        $output = array();
        $order = array();
        $uslovie = array('period'=>$this->input->get('period'), 'typeWork'=>$this->input->get('typeWork'));
        $this->load->model('works_model');    
        if($this->input->get('column')){
            $order['column'] = $this->input->get('column');
            $order['direction'] = $this->input->get('direction');
        }          
        $result = $this->works_model->getListWorks($uslovie, $order);
        if($this->input->get('rayon')){
            foreach($result as $val){
                if($val['rayon'] == $this->input->get('rayon'))
                    $output[]=$val;
                    
            }
        }else{
            $output = $result;
        }
        echo json_encode($output);
        //echo "<pre>" . print_r($result) . "</pre>";
    }
    
    public function setGroup(){
        $data = array('groupNum'=>$this->input->get('gID'));
        $this->load->model('works_model');
        $this->works_model->setGroup($this->input->get('wID'), $data);
    }
    
    public function toPrint(){
        $result = array();
        $this->load->model('works_model');
        $result = $this->works_model->toPrint();
        //var_dump($result);
        echo json_encode($result);
    }
    
    public function toEmail(){
        $result = array();
        $this->load->model('works_model');
        $result = $this->works_model->toPrint();
        $config = Array(
            'protocol' => 'sendmail',
            'mailpath' => '/usr/sbin/sendmail',
            'protocol' => 'smtp',
            'smtp_host' => 'ssl://smtp.yandex.ru',
            'smtp_port' => '465',
            'smtp_user' => 'fttb.zap@yandex.ru',
            'smtp_pass' => 'sn2dr3ml#',
            'mailtype'  => 'html', 
            'charset'   => 'utf-8',
            'newline' => "\r\n"
        );
        $listArr = array('dmitriyzp@gmail.com');
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");

        $mHeader = '<table border="1">';
        $mHeader .= "<tr>";
        $mHeader .= "<th>Номер п.п</th>";
        $mHeader .= "<th>Тип</th>";
        $mHeader .= "<th>Описание</th>";
        $mHeader .= "<th>Объект</th>";
        $mHeader .= "<th>Оборудование</th>";
        $mHeader .= "<th>Исполнители</th>";
        $mHeader .= "</tr>";
        $mFooter = "</table>";
        
            foreach($result as $items){
                if(!is_array($items))
                    continue;
                
                foreach($items as $obj){
                    
                $listArr = explode("|", trim($obj['email']));
                array_pop($listArr);
                $message = "<tr>";
                $message .= "<td>{$obj['id']}</td>";
                $message .= "<td>{$obj['description']}</td>";
                $message .= "<td>{$obj['tip']}</td>";
                $message .= "<td>{$obj['objects']}</td>";
                $message .= "<td>{$obj['switches']}</td>";
                $message .= "<td>{$obj['personal']}</td>";
                $message .= "</tr>";
                
                $this->email->from('fttb.zap@yandex.ru', 'FTTB');
                $this->email->to($listArr);
                $this->email->subject('Наряд на работу');
                $this->email->message($mHeader . $message . $mFooter);
                $this->email->send();        
                echo $this->email->print_debugger();
                    

                
                }
                
            }
        
        
        
    }
    
    public function clearGroup(){
        $this->load->model('works_model');
        $this->works_model->clearGroup();
        
    }
    
    
    public function getAllInfo(){
        $result = array();
        $this->load->model('works_model');
        $result['obj'] = $this->works_model->getObjectWorks($this->input->get('idwork'));
        $result['work'] = $this->works_model->getBaseInfoWork($this->input->get('idwork'));
        //var_dump($result['work']);
        echo json_encode($result);

    }
    public function editWork(){
        
        $this->load->model('works_model');
        $this->load->library('MyLib.php');
        $result  = $this->works_model->updateBaseWorkInfo($this->input->post('workid'), array('description'=>$this->input->post('description'),
                                                    'srok'=>$this->mylib->date2Msql($this->input->post('srok')),
                                                    'id_tipwork'=>$this->input->post('typeWork')));   
        echo $result; 
    }
    
    public function addHistory(){
        $data = array();
        
        $data = array('personal' => $this->input->get('personal'), 'description'=>$this->input->get('reshenie'), 
                    'works_event_id'=>$this->input->get('statusWork'), 'works_id'=>$this->input->get('workid'),
                    'uID' => $this->session->userdata('uID'));
        $this->load->model('works_model');
        $result = $this->works_model->addHistoryData($data);
        
        echo $result;
    }
}