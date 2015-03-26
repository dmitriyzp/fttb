<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');
    
class Parser extends CI_Controller {
    
        public $objectpath = "tables/object.csv"; 
        public $switchpath = "tables/switch.csv";
        public $historypath = "tables/lenta.csv";
        public $zamokpath = "tables/zamok.csv"; 
        
        //временно
        public $addtkdinfo = "tables/addtkdinfo.csv";
        
        
    
    public function addTkd(){
        $sql = '';
        $result = array();
        $result = $this->getCSV($this->addtkdinfo);
        foreach ($result as $val){
            $rasp='';
            $dostup='';
            //ТКД;Расположение;Доступ
            //object_name raspologenie dostup
            if(strlen(trim($val['Расположение']))>1)
                $rasp = "raspologenie='{$val['Расположение']}'";
            if(strlen(trim($val['Доступ']))>1)
                if($rasp)
                    $dostup = ", dostup='{$val['Доступ']}'";
                else
                    $dostup = "dostup='{$val['Доступ']}'";
            
            $sql .= "UPDATE object SET $rasp $dostup WHERE object_name='{$val['ТКД']}'; \n";
            
        }
        
        file_put_contents('tables/addInfo.sql',$sql);
    }

    public function showParserForm() {
        $this->load->view('header_view');
        $this->load->view('parser_view');
        $this->load->view('footer_view');    
    }
    
    public function sendCSV(){
        $config['upload_path'] = "./uploads/";
        $config['allowed_types'] = '*';
        $this->load->library('upload', $config);
        $this->upload->do_upload('fileCSV');
        $mydata = $this->upload->data();
        //var_dump($mydata);
        if(!$mydata['file_name'])
            die('File not found');
        $filename = "./uploads/" . $mydata['file_name'];

        $this->parseSwitch($filename);
        header("Location:" . base_url() . "pars/parser/showParserForm");
        

    }
    
    public function parseObject(){
        // парсинг листа object
        
        $data = array();
        $tmp = array();
        $curTownId=0;
        $result = $this->getCSV($this->objectpath);
        $this->load->model('parser_model');
        
        foreach ($result as $val){
            header('Content-Type: text/html; charset=utf-8');
            $curTownId =$this->parser_model->selUpdateReturn('town',array('town'=>$val['ID_Gorod']));
            $tmp=['object_name'=>$val['Object'], 'gorod_id'=>$curTownId, 'prefix'=>$val['Prefix'], 
            'street_id'=>$this->parser_model->selUpdateReturn('street', array('id_town'=>$curTownId, 'street'=>$val['ID_Ulica'], 'prefix'=> $val['Prefix']))
            ,'house'=>$val['Dom']
             ,'pod'=>$val['Pod']
             ,'mdu_id'=>$this->parser_model->selUpdateReturn('mdu',array('mdu'=>$val['ID_MDU']))
             ,'gpo_id'=>$this->parser_model->selUpdateReturn('gpo',array('gpo'=>$val['ID_GPO']))
             ,'kluch'=>$val['Ключ']
             ,'prenadleg_pod'=>$val['Принадлежность подъезда']
             ,'zamok_id'=>((strlen(trim($val['ID_Zamok']))>0) ? $this->parser_model->selUpdateReturn('zamok',array('zamok'=>$val['ID_Zamok'])) : '0')
             ,'raspologenie'=>$val['Расположение']
             ,'dostup'=>$val['Доступ']
             ,'nakl_vlasnist'=>(($val['Наклейка Власнисть'] == 'ИСТИНА') ? '1' : '0')
             ,'nakl_molniya'=>(($val['Наклейка Молния'] == 'ИСТИНА') ? '1' : '0')
             ,'nakl_shema'=>(($val['Наклейка Схема Электропитания'] == 'ИСТИНА') ? '1' : '0')
             ,'kreplenie_switch'=>$val['Крепление коммутаторов']
             ,'dop_oborud'=>$val['Доп. оборудование']
             ,'avtomat_tkd'=>$val['Автомат в ТКД']
             ,'avtomat_yashik'=>$val['Автомат в щитке']
             ,'birka_shitok'=>(($val['Кабель в щитке бирка'] == 'ИСТИНА') ? '1' : '0')
             ,'podkl_pitanie'=>$val['Сведения об орг. эл.питания']
             ,'tip_kabel_pitaniya'=>$val['Тип кабеля питания']
             ,'optika1_dest'=>$val['Оптика 1 - куда']
             ,'optika1_birka_in'=>(($val['Оптика 1 - бирка внутри'] == 'ИСТИНА') ? '1' : '0')
             ,'optika1_sost'=>$val['Оптика 1 - состояние']
             ,'optika2_dest'=>$val['Оптика 2 - куда']
             ,'optika2_birka_in'=>(($val['Оптика 2 - бирка внутри'] == 'ИСТИНА') ? '1' : '0')
             ,'optika2_sost'=>$val['Оптика 2 - состояние']
             ,'optika3_dest'=>$val['Оптика 3 - куда']
             ,'optika3_birka_in'=>(($val['Оптика 3 - бирка  внутри'] == 'ИСТИНА') ? '1' : '0')
             ,'optika3_sost'=>$val['Оптика 3 - состояние']
             ,'optika4_dest'=>$val['Оптика 4 - куда']
             ,'optika4_birka_in'=>(($val['Оптика 4 - бирка  внутри'] == 'ИСТИНА') ? '1' : '0')
             ,'optika4_sost'=>$val['Оптика 4 - состояние']
             ,'optika1_birka_out'=>(($val['Оптика 1 - бирка на крыше'] == 'ИСТИНА') ? '1' : '0')
             ,'optika2_birka_out'=>(($val['Оптика 2 - бирка на крыше'] == 'ИСТИНА') ? '1' : '0')
             ,'optika3_birka_out'=>(($val['Оптика 3 - бирка на крыше'] == 'ИСТИНА') ? '1' : '0')
             ,'optika4_birka_out'=>(($val['Оптика 4 - бирка на крыше'] == 'ИСТИНА') ? '1' : '0')
             ,'address_proveren'=>(($val['Адрес проверен'] == 'ИСТИНА') ? '1' : '0')
             ,'month_ppo'=>$val['ID_Mesyac_PPO']
             ,'percent_work_ppo'=>$val['Процент выполнения ППО']
             ,'foto'=>(($val['Фото'] == 'ИСТИНА') ? '1' : '0')
             ,'sost_zamka'=>$val['ID_Sost_zamka']];
            
            $this->parser_model->selUpdateObject('object', array('object_name'=>$val['Object']),$tmp);
            
            
        }
        
        
        
    }

