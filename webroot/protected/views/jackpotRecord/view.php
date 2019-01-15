<?php
/* @var $this JackpotRecordController */
/* @var $model JackpotRecord */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	$model->showName,
);
$type = [1=>'首单奖',2=>'幸运奖',3=>'尾单奖'];
$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
//	array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create')),
//	array('label'=>t('epmms','修改') . t('epmms',$model->modelName), 'url'=>array('update', 'id'=>$model->jackpot_id)),
	array('label'=>t('epmms','删除') . t('epmms',$model->modelName), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model->jackpot_id),'confirm'=>t('epmms','你确定要删除吗?'))),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo t('epmms','查看') . t('epmms',$model->modelName) . ' #' . $model->showName; ?></h1>
<div class="epview">
<?php $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay'=>'',	
	'attributes'=>array(
		array('name'=>'jackpot.memberinfo_account','label'=>$model->getAttributeLabel('jackpot_member_id')),
		'jackpot_number',
		'jackpot_money',
		'jackpot_type'=>[
		    'name'=>'jackpot_type',
            'value'=>$type[$model->jackpot_type],
        ],
		'jackpot_start_time'=>[
            'name'=>'jackpot_start_time',
            'value'=>date('Y-m-d H:i:s',$model->jackpot_start_time),
        ],
		'jackpot_end_time'=>[
            'name'=>'jackpot_end_time',
            'value'=>date('Y-m-d H:i:s',$model->jackpot_end_time),
        ],
	),
)); ?>
</div>
