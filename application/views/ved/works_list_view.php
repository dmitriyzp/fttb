<div id="ajaxWait">
    <div id="ajaxAnim"></div>
</div>
<div id="filter">
    <form id="myfilter">
   
    <table>
        <tr>
            <td class="tbl-info-caption t-opt-red" style="width: 150px; padding: 0px 10px 0 10px;">Настройки фильтра</td>
            <td><input type="checkbox" id="closed" value="1"/> Показывать закрытые задачи за: 
                <select id="periodClosed" size="1">
                    <option value="day">Сегодня</option>
                    <option value="3day">Последние 3 дня</option>
                    <option value="week">Последнюю неделю</option>
                    <option value="month">Последний месяц</option>
                </select>
            </td>
        </tr>
        <tr>
        <td></td>
            <td><input type="checkbox" id="selTypeWork" value="3"/> Показывать задачи только с типом: 
                <select id="typeWork">
                    <?php foreach($typeWork as $item):?>
                        <option value="<?=$item['id']?>"><?=$item['tip']?></option>
                    <?php endforeach;?>
                </select>
            </td>
        </tr>
        <tr>
        <td></td>
            <td><input type="checkbox" id="rayon" value="4" /> Показывать задачи только с районом: 
                <select id="selrayon">
                    <?php foreach($rayon as $item):?>
                        <option value="<?=$item['rayon']?>"><?=$item['rayon']?></option>
                    <?php endforeach;?>
                </select>
            </td>
        </tr>
    </table>
    </form>
    <input type="button" style="margin-left: 40px; float: right;" value="Рассылка" onclick="toMail();" />
    <input type="button" style="margin-left: 40px; float: right;" value="Печать" onclick="getPrint();" />
    <input type="button" id="clearGroups" style="margin-left: 40px; float: right;" value="Сброс бригады" onclick="clearGroup();" />
    <input type="button" style="margin-left: 40px; float: right;" value="Обновить" onclick="getDataTable();" />
    
    
    <br /><br />
</div>
<div id="result">
    <table id="resultTable" class="Ztable">
    </table>
