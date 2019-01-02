<?php
/* @var $this UserinfoController */
/* @var $model Userinfo */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('admin'),
	t('epmms','添加'),
);

$this->menu=array(
	array('label'=>t('epmms','返回 用户管理'), 'url'=>array('admin')),
);
?>

<h1><?=t('epmms','创建管理员用户')?></h1>

<?php echo $this->renderPartial('_formCreate', array('model'=>$model)); ?>