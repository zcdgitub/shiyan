<?php
/* @var $this FoundationController */
/* @var $model Foundation */

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
	$.fn.yiiGridView.update('foundation-grid', {
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

<input class="search-button" src="/themes/classic/images/sou_1.png" type="image" name="yt0" /><div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#foundation-grid'));
$columns=array(
array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),
	array('class'=>'RelationDataColumn','name'=>'foundationMember.memberinfo_account'),
	'foundation_currency',
	'foundation_tax',
	'foundation_real_currency',
	'foundation_reamrk',
	//array('class'=>'DataColumn','name'=>'foundation_is_verify','type'=>'verify','filter'=>array(0=>'未审核',1=>'已审核')),
	'foundation_add_date',
	'verify_date'=>'foundation_verify_date',
	array(
	'class'=>'ButtonColumn',
	'template'=>'{view}',
	),
	array(
	'class'=>'ButtonColumn',
	'template'=>'{update}',
	),
	'delete'=>array(
	'class'=>'ButtonColumn',
	'template'=>'{delete}',
	)
);
switch($selTab)
{
	case 0:
	//处理每种标签的特殊情况
		$columns[]=	array(
			'class'=>'ButtonColumn',
			'template'=>'{verify}',
			'buttons'=>array('verify'=>array('isverify'=>'foundation_is_verify')),
		);
		unset($columns['verify_date']);
		break;
	case 1:
		unset($columns['delete']);
		break;
}
$this->widget('GridView', array(
	'id'=>'foundation-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>$columns,
)); ?>
