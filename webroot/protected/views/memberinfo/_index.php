<?php
/* @var $this MemberinfoController */
/* @var $model Memberinfo */

if(MemberinfoItem::model()->itemVisible('membermap_agent_id') || !user()->isAdmin())
{
	$this->menu=array(
		array('label'=>t('epmms','注册') . t('epmms',$model->modelName), 'url'=>array('create'),'visible'=>true)
	);
}
if(defined('YII_DEBUG') || YII_DEBUG)
{
	$this->menu[]=array('label'=>t('epmms','审核全部') . t('epmms',$model->modelName), 'url'=>array('verifyAll'),'visible'=>true);
	$this->menu[]=array('label'=>t('epmms','批量生成') . t('epmms',$model->modelName), 'url'=>array('gen'),'visible'=>true);
	$this->menu[]=array('label'=>t('epmms','重新结算') , 'url'=>array('reVerify'),'visible'=>true);
	$this->menu[]=array('label'=>t('epmms','清空奖金') , 'url'=>array('cleanAward'),'linkOptions'=>['confirm'=>'你确定要清空奖金吗'],'visible'=>true);
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
	array('name'=>'memberinfo_type','visible'=>$item->getAdminItem('memberinfo_type')),
	['name'=>'memberinfo_email','visible'=>$item->getAdminItem('memberinfo_email')],
	array('class'=>'RelationDataColumn','name'=>'membermap.membermapParent','sortable'=>false,'filter'=>CHtml::textField('Memberinfo[membermap][membermap_parent_id]',Memberinfo::id2name($model->membermap->membermap_parent_id)),'header'=>$model->membermap->getAttributeLabel('membermap_parent_id'),'visible'=>$item->getAdminItem('membermap_parent_id')),
	['name'=>'membermap.membermap_order','type'=>'mapOrder','filter'=>false,
		'visible'=>$item->getAdminItem('membermap_order'),'headerHtmlOptions' => array('style'=>'width:60px')],
	array('class'=>'RelationDataColumn','name'=>'membermap.membermapbond','sortable'=>false,'filter'=>CHtml::textField('Memberinfo[membermap][membermap_bond_id]',Memberinfo::id2name($model->membermap->membermap_bond_id)),'header'=>$model->membermap->getAttributeLabel('membermap_bond_id'),'visible'=>$item->getAdminItem('membermap_bond_id')),
	array('class'=>'RelationDataColumn','name'=>'membermap.membermapRecommend','sortable'=>false,'filter'=>CHtml::textField('Memberinfo[membermap][membermap_recommend_id]',Memberinfo::id2name($model->membermap->membermap_recommend_id)),'header'=>$model->membermap->getAttributeLabel('membermap_recommend_id'),'visible'=>$item->getAdminItem('membermap_recommend_id')),
	array('class'=>'DataColumn','name'=>'membermap.membermap_membertype_level','value'=>'$data->membermap->membermapMembertypeLevel->membertype_name',
		'header'=>$model->membermap->getAttributeLabel('membermap_membertype_level'),
		'visible'=>$item->getAdminItem('membermap_membertype_level'),'filter'=>MemberType::model()->getListData()),
	array('class'=>'DataColumn','name'=>'membermap.membermap_level','value'=>'@$data->membermap->memberlevel->member_level_name',
		'header'=>$model->membermap->getAttributeLabel('membermap_level'),'filter'=>MemberLevel::model()->listData,
		'visible'=>$item->getAdminItem('membermap_level')),
	array('class'=>'RelationDataColumn','name'=>'membermap.membermapAgent','sortable'=>false,'filter'=>CHtml::textField('Memberinfo[membermap][membermap_agent_id]',Memberinfo::id2name($model->membermap->membermap_agent_id)),'header'=>$model->membermap->getAttributeLabel('membermap_agent_id'),'visible'=>$item->getAdminItem('membermap_agent_id')),
	'is_agent'=>array('class'=>'DataColumn','name'=>'memberinfo_is_agent','type'=>'agent','filter'=>array(0=>'否',1=>'是'),'visible'=>$item->getAdminItem('memberinfo_is_agent')),
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
	['name'=>'memberinfo_postoffice','visible'=>$item->getAdminItem('memberinfo_postoffice')],
	['name'=>'memberinfo_memo','visible'=>$item->getAdminItem('memberinfo_memo')],
	/*
	['name'=>'membermap.membermap_percent1','header'=>t('epmms','注册币').'(%)'],
	['name'=>'membermap.membermap_percent2','header'=>t('epmms','奖金币').'(%)'],*/
	array('class'=>'DataColumn','name'=>'memberinfo_is_enable','type'=>'yesno',
		'filter'=>array(0=>'否',1=>'是'),'visible'=>$item->getAdminItem('memberinfo_is_enable'),
		'headerHtmlOptions'=>['style'=>'width:60px;']),
	array('class'=>'DataColumn','name'=>'membermap.membermap_is_empty','type'=>'yesno',
		'filter'=>array(0=>'否',1=>'是'),'visible'=>$item->getAdminItem('membermap_is_empty'),
		'headerHtmlOptions'=>['style'=>'width:60px;']),
	['name'=>'memberinfo_register_ip','visible'=>$item->getAdminItem('memberinfo_register_ip')],
	['name'=>'memberinfo_last_ip','visible'=>$item->getAdminItem('memberinfo_last_ip')],
	array('class'=>'DataColumn','name'=>'memberinfo_last_date','type'=>'datetime','visible'=>$item->getAdminItem('memberinfo_last_date')),
	'add_date'=>array('class'=>'DataColumn','name'=>'memberinfo_add_date','type'=>'datetime','visible'=>$item->getAdminItem('memberinfo_add_date')),
	'verify_date'=>array('class'=>'DataColumn','name'=>'membermap.membermap_verify_date','type'=>'datetime','visible'=>$item->getAdminItem('membermap_verify_date')),
	//'award_period'=>array('class'=>'DataColumn','header'=>'最后一期奖金','value'=>'@AwardPeriodSum::model()->find(["condition"=>"award_period_sum_memberinfo_id=:id","params"=>["id"=>$data->memberinfo_id],"order"=>"award_period_sum_period","limit"=>1])->award_period_sum_currency'),
	//'award_total'=>array('class'=>'DataColumn','header'=>'总奖金','value'=>'@AwardTotalSum::model()->findByAttributes(["award_total_sum_memberinfo_id"=>$data->memberinfo_id])->award_total_sum_currency'),
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
		'buttons'=>array('verify'=>array('isverify'=>'membermap.membermap_is_verify','options'=>['confirm_php'=>t('epmms',"'将要扣除' . webapp()->id=='180501'?'6800':\$data->membermap->membermap_money . params('money_unit') . ',你确定要审核吗？'")])),
		'header'=>t('epmms','审核')
	));
if($isVerifyType==0)
{
	unset($columns['is_agent']);
	unset($columns['verify_date']);
	unset($columns['award_total']);
	unset($columns['award_period']);
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