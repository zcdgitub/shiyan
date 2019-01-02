<?php
/* @var $this MessagesController */
/* @var $model Messages */

$this->menu=array(
	array('label'=>t('epmms','发送') . t('epmms',$model->modelName), 'url'=>array('create')),
	array('label'=>t('epmms',user()->isAdmin()?'群发站内信':'系统留言'), 'url'=>array('create','messagesType'=>1)),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('messages-grid', {
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
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#messages-grid'));
$columns=array(
array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),
	'messages_title',
	//['name'=>'messages_content','type'=>'raw'],
	'messages_add_date',
	array('value'=>'is_null($data->messagesSenderMember)?t("epmms","系统"):$data->messagesSenderMember->memberinfo_account','header'=>t('epmms','发件人')),
	array('value'=>'is_null($data->messagesReceiverMember)?(user()->isAdmin()&&is_null($data->messagesSenderMember)?t("epmms","全部"):t("epmms","系统")):$data->messagesReceiverMember->memberinfo_account','header'=>t('epmms','收件人')),
	array(
	'class'=>'ButtonColumn',
	'template'=>'{view}',
	),
	array(
	'class'=>'ButtonColumn',
	'template'=>'{delete}',
	),
	'reply'=>array(
		'class'=>'CLinkColumn',
		'urlExpression'=>'$this->grid->controller->createUrl("create",["id"=>$data->messages_id,"messagesType"=>is_null($data->messagesSenderMember)?1:0])',
		'label'=>t('epmms','回复'),
	),
);
switch($selTab)
{
	case 0:
	//处理每种标签的特殊情况
		break;
	case 1:
		unset($columns['reply']);
		break;
	break;
}
$this->widget('GridView', array(
	'id'=>'messages-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>$columns,
)); ?>