    public function parseSwitch($filename){
        //парсинг листа switch
        $data = array();
        $tmp = array();
        $curTownId=0;

        
        $result = $this->getCSV($filename);
        $this->load->model('parser_model');
        ini_set('max_execution_time', 600);
        
        
        //проверка структуры
        $struc = ['Switch', 'ID_Object', 'Nbr', 'IP', 'ID_Ring', 'ID_Equipment', 'Serial', 'MAC', 'ID_VLAN', 
        'ID_SFP для 25 порта', 'ID_SFP для 26 порта', 'InService', 'ID_TipPort для 25 порта', 'ID_TipPort для 26 порта', 
        'Switch для LLDP 25 порта', 'Switch для LLDP 26 порта', 'Proverka_NameSwitch', 'Data_oprosa_LLDP', 'Nomer_v_kolce',
        'Port_na_DS','Port_DS_des'];
        //окончание проверки структуры
        //var_dump($result);
        foreach($result as $val){
            foreach($val as $key=>$val){
                if(array_search($key,$struc)===false){
                echo $key . ' not equal ' . array_search($key,$struc);
                die('Structure of CSV is wrong!!!');
            }
            break;
            }

        }
        $this->parser_model->clearSwitch();
        foreach ($result as $val){
            header('Content-Type: text/html; charset=utf-8');
            $curMDU = $this->parser_model->selReturn('object',array('object_name'=>$val['ID_Object']));
            
                
            switch(strtoupper($val['InService'])) {
                case "ИСТИНА" : $inserv = "True"; break;
                case "ЛОЖЬ" : $inserv = "False"; break;
                case "TRUE" : $inserv = "True"; break;
                case "FALSE" : $inserv = "False"; break;
                case "PLAN" : $inserv = "Plan"; break;
            }
            if (strlen($val['Data_oprosa_LLDP'])>3){
                $dataoprosa = explode('.',$val['Data_oprosa_LLDP']);
                $dv="{$dataoprosa[2]}-{$dataoprosa[1]}-{$dataoprosa[0]}";
            }
            $tmp=['switch' =>$val['Switch'], 
                'nbr'=>$val['Nbr'],
                'ip'=>sprintf("%u", ip2long($val['IP'])),
                'ring_id'=>$this->parser_model->selUpdateReturn('ring',array('ring'=>$val['ID_Ring'], 'mdu_id' => $curMDU)),
                'equipment_id'=>$this->parser_model->selUpdateReturn('equipment',array('equipment'=>$val['ID_Equipment'])),
                'serial'=>$val['Serial'],
                'mac'=>$val['MAC'],
                'vlan'=>$val['ID_VLAN'],
                'id_sfp_25'=>(($val['ID_SFP для 25 порта']) ? $this->parser_model->selUpdateReturn('sfp',array('sfp'=>$val['ID_SFP для 25 порта'])) : ''),
                'id_sfp_26'=>(($val['ID_SFP для 26 порта']) ? $this->parser_model->selUpdateReturn('sfp',array('sfp'=>$val['ID_SFP для 26 порта'])) : ''),
                'inservice'=>$inserv,
                'id_tipport_25'=>$this->parser_model->selUpdateReturn('tipport',array('tipport'=>$val['ID_TipPort для 25 порта'])),
                'id_tipport_26'=>$this->parser_model->selUpdateReturn('tipport',array('tipport'=>$val['ID_TipPort для 26 порта'])),
                'switch_lldp_25'=>$val['Switch для LLDP 25 порта'],
                'switch_lldp_26'=>$val['Switch для LLDP 26 порта'],
                'proverka_nameswitch'=>$val['Proverka_NameSwitch'],
                'data_oprosa_lldp'=>$dv,
                'nomer_v_kolce'=>$val['Nomer_v_kolce'],
                'port_na_ds'=>$val['Port_na_DS'],
                'isactive'=>1,
                'port_ds_des'=>$val['Port_DS_des']];
            if($curMDU)
                $tmp['object_id']=$curMDU;
            $this->parser_model->selUpdateSwitch('switch', array('ip'=>sprintf("%u", ip2long($val['IP']))),$tmp);
            
            
        }

        unlink($filename);
        
        
    }
    
