<?php
/* @var $this DealController */
/* @var $model Deal */

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create')),
);

Yii::app()->clientScript->registerScript('search', "
$('.search-button').click(function(){
	$('.search-form').toggle();
	return false;
});
$('.search-form form').submit(function(){
	$.fn.yiiGridView.update('deal-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo t('epmms','管理') . t('epmms',$model->modelName)?></h1>

<p style="display:none;">
<?php echo t('epmms','你可以输入一个比较运算符 ');?>(<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
<?php echo t('epmms','或者');?> <b>=</b>) <?php echo t('epmms','在每个搜索值的前面来指定怎么匹配搜索结果。');?>
</p>

<input class="search-button" src="/themes/member/images/sou_1.png" type="image" name="yt0" /><div class="search-form" style="display:none">
<?php $this->renderPartial('_search',array(
	'model'=>$model,
)); ?>
</div><!-- search-form -->

<?php
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#deal-grid'));
$columns=array(
array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),
	array('class'=>'DataColumn','name'=>'dealSale.saleMember.memberinfo_account','header'=>'买入者'),
	array('class'=>'DataColumn','name'=>'dealBuy.buyMember.memberinfo_account','header'=>'卖出者'),
	'deal_currency',
	'deal_date',
	['name'=>'deal_status','type'=>'dealStatus'],
    ['name'=>'deal_type','type'=>'tradeType'],
	array(
	'class'=>'ButtonColumn',
	'template'=>'{view}',
	),
	array(
	'class'=>'ButtonColumn',
	'template'=>'{update}',
	),
	'del'=>array(
	'class'=>'ButtonColumn',
	'template'=>'{delete}',
	)
);
switch($selTab)
{
	case 0:
	//处理每种标签的特殊情况
	break;
    case 1:
        $columns['verify']=	array(
            'class'=>'ButtonColumn',
            'template'=>'{verify}',
            'buttons'=>array('verify'=>array()),
        );
        //unset($columns['del']);
        break;
    case 2:
        unset($columns['del']);
        break;
}
$this->widget('GridView', array(
	'id'=>'deal-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>$columns,
)); ?>
