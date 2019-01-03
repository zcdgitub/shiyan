<?php
/* @var $this JackpotRecordController */
/* @var $model JackpotRecord */

$this->breadcrumbs=array(
	t('epmms',$model->modelName),
);
?>
<?php
$tabType=[0=>'竞买抽奖奖池记录'];
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
