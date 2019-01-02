<?php
/* @var $this MembermapController */
/* @var $model Membermap */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	$model->showName,
);

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create')),
	array('label'=>t('epmms','修改') . t('epmms',$model->modelName), 'url'=>array('update', 'id'=>$model->membermap_id)),
	array('label'=>t('epmms','删除') . t('epmms',$model->modelName), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->membermap_id),'confirm'=>t('epmms','你确定要删除吗?'))),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo t('epmms','查看') . t('epmms',$model->modelName) . ' #' . $model->showName; ?></h1>
<div class="epview">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay'=>'',	
	'attributes'=>array(
		array('name'=>'membermap.memberinfo_account','label'=>$model->getAttributeLabel('membermap_id')),
		array('name'=>'membermapParent.membermap_id','label'=>$model->getAttributeLabel('membermap_parent_id')),
		array('name'=>'membermapRecommend.membermap_id','label'=>$model->getAttributeLabel('membermap_recommend_id')),
		array('name'=>'membermapMembertypeLevel.membertype_name','label'=>$model->getAttributeLabel('membermap_membertype_level')),
		'membermap_layer',
		'membermap_path',
		'membermap_recommend_path',
		'membermap_recommend_number',
		'membermap_recommend_under_number',
		'membermap_child_number',
		'membermap_sub_number',
		'membermap_sub_product_count',
		'membermap_recommend_under_product_count',
		//array('name'=>'membermapProduct.product_name','label'=>$model->getAttributeLabel('membermap_product_id')),
		'membermap_product_money',
		'membermap_product_count',
		array('name'=>'membermapAgent.membermap_id','label'=>$model->getAttributeLabel('membermap_agent_id')),
		array('name'=>'membermap_is_verify','type'=>'verify'),
		'membermap_is_agent',
		array('name'=>'membermap_verify_date','type'=>'datetime'),
		array('name'=>'membermapVerifyMember.memberinfo_account','label'=>$model->getAttributeLabel('membermap_verify_member_id')),
		array('name'=>'membermap_add_date','type'=>'datetime'),
	),
)); ?>
</div>
