<!DOCTYPE html>
<html lang="zh">
<head>
	<meta name="renderer" content="webkit">
	<meta charset="utf-8">
	<!-- blueprint CSS framework -->
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/screen.css" media="screen, projection" />
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/print.css" media="print" />
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->

	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/form.css" />
	<link rel="stylesheet" type="text/css" href="<?php echo themeBaseUrl()?>/css/epmms.css"/>
		<?php //Yii::app()->clientScript->registerCoreScript('jquery');?>
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
</head>

<body>

<div class="ep-container">
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
		)); ?><!-- breadcrumbs -->
	<?php endif?>
	<div class="config-form">
	<?php echo $content; ?>
	</div>
	<div class="clear"></div>
</div><!-- page -->


</body>
</html>