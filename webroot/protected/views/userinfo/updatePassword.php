<?php
/* @var $this UserinfoController */
/* @var $model Userinfo */

$this->breadcrumbs=array(
	'Userinfos'=>array('index'),
	'Create',
);

$this->menu=array(
	array('label'=>t('epmms','返回 编辑'), 'url'=>array('update','id'=>$model->userinfo_id)),
	array('label'=>t('epmms','返回 用户管理'), 'url'=>array('admin')),
);
?>

<h1><?=t('epmms','修改管理员用户密码')?></h1>

<?php echo $this->renderPartial('_formPassword', array('model'=>$model)); ?>