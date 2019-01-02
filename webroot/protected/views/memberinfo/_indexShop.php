<?php
/* @var $this MemberinfoController */
/* @var $model Memberinfo */

if(MemberinfoItem::model()->itemVisible('membermap_agent_id') || !user()->isAdmin())
{
	$this->menu=array(
		array('label'=>t('epmms','注册') . t('epmms',$model->modelName), 'url'=>array('createShop'),'visible'=>true)
	);
}
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

<h1><?php echo t('epmms','审核') . t('epmms',$model->modelName)?></h1>

<?php echo CHtml::imageButton(themeBaseUrl() . '/images/sou_1.png',['class'=>'search-button']); ?>
<div class="search-form" style="display:none">
<?php 
$form = new Form('application.views.memberinfo.searchForm', $model);
$this->renderPartial('_search',array('form'=>$form));
?>
</div><!-- search-form -->
&nbsp;&nbsp;
<?=CHtml::imageButton(themeBaseUrl() . '/images/Excel.png',
	['submit'=>CHtml::normalizeUrl(['','isVerifyType'=>$isVerifyType,'isAgent'=>$isAgent,'grid_mode'=>'export','exportType'=>'Excel5'])])?>
&nbsp;&nbsp;
<?=CHtml::imageButton(themeBaseUrl() . '/images/pdf.png',
	['submit'=>CHtml::normalizeUrl(['','isVerifyType'=>$isVerifyType,'isAgent'=>$isAgent,'grid_mode'=>'export','exportType'=>'PDF'])])?>

<?php $this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#memberinfo-grid')); ?>
<?php
$listModel=Model::model('Bank')->findAll();
$listData['Bank']=CHtml::listdata($listModel,'bank_name','bank_name');
$item=MemberinfoItem::model();
$columns=array(
	array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'headerHtmlOptions' => array('style'=>'width:40px')),
	['name'=>'memberinfo_account','visible'=>$item->getAdminItem('memberinfo_account')],
	array('name'=>'memberinfo_name','visible'=>$item->getAdminItem('memberinfo_name')),
	array('name'=>'memberinfo_nickname','visible'=>$item->getAdminItem('memberinfo_nickname')),
	['name'=>'memberinfo_email','visible'=>$item->getAdminItem('memberinfo_email')],
	array('name'=>'memberinfo_mobi','visible'=>$item->getAdminItem('memberinfo_mobi')),
	array('name'=>'memberinfo_phone','visible'=>$item->getAdminItem('memberinfo_phone')),
	array('name'=>'memberinfo_qq','visible'=>$item->getAdminItem('memberinfo_qq')),
	array('name'=>'memberinfo_msn','visible'=>$item->getAdminItem('memberinfo_msn')),
	array('class'=>'DataColumn','name'=>'memberinfo_sex','type'=>'sex','filter'=>array(0=>'男',1=>'女',2=>'保密'),'visible'=>$item->getAdminItem('memberinfo_sex')),
	array('name'=>'memberinfo_idcard_type','visible'=>$item->getAdminItem('memberinfo_idcard_type')),
	array('name'=>'memberinfo_idcard','visible'=>$item->getAdminItem('memberinfo_idcard')),
	['name'=>'memberinfo_zipcode','visible'=>$item->getAdminItem('memberinfo_zipcode')],
	array('class'=>'DataColumn','name'=>'memberinfo_birthday','type'=>'date','visible'=>$item->getAdminItem('memberinfo_birthday')),
	['name'=>'memberinfo_address_provience','visible'=>$item->getAdminItem('memberinfo_address_provience')],
	['name'=>'memberinfo_address_area','visible'=>$item->getAdminItem('memberinfo_address_area')],
	['name'=>'memberinfo_address_county','visible'=>$item->getAdminItem('memberinfo_address_county')],
	['name'=>'memberinfo_address_detail','visible'=>$item->getAdminItem('memberinfo_address_detail')],
	array('class'=>'DataColumn','name'=>'memberinfoBank.bank_name','filter'=>$listData['Bank'],'header'=>$model->getAttributeLabel('memberinfo_bank_id'),'visible'=>$item->getAdminItem('memberinfo_bank_id')),
	['name'=>'memberinfo_bank_name','visible'=>$item->getAdminItem('memberinfo_bank_name')],
	['name'=>'memberinfo_bank_account','visible'=>$item->getAdminItem('memberinfo_bank_account')],
	['name'=>'memberinfo_bank_provience','visible'=>$item->getAdminItem('memberinfo_bank_provience')],
	['name'=>'memberinfo_bank_area','visible'=>$item->getAdminItem('memberinfo_bank_area')],
	['name'=>'memberinfo_bank_branch','visible'=>$item->getAdminItem('memberinfo_bank_branch')],
	['name'=>'memberinfo_memo','visible'=>$item->getAdminItem('memberinfo_memo')],
	array('class'=>'DataColumn','name'=>'memberinfo_is_enable','type'=>'yesno',
		'filter'=>array(0=>'否',1=>'是'),'visible'=>$item->getAdminItem('memberinfo_is_enable'),
		'headerHtmlOptions'=>['style'=>'width:60px;']),
	['name'=>'memberinfo_register_ip','visible'=>$item->getAdminItem('memberinfo_register_ip')],
	['name'=>'memberinfo_last_ip','visible'=>$item->getAdminItem('memberinfo_last_ip')],
	array('class'=>'DataColumn','name'=>'memberinfo_last_date','type'=>'datetime','visible'=>$item->getAdminItem('memberinfo_last_date')),
	'add_date'=>array('class'=>'DataColumn','name'=>'memberinfo_add_date','type'=>'datetime','visible'=>$item->getAdminItem('memberinfo_add_date')),
	array(
		'class'=>'ButtonColumn',
		'template'=>'{view}',
	),
	'update'=>array(
		'class'=>'ButtonColumn',
		'template'=>'{update}',
	),
	'delete'=>array(
		'class'=>'ButtonColumn',
		'template'=>'{delete}',
		'afterDelete'=>'function(link,success,data){if(data.length>0)alert(data);}',
		'buttons'=>array('delete'=>array('options'=>['confirm_php'=>t('epmms',"'你确定要删除会员' . \$data->memberinfo_account .  '吗？'")]))
	),
	'verify'=>array(
		'class'=>'ButtonColumn',
		'template'=>'{verify}',
		'visible'=>true,
		'verifyButtonUrl'=>'Yii::app()->controller->createUrl("verifyShop",array("id"=>$data->primaryKey))',
		'buttons'=>array('verify'=>array('isverify'=>'memberinfo_is_verify')),
		'header'=>t('epmms','审核')
	));
if($isVerifyType==0)
{
	unset($columns['is_agent']);
	unset($columns['verify_date']);
}
else
{
	unset($columns['verify']);
	unset($columns['delete']);
	unset($columns['add_date']);
	if(!user()->isAdmin())
	{
		unset($columns['update']);
	}
}
$this->widget('EExcelView', array(
	'id'=>'memberinfo-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'title'=>t('epmms',$model->modelName),
	'columns'=>$columns
));
?>
