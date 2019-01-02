<?php
/* @var $this ConfigSmtpController */
/* @var $model ConfigSmtp */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('orgMap'),
	t('epmms',$dataType=='parent'?'接点图谱':'推荐图谱'),
);

$tabParameters=array
(
		'tab1'=>array(
				'title'=>t('epmms','组织结构图'),
				'view'=>'_orgmap',
				'data'=>array('model'=>$model,'json_tree'=>$json_tree,'levels'=>$levels,'orientation'=>$orientation,'dataType'=>$dataType)
		),
		'tab2'=>array(
				'title'=>t('epmms','树状图'),
				'url'=>$this->createUrl('membermap/treeRecommend',['dataType'=>$dataType])
		),
);
if($dataType=='parent')
{

	if(!webapp()->user->checkAccess('Membermap.Tree'))
	{
		unset($tabParameters['tab2']);
	}
}
else
{
	if(!webapp()->user->checkAccess('Membermap.TreeRecommend'))
	{
		unset($tabParameters['tab2']);
	}
}
$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>'tab1'));
?>