</div>
<script>
if (!window.location.origin) {
  window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
}
    $(document).ready(function(){
            $('#zShow').click(function(){
                $('#objectsInfo').hide();
                $('#mainInfo').show();
                $('#workEdit').hide();
            })
            $('#objShow').click(function(){
                $('#objectsInfo').show();
                $('#mainInfo').hide();
                $('#workEdit').hide();
            })
            $('#wEdit').click(function(){
                $('#objectsInfo').hide();
                $('#mainInfo').hide();
                $('#workEdit').show();
            })
            

            $('#dialogChangeStatus').dialog({
                autoOpen: false,
                modal: true,
                width: 870,
                height: 600,
                top: 10,
                left: 10,
                buttons: {
                    "Добавить событие": function(){
                        addStatus();
                    },
                    "Закрыть": function(){
                        $(this).dialog('close');
                    }
                }
            })
            $('#dialogChangeStatus').attr("hidden","false");
            $('[name=srok]').datetimepicker({
                format: "d-m-Y H:i",
                mask:true
            })

            
    })
    
    function toMail(){
        $.ajax({
            url: 'toEmail',
            type: 'GET',
            data: 'emsend=1'
        })
    }
    
    function addStatus(){
        var params = $('#addHistory').serialize();
        $.ajax({
            url: 'addHistory',
            type: 'GET',
            data: params,
            success:function(){
                alert("Записано!");
                $('#dialogChangeStatus').dialog('close');
                getDataTable();
            }
        })
    }
    function editWorkSend(){
        var descr = $('[name=description]').val();
        var tw = $('[name=typeWork] option:selected').val();
        var srok = $('[name=srok]').val();
        $.ajax({
            url: 'editWork',
            type: 'POST',
            data: 'description='+descr+'&typeWork='+tw+'&srok='+srok+'&workid='+$('[name=workid]').val(),
            success: function(){
                alert("Изменения успешно записаны");
                $('#dialogChangeStatus').dialog('close');
                getDataTable();                
            }
        })
    }
    function dlgChngStat(id){
        var lists='';
        var cont='';
        $('[name=reshenie]').val('');
        $('[name=workid]').val(id);
        $('#dialogChangeStatus').dialog({
            title: "Изменение статуса задачи №"+id
        })
        $.ajax({
            url: 'getAllInfo',
            type: 'GET',
            dataType: 'json',
            data: 'idwork='+id,
            success: function(data){
                $('#objTabs').empty();
                $('#objTabs').tabs();
                $('#objTabs').tabs('destroy');
                $('#objTabs').append(function(){
                    lists +='<ul>';
                    for(var i=0;i<data['obj'].length;i++){
                        lists +='<li><a href="#' + data['obj'][i]['object_name'] + '">' + data['obj'][i]['object_name'] + '</a>';
                        cont += '<div id="' + data['obj'][i]['object_name'] + '">';
                        cont += '<iframe width="800" height="400" frameborder="0" src="<?=base_url();?>ved/object/object_all_info/' + data['obj'][i]['id'] + '/'+ data['obj'][i]['object_name'] +'"></iframe>';
                        cont += '</div>';
                    }
                    lists +='</ul>'+cont;
                    
                    return lists;
                });
                
                $('[name=description]').val(data['work']['description']);
                $('[name=srok]').val(data['work']['srok']);
                $('[name=typeWork] :contains("'+data['work']['tip']+'")').attr('selected', 'selected');
                $('#objTabs').tabs();
                
            }
        })
        $('#mainInfo').show();
        $('#objectsInfo').hide();
        $('#workEdit').hide();
        $('#dialogChangeStatus').dialog('open');
    }
    
    function clearGroup(){
        $.ajax({
            url: 'clearGroup',
            type: 'get',
            complete: function(){
                getDataTable();
            }
        })
    }

    function setGroup(wID, gID){
        if(gID>=0){
            $.ajax({
                url: 'setGroup',
                type: 'get',
                data: 'wID='+wID+'&gID='+gID
        })
        }

    }
    
    function getPrint(){
        var win = new Array();
        $.ajax({
            url: 'toPrint',
            type: 'get',
            dataType: 'json',
            success: function(data){
                for(var n=0;n<data['numPages'];n++){
                    var cont = '';
                    win.push('window'+n);
                    win['window'+n] = window.open("", "Group->"+n, "toolbar=yes, resizable=1, width=600, height=300");
                        cont +='<table class="mytable">';
                        cont +='<tr>';
                        cont +='<th>Номер п.п</th>';
                        cont +='<th>Тип</th>';
                        cont +='<th>Описание</th>';
                        cont +='<th>Объект</th>';
                        cont +='<th>Оборудование</th>';
                        cont +='</tr>';
                        for(var i=0;i<data[n].length;i++){
                            cont +='<tr>';
                                cont +='<td>'+data[n][i]['id']+'</td>';
                                cont +='<td>'+data[n][i]['tip']+'</td>';
                                cont +='<td>'+data[n][i]['description']+'</td>';
                                cont +='<td>'+data[n][i]['objects']+'</td>';
                                cont +='<td>'+data[n][i]['switches']+'</td>';
                            cont +='</tr>';
                        }
                        cont +='</table>';
                    win['window'+n].document.write('<!DOCTYPE html><html><head><meta charset="utf-8" />');
                    win['window'+n].document.write('<title>Задачи для группы №'+(n+1)+'</title>');
                    win['window'+n].document.write('<h2>Бригада №'+(n+1)+'</h2><br>');
                    win['window'+n].document.write('<link href="/style/css/style.css" rel="stylesheet"></head><body>');                
                    win['window'+n].document.write(cont);
                    win['window'+n].document.writeln('\<script\> document.close(); \</script\></body></html>');
                    win['window'+n].focus();
                }
            }
        })
    }

        function getDataTable(sortTable, sortOrder){
            var cont='';
            var myorder = (sortTable) ? '&column='+sortTable+'&direction='+sortOrder : '';
            var myfilter = 'find=ok'+myorder;
            if($('#selTypeWork:checked').val())
                myfilter += '&typeWork='+$('#typeWork option:selected').val();
                
            if($('#closed:checked').val())
                myfilter +='&period='+$('#periodClosed option:selected').val();
                
            if($('#rayon:checked').val())
                myfilter +='&rayon='+$('#selrayon option:selected').val();
            $('#ajaxWait').show();
                
            $.ajax({
                url: 'getDataTable',
                type: 'GET',
                dataType: 'json',
                data: myfilter,
                success: function(data){
                    $('#resultTable').empty();
                    $('#resultTable').append(function(){
                        cont +='<tr>';
                        cont +='<th>Задача<a href="#" onclick="getDataTable(\'w.id\', \'ASC\');" class="sortAsc"></a><a href="#" onclick="getDataTable(\'w.id\', \'DESC\');" class="sortDesc"></a></th>';
                        cont +='<th>Текущий статус</th>';
                        cont +='<th>Тип<a href="#" onclick="getDataTable(\'wt.tip\', \'ASC\');" class="sortAsc"></a><a href="#" onclick="getDataTable(\'wt.tip\', \'DESC\');" class="sortDesc"></a></th>';
                        cont +='<th>Объекты</th>';//multi
                        cont +='<th>Оборудование</th>';
                        cont +='<th>История</th>';
                        cont +='<th>Срок выполнения<a href="#" onclick="getDataTable(\'w.srok\', \'ASC\');" class="sortAsc"></a><a href="#" onclick="getDataTable(\'w.srok\', \'DESC\');" class="sortDesc"></a></th>';
                        cont +='<th>Район</th>';
                        cont +='</tr>';
                        for(var i=0;i<data.length;i++){
                                if(data[i]['objects'].length == 0)
                                    continue; 
                                cont +='<tr workstatus="'+data[i]['deystvie']+'">';
                                //cont +='<td><a name="'+data[i]['id']+'"></a><a class="changeStatus" href="#'+data[i]['id']+'" onclick="dlgChngStat('+data[i]['id']+')">'+data[i]['id']+'</a></td>';
                                cont +='<td class="psevdoHref" onclick="dlgChngStat('+data[i]['id']+')">'+data[i]['id']+'</td>';
                                cont +='<td>'+data[i]['deystvie']+'</td>';
                                cont +='<td>'+data[i]['tip']+'</td>';
                                cont +='<td>';                                                                                             
                                for(var addTd=0;addTd<data[i]['objects'].length;addTd++){
                                        cont +='<a class="allinfo" href="'+window.location.origin+'/ved/object/object_all_info/'+data[i]['objects'][addTd]['id']+'/'+data[i]['objects'][addTd]['name']+'" target="_blank" >'+data[i]['objects'][addTd]['name']+'</a> ('+data[i]['objects'][addTd]['kluch']+')';
                                        cont +=' - '+data[i]['objects'][addTd]['addr'];
                                        cont +=' [ '+data[i]['objects'][addTd]['dostup']+' ]<br />';
                                        
                                }
                                cont +='<td>';
                                for(var swCnt=0;swCnt<data[i]['switches'].length;swCnt++)
                                    cont+='<a class="switchInfo" href="'+window.location.origin+'/ved/switches/switchInfo/'+data[i]['switches'][swCnt]['swID']+'/'+data[i]['switches'][swCnt]['switch']+'" target="_blank">'+data[i]['switches'][swCnt]['switch']+'</a><br />';
                                cont +='</td>';
                                cont +='<td>'+data[i]['history']+'</td>';
                                cont +='<td>'+data[i]['srok']+'</td>';
                                cont +='<td>'+data[i]['rayon']+'</td>';
                                    
                                
                            cont +='</tr>';
                        }
                        return cont;

                    })
                    
                },
                complete: function(){
                    $('#ajaxWait').hide();
                }
            })
        }
