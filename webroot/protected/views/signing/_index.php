<?php
/* @var $this SigningController */
/* @var $model Signing */

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
	$.fn.yiiGridView.update('signing-grid', {
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

<!--<input class="search-button" src="/themes/classic/images/sou_1.png" type="image" name="yt0" />-->
<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#signing-grid'));
$columns=array(
array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),
	array('class'=>'RelationDataColumn','name'=>'signingMember.memberinfo_account'),
	//array('class'=>'DataColumn','name'=>'signing_is_verify','type'=>'yesno','filter'=>array(0=>'未签约',1=>'已签约')),
	'sdate'=>'signing_date',
	//['class'=>'DataColumn','name'=>'signing_is_refund','type'=>'yesno','filter'=>array(0=>'未退款',1=>'已退款')],
	//'signing_verify_date',
	['class'=>'DataColumn','name'=>'signing_type','type'=>'signing_type','filter'=>array(0=>'注册签约',1=>'购物签约')],
	array(
	'class'=>'ButtonColumn',
	'template'=>'{view}',
	),
	'verify'=>['class'=>'CLinkColumn','label'=>'签约','urlExpression'=>'webapp()->createUrl("signing/verify",["id"=>$data->signing_id])'],
	'refund'=>['class'=>'CLinkColumn','label'=>'退款','urlExpression'=>'webapp()->createUrl("signing/refund",["id"=>$data->signing_id])'],
);
switch($selTab)
{
	case 0:
		//处理每种标签的特殊情况
		unset($columns['refund']);
		unset($columns['sdate']);
		break;
	case 1:
		unset($columns['verify']);
		break;
	case 2:
		unset($columns['verify']);
		unset($columns['refund']);
		break;
}
$this->widget('GridView', array(
	'id'=>'signing-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>$columns,
)); ?>
