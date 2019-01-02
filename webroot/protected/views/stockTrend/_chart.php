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
	$data[]=[strtotime($item->stock_trend_date)*1000,(float)$item->stock_trend_value];
}
$this->Widget('ext.highcharts.HighstockWidget', array(
	'options'=>array(
		'credits' => array('enabled' => false),
		'title' => array('text' => '电子股趋势'),
		'xAxis' => array(
			'type'=>'datetime',
			'title' => array('text' => '时间'),
			'labels'=>['format'=>'{value:%Y-%m-%d %H:%M}']
		),
		'tooltip' =>[
		   'formatter'=>new CJavaScriptExpression(
				   "function(){
						return '时间:' + Highcharts.dateFormat('%Y-%m-%d %H:%M:%S',this.x) +' 电子股:' + this.y + '股';
					}"
			   )
		],
		'yAxis' => array(
			'title' => array('text' => '电子股'),
			'labels'=>['formatter'=>new CJavaScriptExpression(
					"function(){
							return this.value + '股';
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