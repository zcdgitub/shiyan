<!DOCTYPE html>
<html lang="zh">
<head>
	<meta name="renderer" content="webkit">
	<meta charset="utf-8">
	<?php
	registerCssFile(themeBaseUrl() . '/css/frame_nav.css');
	registerCssFile(themeBaseUrl() . '/css/epmms.css');
	registerCssFile( '/js/jqclock/jqClock.css');
	Yii::app ()->clientScript->registerCoreScript ( 'jquery' );
	Yii::app ()->clientScript->registerCoreScript ( 'cookie' );
	registerScriptFile('/js/jqclock/jqClock.js');
	registerScriptFile('/js/lib/date.format.js');
	?>
	<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body><?php echo $content;?></body>
</html>