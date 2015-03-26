<div id="ajaxWait">
    <div id="ajaxAnim"></div>
</div>
<div id="tabs" class="ajaxWait">
    <form action="<?=base_url();?>ved/object/updateObject" method="post">
    <ul>
        <li><a href="#summaryInfo">Общая информация</a></li>
        <li><a href="#Electriks">Электрика</a></li>
        <li><a href="#Optiks">Оптика</a></li>
        <li><a href="#foto">Фотографии</a></li>
    </ul>
    
        <div id="summaryInfo">
        <div class="content-center" style="width: 600px;">
        <table id="allinfo" style="width: 360px;">
        <tr>
            <td class="tbl-info-caption t-opt-blue t-opt-bold">ТКД</td>
            <td style="text-align: left;"><input type="text" style="border: none; color: rgb(0,112,192); font:bold 14px Arial;" size="20" value="<?=$result_obj['object_name']; ?>" name="object" id="object" readonly="" /></td>
            <td style="border-top: 1px solid gray; border-right: 1px solid #000; text-align: right; color: rgb(0,112,192);"><?=$result_obj['kluch'];?></td>
            <!--<td>Подрядчик <strong></strong></td>-->
        </tr>
        <tr>
            <td style="text-align: right; border-bottom: 1px solid rgb(192,0,0); color: rgb(192,0,0); width: 140px;">Адрес</td>
            <td style="text-align: left; width: 140px; color: rgb(192,0,0); font: bold 14px Arial;">
                г.<?=$result_obj['town']?>, <?=$result_obj['street']?>, <?=$result_obj['house']?> п.<?=$result_obj['pod']?>
                <input type="hidden" name="id_object" value="<?=$id_object; ?>" />
            </td>
        </tr>
        <tr>
            <td></td>
            <td style="color: grey;">
                <p>Адрес проверен <input type="checkbox" value="1" <?=($result_obj['address_proveren']) ? 'checked=""' : ''; ?> name="address_proveren" /></p>
                <!--<p>Фото <input type="checkbox" value="1" <?=($result_obj['foto']) ? 'checked=""' : ''; ?> name="foto" /></p>-->
            </td>
            <td><input type="button" value="Изменить адрес" id="btnChngAddr" style="border-bottom: 1px solid grey; border-right: 1px solid grey; background: white; color: grey;" /></td>
            
        </tr>
            <tr>
                <td style="text-align: right; border-bottom: 1px solid #000; color: #000; width: 140px;">Расположение</td>
                <td colspan="2"><textarea style="width: 300px;" name="raspologenie"><?=$result_obj['raspologenie']; ?></textarea></td>
            </tr>
            <tr>
                <td style="text-align: right; border-bottom: 1px solid #000; color: #000; width: 140px;">Доступ</td>
                <td colspan="2"><textarea style="width: 300px;" name="dostup"><?=$result_obj['dostup']; ?></textarea></td>
            </tr>
            <tr>
                <td class="tbl-info-caption t-opt-blue t-opt-bold">Замок</td>
                <td>
                    <p><strong><?=$result_obj['zamok']; ?></strong></p>
                    <input type="text" size="20" value="<?=$result_obj['sost_zamka']; ?>" name="sost_zamka" />
                </td>
                <td>
                     <input type="button" id="zamenaZamka" style="border-bottom: 1px solid grey; border-right: 1px solid grey; background: white; color: grey;" value="Изменить замок" onclick="$('#changeZamok').prop('hidden', false); getZamokList();"/><br />
                    <div style="border: 1px solid red; width: 400px;" id="changeZamok" hidden="">
                    Номер замка <select class="form-input" id="zamok"></select><br />
                    <input type="button" id="chngZamok" value="Сохранить" />
                    </div>
                </td>

            </tr>

            
            <tr>
                <td style="text-align: right; border-bottom: 1px solid #000; color: #000; width: 140px;">Крепление</td>
                <td colspan="2"><textarea style="width: 300px;" name="kreplenie_switch"><?=$result_obj['kreplenie_switch']; ?></textarea></td>
            </tr>
            <tr>
                <td style="text-align: right; border-bottom: 1px solid #000; color: #000; width: 140px;">Доп.оборудование</td>
                <td colspan="2"><textarea style="width: 300px;" name="dop_oborud"><?=$result_obj['dop_oborud']; ?></textarea></td>
            </tr>
            <tr>
                <td style="text-align: right; border-bottom: 1px solid #000; color: #000; width: 140px;">Наклейки</td>
                <td colspan="2"><p><input type="checkbox" value="1"  <?=($result_obj['nakl_vlasnist']) ? 'checked=""' : ''; ?> name="nakl_vlasnist" /> Собственность "Киевстар" </p>
                <p> <input type="checkbox" value="1" <?=($result_obj['nakl_molniya']) ? 'checked=""' : ''; ?> name="nakl_molniya" /> Молния</p>
                <p> <input type="checkbox" value="1"  <?=($result_obj['nakl_shema']) ? 'checked=""' : ''; ?> name="nakl_shema" /> Схема электропитания</p></td>
            </tr>
            <tr>
                <td style="text-align: right; border-bottom: 1px solid #000; color: #000; width: 140px;">Подрядчик</td>
                <td colspan="2"><?=$result_obj['gpo'];?></td>
                <!--<td><p>Месяц ППО <input type="text" size="5"  value="<?=$result_obj['month_ppo']; ?>" name="month_ppo" /></p></td>
                
                <td><p>Процент выполнения ППО <input type="text" size="5" value="<?=$result_obj['percent_work_ppo']; ?>" name="percent_work_ppo" /></p></td>-->
            </tr>
            <tr>
                <td style="text-align: right; border-bottom: 1px solid #000; color: #000; width: 140px;">Балансодержатель</td>
                <td colspan="2"><input type="text"  value="<?=$result_obj['prenadleg_pod']; ?>" name="prenadleg_pod" /></td>
            </tr>

            </table>
            <!-- Перечень коммутаторов start -->
            <br />
            <table class="mytable" id="resultTable">
                <tr>
                    <th style="background-color: rgb(214,255,214);">Коммутатор</th>
                    <!--<th>Nbr</th>-->
                    <!--<th></th>-->
                    <!--<th>Улица</th>-->
                    <!--<th>дом</th>-->
                    <!--<th>под.</th>-->
                    <!--<th>Объект</th>-->
                    <th style="background-color: rgb(214,255,214);">IP адрес</th>
                    <!--<th>Ring</th>-->
                    <th style="background-color: rgb(214,255,214);">Оборудование</th>
                    <th style="background-color: rgb(214,255,214);">Серийный №</th>
                    <th style="background-color: rgb(214,255,214);">VLAN</th>
                    <th style="background-color: rgb(214,255,214);">In Service</th>
                </tr>
                <?php if(!empty($result_sw)):?>
                    <?php foreach($result_sw as $val): ?>
                        <tr>
                            <td><?=$val['switch_name']?></td>
                            <!--<td><?=$val['nbr']?></td>-->
                            <!--<td><?=$val['prefix']?></td>-->
                            <!--<td><?=$val['street']?></td>-->
                            <!--<td><?=$val['house']?></td>-->
                            <!--<td><?=$val['pod']?></td>-->
                            <!--<td><?=$val['object_name']?></td>-->
                            <td><a href="#" onclick="pingIp('<?=$val['ip']?>');"><?=$val['ip']?></a></td>
                            <!--<td><?=$val['ring']?></td>-->
                            <td><?=$val['equipment']?></td>
                            <td><?=$val['serial']?></td>
                            <td><?=$val['vlan']?></td>
                            <td><?=$val['inservice']?></td>
                        </tr>
                    <?php endforeach; ?>
                <? endif;?>
            
            </table>

            <input type="button" style="clear: both;" id="addSwitch" value="Добавить свитч" /></p>
            <!-- Перечень коммутаторов end-->
        </div>
        </div>
        <div id="Electriks">
            <table class="mytable">
            <tr>
                <td>Автомат в ТКД </td>
                <td><input type="text" size="50" value="<?=$result_obj['avtomat_tkd']; ?>" name="avtomat_tkd" /></td>
            </tr>
            <tr>
                <td>Автомат в щитке</td>
                <td><input type="text" size="50" value="<?=$result_obj['avtomat_yashik']; ?>" name="avtomat_yashik" /></td>
            </tr>
            <tr>
                <td>Кабель в щитке(бирка) </td>
                <td><input type="checkbox" value="1" size="50" <?=($result_obj['birka_shitok']) ? 'checked=""' : ''; ?> name="birka_shitok" /></td>
            </tr>
            <tr>
                <td>Сведения об орг. эл.питания </td>
                <td><input type="text" size="50" value="<?=$result_obj['podkl_pitanie']; ?>" name="podkl_pitanie" /></td>
            </tr>
            <tr>
                <td>Тип кабеля питания </td>
                <td><input type="text" size="50" value="<?=$result_obj['tip_kabel_pitaniya']; ?>" name="tip_kabel_pitaniya" /></td>
            </tr>                                            
            </table>


        </div>
        <div id="Optiks">
        <table class="mytable">
        <tr>
            <td colspan="4" style="background-color: rgb(255,255,153);">Кабель 1</td>
        </tr>
        <tr>
            <td><p>На ТКД <input type="text" size="25" value="<?=$result_obj['optika1_dest']; ?>" name="optika1_dest" /></p></td>
            <td><p>Бирка в подъезде <input type="checkbox" value="1"  <?=($result_obj['optika1_birka_in']) ? 'checked=""' : ''; ?> name="optika1_birka_in" /></p></td>
            <td><p>Бирка на крыше <input type="checkbox" value="1" <?=($result_obj['optika1_birka_out']) ? 'checked=""' : ''; ?> name="optika1_birka_out" /></p></td>
            <td><p>Состояние <input type="text" size="50" value="<?=$result_obj['optika1_sost']; ?>" name="optika1_sost" /></p></td>   
        </tr>
        <tr>
            <td colspan="4" style="background-color: rgb(255,255,153);">Кабель 2</td>
        </tr>
        <tr>
            <td><p>На ТКД  <input type="text" size="25" value="<?=$result_obj['optika2_dest']; ?>" name="optika2_dest" /></p></td>
            <td><p>Бирка в подъезде <input type="checkbox" value="1" <?=($result_obj['optika2_birka_in']) ? 'checked=""' : ''; ?> name="optika2_birka_in" /></p></td>
            <td><p>Бирка на крыше <input type="checkbox" value="1" <?=($result_obj['optika2_birka_out']) ? 'checked=""' : ''; ?> name="optika2_birka_out" /></p></td>
            <td><p>Состояние <input type="text" size="50" value="<?=$result_obj['optika2_sost']; ?>" name="optika2_sost" /></p></td>           
        </tr>
        <tr>
            <td colspan="4" style="background-color: rgb(255,255,153);">Кабель 3</td>
        </tr>
        <tr>
            <td><p>На ТКД  <input type="text" size="25" value="<?=$result_obj['optika3_dest']; ?>" name="optika3_dest" /></p></td>
            <td><p>Бирка в подъезде  <input type="checkbox" value="1" <?=($result_obj['optika3_birka_in']) ? 'checked=""' : ''; ?> name="optika3_birka_in" /></p></td>
            <td><p>Бирка на крыше <input type="checkbox" value="1" <?=($result_obj['optika3_birka_out']) ? 'checked=""' : ''; ?> name="optika3_birka_out" /></p></td>
            <td><p>Состояние<input type="text" size="50" value="<?=$result_obj['optika3_sost']; ?>" name="optika3_sost" /></p></td>           
        </tr>
        <tr>
            <td colspan="4" style="background-color: rgb(255,255,153);">Кабель 4</td>
        </tr>    
        <tr>
            <td><p>На ТКД <input type="text" size="25" value="<?=$result_obj['optika4_dest']; ?>" name="optika4_dest" /></p></td>
            <td><p>Бирка в подъезде <input type="checkbox" value="1" <?=($result_obj['optika4_birka_in']) ? 'checked=""' : ''; ?> name="optika4_birka_in" /></p></td>
            <td><p>Бирка на крыше <input type="checkbox" value="1" <?=($result_obj['optika4_birka_out']) ? 'checked=""' : ''; ?> name="optika4_birka_out" /></p></td>
            <td><p>Состояние<input type="text" size="50" value="<?=$result_obj['optika4_sost']; ?>" name="optika4_sost" /></p></td>
        </tr>                    
        </table>
        </div>
        <div id="foto"></div>
    
    
