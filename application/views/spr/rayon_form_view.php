
<form action="" method="POST">
    <p><label for="role">Введите название района</label></p>
    <p><input type="text" class="form-input" name="rayon" value="<?php echo (isset($tblarray[0]['rayon'])) ? $tblarray[0]['rayon'] : "";?>" id="rayon" placeholder="район" /></p>
    <?php
        if(isset($id))
            echo "<p><input type='hidden' name='id' value='{$id}'\n";
    ?>
    <p><input type="submit" name="save" value="Сохранить" class="small-button" /></p>
</form>
