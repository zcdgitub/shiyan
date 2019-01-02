<?php
/* @var $this PrizeController */
/* @var $model Prize */

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
	$.fn.yiiGridView.update('prize-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo t('epmms','管理') . t('epmms',$model->modelName)?></h1>


<input class="search-button" src="/themes/classic/images/sou_1.png" type="image" name="yt0" /><div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#prize-grid'));
$columns=array(
array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),
	'prize_info',
	array('class'=>'DataColumn','name'=>'prize_is_verify','type'=>'send','filter'=>array(0=>'未发货',1=>'已发货')),
	'prize_verify_date',
	'prize_date',
	array('class'=>'RelationDataColumn','name'=>'prizeMember.memberinfo_account'),
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
		if(user()->isAdmin())
			$columns['send']=['class'=>'CLinkColumn','label'=>'发货','urlExpression'=>'webapp()->createUrl("prize/verify",["id"=>$data->prize_id])'];
	break;
}
$this->widget('GridView', array(
	'id'=>'prize-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>$columns,
)); ?>