</div>
<div class="button-center">
    <input type="submit" id="saveObject" class="small-button" value="Сохранить" />
    <input type="button" id="close" value="Закрыть" class="small-button" />
</div>

</form>
<div id="addSwDialog" title="Добавить свитч">
            <form id="addNewSwitchForm">
            <input type="hidden" name="object_id" />
            <input type="hidden" name="ring_id" />
            
                <table>
                    <tr>
                        <td>Свитч</td>
                        <td><input type="text" name="switch" /></td>
                    </tr>
                    <tr>
                        <td>IP-адрес</td>
                        <td><input type="text" name="ip" /></td>
                    </tr>
                    <tr>
                        <td>Vlan</td>
                        <td><input type="text" name="vlan" /></td>
                    </tr>
                    <tr>
                        <td>Серийный номер</td>
                        <td><input type="text" name="serial" /></td>
                    </tr>
                    <tr>
                        <td>Тип оборудования</td>
                        <td>
                            <select name="equipment_id" size="1">
                                <?php foreach($equipment as $eq):?>
                                    <option value="<?=$eq['id'];?>"><?=$eq['equipment'];?></option>
                                <?php endforeach;?>
                            </select>
                        </td>
                    </tr>                
                </table>  
            </form>
</div>
<div id="chngAddr" title="Изменить адрес">
    <form id="frmAddr">
        <p>Город <select class="form-input" name="town" id="town" size="1">
                                </select></p>
        <p>Улица <select class="form-input" name="street" id="street" size="1">
                                </select>
        <input type="text" size="3" value="<?=$result_obj['house']; ?>" name="house" />
        <input type="text" size="3" value="<?=$result_obj['pod']; ?>" name="pod" /></p>
        
    </form>

