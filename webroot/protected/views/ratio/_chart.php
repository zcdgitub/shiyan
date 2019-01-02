<?php
/* @var $this RatioController */
/* @var $model Ratio */

$this->menu=array(
	array('label'=>t('epmms','详细') . t('epmms',$model->modelName), 'url'=>array('index','datetype'=>'period','selTab'=>$selTab)),
	array('label'=>t('epmms','每日') . t('epmms',$model->modelName), 'url'=>array('index','datetype'=>'day','selTab'=>$selTab)),
	array('label'=>t('epmms','每周') . t('epmms',$model->modelName), 'url'=>array('index','datetype'=>'week','selTab'=>$selTab)),
	array('label'=>t('epmms','每月') . t('epmms',$model->modelName), 'url'=>array('index','datetype'=>'month','selTab'=>$selTab)),
	array('label'=>t('epmms','每年') . t('epmms',$model->modelName), 'url'=>array('index','datetype'=>'year','selTab'=>$selTab)),
);

?>

<?php
$data=[];
foreach($model->search()->data as $item)
{
	$data[]=[strtotime($item->ratio_add_date)*1000,(float)$item->ratio_value];
}
$this->Widget('ext.highcharts.HighstockWidget', array(
	'options'=>array(
		'credits' => array('enabled' => false),
		'title' => array('text' => '拨出比趋势'),
		'xAxis' => array(
			'type'=>'datetime',
			'title' => array('text' => '时间'),
			'labels'=>['format'=>'{value:%Y-%m-%d %H:%M}']
		),
		'tooltip' =>[
		   'formatter'=>new CJavaScriptExpression(
				   "function(){
						return '时间:' + Highcharts.dateFormat('%Y-%m-%d %H:%M:%S',this.x) +' 拨出比:' + Highcharts.numberFormat(this.y*100,2) +'%';
					}"
			   )
		],
		'yAxis' => array(
			'title' => array('text' => '拨出比'),
			'labels'=>['formatter'=>new CJavaScriptExpression(
					"function(){
							return Highcharts.numberFormat(this.value*100,2) +'%';
						}"
				)]
		),
		'series' => array([
			'name'=>'拨出比',
			'data' =>$data
		])
	)
));
?>