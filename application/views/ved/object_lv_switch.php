<table class="mytable divCenter" id="resultTable">
    <tr>
        <th>Коммутатор</th>
        <th>Nbr</th>
        <th></th>
        <th>Улица</th>
        <th>дом</th>
        <th>под.</th>
        <th>Объект</th>
        <th>IP адрес</th>
        <th>Ring</th>
        <th>Оборудование</th>
        <th>Серийный №</th>
        <th>In Service</th>
    </tr>
    <?php if(!empty($result)):?>
        <?php foreach($result as $val): ?>
            <tr>
                <td><?=$val['switch_name']?></td>
                <td><?=$val['nbr']?></td>
                <td><?=$val['prefix']?></td>
                <td><?=$val['street']?></td>
                <td><?=$val['house']?></td>
                <td><?=$val['pod']?></td>
                <td><?=$val['object_name']?></td>
                <td><?=$val['ip']?></td>
                <td><?=$val['ring']?></td>
                <td><?=$val['equipment']?></td>
                <td><?=$val['serial']?></td>
                <td><?=$val['inservice']?></td>
            </tr>
        <?php endforeach; ?>
    <? endif;?>

</table>

<input type="button" style="clear: both; float: left; margin-top: 10px;" class="small-button divCenter" id="addSwitch" value="Добавить свитч" /></p>
<div id="addSwDialog" title="Добавить свитч">
<form id="addNewSwitch">
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
<script>
$(document).ready(function(){
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
    getPermissionFields('object_lv_switch');
    $('#addSwDialog').dialog({
        autoOpen: false,
        modal: true,
        buttons: {
            "Добавить": function(){
                var params = $('#addNewSwitch').serialize();
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
</script>