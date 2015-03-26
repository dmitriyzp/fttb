<div id="ajaxWait">
    <div id="ajaxAnim"></div>
</div>

<div class="filter" id="myfilter" >
    <input type="hidden" id="idZam" value="0" />
    <table class="mytable">
        <tr>
            <th>Серия</th>
            <th>Установлен</th>
        </tr>
        <tr>
            <td>
                <select id="seriya">
                    <option value=""></option>
                    <?php foreach($seriya as $item):?>
                    <option value="<?=$item['seriya']?>"><?=$item['seriya']?></option>
                    <?php endforeach; ?>
                </select>
            </td>
            <td>
                <select id="ustanovlen">
                    <option value=""></option>
                    <option value="no">Не установлен</option>
                    <option value="yes">Установлен</option>
                </select>
            </td>
        </tr>
    </table>
    
    <br />
    <p><input type="button" id="clear" value="Очистить" style="width: 150px;" class="small-button" /></p>
    </div>
    <br />
    <div class="dataTable" >
        <p><table class="mytable" id="resultTable">
        </table>
    </div>  
    <div id="dialogPersonal" title="Выдать замок">
    Сотрудник
                    <select id="fio" size="1">
                        <?php foreach($personal as $fio):?>
                            <option value="<?=$fio['id'];?>"><?=$fio['familiya'];?></option>
                        <?php endforeach;?>
                    </select>
    </div>
   <script>
   var idZamok=0;
   function giveZamok(idZ){
        idZamok = idZ;
        $('#dialogPersonal').dialog('open');
   }
   function sendZamok(){
   $.ajax({
       url: 'giveZamok',
       data: 'idZamok='+idZamok+'&idPersonal='+$('#fio option:selected').val(),
       type: 'GET',
       success: function(){
            getDataTable();
       }
   })
   }
   
   $('#clear').click(function(){
        $('#ustanovlen').val("").attr("selected","selected");
        $('#seriya').val("").attr("selected","selected");
   })
   $(document).ready(function(){
        $('#myfilter > *').bind('change', function(){
            getDataTable();
        })
        $('#dialogPersonal').dialog({
            autoOpen: false,
            modal: true,
            width: '200',
            buttons: {
                "Выдать": function(){
                    sendZamok();
                    $(this).dialog('close');
                },
                "Закрыть": function(){
                    $(this).dialog('close');
                }
            }

        })
   })
   
           function getDataTable(sortTable, sortOrder){
            var cont='';
            var myorder = (sortTable) ? '&column='+sortTable+'&direction='+sortOrder : '';
            $('#ajaxWait').show();
            $.ajax({
                url: 'getDataTable',
                dataType: 'json',
                type: 'GET',
                data: 'seriya='+$('#seriya option:selected').val()+myorder+'&ustanovlen='+$('#ustanovlen option:selected').val(),
                success: function(data){            
                    $('#resultTable').empty();
                    $('#resultTable').append(function(){
                    cont+='<tr>';
                    cont+='<th>Номер замка<a href="#" onclick="getDataTable(\'z.zamok\', \'ASC\');" class="sortAsc"></a><a href="#" onclick="getDataTable(\'z.zamok\', \'DESC\');" class="sortDesc"></a></th>';
                    cont+='<th>Выдан<a href="#" onclick="getDataTable(\'p.familiya\', \'ASC\');" class="sortAsc"></a><a href="#" onclick="getDataTable(\'p.familiya\', \'DESC\');" class="sortDesc"></a></th>';
                    cont+='<th>Дата выдачи<a href="#" onclick="getDataTable(\'z.datavidachi\', \'ASC\');" class="sortAsc"></a><a href="#" onclick="getDataTable(\'z.datavidachi\', \'DESC\');" class="sortDesc"></a></th>';
                    cont+='<th>Дата установки<a href="#" onclick="getDataTable(\'z.dataustanovki\', \'ASC\');" class="sortAsc"></a><a href="#" onclick="getDataTable(\'z.dataustanovki\', \'DESC\');" class="sortDesc"></a></th>';
                    cont+='<th>Адрес установки</th>';
                    cont+='</tr>';
                    for(var i=0;i<data.length;i++){
                        cont+='<tr>';
                            if(data[i]['ustanovlen'] == 'no'){
                                cont+='<td onclick="giveZamok('+data[i]['id']+');" class="psevdoHref">'+data[i]['zamok']+'</td>';
                            }else{
                                cont+='<td>'+data[i]['zamok']+'</td>';    
                            }                        
                            if(data[i]['familiya'] != null)
                                cont+='<td>'+data[i]['familiya']+'</td>';
                            else
                                cont+='<td></td>';
                            if(data[i]['datavidachi'] != '0000-00-00')
                                cont+='<td>'+data[i]['datavidachi']+'</td>';
                            else
                                cont+='<td></td>';
                            if(data[i]['dataustanovki'] != '0000-00-00')
                                cont+='<td>'+data[i]['dataustanovki']+'</td>';
                            else
                                cont+='<td></td>';
                            if(data[i]['object_name'] != null)
                                //cont+='<td>'+data[i]['object_name'] +' ('+data[i]['prefix']+'.'+data[i]['street']+', '+data[i]['house']+' п.'+data[i]['pod']+')</td>';
                                cont+='<td><a class="allinfo" href="'+window.location.origin+'/ved/object/object_all_info/'+data[i]['objID']+'/'+data[i]['object_name']+'" target="_blank" >'+
                                        data[i]['object_name']+'</a> ('+data[i]['prefix']+'.'+data[i]['street']+', '+data[i]['house']+' п.'+data[i]['pod']+')</td>';
                            else
                                cont+='<td></td>';

                        cont+='</tr>';
                    }
                    return cont;                        
                    })


                },
                complete: function(){
                    getPermissionFields('switch_list_view');
                    $('#ajaxWait').hide();
                }
            })
        }
        
   </script> 