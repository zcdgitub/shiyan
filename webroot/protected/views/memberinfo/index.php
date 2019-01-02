<?php
/* @var $this MemberinfoController */
/* @var $model Memberinfo */


$this->breadcrumbs=array(
	t('epmms',$model->modelName),
);

$this->menu=array(
);
?>
<?php
$transferType=[0=>t('epmms','未审核'),1=>t('epmms','已审核')];
$tabParameters=[];
foreach($transferType as $isVerify=>$title)
{
	if($isVerifyType==$isVerify)
	{
		$tabParameters[$isVerify]=['title'=>$title,'view'=>'_index','data'=>['model'=>$model,'isVerifyType'=>$isVerifyType,'isAgent'=>$isAgent]];
	
	}
	else
	{
		$tabParameters[$isVerify]=['title'=>$title,'url'=>$this->createUrl($isAgent==true?'memberinfo/indexAgent':'memberinfo/index',['isVerifyType'=>$isVerify,'isAgent'=>$isAgent])];
	}
}

$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>$isVerifyType));
?>