    public function parseZamok(){
        //парсинг листа Zamok
        $data = array();
        $tmp = array();
        $curTownId=0;
        $result = $this->getCSV($this->zamokpath);
        $this->load->model('parser_model');
        
        foreach ($result as $val){
            header('Content-Type: text/html; charset=utf-8');
            $du='';
            $dv='';
            if (strlen($val['Дата выдачи'])>3){
                $datavidachi = explode('.',$val['Дата выдачи']);
                $dv="{$datavidachi[2]}-{$datavidachi[1]}-{$datavidachi[0]}";
            }
            if(strlen($val['Установлен'])>3){
                $dataustanovki = explode('.',$val['Установлен']);
                $du="{$dataustanovki[2]}-{$dataustanovki[1]}-{$dataustanovki[0]}";
            }
            if(strlen(trim($val['ID_Personal']))>3)
                $personal = $this->parser_model->selUpdateReturn('personal',array('familiya'=>$val['ID_Personal']));
            else
                $personal = null;
            $tmp=['zamok' =>$val['Zamok'], 
                'seriya' =>$val['ID_Seriya_zamkov'],
                'personal_id' =>$personal,
                'datavidachi' =>$dv,
                'dataustanovki' =>$du,
                
                'ustanovlen' => (strlen($val['Установлен']) == 0) ? 'no' : 'yes'];
                
            
            $this->parser_model->selUpdateZamok('zamok', array('zamok'=>$val['Zamok']),$tmp);
            
            
        }

        
        
        
    } 
    public function parseHistory(){
        
        $data = array();
        $result = $this->getCSV($this->historypath);
        $this->load->model('parser_model');
        $dt=0;
        $datast = array();
        foreach ($result as $val){
            header('Content-Type: text/html; charset=utf-8');
            $data['type'] =$val['Тип'];
            $data['object'] =$val['Объект'];
            $data['opisanie'] =$val['Описание проблемы'];
            $data['status'] =$val['Статус'];
            if(!empty($val['Дата смены статуса'])){
                $datast = explode('.',$val['Дата смены статуса']);
                $dt="{$datast[2]}-{$datast[1]}-{$datast[0]}";                
            }
            $data['datastatusa'] =$dt;
            $data['comment'] =$val['Комментарии об устранении'];
            $data['ispolniteli'] =$val['Исполнителиработ'];
            $data['operator'] =$val['Оператор'];
            
            $this->parser_model->parseHistory($data);
            
             
        }
        
        
        
        
    }      
    
    public function getCSV($objectpath){
        $header = NULL;
        $data = array();
        $handle = fopen($objectpath, 'r');
        while($row = fgetcsv($handle,5000,';')){
            $tmp=array();
            foreach($row as $val){
                $tmp[] = iconv('cp1251', 'utf-8', $val);
            }
            if(!$header)
                $header = $tmp;
            else
               $data[] = array_combine($header, $tmp);
        }
        fclose($handle);
        
        return $data;
    }
    
    public function test(){
        $this->load->model('parser_model');
        var_dump($this->parser_model->selUpdateReturn('town',array('town'=>'Мелитополь'),0));
    }
    
    
    
    
}