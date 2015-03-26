
    <p><a href="add" class="small-button" style="margin:10px 0;">Добавить пользователя</a></p>
    <p><table class="mytable" cellspacing="0" align="left">
    <tr>
    <th>Ф.И.О</th>
    <th>Логин</th>
    <th>Права</th>
    <th>Операции</th>
    </tr>
    <tr>
    <td></td>
    <td></td>
    <form action="" method="GET">
    <?php
        echo "<td><select size='1' name='roles' class='form-input form-input-small'>";
        echo "<option value = \"\"></option>";
            foreach ($role as $key => $val) {
                    $defvalue = (isset($_GET['roles']) && $_GET['roles'] == $val) ? "selected" : "";
                    echo "<option {$defvalue} value = \"{$val}\">{$val}</option>";
            }
        echo "</select></td>";

    ?>
    <td><input type="submit" name="filter" value="Фильтр" class="small-button"/></td>
    </form>
    </tr>
    <?php
        if(is_array($tblarray)){ 
            foreach($tblarray as $val){
                echo "<tr>";
                echo "<td>" . $val['fio'] . "</td>\n";
                echo "<td>" . $val['login'] . "</td>\n";
                echo "<td>" . $val['role'] . "</td>\n";
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
