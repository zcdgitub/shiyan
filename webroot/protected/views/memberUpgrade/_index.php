<?php
/* @var $this MemberUpgradeController */
/* @var $model MemberUpgrade */

$this->menu=array(
	array('label'=>t('epmms','我要升级') , 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('member-upgrade-grid', {
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
&nbsp;&nbsp;
<?=CHtml::imageButton(themeBaseUrl() . '/images/Excel.png',
	['submit'=>CHtml::normalizeUrl(['','selTab'=>$selTab,'grid_mode'=>'export','exportType'=>'Excel5'])])?>
<?php
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#member-upgrade-grid'));
$columns=array(
array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),
	array('class'=>'DataColumn','name'=>'memberUpgradeMember.showName'),
	array('class'=>'DataColumn','name'=>'memberUpgradeOldType.membertype_name','header'=>t('epmms','原')),
	array('class'=>'DataColumn','name'=>'memberUpgradeType.membertype_name','header'=>t('epmms','升级到')),
	array('class'=>'DataColumn','name'=>'member_upgrade_money'),
	//'member_upgrade_is_verify',
	'member_upgrade_add_date',
	'verify_date'=>'member_upgrade_verify_date',
	array(
	'class'=>'ButtonColumn',
	'template'=>'{view}',
	),

);
switch($selTab)
{
	case 0:
		unset($columns['verify_date']);
		break;
	case 1:
		unset($columns['verify']);
		unset($columns['delete']);
		break;
}
$this->widget('GridView', array(
	'id'=>'member-upgrade-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>$columns,
)); ?>
