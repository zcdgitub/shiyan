<?php
/* @var $this AnnouncementController */
/* @var $model Announcement */

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('announcement-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo t('epmms','管理') . t('epmms',$model->modelName)?></h1>


<?php echo CHtml::imageButton(themeBaseUrl() . '/images/sou_1.png',['class'=>'search-button']); ?>
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#announcement-grid'));
$columns=array(
array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),
	['header'=>'标题','value'=>'webapp()->format->contentThumb($data->announcement_title)','name'=>'announcement_title'],
	array('class'=>'RelationDataColumn','name'=>'announcementClass.class_name','header'=>t('epmms','分类')),
	//['header'=>'内容','value'=>'webapp()->format->contentThumb($data->announcement_content)','type'=>'raw'],
	array('class'=>'RelationDataColumn','name'=>'announcementUserinfo.userinfo_account','header'=>t('epmms','发布人')),
	'announcement_mod_date',
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
	break;
}
$this->widget('GridView', array(
	'id'=>'announcement-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>$columns,
)); ?>