</div>
<script>
$(document).ready(function(){
    $('#close').click(function(){
        window.close();
    })    
    $('table tr > *').css({'padding':'3px 3px 3px 3px'});
    $('#btnChngAddr').click(function(){
        $('#chngAddr').dialog('open');
    })
    $('#addSwitch').click(function(){
        $.ajax({
            url: window.location.origin +'/ved/object/addSwGetParams',
            type: 'GET',
            data: 'tkd=<?=$tkd?>',
            dataType: 'json',
            success: function(data){
                $('[name=switch]').val(data['newSwitch']);
                $('[name=ip]').val(data['newIp']);
                $('[name=vlan]').val(data['newVlan']);
                $('[name=object_id]').val(data['object_id']);
                $('[name=ring_id]').val(data['ring_id']);
            },
            
            complete: function(){
                $('#addSwDialog').dialog('open');
            }
        })
        
    })
    $('#chngAddr').dialog({
        autoOpen: false,
        modal: true,
        width: "360px",
        buttons: {
            "Соханить": function(){
                var params = $('#frmAddr').serialize();
                $.ajax({
                    url: window.location.origin+'/ved/object/updateStreet',
                    type: 'POST',
                    data: params+'&obj='+$('[name=id_object]').val(),
                    success: function(){
                        location.reload();
                    }
                })
            },
            "Закрыть": function(){
                    $(this).dialog('close');
            }
        }
    })
    $('#addSwDialog').dialog({
            autoOpen: false,
            modal: true,
            buttons: {
                "Добавить": function(){
                    var params = $('#addNewSwitchForm').serialize();
                    $.ajax({
                        url: window.location.origin+'/ved/object/addSwitch',
                        type: 'POST',
                        data: params,
                        success: function(){
                            location.reload();
                        }
                    })
                    $(this).dialog('close');
                },
                "Закрыть": function(){
                    $(this).dialog('close');
                }
            }
        })   
})

