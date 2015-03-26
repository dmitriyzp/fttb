<script>
$(document).ready(function(){
    $('#datebirth').datepicker({
        yearRange:'1950:2014',
        dateFormat: "yy-mm-dd",
        changeMonth: true,
        changeYear: true,
        inline: true,
        monthNamesShort: [ "Янв", "Фев", "Мар", "Апр", "Май", "Июн", "Июл", "Авг", "Сен", "Окт", "Ноя", "Дек" ]
    })
})
</script>
<form action="" method="POST">
    <p><label for="familiya">Введите фамилию сотрудника</label></p>
    <p><input type="text" class="form-input" name="familiya" value="<?php echo (isset($tblarray[0]['familiya'])) ? $tblarray[0]['familiya'] : "";?>" id="familiya" placeholder="Фамилия" /></p>
    <p><label for="name">Введите имя сотрудника</label></p>
    <p><input type="text" class="form-input" name="name" value="<?php echo (isset($tblarray[0]['name'])) ? $tblarray[0]['name'] : "";?>" id="name" placeholder="Имя" /></p>
    <p><label for="otchestvo">Введите отчество сотрудника</label></p>
    <p><input type="text" class="form-input" name="otchestvo" value="<?php echo (isset($tblarray[0]['otchestvo'])) ? $tblarray[0]['otchestvo'] : "";?>" id="otchestvo" placeholder="Отчество" /></p>
    <p><label for="email">Введите email сотрудника</label></p>
    <p><input type="text" class="form-input" name="email" value="<?php echo (isset($tblarray[0]['email'])) ? $tblarray[0]['email'] : "";?>" id="email" placeholder="E-mail" /></p>
    <p><label for="phone">Введите телефон сотрудника</label></p>
    <p><input type="text" class="form-input" name="phone" value="<?php echo (isset($tblarray[0]['phone'])) ? $tblarray[0]['phone'] : "";?>" id="phone" placeholder="Телефон" /></p>
    <p><label for="passport">Паспортные данные</label></p>
    <p><input type="text" class="form-input" name="passport" value="<?php echo (isset($tblarray[0]['passport'])) ? $tblarray[0]['passport'] : "";?>" id="passport" placeholder="серия, номер, кем выдан" /></p>
    <p><label for="datebirth">Укажите дату рождения</label></p>
    <p><input type="text" class="form-input" name="datebirth" value="<?php echo (isset($tblarray[0]['datebirth'])) ? $tblarray[0]['datebirth'] : "";?>" id="datebirth" placeholder="дд-мм-гггг" /></p>
    <?php
    //familiya, name, otchestvo, email, phone,
        if(isset($id))
            echo "<p><input type='hidden' name='id' value='{$id}'\n";
    ?>
    <p><input type="submit" name="save" value="Сохранить" class="small-button" /></p>
</form>