</script>
<div id="dialogChangeStatus" title="Изменение статуса работы" hidden="">
    <div id="mainChangeTabs">
            <button class="btnGhost" id="zShow">Задача</button>
            <button class="btnGhost" id="objShow">Объекты</button>
            <button class="btnGhost" id="wEdit">Описание задачи</button>
        <div id="mainInfo">
            <form id="addHistory">
                <table>
                    <tr>
                        <td>Выбор сотрудников</td>
                        <td>Решение проблемы:</td>
                        <td>Статус работы:</td>
                    </tr>
                    <tr>
                        <td>
                            <select name="personal[]" multiple="true" size="10" style="width: 150px; overflow: auto;">
                                <?php foreach($personal as $fam): ?>
                                    <option value="<?=$fam['id'];?>"><?=$fam['familiya'] . ' ' . $fam['name'];?></option>
                                <?php endforeach;?>
                            </select>
                        </td>
                        <td><textarea name="reshenie" style="width: 150px; height: 100px;"></textarea></td>
                        <td><input type="radio" name="statusWork" value="2" />Назначена<br />
                        <input type="radio" name="statusWork" value="3" checked="" />В работе<br />
                        <input type="radio" name="statusWork" value="4" />Закрыть<br />
                        <input type="hidden" name="workid" value="" /></td>
                    </tr>
                    </table> 

                
            </form>
        </div>
        <div id="workEdit">
        <table>
            <tr>
                <td>Описание</td>
                <td>Тип</td>
                <td>Срок</td>
            </tr>
            <tr>
                <td><textarea name="description" style="width: 150px; height: 100px;"></textarea></td>
                <td>
                    <select name="typeWork" size="1">
                        <?php foreach($typeWork as $item) :?>
                        <option value="<?=$item['id'];?>"><?=$item['tip'];?></option>
                        <?php endforeach;?>
                    </select>
                </td>
                <td><input type="text" name="srok"/></td>
            </tr>
            <tr>
                <td rowspan="3"><input type="button" onclick="editWorkSend();" value="Сохранить изменения" /></td>
            </tr>
        </table>
        </div>
            
        <div id="objectsInfo">
        
        <div id="objTabs" style="width: 820px;">

        </div>

        </div>
    </div>
 </div>
