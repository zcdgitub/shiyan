<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

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
	$.fn.yiiGridView.update('<?php echo $this->class2id($this->modelClass); ?>-grid', {
		data: $(this).serialize()
	});
	return false;
});
");
?>

<h1><?php echo "<?php"; ?> echo t('epmms','管理') . t('epmms',$model->modelName)?></h1>

<p>
<?php echo "<?php"; ?> echo t('epmms','你可以输入一个比较运算符 ');?>(<b>&lt;</b>, <b>&lt;=</b>, <b>&gt;</b>, <b>&gt;=</b>, <b>&lt;&gt;</b>
<?php echo "<?php"; ?> echo t('epmms','或者');?> <b>=</b>) <?php echo "<?php"; ?> echo t('epmms','在每个搜索值的前面来指定怎么匹配搜索结果。');?>
</p>

<?php echo CHtml::imageButton(themeBaseUrl() . '/images/sou_1.png',['class'=>'search-button']); ?>
<div class="search-form" style="display:none">
<?php echo "<?php \$this->renderPartial('_search',array(
	'model'=>\$model,
)); ?>\n"; ?>
</div><!-- search-form -->

<?php
echo "<?php\n";
?>
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'#<?php echo $this->class2id($this->modelClass); ?>-grid'));
$columns=array(
array('class'=>'DataColumn','value'=>'$row+1','header'=>t('epmms','序号'),'htmlOptions' => array('style'=>'width:40px')),
<?php
$count=0;
foreach($this->tableSchema->columns as $column)
{
	if(++$count==15)
		echo "\t/*\n";
	$cname=$this->getShortColumnName($column->name);
	if($column->isForeignKey)
	{
		$model=Model::model($this->modelClass);
		$relations=$model->relations();
		foreach($relations as $relationName=>$relation)
		{
			if($relation[2]==$column->name)
			{
				break;
			}
		}
		if(!empty($relation))
		{
			$foreignModel=Model::model($relation[1]);
			echo "\tarray('class'=>'RelationDataColumn','name'=>'{$relationName}.{$foreignModel->nameColumn}'),\n";
		}
		continue;
	}
	if($column->isPrimaryKey)
		continue;
	if(!strcasecmp($cname,'password') || !strcasecmp($cname,'password2') || !strcasecmp($cname,'password3'))
		continue;
	if(!strcasecmp($cname,'mod_date'))
		continue;
	if(strpos($column->name,'question')!==false)
		continue;
	if(strpos($column->name,'answer')!==false)
		continue;
	if(!strcasecmp($cname,'is_enable'))
		echo "\tarray('class'=>'DataColumn','name'=>'{$column->name}','type'=>'enable','filter'=>array(0=>'禁用',1=>'启用')),\n";
	if(!strcasecmp($cname,'is_verify'))
		echo "\tarray('class'=>'DataColumn','name'=>'{$column->name}','type'=>'verify','filter'=>array(0=>'未审核',1=>'已审核')),\n";
	else if(!strcasecmp($cname,'is_preset'))
		echo "\tarray('class'=>'DataColumn','name'=>'{$column->name}','type'=>'preset','filter'=>array(0=>'自定义',1=>'预置')),\n";
	else if(!strcasecmp($cname,'sex'))
		echo "\tarray('class'=>'DataColumn','name'=>'{$column->name}','type'=>'sex','filter'=>array(0=>'男',1=>'女',2=>'保密')),\n";
	else if($column->dbType=='timestamp'||$column->dbType=='datetime')
		echo "\tarray('class'=>'DataColumn','name'=>'{$column->name}','type'=>'datetime'),\n";
	else if($column->dbType=='date')
		echo "\tarray('class'=>'DataColumn','name'=>'{$column->name}','type'=>'date'),\n";
	else
		echo "\t'" . $column->name."',\n";
}
if($count>=15)
	echo "\t*/\n";
?>
	array(
	'class'=>'ButtonColumn',
	'template'=>'{view}',
	),
	array(
	'class'=>'ButtonColumn',
	'template'=>'{update}',
	),
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
	'id'=>'<?php echo $this->class2id($this->modelClass); ?>-grid',
	'dataProvider'=>$model->search(),
	'filter'=>$model,
	'ajaxUpdate'=>false,
	'columns'=>$columns,
)); ?>
