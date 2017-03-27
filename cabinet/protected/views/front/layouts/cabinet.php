<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="en" lang="en">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="language" content="<?= $_GET['language'] ?>" />

	<!-- google analitic -->
<script type="text/javascript">

  var _gaq = _gaq || [];
  _gaq.push(['_setAccount', 'UA-37200867-1']);
  _gaq.push(['_trackPageview']);

  (function() {
    var ga = document.createElement('script'); ga.type = 'text/javascript'; ga.async = true;
    ga.src = ('https:' == document.location.protocol ? 'https://ssl' : 'http://www') + '.google-analytics.com/ga.js';
    var s = document.getElementsByTagName('script')[0]; s.parentNode.insertBefore(ga, s);
  })();

</script>	
<!-- google analitic(end) -->
	<!-- blueprint CSS framework -->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
			<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.jscrollpane.css" />
        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico">
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
	
	<?php if($_GET['language'] == 'ar') : ?>
	<style type="text/css">
		p, h1, h2, h3, h4, h5, h6, table, table tr, table td, ul, ol, ul li, ol li, span, a {
		    direction: rtl !important;
		}
		div.flash {
			text-align: right !important;			
		}		
	</style>
	<?php endif; ?>	
	
<?php    $p = (Yii::app()->getParams());

        Yii::app()->clientScript->registerPackage("jquery");
        Yii::app()->clientScript->registerPackage("jquery.ui");
        Yii::app()->clientScript->registerScript('headflash',"
            $('div.flash .close').live('click', function() { $(this).parent().fadeOut(200)});
            ",  CClientScript::POS_HEAD);        
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
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.jscrollpane.min.js", CClientScript::POS_END);
Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/styledropdowns.js", CClientScript::POS_END);
?>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/main.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/footer.css" media="screen, projection" />

	<title><?php echo CHtml::encode($this->pageTitle); ?></title>

</head>

<body>
<div id="newbody">
<div id="header">
    <div class="container">
        <div id="header-top">
            <?/*=CHtml::link('<div id="logo" class="left">&beta;eta</div>',array('/'))*/?>
			<?=CHtml::link('<div id="logo" class="left"></div>',array('/'))?>
            <div class="headerlink right"><?=CHTML::link(Yii::t('main', 'Выход'),array("/account/logout"))?></div>
            <div class="headerlink right"><?=CHTML::link(Yii::t('main', "Личные данные"),array("/profile/index"))?></div>
            <div id="userdetails" class="right"><?= Yii::t('main', "Вы вошли как") ?> <b><?=Yii::app()->user->name?></b></div>
           <div id="select_language" class="right">
	    <?
			$mLanguages = new Languages();
			$langs = $mLanguages->findAll(array('condition' => "`active` = '1'", 'order'=>'`sort` ASC'));
			
			foreach($langs as $lng_item) {
				if($lng_item->iso == $_GET['language']) {
					$lang = $lng_item->title;
				} 
			} 	    
			if(!isset($lang)) $lang = 'Русский';
	?> 
	   <div class="rel-container select-container langs">
            <div class="valuebox"><p><?=$lang?></p>
            </div>
            <div class="radiobox">
                <div class="optcontainer">
          <? foreach($langs as $lng_item) :?>
			<input id="languages" type="hidden" value="ru" name="language" />
			<span id="MsgMessages_assignee">
				<input class="" class="langsel" id="language<?= $lng_item->iso ?>" value="<?= $lng_item->iso ?>" type="radio" name="language<?= $lng_item->iso ?>" /> 
				<label for="language<?= $lng_item->iso ?>"><?= $lng_item->title ?></label>
			</span>
			<? endforeach; ?>				
                </div>
            </div>
        </div>
					      
            </div> 	
        </div>
    </div>
</div>

<?php // TODO: придумать Хак, чтобы нормально отображались зубцы. Требует поддержки прозрачных PNG картинок...?>
<div id="header-bottom">
    <div class="container">
        <div id="header-menu">
		
		<?=CHTML::link("",'http://fx-private.com/',array('class'=>'homelink left', 'target'=>'_blank'))?>
		<?
			
				$connection=Yii::app()->db;
				$sql = 'SELECT COUNT(*) as cnt FROM `msg_messages` mm LEFT JOIN `msg_threads` mt ON mt.`ID` = mm.`thread_id` WHERE mt.`client` = \''.$this->user->ID.'\' AND mm.`status` = \'1\' AND mm.`sender` <> \''.$this->user->ID.'\'';
				$cnt = $connection->createCommand($sql)->queryRow();
					
				$msg_unread = $cnt['cnt'];
				
				$menu1 = array(
						array(
									'label'=>Yii::t('main','FAQ'),
									'url'=>Yii::t('links', 'http://fx-private.com/faq/cabinet.html'),
									'linkOptions'=>array('target'=>'_BLANK'),
									'itemOptions'=>array('id'=>'faq')
									),

								array(
									'label'=>Yii::t('main', 'Чат'),
									'url'=>'https://siteheart.com/webconsultation/363838?',
									'linkOptions'=>array('target'=>'siteheart_sitewindow_363838',
									'onclick'=>"o=window.open;o('https://siteheart.com/webconsultation/363838?', 'siteheart_sitewindow_363838', 'width=550,height=400,top=30,left=30,resizable=yes'); return false;"),
									'itemOptions'=>array('id'=>'chat', )
									),

								array(
									'label'=>Yii::t('main', 'Мои сообщения'),
									'url'=>array('/messages'),
									'itemOptions'=>array('id'=>($msg_unread?'messages_unread':'messages')),
									'active'=>$this->id == 'messages'?true:false,
									'encodeLabel'=>false,
									),

								array(
									'label'=>Yii::t('main', 'История платежей'),
									'url'=>array('/account/paymenthistory', 'view'=>'about'),
									'itemOptions'=>array('id'=>'history')
									),

								array(
									'label'=>Yii::t('main', 'Мои счета'),
									'url'=>array('/account'),
									'itemOptions'=>array('id'=>'accounts')
									)
				);
				
				if (isset($this->user->partner)){
				if ($this->user->partner == 2){
					
					$menu1 = array();
					$menu1 = array(
						array(
									'label'=>Yii::t('main','FAQ'),
									'url'=>Yii::t('links', 'http://fx-private.com/faq/cabinet.html'),
									'linkOptions'=>array('target'=>'_BLANK'),
									'itemOptions'=>array('id'=>'faq')
									),

								array(
									'label'=>Yii::t('main', 'Чат'),
									'url'=>'https://siteheart.com/webconsultation/363838?',
									'linkOptions'=>array('target'=>'siteheart_sitewindow_363838',
									'onclick'=>"o=window.open;o('https://siteheart.com/webconsultation/363838?', 'siteheart_sitewindow_363838', 'width=550,height=400,top=30,left=30,resizable=yes'); return false;"),
									'itemOptions'=>array('id'=>'chat', )
									),

								array(
									'label'=>Yii::t('main', 'Мои сообщения'),
									'url'=>array('/messages/index'),
									'itemOptions'=>array('id'=> ($msg_unread?'messages_unread':'messages')),
									'active'=>$this->id == 'messages'?true:false,
									'encodeLabel'=>false,
									),
								array(
									'label'=>Yii::t('main', 'Я партнёр'),
									'url'=>array('/partner/index'),
									'active'=>$this->id == 'partner'?true:false,
									'itemOptions'=>array('id'=>'partner')
									),
								array(
									'label'=>Yii::t('main', 'История платежей'),
									'url'=>array('/account/paymenthistory'),
									'itemOptions'=>array('id'=>'history')
									),

								array(
									'label'=>Yii::t('main', 'Мои счета'),
									'url'=>array('/account/index'),
									'itemOptions'=>array('id'=>'accounts')
									)
				);
				}
			}
		?>
            	<div id="mainmenu">
		<?php $this->widget('zii.widgets.CMenu',array(
			'itemTemplate'=>($_GET['language'] != 'ar')?'<div class="tab right"><div class="tableft"></div><div class="tabcenter"><div class="tabico"></div><div class="tablabel">{menu}</div></div><div class="tabright"></div></div>':'<div class="tab left"><div class="tabright"></div><div class="tabcenter"><div class="tabico"></div><div class="tablabel">{menu}</div></div><div class="tableft"></div></div>',
                        'items'=>$menu1
		)); ?>
                </div><!-- mainmenu -->
                    <?=CHTML::link(Yii::t('main', 'Открыть счет'),array("/account/new"),array('class'=>'roundedbutton right'))?>
        </div>

    </div>
</div>
    <!-- header -->

<div class="container" id="page">
<?php echo $content; ?>
</div><!-- page -->
<?php //new body end?>
</div>

<?php $this->widget('FooterWidget'); ?><!-- footer -->

<?php 
if (empty(Yii::app()->request->cookies['nT'])) {
            ?>
    <div class="container msg-container">
    <div id="msg">
        <div id="msg-rel">
            <div id="msg-close"></div>
            <div id="msg-text"><?= Yii::t('main', 'Для повышения безопасности сессия закончится через') ?></div>
            <div id="msg-timer">00:05:00</div>
        </div>
    </div>
    </div>        
<?php } ?>
</body>
</html>