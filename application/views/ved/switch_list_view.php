<div id="ajaxWait">
    <div id="ajaxAnim"></div>
</div>
    <div class="filter" id="myfilter" >
    <table class="mytable">
        <tr>
            <th>MDU</th>
            <th>ТКД</th>
            <th>IP адрес</th>
            <th>Ring</th>
            <th>Оборудование</th>
            <th>Статус</th>
        </tr>
        <tr>
            <td>
                <div class="mduList"></div>
            </td>
            <td>
                <input type="text" class="form-input" name="tkd" placeholder="Введите номер ТКД" />
            </td>
            <td>
                <input type="text" class="form-input" name="ip" />
            </td>
            <td><input type="text" class="form-input" name="ring" /></td>
            <td><div class="equipment"></div></td>
            <td>
            <p><span style="color: darkblue;"><input type="radio" name="inservice" id="inservice" value="true" checked  /> InService</span>
            <span style="color: darkblue;"><input type="radio" name="inservice" id="inservice" value="false" /> Not in Service</span>
            <span style="color: darkblue;"><input type="radio" name="inservice" id="inservice" value="Plan"  /> Plan</span></p>          
            </td>
        </tr>
    </table>
    
    <br />
    <p><input type="button" name="clear" id="clear" value="Очистить" style="width: 150px;" class="small-button" /></p>
    </div>
    <br />
    <div class="dataTable">
        <form name="sw2Work">
            <p><table class="mytable" id="resultTable">
            </table>
        </form>
    </div>       
    <script>
    if (!window.location.origin) {
  window.location.origin = window.location.protocol + "//" + window.location.hostname + (window.location.port ? ':' + window.location.port: '');
}
    function showDialogWork(idWork){
        typeWork=idWork;
        $('#mostUseProblem').val("").attr("selected","selected");
        $('#srok').val("");
        $('#dialogAddObject').dialog('open');
    }
    
    function showTypeWork(){
        if($('[name=sw2Work]').serialize().length == 0)
            $('.header_left').hide();
        else
            $('.header_left').show();
    }
    
    function add2Work(typeWork){
        var myArr = $('[name=sw2Work]').serialize();
        $('#dialogAddObject').dialog({
            hide: true
        })
        myArr += '&srok='+$('#srok').val()+'&typeWork='+$('#typeWork').val()+'&description='+ encodeURIComponent($('#descProblem').val());
        $.ajax({
            url: 'add2Work',
            data: myArr,
            dataType: 'json',
            type: 'POST'
            
        })
        $('.toWork').attr('checked', false);
        $('.header_left').hide();
        
    }
    
        $(document).ready(function(){
            showTypeWork();
            getRing();
            getMduList();
            getIpList();
            getTkdList();
            getEquipmentList();
            getPermissionFields('switch_list_view');
            $('#myfilter > *').bind('change', function(){
                getDataTable();
                showTypeWork();
                $('.header_left').hide();
            })
            
            $('#srok').datetimepicker({
                format: "d-m-Y H:i",
                mask:true
            })
            //перехват энтера для переноса строк
            $('#descProblem')
                .on('keydown', function(event){
                if(event.keyCode == 13){
                    $(this).val($(this).val()+'<br>');
                }
            })
            $('[name=clear]').click(function(){                
                $('#mduName').val("").attr("selected","selected");
                $('#equipment').val("").attr("selected","selected");
                $('[name=tkd]').val("");
                $('[name=ip]').val("");
                $('[name=ring]').val("");
                
            })
            $('#dialogAddObject').dialog({
                autoOpen: false,
                modal: true,
                buttons: {
                    "Добавить": function(){
                        add2Work(typeWork);
                        $(this).dialog("close");
                    }
                },
                close: function(){
                    $('#descProblem').val('');
                }
            })
            $('#mostUseProblem').click(function(){
                $('#descProblem').val($(this).val());
            })
            $('#find').click(function(){
                getDataTable();
                showTypeWork();
            })
            
            
            $('#cssmenu').mousedown(function(){
                $('#resultTable').empty();
                //убираем тормоза браузера при выводе большого кол-ва позиций
            })
            
            
    })

        function confirmUrl(message, url){
            var answ = confirm(message);
            if (answ)
                window.location.href=url;
        }
        
        function getDataTable(sortTable, sortOrder){
            var cont='';
            var myorder = (sortTable) ? '&column='+sortTable+'&direction='+sortOrder : '';
            $('#ajaxWait').show();
            $.ajax({
                url: 'getDataTable',
                dataType: 'json',
                type: 'GET',
                data: 'find=true&inservice='+$('#inservice:checked').val()+myorder+'&tkd='+$('[name=tkd]').val()+'&mduName='+$('#mduName option:selected').val()+'&ip='+$('[name=ip]').val()+'&ring='+$('[name=ring]').val()+'&equipment='+$('#equipment option:selected').val(),
                success: function(data){
                    $('#resultTable').empty();
                    $('#resultTable').append(function(){
                    cont+='<tr>';
                    cont +='<th>В задачи</th>';
                    cont+='<th>Коммутатор<a href="#" onclick="getDataTable(\'sw.switch\', \'ASC\');" class="sortAsc"></a><a href="#" onclick="getDataTable(\'sw.switch\', \'DESC\');" class="sortDesc"></a></th>';
                    cont+='<th>IP адрес<a href="#" onclick="getDataTable(\'ip\', \'ASC\');" class="sortAsc"></a><a href="#" onclick="getDataTable(\'ip\', \'DESC\');" class="sortDesc"></a></th>';
                    cont+='<th>Объект<a href="#" onclick="getDataTable(\'o.object_name\', \'ASC\');" class="sortAsc"></a><a href="#" onclick="getDataTable(\'o.object_name\', \'DESC\');" class="sortDesc"></a></th>';
                    //cont+='<th>Nbr</th>';
                    cont+='<th></th>';
                    cont+='<th>Улица<a href="#" onclick="getDataTable(\'st.street\', \'ASC\');" class="sortAsc"></a><a href="#" onclick="getDataTable(\'st.street\', \'DESC\');" class="sortDesc"></a></th>';
                    cont+='<th>дом<a href="#" onclick="getDataTable(\'o.house\', \'ASC\');" class="sortAsc"></a><a href="#" onclick="getDataTable(\'o.house\', \'DESC\');" class="sortDesc"></a></th>';
                    cont+='<th>под.<a href="#" onclick="getDataTable(\'o.pod\', \'ASC\');" class="sortAsc"></a><a href="#" onclick="getDataTable(\'o.pod\', \'DESC\');" class="sortDesc"></a></th>';
                    cont+='<th>Ring</th>';
                    cont+='<th>Оборудование<a href="#" onclick="getDataTable(\'e.equipment\', \'ASC\');" class="sortAsc"></a><a href="#" onclick="getDataTable(\'e.equipment\', \'DESC\');" class="sortDesc"></a></th>';
                    cont+='<th>Серийный №</th>';
                    cont+='<th>In Service</th>';
                    cont+='</tr>';
                    for(var i=0;i<data.length;i++){
                        cont+='<tr>';
                            cont +='<td><input class="toWork" type="checkbox" onclick="showTypeWork();" name="sw['+data[i]['id']+']" value="'+data[i]['id']+'"></td>';
                            cont+='<td><a class="switchInfo" href="switchInfo/'+data[i]['id']+'/'+data[i]['switch_name']+'" target="_blank">'+data[i]['switch_name']+'</a></td>';
                            cont+='<td>'+data[i]['ip']+'</td>';
                            cont +='<td><a class="allinfo" href="'+window.location.origin+'/ved/object/object_all_info/'+data[i]['oID']+'/'+data[i]['object_name']+'" target="_blank" >'+data[i]['object_name']+'</a> ('+data[i]['kluch']+')</td>';
                            //cont+='<td>'+data[i]['nbr']+'</td>';
                            cont+='<td>'+data[i]['prefix']+'</td>';
                            cont+='<td>'+data[i]['street']+'</td>';
                            cont+='<td>'+data[i]['house']+'</td>';
                            cont+='<td>'+data[i]['pod']+'</td>';
                            cont+='<td>'+data[i]['ring']+'</td>';
                            cont+='<td>'+data[i]['equipment']+'</td>';
                            cont+='<td>'+data[i]['serial']+'</td>';
                            cont+='<td><a href="#" class="changeStat" onclick="changeStatus('+data[i]['id']+');">'+data[i]['inservice']+'</a></td>';
                            
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
        
        function changeStatus(id){
            $('#dialog-message').remove();
            
            $('.content').append('<div id="dialog-message" title="Изменение статуса оборудования"><p><span class="ui-icon ui-icon-alert" style="float:left; margin:0 7px 20px 0;"></span>Вы действительно хотите изменить статус оборудования?</p></div>');
            $("#dialog-message").dialog({
                resizable: false,
                modal: true,
                width: 350,
                buttons:{
                    "Inservice":function(){
                        $.ajax({
                            url: 'changeStatus',
                            type: 'get',
                            data: "id="+id+"&status=True",
                            success: function(){
                                location.reload(true);
                            }
                        })
                        $(this).dialog("close");  
                        },
                        "Not in service":function(){
                        $.ajax({
                            url: 'changeStatus',
                            type: 'get',
                            data: "id="+id+"&status=False",
                            success: function(){
                                location.reload(true);
                            }
                        })
                        $(this).dialog("close");  
                        },
                        "Plan":function(){
                        $.ajax({
                            url: 'changeStatus',
                            type: 'get',
                            data: "id="+id+"&status=Plan",
                            success: function(){
                                location.reload(true);
                            }
                        })  
                        $(this).dialog("close");
                        }
                    }
                    
            });
        }
        
        function getTkdList(){
            var tkdArray =[];
            $.ajax({
                type: 'GET',
                url: 'getTkdList',
                dataType: 'json',
                data: 'mdu='+(($('#mduName option:selected').val()) ? $('#mduName option:selected').val() : ''),
                success: function(data){
                    for(var i=0; i<data.length; i++){
                        tkdArray.push(data[i]['tkd']);
                    }
                }
            })
            $('[name=tkd]').autocomplete({
                source: function(request, response) {
                    var results = $.ui.autocomplete.filter(tkdArray, request.term);
                    response(results.slice(0, 20));
                }
            })
        }
        
        function getIpList(){
            var ipArray =[];
            $.ajax({
                type: 'GET',
                url: 'getIpList',
                dataType: 'json',
                data: 'mdu='+(($('#mduName option:selected').val()) ? $('#mduName option:selected').val() : ''),
                success: function(data){
                    for(var i=0; i<data.length; i++){
                        ipArray.push(data[i]['ip']);
                    }
                }
            })
            $('[name=ip]').autocomplete({
                source: function(request, response) {
                    var results = $.ui.autocomplete.filter(ipArray, request.term);
                    response(results.slice(0, 20));
                }
            })
        }
        
        function getRing() {
        var ringArray =[];
            $.ajax({
                type: 'GET',
                url: 'getRingList',
                dataType: 'json',
                success: function(data){
                    for(var i=0; i<data.length; i++){
                        ringArray.push(data[i]['ring']);
                    }
                }
            })
            $('[name=ring]').autocomplete({
                source: function(request, response) {
                    var results = $.ui.autocomplete.filter(ringArray, request.term);
                    response(results.slice(0, 20));
                }
            })
        }
        
        function getDependsList(){
            getTkdList();
            getIpList();
        }

        
        function getMduList(){
            var cont ='';
            $('[name="mduName"]').remove();
                $.ajax({
                   type: 'GET',
                   url: 'getMduList',
                   dataType: 'json',
                   success: function(data){
                        $('.mduList').append(function(){
                            if(data){
                                cont +='<p><select class="form-input" name="mduName" id="mduName" size="1" onchange="getDependsList();">';
                                cont +='<option value=""></option>';
                                for(var i=0; i<data.length; i++){
                                    cont +='<option value="'+data[i]['mdu']+'">'+data[i]['mdu']+'</option>';
                                }
                                cont +='</select>';
                                return cont;                               
                            }

                        })

                   }

                })
        }
        function getEquipmentList(){
            var cont ='';
            $('[name="equipment"]').remove();
                $.ajax({
                   type: 'GET',
                   url: 'getEquipmentList',
                   dataType: 'json',
                   success: function(data){
                        $('.equipment').append(function(){
                            if(data){
                                cont +='<p><select class="form-input" name="equipment" id="equipment" size="1">';
                                cont +='<option value=""></option>';
                                for(var i=0; i<data.length; i++){
                                    cont +='<option value="'+data[i]['equipment']+'">'+data[i]['equipment']+'</option>';
                                }
                                cont +='</select>';
                                return cont;                               
                            }

                        })

                   }

                })
        }       
                       

</script>
<div id="dialogAddObject" title="Добавить в работу">
    <table>
        <tr>
            <th>Тип работ</th>
        </tr>
        <tr>
            <td>
                <select id="typeWork">
                    <?php foreach($typeWork as $item):?>
                        <option value="<?=$item['id']?>"><?=$item['tip']?></option>
                    <?php endforeach;?>
                </select>
            </td>
        </tr>
        <tr>
            <th>Описание проблемы</th>
        </tr>
        <tr>
        <td>
            <select id="mostUseProblem"  style="width: 250px;">
                <option selected="" ></option>
                <?php foreach($mostUseProblem as $item):?>
                    <option><?=$item;?></option>
                <?php endforeach;?>
            </select>
        </td>

        </tr>
        <tr>
        <td>
            <textarea style="width: 250px;" wrap="hard" id="descProblem"></textarea>
        </td>
        </tr>
        <tr>
            <th>Срок выполнения задачи</th>
        </tr>
        <tr>
            <td><input style="width: 100px;" type="text" name="srok" id="srok" readonly="" /></td>
        </tr>
    </table>
 </div>
