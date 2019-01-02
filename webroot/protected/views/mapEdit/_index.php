<?php
/* @var $this MapEditController */
/* @var $model MapEdit */

$this->menu=array(
	array('label'=>t('epmms','删除点位') , 'url'=>array('deleteMap')),
	array('label'=>t('epmms','添加点位') , 'url'=>array('addMap')),
	array('label'=>t('epmms','移动点位') , 'url'=>array('moveMap')),
	array('label'=>t('epmms','交换点位') , 'url'=>array('swapMap')),
);
$ss=SystemStatus::model()->find();
if($ss->system_status_mapedit==2)
{
	$this->menu[]=array('label'=>t('epmms','审核') , 'url'=>array('verify'));
}
$this->menu[]=array('label'=>t('epmms','更新业绩') , 'url'=>array('remap'));
Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('map-edit-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo t('epmms','管理') . t('epmms',$model->modelName)?></h1>

<!--
<?php /*echo CHtml::imageButton(themeBaseUrl() . '/images/sou_1.png',['class'=>'search-button']); */?>

<div class="search-form" style="display:none">
<?php
/*$this->renderPartial('_search',array(
	'model'=>$model,
)); */?>
</div><!-- search-form -->
-->
<?php
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#map-edit-grid'));
$columns=array(
array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),
	['name'=>'map_edit_type','type'=>'mapType'],
	['name'=>'map_edit_operate','type'=>'mapOperate'],
	array('class'=>'DataColumn','name'=>'mapEditSrcMember.showName','header'=>$model->getAttributeLabel('map_edit_src_member_id')),
	array('class'=>'DataColumn','name'=>'mapEditDstMember.showName','header'=>$model->getAttributeLabel('map_edit_dst_member_id')),
	array('name'=>'map_edit_dst_order','type'=>'mapOrder'),
	array('name'=>'mapEditDstRecommend.showName','header'=>$model->getAttributeLabel('map_edit_dst_recommend_id')),
	'map_edit_info',
	'map_edit_add_date',
	'verify_date'=>'map_edit_verify_date',
	array(
	'class'=>'ButtonColumn',
	'template'=>'{view}',
	),
	array(
	'class'=>'ButtonColumn',
	'template'=>'{update}',
	),
	array(
	'class'=>'ButtonColumn',
	'template'=>'{delete}',
	)
);
switch($selTab)
{
	case 0:
		//处理每种标签的特殊情况
		unset($columns['verify_date']);
		break;
}

$this->widget('GridView', array(
	'id'=>'map-edit-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>$columns,
));
?>
