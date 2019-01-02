<?php
/* @var $this AwardConfigController */
/* @var $model AwardConfig */

$this->breadcrumbs=array(
	t('epmms','奖金封顶'),
	$config[$selTab]['title'],
);

$tabType=[];
foreach($config as $configIndex=>$awardConfig)
{
	$tabType[$configIndex]=$config[$configIndex]['title'];
}
$tabParameters=[];
foreach($tabType as $tab=>$title)
{
	if($selTab==$tab)
	{
		$tabParameters[$tab]=['title'=>$title,'view'=>'_award','data'=>['config'=>$config[$tab],'selTab'=>$tab]];
	}
	else
	{
		$tabParameters[$tab]=['title'=>$title,'url'=>$this->createUrl('index',['selTab'=>$tab])];
	}
}
$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>$selTab));
?>
