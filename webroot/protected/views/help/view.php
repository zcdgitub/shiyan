<?php
/* @var $this HelpController */
/* @var $model Help */

$this->breadcrumbs=array(
	t('epmms',$model->modelName),
	$model->showName,
);

$this->menu=array(

);
?>

<div class="helpview">
	<h1><?=$model->help_title?></h1>
	<p><?=$model->help_content?></p>
</div>
