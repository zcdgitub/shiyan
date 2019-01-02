<?php
/* @var $this UserinfoController */
/* @var $model Userinfo */
if(isset($isMy) && $isMy==true)
{
	$this->breadcrumbs=array(
		t('epmms','修改密码'),
	);
}
else
{
	$this->breadcrumbs=array(
		t('epmms',$model->modelName)=>array('index'),
		t('epmms','修改密码'),
	);
}

$this->menu=array(
	array('label'=>t('epmms','修改') . t('epmms',$model->modelName), 'url'=>array('update', 'id'=>$model->memberinfo_id)),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
	array('label'=>t('epmms','查看') . t('epmms',$model->modelName), 'url'=>array('view', 'id'=>$model->memberinfo_id)),
);
?>

<h1><?=t('epmms','修改会员密码')?></h1>

<?php echo $this->renderPartial('_formPassword', array('model'=>$model,'isMy'=>$isMy,'passwordAuth'=>isset($passwordAuth)?$passwordAuth:null)); ?>