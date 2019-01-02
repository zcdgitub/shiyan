<?php
/* @var $this AnnouncementController */
/* @var $model Announcement */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	$model->showName,
);

$this->menu=array(

);
?>

<div class="epview">
<h1><?=$model->announcement_title?></h1>
<p><?=$model->announcement_content?></p>
</div>