$('#town').click(function(){
    getStreetList();
})

$('#chngZamok').click(function(){
    updateZamok();
})
$(document).ready(function(){
    $('#tabs').tabs();
    getTownList();
})
function pingIp(ip){
    $('#ajaxWait').show();
    var cont='';
    $.ajax({
        url: window.location.origin+'/ved/object/pingHost',
        type: 'GET',
        dataType: 'json',
        data: 'ip='+ip+'&jumps=4',
        success: function(data){
                for(var i=0;i<data.length;i++){
                        cont += data[i]+"\n";
                    
                }
                
            
        },
        complete: function(){
            $('#ajaxWait').hide();
            if(cont == undefined)
                alert('Нет пинга')
            else
                alert(cont);
        }
    })
}
function updateZamok(){
    $('#ajaxWait').show();
    $.ajax({
        url: '<?=base_url();?>ved/object/updateZamok',
        data: 'zamok_id='+$('#zamok option:selected').val()+'&object_name='+$('#object').val()+'&addr='+$('#street option:selected').text() + ' д.'+$('[name=house]').val()+' п.'+$('[name=pod]').val()+'&nameZamok='+$('#zamok option:selected').text(),
        type: 'post',
        success: function(){
            $('#ajaxWait').hide();
            alert('Операция выполнена');
            $('#changeZamok').prop('hidden', true);
        }
    })
}

