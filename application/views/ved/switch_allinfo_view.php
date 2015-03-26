
<form action="<?=base_url();?>ved/switches/switchUpdate" method="post">
<table class="divCenter" style="margin-top: 10px; width: 400px;">
    <tr>
        <td>Switch</td><td><input type="text" size="30" name="switch" id="switch" readonly="" value="<?=$result['switch'];?>" /></td>
    </tr>
    <tr>
        <td>NBR</td><td><input type="text" size="30" name="nbr" id="nbr" value="<?=$result['nbr'];?>" /></td>
    </tr>
    <tr>
        <td>Object</td><td><input type="text" size="30" name="object" id="object" readonly="" value="<?=$result['object_name'];?>" /></td>
    </tr>
    <tr>
        <td>IP</td><td><input type="text" size="30" name="ip" id="ip" value="<?=$result['ip'];?>" /></td>
    </tr>
    <tr>
        <td>RING</td><td><select class="form-input" name="ring" id="ring" size="1">
                </select></td>
    </tr>
    <tr>
        <td>Equipment</td><td><select class="form-input" name="equipment" id="equipment" size="1">
                </select></td>
    </tr>
    <tr>
        <td>Serial</td><td> <input type="text" size="30" name="serial" id="serial" value="<?=$result['serial'];?>" /></td>
    </tr>
    <tr>
        <td>Inservice</td><td> <input type="text" size="30" name="inservice" id="inservice" value="<?=$result['inservice'];?>" readonly /></td>
    </tr>
    <tr>
        <td>Vlan</td><td><input type="text" size="30" name="vlan" id="vlan" value="<?=$result['vlan'];?>" /></td>
    </tr>
    <tr>
        <td>SFP25</td><td><select class="form-input" name="sfp25" id="sfp25" size="1">
                </select></td>
    </tr>
    <tr>
        <td>SFP26</td><td><select class="form-input" name="sfp26" id="sfp26" size="1">
                </select></td>
    </tr>
    <tr>
        <td>TIPPORT25</td><td><select class="form-input" name="tipport25" id="tipport25" size="1">
                </select></td>
    </tr>
    <tr>
        <td>TIPPORT26</td><td><select class="form-input" name="tipport26" id="tipport26" size="1">
                </select></td>
    </tr>
    <tr>
        <td>Switch_LLDP_25</td><td><input size="30" type="text" name="switch_lldp_25" id="switch_lldp_25" value="<?=$result['switch_lldp_25'];?>" /></td>
    </tr>
    <tr>
        <td>Switch_LLDP_26</td><td><input size="30" type="text" name="switch_lldp_26" id="switch_lldp_26" value="<?=$result['switch_lldp_26'];?>" /></td>
    </tr>
    <tr>
        <td>Proverka NameSwitch</td><td><strong><?=$result['proverka_nameswitch'];?></strong></td>
    </tr>
    <tr>
        <td>Дата опроса LLDP</td><td><input type="text" size="30" name="data_oprosa_lldp" id="data_oprosa_lldp" value="<?=$result['data_oprosa_lldp'];?>" /></td>
    </tr>
    <tr>
        <td>Номер в кольце</td><td><input type="text" size="30" name="nomer_v_kolce" id="nomer_v_kolce" value="<?=$result['nomer_v_kolce'];?>" /></td>
    </tr>
    <tr>
        <td>Порт на DS </td><td><input type="text" size="30" name="port_na_ds" id="port_na_ds" value="<?=$result['port_na_ds'];?>" /></td>
    </tr>
    <tr>
        <td>Порт DS DES</td><td><input type="text" size="30" name="port_ds_des" id="port_ds_des" value="<?=$result['port_ds_des'];?>" /></td>
    </tr>
    
</table>
<input type="submit" class="small-button" name="save" id="save" value="Сохранить" />
</form>

<script>
    var curDate = new Date();
$(document).ready(function(){
    var curStatus = $('[name=inservice]').val();
    if(curStatus == "True"){
        $("table tr td > *").attr('disabled', 'disabled');
        $("table tr > *").css({'border-bottom':'1px dotted black'});
        $("#save").hide();
    }
        $('#data_oprosa_lldp').datepicker({
            yearRange:'2013:'+curDate.getFullYear(),
            dateFormat: "yy-mm-dd",
            changeMonth: true,
            changeYear: true,
            inline: true,
            monthNamesShort: [ "Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек" ]
        })
        

    getEquipmentList();
    getRingList();
    getSfpList();
    getTipportList();
    getPermissionFields('switch_allinfo_view');
        
    
})


