
    <p><a href="add" class="small-button" style="margin:10px 0;">Добавить сотрудника</a></p>
    <p><table class="mytable" cellspacing="0" align="left">
    <tr>
    <th>Фамилия</th>
    <th>Имя</th>
    <th>Отчество</th>
    <th>E-Mail</th>
    <th>Телефон</th>
    <th>Паспорт</th>
    <th>Дата рождения</th>
    <th>Операции</th>
    </tr>
    <?php
        if(is_array($tblarray)){ 
            //familiya, name, otchestvo, email, phone,
            foreach($tblarray as $val){
                echo "<tr>";
                echo "<td>" . $val['familiya'] . "</td>\n";
                echo "<td>" . $val['name'] . "</td>\n";
                echo "<td>" . $val['otchestvo'] . "</td>\n";
                echo "<td>" . $val['email'] . "</td>\n";
                echo "<td>" . $val['phone'] . "</td>\n";
                echo "<td>" . $val['passport'] . "</td>\n";
                echo "<td>" . $val['datebirth'] . "</td>\n";
                echo "<td style=\"width: 150px;\"><a onclick=\"confirmUrl('Вы уверены', 'delete/'+{$val['id']});\" href=\"#\">Удалить</a> || <a href=\"edit/{$val['id']}\">Изменить</a></td>\n";
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

