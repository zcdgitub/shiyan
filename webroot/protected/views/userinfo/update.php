<?php
/* @var $this UserinfoController */
/* @var $model Userinfo */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('admin'),
	$model->showName,
	t('epmms','修改'),
);

$this->menu=array(
	array('label'=>t('epmms','创建用户'), 'url'=>array('create')),
	array('label'=>t('epmms','修改密码'), 'url'=>array('updatePassword','id'=>$model->userinfo_id)),
	array('label'=>t('epmms','返回 用户管理'), 'url'=>array('admin')),
);
?>

<h1 style="text-align:center"><?=t('epmms','编辑用户')?> <?php echo $model->userinfo_account; ?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>