<?php
/* @var $this JoblogController */
/* @var $model Joblog */

$this->breadcrumbs=array(
	t('epmms',$model->modelName),
);
?>
<?php
$tabType=[0=>'定时任务',1=>'运行历史'];
$tabParameters=[0=>['url'=>$this->createUrl('job/index')],1=>[]];
foreach($tabType as $tab=>$title)
{
	if($selTab==$tab)
	{
		$tabParameters[$tab]=['title'=>$title,'view'=>'_index','data'=>['model'=>$model,'selTab'=>$tab]];
	}
	else
	{
		$tabParameters[$tab]=['title'=>$title,'url'=>$tabParameters[$tab]['url']];
	}
}
$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>$selTab));
?>
