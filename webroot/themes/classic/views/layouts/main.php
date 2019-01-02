<!DOCTYPE html>
<html lang="zh">
<head>
<meta charset="utf-8">
	<meta name="renderer" content="webkit">
	<link rel="shortcut icon" href="/favicon.png" type="image/x-icon">
	<?php
	registerCssFile(Yii::app()->request->baseUrl . '/css/screen.css');
	registerCssFile(Yii::app()->request->baseUrl . '/css/form.css');
	registerCssFile(themeBaseUrl() . '/css/epmms.css');
	?>
	<!--[if lt IE 8]>
	<link rel="stylesheet" type="text/css" href="<?php echo Yii::app()->request->baseUrl; ?>/css/ie.css" media="screen, projection" />
	<![endif]-->
<title><?php echo CHtml::encode($this->pageTitle); ?></title>
	<?php
	registerCssFile(themeBaseUrl() . '/css/epmms.css');
	?>
</head>

<body>
<div class="ep-container">
	<?php if(isset($this->breadcrumbs)):?>
		<?php $this->widget('zii.widgets.CBreadcrumbs', array(
			'links'=>$this->breadcrumbs,
			'homeLink'=>CHtml::link(Yii::t('zii','Home'),$this->createUrl('start/index')),
		)); ?><!-- breadcrumbs -->
	<?php endif?>
	<?php $this->widget('ext.Flashes.Flashes'); ?>
	<?php echo $content; ?>
	<div class="clear"></div>
</div><!-- page -->
<?php
if (Yii::app()->components['user']->loginRequiredAjaxResponse){
	Yii::app()->clientScript->registerScript('ajaxLoginRequired', '
            jQuery("body").ajaxComplete(
                function(event, request, options) {
                    if(!request)
                    {
                        console.log("不可识别response");
                        return;
                    }
                    if (request.responseText == "'.Yii::app()->components['user']->loginRequiredAjaxResponse.'") {
                        window.location.href ="' . webapp()->createUrl(user()->loginUrl[0]) . '";
                    }else if(request.responseText=="password2")
                    {
                    		window.location.href="' . webapp()->createUrl(user()->authenticUrl[0]) . '"
                    }
                }
            );
        ');
}
?>
</body>
</html>