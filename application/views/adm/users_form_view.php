
<?php
    if(isset($action))
        $post = base_url() . "adm/users/" . $action;
    else
        $post="";
?>
<form action="<?php echo $post;?>" method="POST">
    <p><label for="fio">Введите Ф.И.О</label></p>
    <p><input type="text" class="form-input" name="fio" value="<?php echo (isset($tblarray[0]['fio'])) ? $tblarray[0]['fio'] : "";?>" id="fio" placeholder="Введите Ф.И.О" /></p>
    <p><label for="login">Введите логин</label></p>
    <p><input type="text" class="form-input" name="login" value="<?php echo (isset($tblarray[0]['login'])) ? $tblarray[0]['login'] : "";?>" id="login" placeholder="Введите логин" /></p>
    <p><label for="password">Введите пароль</label></p>
    <p><input type="password" class="form-input" name="password" value="" id="password" placeholder="" /></p>
    <p><label for="role_id">Права</label></p>
    <?php
        if(isset($id))
            echo "<p><input type='hidden' name='id' value='{$id}'</p>\n";
    ?>
    <?php
        echo "<select size='1' name='roles' class='form-input'>";
            foreach ($role as $key => $val) {
                    $sel = '';
                    if($val == $tblarray[0]['role'])
                        $sel = "selected";
                    echo "<option $sel value = \"{$key}\">{$val}</option>";
            }
        echo "</select>";

    ?>
    <p><input type="submit" name="save" value="Сохранить" class="small-button" /></p>
</form>
