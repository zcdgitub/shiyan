<?php
/* @var $this ConfigBackupController */
/* @var $model ConfigBackup */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('update'),
	t('epmms','修改'),
);
?>

<?php
$tabType=[0=>t('epmms','备份配置')];
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