function getTownList(){
            var cont ='';
            $('#town').empty();
                $.ajax({
                   type: 'GET',
                   url: '<?=base_url();?>ved/object/getTownList',
                   dataType: 'json',
                   success: function(data){
                        $('#town').append(function(){
                            if(data){
                                for(var i=0; i<data.length; i++){
                                    cont +='<option value="'+data[i]['id']+'" '+((data[i]['town'] == '<?=$result_obj['town'];?>') ? 'selected' : '')+'>'+data[i]['town']+'</option>';
                                }
                                return cont;                               
                            }
                        })
                   },
                   complete: function(){
                        getStreetList();
                        getPermissionFields('object_lv_allinfo');
                   }
                })
                
}



function getZamokList(){
            var cont ='';
            $('#zamok').empty();
                $.ajax({
                   type: 'GET',
                   url: '<?=base_url();?>ved/object/getZamokList',
                   dataType: 'json',
                   success: function(data){
                        $('#zamok').append(function(){
                            if(data){
                                for(var i=0; i<data.length; i++){
                                    cont +='<option value="'+data[i]['id']+'">'+data[i]['zamok']+'</option>';
                                }
                                return cont;                               
                            }
                        })
                   },
                   complete: function(){
                        getPermissionFields('object_lv_allinfo');
                   }
                })
                
}

function getStreetList(){
            var cont ='';
            $('#street').empty();
            $('#ajaxWait').show();
                $.ajax({
                   type: 'GET',
                   url: '<?=base_url();?>ved/object/fillStreetList',
                   data: 'town_id='+$('#town option:selected').val(),
                   dataType: 'json',
                   success: function(data){
                        $('#street').append(function(){
                            if(data){
                                for(var i=0; i<data.length; i++){
                                    cont +='<option value="'+data[i]['id']+'" '+((data[i]['street'] == '<?=$result_obj['street'];?>') ? 'selected' : '')+'>'+data[i]['street']+'</option>';
                                }
                                return cont;                               
                            }
                        })
                   },
                   complete: function(){
                        getPermissionFields('object_lv_allinfo');
                        $('#ajaxWait').hide();
                   }
                })
                
}

</script>