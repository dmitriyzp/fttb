<form id="myfilter">
<table class="mytable">
    <tr>
        <th colspan="3">Статус</th>
        <th>Тип работы</th>
        <th>Оператор</th>
        <th>Дата</th>
        <th>Описание</th>
        <th>Объект</th>
        <th>Комментарий</th>
        <th>Сотрудник</th>
    </tr>
    <tr>
        <td><input type="checkbox" name="status[new]" value="1" checked=""/>Новая</td>
        <td><input type="checkbox" name="status[inwork]" value="3" checked=""/>В работе</td>
        <td><input type="checkbox" name="status[closed]" value="4" />Закрытая</td>
        <td>
            <select id="typeWork" name="typeWork">
            <option></option>
            <?php foreach($typeWork as $item):?>
                <option value="<?=$item['id']?>"><?=$item['tip']?></option>
            <?php endforeach;?>
            </select>
        </td>
        <td>
            <select id="operator" name="operator">
            <option></option>
            <?php foreach($operator as $item):?>
                <option value="<?=$item['id']?>"><?=$item['fio']?></option>
            <?php endforeach;?>
            </select>
        </td>
            <td><input style="width: 100px;" type="text" name="srok" id="srok"/></td>
            <td><input type="text" name="opisanie" placeholder="описание проблемы" /></td> 
        <td>
            <input type="text" class="form-input" name="tkd" placeholder="Введите номер ТКД" />
        </td>
        <td><input type="text" name="primechanie" placeholder="Примечание" /></td> 
        <td>
            <select name="personal[]" multiple="true" size="10" style="width: 150px; overflow: auto;">
            <option value=""></option>
            <?php foreach($personal as $fam): ?>
                <option value="<?=$fam['id'];?>"><?=$fam['familiya'] . ' ' . $fam['name'];?></option>
            <?php endforeach;?>
            </select>
        </td>
    </tr>
</table>
<input type="button" class="small-button" onclick="getDataTable();" value="Поиск" />
</form>
<div id="result">
    <table id="resultTable" class="mytable">
    </table>
</div>
<script>
$(document).ready(function(){
    getTkdList();
    $('#srok').datepicker({
                dateFormat: "yy-mm-dd",
                changeMonth: true,
                changeYear: true,
                inline: true,
                monthNamesShort: [ "Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек" ]
    })
})
        function getTkdList(){
            var tkdArray =[];
            $.ajax({
                type: 'GET',
                url: 'getTkdList',
                dataType: 'json',
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
        
        function getDataTable(){
            var cont='';
            var myfilter = $('#myfilter').serialize();
            $.ajax({
                url: 'getDataTable',
                type: 'GET',
                dataType: 'json',
                data: myfilter,
                success: function(data){
                    $('#resultTable').empty();
                    $('#resultTable').append(function(){
                        cont +='<tr>';
                        cont +='<th>Статус</th>';
                        cont +='<th>Тип работы</th>';
                        cont +='<th>Оператор</th>';
                        cont +='<th>Дата</th>';
                        cont +='<th>Описание</th>';
                        cont +='<th>Объект</th>';
                        cont +='<th>Комментарий</th>';
                        cont +='<th>Сотрудник</th>';
                        cont +='</tr>';
                        for(var i=0;i<data.length;i++){
                            cont +='<tr>';
                                cont +='<td>'+data[i]['deystvie']+'</td>';
                                cont +='<td>'+data[i]['tip']+'</td>';
                                cont +='<td>'+data[i]['fio']+'</td>';
                                cont +='<td>'+data[i]['data_deystviya']+'</td>';
                                cont +='<td>'+data[i]['opisanie']+'</td>';
                                cont +='<td>'+data[i]['objects']+'</td>';
                                cont +='<td>'+data[i]['primechanie']+'</td>';
                                cont +='<td>'+data[i]['personal']+'</td>';
                            cont +='</tr>';
                        }
                        return cont;

                    })
                    
                }
            })
        }
</script>