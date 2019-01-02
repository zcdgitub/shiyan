<?php
/* @var $this MapEditController */
/* @var $model MapEdit */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	$model->showName,
);

$this->menu=array(
	array('label'=>t('epmms','删除点位') , 'url'=>array('deleteMap')),
	array('label'=>t('epmms','添加点位') , 'url'=>array('addMap')),
	array('label'=>t('epmms','移动点位') , 'url'=>array('moveMap')),
	array('label'=>t('epmms','交换点位') , 'url'=>array('swapMap')),
	array('label'=>t('epmms','修改') . t('epmms',$model->modelName), 'url'=>array('update', 'id'=>$model->map_edit_id)),
	array('label'=>t('epmms','删除') . t('epmms',$model->modelName), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->map_edit_id),'confirm'=>t('epmms','你确定要删除吗?'))),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo t('epmms','查看') . t('epmms',$model->modelName) . ' #' . $model->showName; ?></h1>
<div class="epview">
<?php
$attr=array(
	array('name'=>'mapEditSrcMember.showName','label'=>$model->getAttributeLabel('map_edit_src_member_id')),
	array('name'=>'mapEditDstMember.showName','label'=>$model->getAttributeLabel('map_edit_dst_member_id')),
	'order'=>['name'=>'map_edit_dst_order','type'=>'mapOrder'],
	array('name'=>'mapEditDstRecommend.showName','label'=>$model->getAttributeLabel('map_edit_dst_recommend_id')),
	['name'=>'map_edit_type','type'=>'mapType'],
	'map_edit_add_date',
	'map_edit_verify_date',
	['name'=>'map_edit_operate','type'=>'mapOperate'],
	'map_edit_info',
	['name'=>'map_edit_is_verify','type'=>'verify'],
);
if( ($model->map_edit_operate==1 || $model->map_edit_operate==4) && $model->map_edit_type==2 )
{
	unset($attr['order']);
}
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay'=>'',	
	'attributes'=>$attr,
)); ?>
</div>
