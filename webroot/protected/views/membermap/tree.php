<?php
/* @var $this ConfigSmtpController */
/* @var $model ConfigSmtp */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	t('epmms',$dataType=='parent'?'接点图谱':'推荐图谱'),
);

$tabParameters=array
(
		'tab1'=>array(
			'title'=>t('epmms','组织结构图'),
			'url'=>$this->createUrl('membermap/orgMap',['dataType'=>$dataType])
		),
		'tab2'=>array(
			'title'=>t('epmms','树状图'),
			'view'=>'_tree',
			'data'=>array('model'=>$model,'json_tree'=>$json_tree,'dataType'=>$dataType)
		),
);
if($dataType=='parent')
{
	if(!webapp()->user->checkAccess('Membermap.OrgMap'))
	{
		unset($tabParameters['tab1']);
	}
}
else
{
	if(!webapp()->user->checkAccess('Membermap.OrgMapRecommend'))
	{
		unset($tabParameters['tab1']);
	}
}
$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>'tab2'));
?>