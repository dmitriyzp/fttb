
<form action="" method="POST">
    <p><label for="role">Введите название MDU</label></p>
    <p><input type="text" class="form-input" name="mdu" value="<?php echo (isset($tblarray[0]['mdu'])) ? $tblarray[0]['mdu'] : "";?>" id="mdu" placeholder="MDU" /></p>
    <p>
        <select name="rayon" size="1">
            <option value="0"></option>
            <?php foreach($listRayon as $item):?>
                <option value="<?=$item['id']?>"><?=$item['rayon']?></option>
            <?php endforeach;?>
        </select>
    </p>
    <?php
        if(isset($id))
            echo "<p><input type='hidden' name='id' value='{$id}'\n";
    ?>
    <p><input type="submit" name="save" value="Сохранить" class="small-button" /></p>
</form>
