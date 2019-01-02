<?php
/* @var $this MemberinfoController */
/* @var $model Memberinfo */


$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create'),'visible'=>true),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('memberinfo-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo t('epmms','管理') . t('epmms',$model->modelName)?></h1>

<?php echo CHtml::imageButton(themeBaseUrl() . '/images/sou_1.png',['class'=>'search-button']); ?>

<div class="search-form" style="display:none">
<?php 
$form = new Form('application.views.memberinfo.searchForm', $model);
$this->renderPartial('_search',array('form'=>$form));
?>
</div><!-- search-form -->
<?php $this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#memberinfo-grid')); ?>
<?php
$listModel=Model::model('Bank')->findAll();
$listData['Bank']=CHtml::listdata($listModel,'bank_name','bank_name');
$columns=array(
	array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px;text-align:center')),
	'memberinfo_account',
	//array('name'=>'memberinfo_name','visible'=>$item->itemVisible('memberinfo_name')),
	array('name'=>'memberinfo_nickname','visible'=>$item->itemVisible('memberinfo_nickname')),
	'memberinfo_email',
	array('class'=>'RelationDataColumn','name'=>'membermap.membermapParent','header'=>$model->membermap->getAttributeLabel('membermap_parent_id')),
	array('class'=>'RelationDataColumn','name'=>'membermap.membermapRecommend','header'=>$model->membermap->getAttributeLabel('membermap_recommend_id')),
	array('class'=>'RelationDataColumn','name'=>'membermap.membermapMembertypeLevel','header'=>$model->membermap->getAttributeLabel('membermap_membertype_level')),
	//array('class'=>'RelationDataColumn','name'=>'membermap.product.membermap_product_id'),
	array('class'=>'RelationDataColumn','name'=>'membermap.membermapAgent','header'=>$model->membermap->getAttributeLabel('membermap_agent_id')),
	'is_agent'=>array('class'=>'DataColumn','name'=>'membermap.membermap_is_agent','type'=>'agent'),
	//array('name'=>'memberinfo_mobi','visible'=>$item->itemVisible('memberinfo_mobi')),
	//array('name'=>'memberinfo_phone','visible'=>$item->itemVisible('memberinfo_phone')),
	//array('name'=>'memberinfo_qq','visible'=>$item->itemVisible('memberinfo_qq')),
	//array('name'=>'memberinfo_msn','visible'=>$item->itemVisible('memberinfo_msn')),
	//array('class'=>'DataColumn','name'=>'memberinfo_sex','type'=>'sex','filter'=>array(0=>'男',1=>'女',2=>'保密'),'visible'=>$item->itemVisible('memberinfo_sex')),
	//array('name'=>'memberinfo_idcard_type','visible'=>$item->itemVisible('memberinfo_idcard_type')),
	//array('name'=>'memberinfo_idcard','visible'=>$item->itemVisible('memberinfo_idcard')),
	/*
	'memberinfo_zipcode',
	array('class'=>'DataColumn','name'=>'memberinfo_birthday','type'=>'date'),
	'memberinfo_address_provience',
	'memberinfo_address_area',
	'memberinfo_address_county',
	'memberinfo_address_detail',
	array('class'=>'DataColumn','name'=>'memberinfoBank.bank_name','filter'=>$listData['Bank'],'header'=>$model->getAttributeLabel('memberinfo_bank_id')),
	'memberinfo_bank_name',
	'memberinfo_bank_account',
	'memberinfo_bank_provience',
	'memberinfo_bank_area',
	'memberinfo_bank_branch',
	'memberinfo_memo',
	array('class'=>'DataColumn','name'=>'memberinfo_is_enable','type'=>'enable','filter'=>array(0=>'禁用',1=>'启用')),
	'memberinfo_register_ip',
	'memberinfo_last_ip',
	array('class'=>'DataColumn','name'=>'memberinfo_last_date','type'=>'datetime'),
	array('class'=>'DataColumn','name'=>'memberinfo_add_date','type'=>'datetime'),
	*/
);
$this->widget('GridView', array(
	'id'=>'memberinfo-grid',
	'dataProvider'=>$model->search(),
	//'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>$columns
));
?>
