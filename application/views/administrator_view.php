<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8" />
	<!--[if lt IE 9]><script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script><![endif]-->
	<title>Кабинет</title>
	<link href="/style/css/style.css" rel="stylesheet">
    <script src="http://code.jquery.com/jquery-latest.min.js"></script>
    <script src="http://code.jquery.com/ui/1.9.2/jquery-ui.js"></script>
    <script src="/style/js/functions.js"></script>
</head>

<body>

<div class="wrapper">

<header class="header">
		<strong>Ваш логин - <?=$this->session->userdata('user'); ?></strong> 

</header><!-- .header-->
<div class="middle">
        <div class="container">
			<main class="content">
                <?php
                    echo "<h1>Добро пожаловать на сайт</h1> " ;
                ?>
			</main><!-- .content -->
		</div><!-- .container-->

		<aside class="left-sidebar">
            <div id='cssmenu'>
            <ul>
                <li class="active has-sub"><a href="#"><span>Администрирование</span></a>
                    <ul>
                        <li><a href="/administrator/showUsers"><span>Учетные записи</span></a></li>
                        <li><a href="/administrator/showRoles"><span>Роли</span></a></li>
                        <li class="last"><a href="/administrator/showMethods"><span>Список методов</span></a></li>
                    </ul>
                </li>
                <li class="has-sub"><a href="#"><span>Справочники</span></a></li>
                <li class="has-sub"><a href="#"><span>Ведомости</span></a></li>
                <li class="last"><a href="logout"><span>Выход</span></a></li>
            </ul>
            </div>
		</aside><!-- .left-sidebar -->

	</div><!-- .middle-->

</div><!-- .wrapper -->

<footer class="footer">
	<strong>Все пиратские права защищены</strong>
</footer><!-- .footer -->
<script>
function confirmUrl(message, url){
    var answ = confirm(message);
    if (answ)
        window.location.href=url;
}
</script>
<script>
$(function() {
	$('.container').append('<div class="empty"></div>'); // я вынес из разметки слой и сделал его добавление через js, чтобы в случае отключенных в браузере скриптов слой footer не наползал на контентную часть
	var footerHeight = $('.footer').height(); // узнаем высоту слоя footer
	var contHeight = $(document).height()-100; // узнаем высоту основного слоя; 100% минус высота шапки, минус высота отступа снизу макета. Т.е. 100%-(135px+10px+10px)
	$('.left-sidebar').css({'height':contHeight}); // прописываем полученную высоту основного слоя
	$('.empty').css({'height':footerHeight}); // прописываем высоту распорки empty
	$('.footer').css({'margin-top':-footerHeight}); // сдвигаем слой footer наверх на число равной его высоте и соответственно высоте распорки empty

})

</script>
</body>
</html>