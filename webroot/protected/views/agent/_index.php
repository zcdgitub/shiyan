<?php
/* @var $this AgentController */
/* @var $model Agent */

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
	$.fn.yiiGridView.update('agent-grid', {
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
<?php $this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#agent-grid')); ?>
<?php
$this->widget('GridView', array(
	'id'=>'agent-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'columns'=>array(
		array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),	
		array('class'=>'RelationDataColumn','name'=>'agentMemberinfo.memberinfo_account','header'=>$model->getAttributeLabel('agent_memberinfo_id')),
		'agent_account',
		'agenttype.agent_type_name',
		'agent_province',
		'agent_area',
		'agent_county',
		'agent_memo',
		array('class'=>'DataColumn','name'=>'agent_add_date','type'=>'datetime'),
		array('class'=>'DataColumn','name'=>'agent_verify_date','type'=>'datetime'),
		array(
				'class'=>'ButtonColumn',
				'template'=>'{delete}',
		),
		array(
			'class'=>'ButtonColumn',
			'template'=>'{update}',
		),
		array(
				'class'=>'ButtonColumn',
				'template'=>'{verify}',
				'visible'=>true,
				'buttons'=>array('verify'=>array('isverify'=>'agent_is_verify')),
				'header'=>t('epmms','审核')
		),
	),
)); ?>
