<?php
/* @var $this AgentController */
/* @var $model Agent */

$this->breadcrumbs=array(
	t('epmms',$model->modelName),
);

$tabType=[0=>t('epmms','未审核'),1=>t('epmms','已审核')];
$tabParameters=[];
foreach($tabType as $tab=>$title)
{
	if($selTab==$tab)
	{
		$tabParameters[$tab]=['title'=>$title,'view'=>'_index','data'=>['model'=>$model,'selTab'=>$tab]];
	}
	else
	{
		$tabParameters[$tab]=['title'=>$title,'url'=>$this->createUrl('index',['selTab'=>$tab])];
	}
}
$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>$selTab));
?>
