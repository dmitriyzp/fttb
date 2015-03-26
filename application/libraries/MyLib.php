<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class MyLib {

    function getPrefixStreet(){
        $prefix = array('ул', 'пр-т', 'бул', 'пл', 'пер');
        return $prefix;
    }
    
    function getSostZamok(){
        $sost = array('Исправен', 'Не исправен', 'Отсутствует');
        return $sost;
    }
    
    function getTipPort(){
        $tip = array('F'=>'Fiber', 'C'=>'Copper', 'D'=>'Down', 'N'=>'NA');
        return $tip;
    }
       
    function monthPPO() {
        $monthppo = array(1 => 'Январь', 2 => 'Февраль', 3 => 'Март', 4 => 'Апрель', 5 => 'Май', 6 => 'Июнь', 7 => 'Июль', 8 => 'Август', 9 => 'Сентябрь', 10 => 'Октябрь', 11 => 'Ноябрь', 12 => 'Декабрь');
        return $monthppo;
    }
    
    function mostUseProblem(){
        $p = array('Нет пинга / причина неизвестна',
                   'Заменить коммутатор',
                   'Демонтировать коммутатор',
                   'Установить дополнительный коммутатор',
                   'Восстановить медный линк',
                   'Восстановить связь между ТКД',
                   'Восстановить связь между AGG',
                   'Провести ППО');
        return $p;
    }
    
    
    function inet_aton($ip){
      $ip = ip2long($ip);
      ($ip < 0) ? $ip+=4294967296 : true;
      return $ip;
    }
    
    function inet_ntoa($int){
      return long2ip($int);
    }
    function date2Msql($dateString){
        //Преобразование из дд-мм-гггг чч:мм В гггг-мм-дд чч:мм
        $str = explode(" ", $dateString);
        $newDate = explode("-", $str[0]);
        $mysqlDT = $newDate[2] . '-' . $newDate[1] . '-' . $newDate[0] . ' ' . $str[1];
        return $mysqlDT;
    }
    
}