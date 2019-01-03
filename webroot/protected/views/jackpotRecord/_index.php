<?php
/* @var $this JackpotRecordController */
/* @var $model JackpotRecord */

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
//	array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create')),
);
$type = [1=>'首单奖',2=>'幸运奖',3=>'尾单奖'];

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('jackpot-record-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo t('epmms','管理') . t('epmms',$model->modelName)?></h1>

<p>
<?php echo t('epmms','你可以输入一个比较运算符 ');?>(<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
<?php echo t('epmms','或者');?> <b>=</b>) <?php echo t('epmms','在每个搜索值的前面来指定怎么匹配搜索结果。');?>
</p>

<input class="search-button" src="/themes/classic/images/sou_1.png" type="image" name="yt0" /><div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#jackpot-record-grid'));
$columns=array(
array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),
	array('class'=>'RelationDataColumn','name'=>'jackpot.memberinfo_account'),
	'jackpot_money',
	'jackpot_type'=>[
	    'name' =>'jackpot_type',
        'value'=>function($model) use($type){
            return $type[$model->jackpot_type];
        },
        'filter'=> $type,
    ],
	'jackpot_start_time'=>[
        'name'   =>'jackpot_start_time',
        'value'  => function($model){
            return date('Y-m-d H:i:s',$model->jackpot_start_time);
        }
    ],
	'jackpot_end_time'=>[
        'name'   =>'jackpot_end_time',
        'value'  => function($model){
            return date('Y-m-d H:i:s',$model->jackpot_end_time);
        }
    ],
	array(
	'class'=>'ButtonColumn',
	'template'=>'{view}',
	),
//	array(
//	'class'=>'ButtonColumn',
//	'template'=>'{update}',
//	),
	array(
	'class'=>'ButtonColumn',
	'template'=>'{delete}',
	)
);
switch($selTab)
{
	case 0:
	//处理每种标签的特殊情况
	break;
}
$this->widget('GridView', array(
	'id'=>'jackpot-record-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>$columns,
)); ?>
