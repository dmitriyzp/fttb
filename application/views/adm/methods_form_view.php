
<form action="" method="POST">
    <p><label for="role">Введите название метода</label></p>
    <p><input type="text" class="form-input" name="pathMethod" value="<?php echo (isset($tblarray[0]['pathMethod'])) ? $tblarray[0]['pathMethod'] : "";?>" id="pathMethod" placeholder="название метода" /></p>
    <p><label for="role">Введите краткое описание метода</label></p>
    <p><input type="text" class="form-input" name="description" value="<?php echo (isset($tblarray[0]['description'])) ? $tblarray[0]['description'] : "";?>" id="description" placeholder="описание метода" /></p>
    <?php
        if(isset($id))
            echo "<p><input type='hidden' name='id' value='{$id}'\n";
    ?>
    <p><input type="submit" name="save" value="Сохранить" class="small-button" /></p>
</form>
