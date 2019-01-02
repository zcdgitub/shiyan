<?php
/* @var $this MapEditController */
/* @var $model MapEdit */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	$model->showName=>array('view','id'=>$model->map_edit_id),
	t('epmms','修改'),
);

$this->menu=array(
	array('label'=>t('epmms','查看') . t('epmms',$model->modelName), 'url'=>array('view', 'id'=>$model->map_edit_id)),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
$form[1]='_del';
$form[2]='_add';
$form[3]='_move';
$form[4]='_swap';
?>

<h1><?php echo t('epmms','修改') . t('epmms',$model->modelName) . ' #' . $model->showName; ?></h1>

<?php echo $this->renderPartial($form[$model->map_edit_operate], array('model'=>$model)); ?>