
    <p><a href="add" class="small-button" style="margin:10px 0;">Добавить метод</a></p>
    <p><table class="mytable" cellspacing="0" align="left">
    <tr>
    <th>Название метода</th>
    <th>Описание метода</th>
    <th>Операции</th>
    </tr>
    <?php
        if(is_array($tblarray)){ 
            foreach($tblarray as $val){
                echo "<tr>";
                echo "<td>" . $val['pathMethod'] . "</td>\n";
                echo "<td>" . $val['description'] . "</td>\n";
                echo "<td><a onclick=\"confirmUrl('Вы уверены', 'delete/'+{$val['id']});\" href=\"#\">Удалить</a> || <a href=\"edit/{$val['id']}\">Изменить</a></td>\n";
                echo "</tr>\n";
            }
        }
    ?>
    </table></p>
    <script>
        function confirmUrl(message, url){
            var answ = confirm(message);
            if (answ)
                window.location.href=url;
        }
</script>
