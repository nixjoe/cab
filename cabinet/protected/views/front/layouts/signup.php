<?php 
Yii::app()->clientScript->registerScript('headflash',"
    $('div.flash .close').live('click', function() { $(this).parent().fadeOut(200)});
    ",  CClientScript::POS_READY);
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="ru" lang="ru">
<head>
	<meta http-equiv="content-type" content="text/html; charset=UTF-8" />
	<meta name="language" content="ru" />
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
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
        <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/jquery.jscrollpane.css" />
        <link rel="shortcut icon" href="<?php echo Yii::app()->request->baseUrl; ?>/images/favicon.ico">        
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/register.css" media="screen, projection" />
    <link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/footer.css" media="screen, projection" />
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<?
	 Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.mousewheel.js", CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/jquery.jscrollpane.min.js", CClientScript::POS_HEAD);
    Yii::app()->clientScript->registerScriptFile(Yii::app()->request->baseUrl . "/js/styledropdowns.js", CClientScript::POS_END);
	?>
	<?php if($_GET['language'] == 'ar') : ?>
	<style type="text/css">
		p, h1, h2, h3, h4, h5, h6, table, table tr, table td, ul, ol, ul li, ol li, span, a, .line-error,#errorrep .flash .errorSummary, .flash .header  {
		    direction: rtl !important;
		}
		div.flash {
			text-align: right !important;			
		}
	</style>
	<?php endif; ?>		
</head>

<body>
    <div id="newbody">
<div class="container" id="page">

	<div id="header">
            <div id="header-left"></div>
            <a href="http://fx-private.com/<?= $_GET['language'] == 'ru' ? '' : $_GET['language'].'/' ?>" target="_blank" id="header-logo"></a>
            
	
	
    <br /> <div id="select_language" class="margin-top right" style="margin:-133px 75px 11px 0">
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
					      
            </div><div id="header-right"></div></div>
    <!-- header -->
	<?php echo $content; ?>

</div><!-- page -->
</div><!-- new body-->

<?php $this->widget('FooterWidget'); ?><!-- footer -->

</body>
</html>