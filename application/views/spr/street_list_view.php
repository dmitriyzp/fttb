
    <p><a href="add" class="small-button" style="margin:10px 0;">Добавить улицу</a></p>
    <p><table class="mytable" cellspacing="0" align="left">
    <tr>
    <th>Город</th>
    <th>Улица</th>
    <th>Операции</th>
    </tr>
    <tr>
    <form action="" method="GET">
    <?php
        echo "<td><select size='1' name='town' class='form-input form-input-small'>";
        echo "<option value = \"\"></option>";
            foreach ($town as $key => $val) {
                    $defvalue = (isset($_GET['town']) && $_GET['town'] == $val) ? "selected" : "";
                    echo "<option {$defvalue} value = \"{$val}\">{$val}</option>";
            }
        echo "</select></td>";

    ?>
    <td><input type="text" name="street" placeholder="Поиск по улице" class="form-input form-input-small" /></td>
    <td><input type="submit" name="filter" value="Фильтр" class="small-button"/></td>
    </form>
    </tr>
    <?php
        if(is_array($tblarray)){ 
            foreach($tblarray as $val){
                echo "<tr>";
                echo "<td>" . $val['town'] . "</td>\n";
                echo "<td style=\"text-align: left;\">" . $val['street'] . "</td>\n";
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
