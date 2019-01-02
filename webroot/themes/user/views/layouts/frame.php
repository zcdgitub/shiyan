<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8">
<link rel="stylesheet" type="text/css" href="<?php echo themeBaseUrl()?>/css/frame_nav.css" />
<link rel="stylesheet" type="text/css" href="<?php echo themeBaseUrl()?>/css/epmms.css" />
<link rel="stylesheet" type="text/css" href="/js/jqclock/jqClock.css" />
<?php
Yii::app ()->clientScript->registerCoreScript ( 'jquery' );
Yii::app ()->clientScript->registerCoreScript ( 'cookie' );
Yii::app ()->clientScript->registerScriptFile('/js/jqclock/jqClock.js');
Yii::app ()->clientScript->registerScriptFile('/js/lib/date.format.js');
?>
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>
<body><?php echo $content;?></body>
</html>