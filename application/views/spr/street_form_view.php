
<form action="" method="POST">
    <p><label for="street">Введите название улицы</label></p>
    <p>
    <?php
        echo "<select size='1' name='prefix' class='form-input' style=\"width: 60px; padding-left:1px; padding-right:1px;\">";
            foreach ($prefix as $val) {
                    $checked = ($tblarray[0]['prefix'] == $val) ? "selected" : "";
                    echo "<option value = \"{$val}\" {$checked}>{$val}</option>";
            }
        echo "</select>";

    ?>
    <input type="text" class="form-input" name="street" value="<?php echo (isset($tblarray[0]['street'])) ? $tblarray[0]['street'] : "";?>" id="street" placeholder="улица" /></p>
    <p><label for="town">Город</label></p>
    <?php
        if(isset($id))
            echo "<p><input type='hidden' name='id' value='{$id}'</p>\n";
    ?>
    <?php
        echo "<select size='1' name='town' class='form-input'>";
            foreach ($town as $key => $val) {
                    $chk = ($tblarray[0]['town'] == $val) ? "selected" : "";
                    echo "<option value = \"{$key}\" $chk>{$val}</option>";
            }
        echo "</select>";
    ?>
    <p><input type="submit" name="save" value="Сохранить" class="small-button" /></p>
</form>