function getEquipmentList(){
            var cont ='';
            $('#equipment').empty();
                $.ajax({
                   type: 'GET',
                   url: '<?=base_url();?>ved/switches/getEquipmentList',
                   dataType: 'json',
                   success: function(data){
                        $('#equipment').append(function(){
                            if(data){
                                for(var i=0; i<data.length; i++){
                                    cont +='<option value="'+data[i]['id']+'" '+((data[i]['equipment'] == '<?=$result['equipment'];?>') ? 'selected' : '')+'>'+data[i]['equipment']+'</option>';
                                }
                                return cont;                               
                            }
                        })
                   },
                   complete: function(){
                        getPermissionFields('switch_allinfo_view');
                   }
                })
                
}

function getRingList(){
            var cont ='';
            $('#ring').empty();
                $.ajax({
                   type: 'GET',
                   url: '<?=base_url();?>ved/switches/getRingList',
                   dataType: 'json',
                   success: function(data){
                        $('#ring').append(function(){
                            if(data){
                                for(var i=0; i<data.length; i++){
                                    cont +='<option value="'+data[i]['id']+'" '+((data[i]['ring'] == '<?=$result['ring'];?>') ? 'selected' : '')+'>'+data[i]['ring']+'</option>';
                                }
                                return cont;                               
                            }
                        })
                   },
                   complete: function(){
                        getPermissionFields('switch_allinfo_view');
                   }
                })
                
}

function getSfpList(){
            var cont ='';
            $('#sfp25').empty();
            $('#sfp26').empty();
                $.ajax({
                   type: 'GET',
                   url: '<?=base_url();?>ved/switches/getSfpList',
                   dataType: 'json',
                   success: function(data){
                        $('#sfp25').append(function(){
                            if(data){
                                cont +='<option value=""></option>';
                                for(var i=0; i<data.length; i++){
                                    cont +='<option value="'+data[i]['id']+'" '+((data[i]['sfp'] == '<?=$result['sfp25'];?>') ? 'selected' : '')+'>'+data[i]['sfp']+'</option>';
                                }
                                return cont;                               
                            }
                        })
                        var cont ='';
                        $('#sfp26').append(function(){
                            if(data){
                                cont +='<option value=""></option>';
                                for(var i=0; i<data.length; i++){
                                    cont +='<option value="'+data[i]['id']+'" '+((data[i]['sfp'] == '<?=$result['sfp26'];?>') ? 'selected' : '')+'>'+data[i]['sfp']+'</option>';
                                }
                                return cont;                               
                            }
                        })
                   },
                   complete: function(){
                        getPermissionFields('switch_allinfo_view');
                   }
                })
                
}

function getTipportList(){
            var cont ='';
            $('#tipport25').empty();
            $('#tipport26').empty();
                $.ajax({
                   type: 'GET',
                   url: '<?=base_url();?>ved/switches/getTipportList',
                   dataType: 'json',
                   success: function(data){
                        $('#tipport25').append(function(){
                            if(data){
                                cont +='<option value=""></option>';
                                for(var i=0; i<data.length; i++){
                                    cont +='<option value="'+data[i]['id']+'" '+((data[i]['tipport'] == '<?=$result['tipport25'];?>') ? 'selected' : '')+'>'+data[i]['tipport']+'</option>';
                                }
                                return cont;                               
                            }
                        })
                        var cont ='';
                        $('#tipport26').append(function(){
                            if(data){
                                cont +='<option value=""></option>';
                                for(var i=0; i<data.length; i++){
                                    cont +='<option value="'+data[i]['id']+'" '+((data[i]['tipport'] == '<?=$result['tipport26'];?>') ? 'selected' : '')+'>'+data[i]['tipport']+'</option>';
                                }
                                return cont;                               
                            }
                        })
                   },
                   complete: function(){
                        getPermissionFields('switch_allinfo_view');
                   }
                })
                
}    
        

</script>