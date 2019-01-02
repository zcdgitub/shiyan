<?php
/* @var $this MemberTypeController */
/* @var $model MemberType */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	$model->showName=>array('view','id'=>$model->membertype_level),
	t('epmms','修改'),
);

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create')),
	array('label'=>t('epmms','查看') . t('epmms',$model->modelName), 'url'=>array('view', 'id'=>$model->membertype_level)),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo t('epmms','修改') . t('epmms',$model->modelName) . ' #' . $model->showName; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>