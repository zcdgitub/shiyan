<?php
/* @var $this AwardConfigController */
/* @var $model AwardConfig */
if($selTab===-1)
{
	$this->breadcrumbs=array(
		t('epmms',$model->modelName),
	);
}
else
{
	$this->breadcrumbs=array(
		$config[$selTab]['title'],
	);
}
?>
<?php
$tabType=[-1=>t('epmms','基本奖金配置')];
foreach($config as $configIndex=>$awardConfig)
{
	$tabType[$configIndex]=$config[$configIndex]['title'];

}


$tabParameters=[];
foreach($tabType as $tab=>$title)
{
	
	if($selTab==$tab)
	{
		if($selTab===-1){
			$tabParameters[$tab]=['title'=>$title,'view'=>'_index','data'=>['model'=>$model,'selTab'=>$tab]];
				
			
		}

		else{

			$tabParameters[$tab]=['title'=>$title,'view'=>'_award','data'=>['config'=>$config,'selTab'=>$tab]];
			//var_dump($tabParameters[$tab]);
			
		}
	}
	else
	{
		$tabParameters[$tab]=['title'=>$title,'url'=>$this->createUrl('index',['selTab'=>$tab])];
		
		
	}
}

$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>$selTab));
?>
