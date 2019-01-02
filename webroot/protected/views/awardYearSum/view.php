<?php
/* @var $this AwardYearSumController */
/* @var $model AwardYearSum */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	$model->showName,
);

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create')),
	array('label'=>t('epmms','修改') . t('epmms',$model->modelName), 'url'=>array('update', 'id'=>$model->award_year_sum_id)),
	array('label'=>t('epmms','删除') . t('epmms',$model->modelName), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->award_year_sum_id),'confirm'=>t('epmms','你确定要删除吗?'))),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo t('epmms','查看') . t('epmms',$model->modelName) . ' #' . $model->showName; ?></h1>
<div class="epview">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay'=>'',	
	'attributes'=>array(
		array('name'=>'awardYearSumMemberinfo.memberinfo_account','label'=>$model->getAttributeLabel('award_year_sum_memberinfo_id')),
		'award_year_sum_currency',
		array('name'=>'award_year_sum_date','type'=>'date'),
		'award_year_sum_add_date',
		array('name'=>'awardYearSumType.sum_type_name','label'=>$model->getAttributeLabel('award_year_sum_type')),
	),
)); ?>
</div>
