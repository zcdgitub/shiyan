<?php
/* @var $this StockTrendController */
/* @var $model StockTrend */

$this->breadcrumbs=array(
	t('epmms',$model->modelName),
);
?>
<?php
$tabType=[0=>'电子股趋势',1=>'电子股记录'];
$tabParameters=[];
foreach($tabType as $tab=>$title)
{
	if($selTab==$tab)
	{
		$tabParameters[$tab]=['title'=>$title,'view'=>$tab==0?'_chart':'_index','data'=>['model'=>$model,'selTab'=>$tab]];
	}
	else
	{
		$tabParameters[$tab]=['title'=>$title,'url'=>$this->createUrl('index',['selTab'=>$tab])];
	}
}
$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>$selTab));
?>
