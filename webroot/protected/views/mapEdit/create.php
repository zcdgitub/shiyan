<?php
/* @var $this MapEditController */
/* @var $model MapEdit */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	t('epmms','添加'),
);

$this->menu=array(
	array('label'=>t('epmms','删除点位') , 'url'=>array('deleteMap')),
	array('label'=>t('epmms','添加点位') , 'url'=>array('addMap')),
	array('label'=>t('epmms','移动点位') , 'url'=>array('moveMap')),
	array('label'=>t('epmms','交换点位') , 'url'=>array('swapMap')),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
$form[1]='_del';
$form[2]='_add';
$form[3]='_move';
$form[4]='_swap';
?>
<?php echo $this->renderPartial($form[$operate], array('model'=>$model)); ?>