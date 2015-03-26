
<form action="" method="POST">
    <p><label for="sklad">Введите название склада</label></p>
    <p><input type="text" class="form-input" name="sklad" value="<?php echo (isset($tblarray[0]['sklad'])) ? $tblarray[0]['sklad'] : "";?>" id="sklad" placeholder="Название склада" /></p>
    <?php
        if(isset($id))
            echo "<p><input type='hidden' name='id' value='{$id}'\n";
    ?>
    <p><input type="submit" name="save" value="Сохранить" class="small-button" /></p>
</form>
