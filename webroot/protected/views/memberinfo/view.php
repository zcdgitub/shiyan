<style type="text/css">
	.memberinfo-table tr td
	{
		vertical-align: top;
	}

</style>
<?php
/* @var $this MemberinfoController */
/* @var $model Memberinfo */

$this->breadcrumbs=array(
	t('epmms',$model->modelName),
	$model->memberinfo_account,
);
$this->menu=array(
	'update'=>array('label'=>t('epmms','修改') . t('epmms',$model->modelName), 'url'=>array('update', 'id'=>$model->memberinfo_id)),
	array('label'=>t('epmms','注册') . t('epmms',$model->modelName), 'url'=>array('create'))
);
if($model->memberinfo_id!=user()->id)
{
	if($model->membermap->membermap_agent_id!=user()->id || $model->membermap->membermap_is_verify==1)
	{
		unset($this->menu['update']);
	}
}
if($model->memberinfo_is_verify==0)
{
	$this->menu[]=array('label'=>t('epmms','审核') . t('epmms',$model->modelName), 'url'=>array('verify','id'=>$model->memberinfo_id));
}
?>

<h1><?php echo t('epmms','查看') . t('epmms',$model->modelName) . $model->memberinfo_account; ?></h1>
<div class="epview">
	<table class="memberinfo-table">
		<tr>
			<td>
				会员信息
<?php
$item_model=MemberinfoItem::model();
$this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'attributes'=>array(
		['name'=>'membermap.membermapParent.showName','label'=>$model->membermap->getAttributeLabel('membermap_parent_id'),
			'visible'=>$item_model->getViewItem('membermap_parent_id')],
		['name'=>'membermap.membermapRecommend.showName','label'=>$model->membermap->getAttributeLabel('membermap_recommend_id'),
			'visible'=>$item_model->getViewItem('membermap_recommend_id')],
		['name'=>'membermap.membermapAgent.showName','label'=>$model->membermap->getAttributeLabel('membermap_agent_id'),
			'visible'=>$item_model->getViewItem('membermap_agent_id')],
		['name'=>'membermap.membertype.showName','label'=>$model->membermap->getAttributeLabel('membermap_membertype_level'),
			'visible'=>$item_model->getViewItem('membermap_membertype_level')],
		['name'=>'membermap.membermap_order','type'=>'mapOrder','visible'=>$item_model->getViewItem('membermap_order')],
		['name'=>'membermap.memberlevel.showName','label'=>$model->membermap->getAttributeLabel('membermap_level'),
			'visible'=>$item_model->getViewItem('membermap_level')],
		['name'=>'memberinfo_account','visible'=>$item_model->getViewItem('memberinfo_account')],
		['name'=>'memberinfo_nickname','visible'=>$item_model->getViewItem('memberinfo_nickname')],
		['name'=>'memberinfo_name','visible'=>$item_model->getViewItem('memberinfo_name')],
		['name'=>'memberinfo_is_agent','type'=>'agent','visible'=>$item_model->getViewItem('memberinfo_is_agent')],
		['name'=>'memberinfo_email','visible'=>$item_model->getViewItem('memberinfo_eamil')],
		['name'=>'memberinfo_mobi','visible'=>$item_model->getViewItem('memberinfo_mobi')],
		['name'=>'memberinfo_phone','visible'=>$item_model->getViewItem('memberinfo_phone')],
		['name'=>'memberinfo_qq','visible'=>$item_model->getViewItem('memberinfo_qq')],
		['name'=>'memberinfo_msn','visible'=>$item_model->getViewItem('memberinfo_msn')],
		['name'=>'memberinfo_sex','type'=>'sex','visible'=>$item_model->getViewItem('memberinfo_sex')],
		['name'=>'memberinfo_idcard_type','visible'=>$item_model->getViewItem('memberinfo_idcard_type')],
		['name'=>'memberinfo_idcard','visible'=>$item_model->getViewItem('memberinfo_idcard')],
		['name'=>'memberinfo_birthday','visible'=>$item_model->getViewItem('memberinfo_birthday')],
		['name'=>'memberinfoBank.bank_name','label'=>$model->getAttributeLabel('memberinfo_bank_id'),'visible'=>$item_model->getViewItem('memberinfo_bank_id')],
		['name'=>'memberinfo_bank_name','visible'=>$item_model->getViewItem('memberinfo_bank_name')],
		['name'=>'memberinfo_bank_account','visible'=>$item_model->getViewItem('memberinfo_bank_account')],
		['name'=>'memberinfo_bank_provience','visible'=>$item_model->getViewItem('memberinfo_bank_provience')],
		['name'=>'memberinfo_bank_area','visible'=>$item_model->getViewItem('memberinfo_bank_area')],
		['name'=>'memberinfo_bank_branch','visible'=>$item_model->getViewItem('memberinfo_bank_branch')],
		['name'=>'memberinfo_postoffice','visible'=>$item_model->getViewItem('memberinfo_postoffice')],
		['name'=>'memberinfo_address_provience','visible'=>$item_model->getViewItem('memberinfo_address_provience')],
		['name'=>'memberinfo_address_area','visible'=>$item_model->getViewItem('memberinfo_address_area')],
		['name'=>'memberinfo_address_county','visible'=>$item_model->getViewItem('memberinfo_address_county')],
		['name'=>'memberinfo_address_detail','visible'=>$item_model->getViewItem('memberinfo_address_detail')],
		['name'=>'memberinfo_zipcode','visible'=>$item_model->getViewItem('memberinfo_zipcode')],
		['name'=>'memberinfo_memo','visible'=>$item_model->getViewItem('memberinfo_memo')],
		['name'=>'memberinfo_is_enable','type'=>'yesno','visible'=>$item_model->getViewItem('memberinfo_is_enable')],
		['name'=>'membermap.membermap_is_empty','type'=>'yesno','visible'=>$item_model->getViewItem('membermap_is_empty')],
		['name'=>'memberinfo_register_ip','visible'=>$item_model->getViewItem('memberinfo_register_ip')],
		['name'=>'memberinfo_last_ip','visible'=>$item_model->getViewItem('memberinfo_last_ip')],
		['name'=>'memberinfo_last_date','visible'=>$item_model->getViewItem('memberinfo_last_date')],
		['name'=>'memberinfo_add_date','visible'=>$item_model->getViewItem('memberinfo_add_date')],
		['name'=>'membermap.membermap_verify_date','visible'=>$item_model->getViewItem('membermap_verify_date')],
		//array('label'=>'最后一期奖金','value'=>@AwardPeriodSum::model()->find(["condition"=>"award_period_sum_memberinfo_id=:id","params"=>["id"=>$model->memberinfo_id],"order"=>"award_period_sum_period","limit"=>1])->award_period_sum_currency),
		//array('label'=>'总奖金','value'=>@AwardTotalSum::model()->findByAttributes(["award_total_sum_memberinfo_id"=>$model->memberinfo_id])->award_total_sum_currency),
	),
));
?>
			</td>
		</tr></table>
</div>
