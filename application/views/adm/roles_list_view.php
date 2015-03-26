
    <p><input type="button" id="addRole" class="small-button" style="margin:10px 0;" value="Добавить роль" /></p>
    <p><table class="mytable" cellspacing="0" align="left">
    <tr>
    <th>Роль</th>
    <th>Операции</th>
    </tr>
    <?php
if (is_array($tblarray)) {
    foreach ($tblarray as $val) {
        echo "<tr>";
        echo "<td>" . $val['role'] . "</td>\n";
        echo "<td><a onclick=\"confirmUrl('Вы уверены', 'delete/'+{$val['id']});\" href=\"#\">Удалить</a> || <a href=\"edit/{$val['id']}\">Изменить</a></td>\n";
        echo "</tr>\n";
    }
}
?>
    </table></p>
    <script>
    $('#addRole').click(function(){
        var newRole = prompt("Введите название роли");
        if(newRole.length>1){
            $.ajax({
                url: "add",
                data: 'newRole='+newRole,
                type: "GET",
                success: function(){
                    window.location.href=window.location.origin+"/adm/roles/showRoles";
                }
            })
        }
    })
        function confirmUrl(message, url){
            var answ = confirm(message);
            if (answ)
                window.location.href=url;
        }
</script>
