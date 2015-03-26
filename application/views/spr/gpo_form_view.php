
<form action="" method="POST">
    <p><label for="gpo">Введите название организации</label></p>
    <p><input type="text" class="form-input" name="gpo" value="<?php echo (isset($tblarray[0]['gpo'])) ? $tblarray[0]['gpo'] : "";?>" id="gpo" placeholder="Организация" /></p>
    <?php
        if(isset($id))
            echo "<p><input type='hidden' name='id' value='{$id}'\n";
    ?>
    <p><input type="submit" name="save" value="Сохранить" class="small-button" /></p>
</form>
