<?php
/* @var $this ConfigWithdrawalsController */
/* @var $model ConfigWithdrawals */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	t('epmms','修改'),
);
	$tabType=[0=>'提现配置'];
	$tabParameters=[];
	foreach($tabType as $tab=>$title)
	{
		if($selTab==$tab)
		{
		$tabParameters[$tab]=['title'=>$title,'view'=>'_form','data'=>['model'=>$model,'selTab'=>$tab]];
		}
		else
		{
			$tabParameters[$tab]=['title'=>$title,'url'=>$this->createUrl('index',['selTab'=>$tab])];
		}
	}
	$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>$selTab));
?>
