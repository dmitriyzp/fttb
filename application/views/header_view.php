<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<title><?=((isset($title)) ? $title : 'FTTB');?></title>
	<link href="/style/css/style.css" rel="stylesheet">
    <link rel="stylesheet" href="/style/css/jquery-ui.css">
    <link rel="stylesheet" href="/style/css/jquery.datetimepicker.css">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="http://code.jquery.com/ui/1.11.1/jquery-ui.min.js"></script>
    <script src="/style/js/functions.js"></script>
    <script src="/style/js/jquery.datetimepicker.js"></script>
</head>

<body>

<div class="wrapper">

<header class="header" >
<div class="header_left" hidden="">
    <input type="button" class="btnGhost" value="В работу" onclick="showDialogWork();" /><br />
</div>

<div id="centerMenu">
<ul class="menu">
    <?php if($this->session->userdata('role') == 'Администратор'):?>
    <li><a href="#">Администрирование</a>
        <ul>
            <li><a href="/adm/users/showUsers">Учетные записи</a></li>
            <li><a href="/adm/roles/showRoles">Роли</a></li>
            <li><a href="#">Справочники</a>
            <ul>
                <li><a href="/spr/mdu/showMdu">MDU</a></li>
                <li><a href="/spr/gpo/showGpo">gpo</a></li>
                <li><a href="/spr/personal/showPersonal">Персонал</a></li>
                <li><a href="/spr/sklad/showSklad">Склад</a></li>
                <li><a href="/spr/town/showTown">Города</a></li>
                <li><a href="/spr/rayon/showRayon">Районы</a></li>
                <li><a href="/spr/street/showStreet">Улицы</a></li>
            </ul>
        </li>
        </ul>
    </li>

    <li><a href="/pars/parser/showParserForm">Парсер свитчей</a></li>
    <?php endif;?>
    <li><a href="/ved/object/showObject">Объекты</a></li>
    <li><a href="/ved/switches/showSwitches" ><span>Коммутаторы</a></li>
    <li><a href="/ved/zamok/showZamok" >Замки</a></li>
    <li><a href="/ved/works/showWorks" >Задачи</a></li>
    <li><a href="/ved/kabinet/showKabinet" >Личный кабинет</a></li>
    <li><a href="/ved/history/showHistory" >История</a></li>
    <li><a href="#" >B2B</a></li>
    <li><a href="/administrator/logout">Выход</a></li>
</ul>
</div>
<div class="header_right">
    <strong>Ваш логин - <?=$this->session->userdata('user'); ?></strong><br />
    <strong>Роль - <?=$this->session->userdata('role'); ?></strong><br />
</div>
</header><!-- .header-->
<div class="middle">
<div class="container">
<main class="content">