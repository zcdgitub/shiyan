<?php
/* @var $this DupController */
/* @var $model Dup */

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
	$.fn.yiiGridView.update('dup-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo t('epmms','管理') . t('epmms',$model->modelName)?></h1>

<p>
<?php echo t('epmms','你可以输入一个比较运算符 ');?>(<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
<?php echo t('epmms','或者');?> <b>=</b>) <?php echo t('epmms','在每个搜索值的前面来指定怎么匹配搜索结果。');?>
</p>

<input class="search-button" src="/themes/member/images/sou_1.png" type="image" name="yt0" /><div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#dup-grid'));
$columns=array(
array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),
	array('class'=>'RelationDataColumn','name'=>'dupMember.memberinfo_account'),
	'dup_money',
	'dup_add_date',
	'verify_date'=>'dup_verify_date',
	'verify'=>array(
		'class'=>'ButtonColumn',
		'template'=>'{verify}',
		'visible'=>true,
		//'buttons'=>array('verify'=>array('isverify'=>'dup_is_verify'))
	),
);
switch($selTab)
{
	case 0:
	//处理每种标签的特殊情况
		unset($columns['verify_date']);
		break;
	case 1:
		unset($columns['verify']);
		break;
}
$this->widget('GridView', array(
	'id'=>'dup-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>$columns,
)); ?>
