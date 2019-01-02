<?php
/* @var $this AppropriateController */
/* @var $model Appropriate */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	$model->showName,
);

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','拨款') , 'url'=>array('create')),
	array('label'=>t('epmms','扣款'), 'url'=>array('deduct')),
	array('label'=>t('epmms','修改') . t('epmms',$model->modelName), 'url'=>array('update', 'id'=>$model->appropriate_id)),
	array('label'=>t('epmms','删除') . t('epmms',$model->modelName), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->appropriate_id),'confirm'=>t('epmms','你确定要删除吗?'))),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo t('epmms','查看') . t('epmms',$model->modelName) . ' #' . $model->showName; ?></h1>
<div class="epview">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay'=>'',	
	'attributes'=>array(
		'appropriate_currency',
		array('name'=>'appropriateFinanceType.finance_type_name','label'=>$model->getAttributeLabel('appropriate_finance_type_id')),
		'appropriate_add_date',
		array('name'=>'appropriateMemberinfo.memberinfo_account','label'=>$model->getAttributeLabel('appropriate_memberinfo_id')),
		array('label'=>'代理中心编号','value'=>@Agent::model()->findByAttributes(["agent_memberinfo_id"=>$model->appropriate_memberinfo_id])->agent_account),
		['name'=>'appropriate_type','type'=>'appropriateType'],
	),
)); ?>
</div>
