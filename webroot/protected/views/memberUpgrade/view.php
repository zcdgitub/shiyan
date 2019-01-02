<?php
/* @var $this MemberUpgradeController */
/* @var $model MemberUpgrade */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	$model->showName,
);

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create')),
	array('label'=>t('epmms','修改') . t('epmms',$model->modelName), 'url'=>array('update', 'id'=>$model->member_upgrade_id)),
	array('label'=>t('epmms','删除') . t('epmms',$model->modelName), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->member_upgrade_id),'confirm'=>t('epmms','你确定要删除吗?'))),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo t('epmms','查看') . t('epmms',$model->modelName) . ' #' . $model->showName; ?></h1>
<div class="epview">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay'=>'',	
	'attributes'=>array(
		array('name'=>'memberUpgradeMember.showName','label'=>$model->getAttributeLabel('member_upgrade_member_id')),
		array('name'=>'memberUpgradeOldType.membertype_name','label'=>$model->getAttributeLabel('member_upgrade_old_type')),
		array('name'=>'memberUpgradeType.membertype_name','label'=>$model->getAttributeLabel('member_upgrade_type')),
		array('name'=>'member_upgrade_money'),
		['name'=>'member_upgrade_is_verify','type'=>'verify'],
		'member_upgrade_add_date',
		'member_upgrade_verify_date',
	),
)); ?>
</div>
