
<form action="" method="POST">
    <p><label for="town">Введите название города</label></p>
    <p><input type="text" class="form-input" name="town" value="<?php echo (isset($tblarray[0]['town'])) ? $tblarray[0]['town'] : "";?>" id="town" placeholder="Город" /></p>
    <?php
        if(isset($id))
            echo "<p><input type='hidden' name='id' value='{$id}'\n";
    ?>
    <p><input type="submit" name="save" value="Сохранить" class="small-button" /></p>
</form>
