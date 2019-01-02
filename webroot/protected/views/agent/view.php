<?php
/* @var $this AgentController */
/* @var $model Agent */
/*
$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	$model->showName,
);

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create')),
	array('label'=>t('epmms','修改') . t('epmms',$model->modelName), 'url'=>array('update', 'id'=>$model->agent_id)),
	array('label'=>t('epmms','删除') . t('epmms',$model->modelName), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->agent_id),'confirm'=>t('epmms','你确定要删除吗?'))),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
*/
?>
<? if(!is_null($model)):?>
	<? if($model->agent_is_verify==0):?>
		<h1>您的申请已提交，请等待审核。</h1>
	<?endif;?>
	<h1>查看代理中心</h1>
	<div class="epview">
	<?php $this->widget('zii.widgets.CDetailView', array(
		'data'=>$model,
		'nullDisplay'=>'',
		'attributes'=>array(
			array('name'=>'agentMemberinfo.memberinfo_account','label'=>$model->getAttributeLabel('agent_memberinfo_id')),
			'agent_account',
			'agent_memo',
			array('name'=>'agent_is_verify','type'=>'verify'),
			array('name'=>'agent_add_date','type'=>'datetime'),
			array('name'=>'agent_verify_date','type'=>'datetime'),
		),
	)); ?>
	</div>
<? else:?>
	<h1>您还没有申请代理中心</h1>
<? endif;?>