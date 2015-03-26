<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');


class Object extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();
        if (!$this->session->userdata('loggedin'))
            redirect(base_url() . "start");
    }

    public function getDataTable()
    {
        if ($this->input->get('find')) {
            $result = array();
            $uslovie = array();
            $order = array();
            if ($this->input->get('mduName') == '')
                $uslovie[] = array('mdu' => $this->input->get('typeObject'));
            else
                $uslovie[] = array('mdu' => $this->input->get('mduName'));
            $uslovie[] = array('object_name' => $this->input->get('tkd'));
            $uslovie[] = array('street' => $this->input->get('street'));
            $uslovie[] = array('house' => $this->input->get('house'));
            if($this->input->get('column')){
                $order['column'] = $this->input->get('column');
                $order['direction'] = $this->input->get('direction');
            }
            $this->load->model('object_model');
            $result = $this->object_model->getTable($uslovie, $order);
            header('Content-type: text/html; charset=windows-1251');
            echo json_encode($result);
        }
    }


    public function showObject()
    {
        $data = array();
        $this->load->library('MyLib.php');
        $this->load->model('works_model');
        $data['typeWork'] = $this->works_model->getTypeWorks();
        $data['mostUseProblem'] = $this->mylib->mostUseProblem();
        $this->load->view('header_view');
        $this->load->view('ved/object_list_view', $data);
        $this->load->view('footer_view');

    }

    public function getParams()
    {
        if ($this->input->get('objectType')) {
            $var = array();
            $result = array();
            $getType = $this->input->get('objectType');
            if ($getType == 'MDU') {
                $uslovie = array('mdu' => 'MDU');
                $this->load->model('mdu_model');
                $var = $this->mdu_model->listMdu($uslovie, 'like');
                foreach ($var as $item) {
                    $result[] = $item['mdu'];
                }
            } else {
                exit;
            }

            echo json_encode($result);
        }
    }

    function object_sw_info($nameObj)
    {
        // Не используется
        $data = array();
        $uslovie = array(array('o.object_name' => $nameObj));
        $this->load->model('switch_model');
        $this->load->model('equipment_model');
        $data['equipment'] = $this->equipment_model->getListEquipment();
        $data['result'] = $this->switch_model->getTable($uslovie);
        $data['tkd'] = $nameObj;
        $title = $nameObj;
        $this->load->view('e_header_view', array('title'=>$title));
        $this->load->view('ved/object_lv_switch', $data);
        $this->load->view('e_footer_view');
    }

    function object_all_info($objId,$title='')
    {
        //TODO: title вытаскивать запросом, а не передавать вручную
        $data = array();
        $uslovie = array(array('o.object_name' => $title));
        $this->load->model('switch_model');
        $this->load->model('equipment_model');
        $this->load->model('object_model');
        $data['equipment'] = $this->equipment_model->getListEquipment();
        $data['result_sw'] = $this->switch_model->getTable($uslovie);
        $data['tkd'] = $title;
        $data['result_obj'] = $this->object_model->getAllData($objId);
        $data['id_object'] = $objId;
        $this->load->view('e_header_view', array('title'=>$title));
        $this->load->view('ved/object_lv_allinfo', $data);
        $this->load->view('e_footer_view');
    }

    function pingHost() {
        $jumps = $this->input->get('jumps');
        $ip = $this->input->get('ip');
        $getres = exec("/bin/ping -c $jumps $ip", $output, $status);
        $result = array();
        foreach($output as $item){
            $result[] = iconv('cp866', 'UTF-8//IGNORE',$item);
        }
        echo json_encode($result);
        
    }
    
    public function getStreetList()
    {
        $result = array();
        $this->load->model('street_model');
        $result = $this->street_model->getStreetList();
        echo json_encode($result);
    }

    public function getTkdList()
    {
        if ($this->input->get('mdu')) {
            $result = array();
            $myTkdArray = array();
            $this->load->model('object_model');
            $uslovie = array('m.mdu' => $this->input->get('mdu'));
            $myTkdArray = $this->object_model->listTkd($uslovie);
            foreach ($myTkdArray as $val) {
                $result[] = $val['tkd'];
            }
            echo json_encode($result);
        }
    }

    public function getTownList()
    {
        $this->load->model('town_model');
        $result = $this->town_model->listTown();
        echo json_encode($result);
    }
    
    public function fillStreetList()
    {
        $this->load->model('street_model');
        $result = $this->street_model->getStreetList($this->input->get('town_id'));
        echo json_encode($result);
    }
    
    public function getMduList()
    {
        $this->load->model('mdu_model');
        $result = $this->mdu_model->getMduList();
        echo json_encode($result);
    }
    
    public function getGpoList()
    {
        $this->load->model('gpo_model');
        $result = $this->gpo_model->getGpoList();
        echo json_encode($result);
    }
    
    public function updateStreet(){
        $this->load->model('object_model');
        $data = array('gorod_id'=>$this->input->post('town'), 'street_id'=>$this->input->post('street'), 
                    'house'=>$this->input->post('house'), 'pod'=>$this->input->post('pod')); 
        $this->object_model->updateObject(array('field'=>'id', 'val'=>$this->input->post('obj')),$data);
    }

    public function updateObject(){
        $data = array(/*'gorod_id'=>$this->input->post('town'), 'street_id'=>$this->input->post('street'), 
                    'house'=>$this->input->post('house'), 'pod'=>$this->input->post('pod'), */ 
                    'prenadleg_pod'=>$this->input->post('prenadleg_pod'), 
                    'raspologenie'=>$this->input->post('raspologenie'), 'dostup'=>$this->input->post('dostup'), 
                    'nakl_vlasnist'=>($this->input->post('nakl_vlasnist')) ? '1' : '0', 'nakl_molniya'=>($this->input->post('nakl_molniya')) ? '1' : '0', 
                    'nakl_shema'=>($this->input->post('nakl_shema')) ? '1' : '0', 
                    'kreplenie_switch'=>$this->input->post('kreplenie_switch'), 'dop_oborud'=>$this->input->post('dop_oborud'), 
                    'avtomat_tkd'=>$this->input->post('avtomat_tkd'), 'avtomat_yashik'=>$this->input->post('avtomat_yashik'), 
                    'birka_shitok'=>($this->input->post('birka_shitok')) ? '1' : '0', 'podkl_pitanie'=>$this->input->post('podkl_pitanie'), 
                    'tip_kabel_pitaniya'=>$this->input->post('tip_kabel_pitaniya'), 'optika1_dest'=>$this->input->post('optika1_dest'), 
                    'optika1_birka_in'=>($this->input->post('optika1_birka_in')) ? '1' : '0', 'optika1_sost'=>$this->input->post('optika1_sost'), 
                    'optika2_dest'=>$this->input->post('optika2_dest'), 'optika2_birka_in'=>($this->input->post('optika2_birka_in')) ? '1' : '0', 
                    'optika2_sost'=>$this->input->post('optika2_sost'), 'optika3_dest'=>$this->input->post('optika3_dest'), 
                    'optika3_birka_in'=>($this->input->post('optika3_birka_in')) ? '1' : '0', 'optika3_sost'=>$this->input->post('optika3_sost'), 
                    'optika4_dest'=>$this->input->post('optika4_dest'), 'optika4_birka_in'=>($this->input->post('optika4_birka_in')) ? '1' : '0', 
                    'optika4_sost'=>$this->input->post('optika4_sost'), 'optika1_birka_out'=>($this->input->post('optika1_birka_out')) ? '1' : '0', 
                    'optika2_birka_out'=>($this->input->post('optika2_birka_out')) ? '1' : '0', 'optika3_birka_out'=>($this->input->post('optika3_birka_out')) ? '1' : '0', 
                    'optika4_birka_out'=>($this->input->post('optika4_birka_out')) ? '1' : '0', 'address_proveren'=>($this->input->post('address_proveren')) ? '1' : '0', 
                    //'month_ppo'=>$this->input->post('month_ppo'), 'percent_work_ppo'=>$this->input->post('percent_work_ppo'), 
                    'foto'=>($this->input->post('foto')) ? '1' : '0', 'sost_zamka'=>$this->input->post('sost_zamka'));
        $this->load->model('object_model');
        $ret = $this->object_model->updateObject(array('field'=>'object_name', 'val'=>$this->input->post('object')),$data);
        $this->object_all_info($this->input->post('id_object'), $this->input->post('object'));    


  }
  
    public function getSotrudnikList()
    {
        $this->load->model('personal_model');
        $result = $this->personal_model->getPersonalList();
        echo json_encode($result);
    }
    
    public function getZamokList()
    {
        $this->load->model('zamok_model');
        $result = $this->zamok_model->getZamokList();
        echo json_encode($result);
    } 
    
    public function updateZamok(){
        $this->load->model('zamok_model');
        $this->zamok_model->setupZamok($this->input->post('zamok_id'), array('id'=>$this->input->post('zamok_id'),
                                                                            'ustanovlen'=>'yes', 'dataustanovki'=>date('Y-m-d')));
        $this->load->model('object_model');
        $this->object_model->updateObject(array('field'=>'object_name', 'val'=>$this->input->post('object_name')),array('zamok_id'=>$this->input->post('zamok_id')));
	
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
        $listArr = array('fttb.zap@gmail.com');
        
        $this->load->library('email', $config);
        $this->email->set_newline("\r\n");
        $this->email->from('fttb.zap@yandex.ru', 'FTTB');
        $this->email->to($listArr);
        $this->email->subject('Замена замка - Рассылка');
        $this->email->message("Заменен замок на ТКД: " . $this->input->post('object_name') . ", по адресу " . $this->input->post('addr') . ".<br> Установлен замок - " . $this->input->post('nameZamok'));
        $this->email->send();        
    
        echo $this->email->print_debugger();
    }
    
    public function add2Work() {
        $this->load->library('MyLib.php');
        $data['objects'] = $this->input->post('obj');
        $data['typeWork'] = $this->input->post('typeWork');
        $data['uID'] = $this->session->userdata('uID');
        $data['description'] = $this->input->post('description');
        $data['srok'] = $this->mylib->date2Msql($this->input->post('srok'));
        $this->load->model('works_model');
        $this->works_model->addNewWork($data, 'object');
        
        
    }
    
    public function addSwGetParams(){
        //Получаем данные для автозаполнения полей
        //перед добавление нового свитча
        $result = array();
        $this->load->model('switch_model');
        $id = $this->input->get('tkd');
        $result = $this->switch_model->getNextParamsSwitch($id);
        echo json_encode($result);
        
    }
    
    public function addSwitch(){
        $this->load->model('switch_model');
        $this->switch_model->addSwitch($this->input->post());

    }
    
 

}
