<div id="ajaxWait">
    <div id="ajaxAnim"></div>
</div>
    <div class="filter" id="myfilter">
    <table class="mytable">
        <tr>
            <th>MDU</th>
            <th>Номер ТКД</th>
            <th>Адрес</th>
            <th>Тип объекта</th>
        </tr>
        <tr>
            
            <td>
                <input type="hidden" name="mduName" id="mduName" value="" />
                <div class="mduList"></div>
            </td>
            <td>
                <input type="text" class="form-input" name="tkd" placeholder="Введите номер ТКД" />
            </td>
            <td>
                <input type="text" class="form-input" id="strid" name="street" style="width: 220px;" placeholder="Название улицы" /><input type="text" class="form-input" name="house" style="width: 40px; padding-left: 2px;" placeholder="Дом" />
            </td>
            <td>
                <p><span style="color: darkblue;"><input type="radio" name="typeObject" id="typeObject" value="MDU" checked /> MDU</span>
                <span style="color: darkblue;"><input type="radio" name="typeObject" id="typeObject" value="AGG" /> AGG</span>
                <span style="color: darkblue;"><input type="radio" name="typeObject" id="typeObject" value="B2B"  /> B2B</span></p>
            </td>
        </tr>
    </table>

    <p><input type="button" name="clear" value="Очистить" style="width: 150px;" class="small-button button-center" /></p>
    
    </div>
    <br />
    <div class="dataTable">
        <form name="obj2Work">
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
        if($('[name=obj2Work]').serialize().length == 0)
            $('.header_left').hide();
        else
            $('.header_left').show();
    }
    
    function add2Work(typeWork){
        var myArr = $('[name=obj2Work]').serialize();
        $('#dialogAddObject').dialog({
            hide: true
        })
        $('#ajaxWait').show();
        myArr += '&srok='+$('#srok').val()+'&typeWork='+$('#typeWork').val()+'&description='+ encodeURIComponent($('#descProblem').val());
        $.ajax({
            url: 'add2Work',
            data: myArr,
            dataType: 'json',
            type: 'POST'
            
        })
        $('.towork').attr('checked', false);
        $('.header_left').hide();
        $('#ajaxWait').hide();
        
    }
        $(document).ready(function(){

            
            showTypeWork();
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
            $('#myfilter > *').bind('change', function(){
                getDataTable();
                showTypeWork();
                $('.header_left').hide();
            })
            
            $('#myfilter > *')
                .on('keydown', function(event){
                if(event.keyCode == 13){
                    getDataTable();
                    showTypeWork();
                    $('.header_left').hide();
                }
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
            $('#ajaxWait').show();
            getParam();
            getStreetList();
            getTkdList();
            $('#ajaxWait').hide();
            $('#cssmenu').mousedown(function(){
                $('#resultTable').empty();
                //убираем тормоза браузера при выводе большого кол-ва позиций
            })
            $('[name=clear]').click(function(){                
                $('#mduName').val("").attr("selected","selected");
                $('[name=street]').val("");
                $('[name=tkd]').val("");
                $('[name=house]').val("");
                
            })
            $('[name="typeObject"]').click(function(){
                getTkdList();
                getParam();
                $('[name=tkd]').val('');
            })
            getPermissionFields('object_list_view');
            
            
        })
        
        
        function getDataTable (sortTable, sortOrder){
            var cont='';
            var myorder = (sortTable) ? '&column='+sortTable+'&direction='+sortOrder : '';
            $('#ajaxWait').show();
            $.ajax({
                url: 'getDataTable',
                type: 'GET',
                dataType: 'json',
                data: 'find=true&typeObject='+$('#typeObject:checked').val()+myorder+'&mduName='+(($('#mduName :selected').val()==undefined) ? '' : $('#mduName :selected').val())+'&tkd='+$('[name=tkd]').val()+'&street='+encodeURI($('[name=street]').val())+'&house='+encodeURI($('[name=house]').val()),
                success: function(data){
                    $('#resultTable').empty();
                    $('#resultTable').append(function(){
                        cont +='<tr>';
                        cont +='<th>В задачи</th>';
                        cont +='<th>Объект <a href="#" onclick="getDataTable(\'o.object_name\', \'ASC\');" class="sortAsc"></a><a href="#" onclick="getDataTable(\'o.object_name\', \'DESC\');" class="sortDesc"></a></th>';
                        cont +='<th></th>';
                        cont +='<th>Улица<a href="#" onclick="getDataTable(\'s.street\', \'ASC\');" class="sortAsc"></a><a href="#" onclick="getDataTable(\'s.street\', \'DESC\');" class="sortDesc"></a></th>';
                        cont +='<th>дом<a href="#" onclick="getDataTable(\'o.house\', \'ASC\');" class="sortAsc"></a><a href="#" onclick="getDataTable(\'o.house\', \'DESC\');" class="sortDesc"></a></th>';
                        cont +='<th>под.<a href="#" onclick="getDataTable(\'o.pod\', \'ASC\');" class="sortAsc"></a><a href="#" onclick="getDataTable(\'o.pod\', \'DESC\');" class="sortDesc"></a></th>';
                        cont +='</tr>';
                        for(var i=0;i<data.length;i++){
                            cont +='<tr>';
                                cont +='<td><input type="checkbox" class="towork" onclick="showTypeWork();" name="obj['+data[i]['id']+']" value="'+data[i]['id']+'"></td>';
                                cont +='<td><a class="allinfo" href="object_all_info/'+data[i]['id']+'/'+data[i]['object_name']+'" target="_blank" >'+data[i]['object_name']+'</a> ('+data[i]['kluch']+')</td>';
                                cont +='<td>'+data[i]['prefix']+'</td>';
                                cont +='<td>'+data[i]['street']+'</td>';
                                cont +='<td>'+data[i]['house']+'</td>';
                                cont +='<td>'+data[i]['pod']+'</td>';
                            cont +='</tr>';
                        }
                        return cont;

                    })
                    
                },
                complete: function(){
                    getPermissionFields('object_list_view');
                    $('#ajaxWait').hide();
                }
            })
        }
        function confirmUrl(message, url){
            var answ = confirm(message);
            if (answ)
                window.location.href=url;
        }
        
        function getTkdList(){
            var tkdArray =[];
            $('#mduName').val($('#mduName option:selected').val());
            $.ajax({
                type: 'GET',
                url: 'getTkdList',
                dataType: 'json',
                data: 'mdu='+(($('#mduName option:selected').val()) ? $('#mduName option:selected').val() : $('[name="typeObject"]:checked').val()),
                success: function(data){
                    for(var i=0; i<data.length; i++){
                        tkdArray.push(data[i]);
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
        
        function getStreetList(){
            var streetArray=[];
                $.ajax({
                    type: 'GET',
                    url: 'getStreetList',
                    dataType: 'json',
                    success: function(data){
                        for(var i=0; i<data.length; i++){
                            streetArray.push(data[i]['street']);
                        }
                        
                    }
                })
                $('[name="street"]').autocomplete({
                    source: function(request, response) {
                        var results = $.ui.autocomplete.filter(streetArray, request.term);
                        response(results.slice(0, 20));
                    }
                });
        }
        
        function getParam(){
            var curVal = $('[name="typeObject"]:checked').val();
            var cont ='';
            $('[name="mduName"]').remove();
                $.ajax({
                   type: 'GET',
                   url: 'getParams',
                   data: 'objectType='+curVal,
                   dataType: 'json',
                   success: function(data){
                        $('.mduList').append(function(){
                            if(data){
                                cont +='<p><select class="form-input" name="mduName" id="mduName" size="1" onchange="getTkdList();">';
                                cont +='<option value=""></option>';
                                for(var i=0; i<data.length; i++){
                                    cont +='<option value="'+data[i]+'">'+data[i]+'</option>';
                                }
                                cont +='</select>';
                                return cont;                               
                            }

                        })

                   },
                   complete: function(){
                        getPermissionFields('object_list_view');
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