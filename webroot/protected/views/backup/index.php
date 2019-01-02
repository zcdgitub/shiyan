<?php
/* @var $this BackupController */
/* @var $model Backup */

$this->breadcrumbs=array(
	t('epmms',$model->modelName),
);
?>
<?php
$tabType=[0=>t('epmms','自定义备份'),1=>t('epmms','系统备份')];
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
