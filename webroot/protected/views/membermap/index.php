<?php
/* @var $this MembermapController */
/* @var $model Membermap */

$this->breadcrumbs=array(
	t('epmms',$model->modelName),
);

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
	$.fn.yiiGridView.update('membermap-grid', {
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

<?php echo CHtml::imageButton(themeBaseUrl() . '/images/sou_1.png',['class'=>'search-button']); ?>

<div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php
$listModel=Model::model('Memberinfo')->findAll();
$listData['Memberinfo']=CHtml::listdata($listModel,'memberinfo_account','memberinfo_account');
$listModel=Model::model('Membermap')->findAll();
$listData['Membermap']=CHtml::listdata($listModel,'membermap_id','membermap_id');
$listModel=Model::model('Membermap')->findAll();
$listData['Membermap']=CHtml::listdata($listModel,'membermap_id','membermap_id');
$listModel=Model::model('Membertype')->findAll();
$listData['Membertype']=CHtml::listdata($listModel,'membertype_name','membertype_name');
$listModel=Model::model('Product')->findAll();
$listData['Product']=CHtml::listdata($listModel,'product_name','product_name');
$listModel=Model::model('Membermap')->findAll();
$listData['Membermap']=CHtml::listdata($listModel,'membermap_id','membermap_id');
$listModel=Model::model('Memberinfo')->findAll();
$listData['Memberinfo']=CHtml::listdata($listModel,'memberinfo_account','memberinfo_account');
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#membermap-grid'));
$this->widget('GridView', array(
	'id'=>'membermap-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),	
		array('class'=>'DataColumn','name'=>'membermap.memberinfo_account','filter'=>$listData['Memberinfo'],'header'=>$model->getAttributeLabel('membermap_id')),
		array('class'=>'DataColumn','name'=>'membermapParent.membermap_id','filter'=>$listData['Membermap'],'header'=>$model->getAttributeLabel('membermap_parent_id')),
		array('class'=>'DataColumn','name'=>'membermapRecommend.membermap_id','filter'=>$listData['Membermap'],'header'=>$model->getAttributeLabel('membermap_recommend_id')),
		array('class'=>'DataColumn','name'=>'membermapMembertypeLevel.membertype_name','filter'=>$listData['Membertype'],'header'=>$model->getAttributeLabel('membermap_membertype_level')),
		'membermap_layer',
		'membermap_path',
		'membermap_recommend_path',
		'membermap_recommend_number',
		'membermap_recommend_under_number',
		'membermap_child_number',
		'membermap_sub_number',
		'membermap_sub_product_count',
		'membermap_recommend_under_product_count',
		//array('class'=>'DataColumn','name'=>'membermapProduct.product_name','filter'=>$listData['Product'],'header'=>$model->getAttributeLabel('membermap_product_id')),
		/*
		'membermap_product_money',
		'membermap_product_count',
		array('class'=>'DataColumn','name'=>'membermapAgent.membermap_id','filter'=>$listData['Membermap'],'header'=>$model->getAttributeLabel('membermap_agent_id')),
		array('class'=>'DataColumn','name'=>'membermap_is_verify','type'=>'verify','filter'=>array(0=>'未审核',1=>'已审核')),
		'membermap_is_agent',
		array('class'=>'DataColumn','name'=>'membermap_verify_date','type'=>'datetime'),
		array('class'=>'DataColumn','name'=>'membermapVerifyMember.memberinfo_account','filter'=>$listData['Memberinfo'],'header'=>$model->getAttributeLabel('membermap_verify_member_id')),
		array('class'=>'DataColumn','name'=>'membermap_add_date','type'=>'datetime'),
		*/
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
		),

	),
)); ?>
