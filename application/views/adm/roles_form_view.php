
<script>
$(document).ready(function(){
    $('#tabs').tabs();
    getFormsNames();

    $('#nameform').change(function(){
        $('#namefield').empty();
        getFormsFields();
        
        
    })
    $('#addfield').click(function(){
        addNewField();
    })

})

function addNewField(){
    var cont='';
    cont +='<tr>';
    cont +='<td>'+$('#nameform :selected').text()+'</td>';
    cont +='<td>'+$('#namefield :selected').text()+'</td>';
    cont +='<td><input type="radio" name="newfield'+$('#namefield option:selected').val()+'" value="1" '+($('#perm').prop('checked') ? 'checked ' : '')+'disabled></td>';
    cont +='<td><input type="radio" name="newfield'+$('#namefield option:selected').val()+'" value="0" '+(!$('#perm').prop('checked') ? 'checked ' : '')+'disabled></td>';
    cont +='</tr>'; 
    if($('[name=newfield'+$('#namefield option:selected').val()+']').length<1){
        $('#fftable').append(cont);
    }
    $.ajax({
        url: '<?=base_url();?>adm/roles/addnewfield',
        type: 'get',
        dataType: 'text',
        data: 'role_id='+<?=$id?>+'&islocked='+($('#perm').prop('checked') ? '1 ' : '0') + '&fieldid='+$('#namefield option:selected').val()
    })

    
}

function getFormsNames(){
    var cont='';
    $.ajax({
        url: '<?=base_url();?>adm/roles/getFormsAjax',
        dataType: 'json',
        type: 'GET',
        success: function(data){
            $('#nameform').empty();
            $('#nameform').append(function(){
                cont += '<option value=""></option>';
                for(var i=0; i<data.length; i++){
                    cont += '<option value="'+data[i]['id']+'">'+data[i]['description']+'</option>';
                }
                return cont;
            })

        }
    })
}

function getFormsFields(){
    var cont='';
    var formid = $('#nameform :selected').val();
    $.ajax({
        url: '<?=base_url();?>adm/roles/getFormsFieldsAjax',
        dataType: 'json',
        type: 'GET',
        data: 'formid='+formid,
        success: function(data){

            $('#namefield').append(function(){
                for(var i=0; i<data.length; i++){
                    cont += '<option value="'+data[i]['id']+'">'+data[i]['description']+'</option>';
                }
                return cont;
            })
        }
    })
}
</script>
 <div id="tabs">
        <ul>
            <!-- <li><a href="#methods">Методы</a></li> -->
            <li><a href="#forms">Формы и поля</a></li>
        </ul>
<!-- <div id="methods">
<form action="" method="POST">
    <p><label for="role">Введите название роли</label></p>
    <p><input type="text" class="form-input" name="role" value="<?php echo (isset($tblarray[0]['role'])) ? $tblarray[0]['role'] : ""; ?>" id="role" placeholder="название роли" /></p>
    <?php if (isset($id)): ?>
        <br />
        <p><input type="hidden" name="id" value="<?=$id;?>" /></p>
            <p><table class="mytable"></p>
                <tr><th>Название метода</th><th>Описание метода</th><th>Разрешено</th><th>Запрещено</th></tr>
                <?php foreach ($methods as $item): ?>
                    <tr><td><?= $item['pathMethod']; ?></td><td><?= $item['description']; ?></td>
                    <td><input type="radio" name="fid<?= $item['funct_id']; ?>" value="1" <?= ($item['allow'] == 1) ? "checked" : ""; ?>/></td>
                    <td><input type="radio" name="fid<?= $item['funct_id']; ?>" value="0" <?= (!$item['allow'] == 1) ? "checked" : ""; ?>/></td>
                    </tr>
                <?php endforeach; ?>
                
            </table>
    <?php endif; ?>
</div> -->
<div id="forms">
<form action="" method="POST">
    <?php if (isset($id)): ?>
    <input type="hidden" name="id" value="<?=$id;?>" />
    <?php endif; ?>
    <p><label for="role">Введите название роли</label></p>
    <p><input type="text" class="form-input" name="role" value="<?php echo (isset($tblarray[0]['role'])) ? $tblarray[0]['role'] : ""; ?>" id="role" placeholder="название роли" /></p>
    <!--<div id="addNewField" style="border: 1px dotted steelblue; padding: 5px;">
        Наименование формы <select size="1" class="form-input form-input-small" id="nameform" >
        </select>
        Наименование полей <select size="1" class="form-input form-input-small" id="namefield" >
        </select>
        <input type="checkbox" value="1" id="perm" /> Разрешить
        <input type="button" class="small-button" id="addfield" value="Добавить" />
        <br />
    </div> -->
    <div id="fieldForm">
    <br />
    <p><table class="mytable" id="fftable"></p>
    <tr><th>Название формы</th><th>Описание поля</th><th>Разрешено</th><th>Запрещено</th></tr>
        <?php if (isset($id) && !empty($formfields)): ?>

                    <?php foreach ($formfields as $item): ?>
                        <tr><td><?= $item['formname']; ?></td><td><?= $item['fieldname']; ?></td>
                        <td><input type="radio" name="field<?= $item['fieldid']; ?>" value="0" <?= ($item['islocked'] == 0) ? "checked" : ""; ?>/></td>
                        <td><input type="radio" name="field<?= $item['fieldid']; ?>" value="1" <?= ($item['islocked'] == 1) ? "checked" : ""; ?>/></td>
                        </tr>
                    <?php endforeach; ?>
        <?php endif; ?>
    </table>
    </div>
</div>

</div>
  <br />
<div style="clear: both;">
            <p><input type="submit" name="<?= (isset($id)) ? "update" : "save"; ?>" value="Сохранить" class="small-button" /></p>
</div>
</form>


