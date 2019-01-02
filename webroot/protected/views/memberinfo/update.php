<?php
/* @var $this MemberinfoController */
/* @var $model Memberinfo */
if(isset($isMy) && $isMy==true)
{
	$this->breadcrumbs=array(
		t('epmms','修改资料'),
	);
}
else
{
	$this->breadcrumbs=array(
		t('epmms',$model->modelName)=>array('index'),
		t('epmms','修改'),
	);
}
$this->menu=array(
	array('label'=>t('epmms','查看') . t('epmms',$model->modelName), 'url'=>array($model->memberinfo_is_agent==2?'viewShop':'view', 'id'=>$model->memberinfo_id)),
	array('label'=>t('epmms','修改密码'), 'url'=>array('updatePassword', 'id'=>$model->memberinfo_id)),
);
?>

<h1><?php echo t('epmms','修改') . t('epmms',$model->modelName) . ' #' . $model->memberinfo_account; ?></h1>



<?php echo $this->renderPartial('_form', array('model'=>$model,'form'=>$form)); ?>