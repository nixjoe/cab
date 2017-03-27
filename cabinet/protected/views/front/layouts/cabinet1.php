<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="language" content="ru" />

	<!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.jscrollpane.css" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
<?php    $p = (Yii::app()->getParams());
        Yii::app()->clientScript->registerPackage("jquery");
        Yii::app()->clientScript->registerPackage("jquery.ui");
        Yii::app()->clientScript->registerScript('inend',"
            $('#msg-close').click(function(){
                //$('#msg').hide('slow');
                $('#msg').fadeOut(400);
                setCookie('nT', '1');
            });" .
              // Данный код будет работать только если на экран показывается таймер выхода.
              // Если таймер не показывается - код лучше пропустить, иначе возникнут ошибки.
                (empty(Yii::app()->request->cookies['nT'])?
                "
//countdown code
        var javascript_countdown = function () {
	var time_left = 10; //number of seconds for countdown
	var output_element_id = 'javascript_countdown_time';
	var keep_counting = 1;
	var no_time_left_message = '00:00:00';

        function countdown() {
		if(time_left < 2) {
			keep_counting = 0;
		}

		time_left = time_left - 1;
	}
	function add_leading_zero(n) {
		if(n.toString().length < 2) {
			return '0' + n;
		} else {
			return n;
		}
	}
	function format_output() {
		var hours, minutes, seconds;
		seconds = time_left % 60;
		minutes = Math.floor(time_left / 60) % 60;
		hours = Math.floor(time_left / 3600);

		seconds = add_leading_zero( seconds );
		minutes = add_leading_zero( minutes );
		hours = add_leading_zero( hours );

		return hours + ':' + minutes + ':' + seconds;
	}
	function show_time_left() {
		document.getElementById(output_element_id).innerHTML = format_output();//time_left;
	}
	function no_time_left() {
		document.getElementById(output_element_id).innerHTML = no_time_left_message;
	}
	return {
		count: function () {
			countdown();
			show_time_left();
		},
		timer: function () {
			javascript_countdown.count();

			if(keep_counting) {
				setTimeout('javascript_countdown.timer();', 1000);
			} else {
				no_time_left();
			}
		},
		setTimeLeft: function (t) {
			time_left = t;
			if(keep_counting == 0) {
				javascript_countdown.timer();
			}
		},
		init: function (t, element_id) {
			time_left = t;
			output_element_id = element_id;
			javascript_countdown.timer();
		}
	};
}();
javascript_countdown.init({$p->logoutTimer}, 'msg-timer');
":'') . "
function delayedRedirect(){
    window.location = '" . Yii::app()->createUrl("account/logout") . "'
}
setTimeout('delayedRedirect()', {$p->logoutTimer}*1000);

",  CClientScript::POS_END);
        Yii::app()->clientScript->registerScript('ready',"
// Функции для управления кукисами через жс.
function setCookie(key, value) {
   var expires = new Date();
   expires.setTime(expires.getTime() + 31536000000); //1 year
   document.cookie = key + '=' + value + ';expires=' + expires.toUTCString();
   }

function getCookie(key) {
   var keyValue = document.cookie.match('(^|;) ?' + key + '=([^;]*)(;|$)');
   return keyValue ? keyValue[2] : null;
   }
",  CClientScript::POS_BEGIN);

?>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
        <script type="text/javascript">

        </script>
</head>

<body>
    !!!!!!!!!!!!!!!!!
<div id="header">
    <div class="container">
        <div id="header-top">
            <div id="logo" class="left"></div>
            <div class="headerlink right"><?=CHTML::link("Выход",array("site/logout"))?></div>
            <div class="headerlink right"><?=CHTML::link("Личные данные",array("profile"))?></div>
            <div id="userdetails" class="right">Вы вошли как <b><?=Yii::app()->user->name?></b></div>
        </div>
    </div>
</div>
<?php // TODO: придумать Хак, чтобы нормально отображались зубцы. Требует поддержки прозрачных PNG картинок...?>
<div id="header-bottom">
    <div class="container">
        <div id="header-menu">
            <?=CHTML::link("<img src='images/homelink.png'>",array(""),array('class'=>'homelink left'))?>
            	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'itemTemplate'=>'<span>{menu}</span>',
                        'items'=>array(
                            array('label'=>'FAQ', 'url'=>array('account/page', 'view'=>'faq'),'itemOptions'=>array('id'=>'faq')),
                            array('label'=>'Чат', 'url'=>array('/account/'),'itemOptions'=>array('id'=>'chat')),
                            array('label'=>'Мои сообщения', 'url'=>array('/account/'),'itemOptions'=>array('id'=>'messages')),
                            array('label'=>'История платежей', 'url'=>array('/account/paymenthistory'),'itemOptions'=>array('id'=>'history')),
                            array('label'=>'Мои счета', 'url'=>array('/account/index'),'itemOptions'=>array('id'=>'accounts')),
			//	array('label'=>'Открыть счет', 'url'=>array('/account/newaccount'), 'visible'=>!Yii::app()->user->isGuest)
			),
		)); ?>
                </div><!-- mainmenu -->
                    <?=CHTML::link("Открыть счет",array("/account/newaccount"),array('class'=>'roundedbutton right'))?>
        </div>

    </div>
</div>
    <!-- header -->


<div class="container" id="page">
<?php echo $content; ?>
    <?php if (empty(Yii::app()->request->cookies['nT'])) {
                ?>
        <div id="msg">
            <div id="msg-rel">
                <div id="msg-close"></div>
                <div id="msg-text">Для повышения безопасности сессия закончится через</div>
                <div id="msg-timer">00:05:00</div>
            </div>
        </div>
    <?php } ?>
</div><!-- page -->
    <div id="footer-out">
	<div id="footer">
            <div class="left" id="footercopy">
		&copy; 2011-<?php echo date('Y'); ?>, All Rigts Reserved.<br/><br/>
                <img src="images/footer-logo.png" alt="FX-Private"></img>
                <br/>
            </div>
            <div class="left" id="footerlinks">
                 <?=CHtml::link('Инструкция пользователя','files/Инструкция пользователя ЛК.pdf')?>
				<a href="http://www.fx-private.ru/fortraders/safety.html">Советы по безопасности</a>
                <a href="http://www.fx-private.com/files/fxprivate4setup.exe">Скачать МТ4</a><br>
                <?=CHtml::link('Перейти на сайт компании','http://www.fx-private.com')?>
				<?=CHtml::link('Инструкция пользователя ЛК','files/Инструкция пользователя ЛК.doc')?>
               
            </div>
	</div>
    </div>


</body>
</html>