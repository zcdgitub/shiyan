<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

<?php
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label='$model->modelName';
echo "\$this->breadcrumbs=array(
	t('epmms',$label)=>array('index'),
	\$model->showName,
);\n";
?>

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create')),
	array('label'=>t('epmms','修改') . t('epmms',$model->modelName), 'url'=>array('update', 'id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),
	array('label'=>t('epmms','删除') . t('epmms',$model->modelName), 'url'=>'#', 'linkOptions'=>array('submit'=>array('delete','id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>),'confirm'=>t('epmms','你确定要删除吗?'))),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo "<?php echo t('epmms','查看') . t('epmms',\$model->modelName) . ' #' . \$model->showName; ?>";?></h1>
<div class="epview">
<?php echo "<?php"; ?> $this->widget('zii.widgets.CDetailView', array(
	'data'=>$model,
	'nullDisplay'=>'',	
	'attributes'=>array(
<?php
foreach($this->tableSchema->columns as $column)
{
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
		if(!empty($relation) && $relation[0]==Model::BELONGS_TO)
		{
			$foreignModel=Model::model($relation[1]);
			echo "\t\tarray('name'=>'{$relationName}.{$foreignModel->nameColumn}','label'=>\$model->getAttributeLabel('{$column->name}')),\n";
		}
		continue;
	}
	if($column->isPrimaryKey)
		continue;
	if(!strcasecmp($cname,'password')||!strcasecmp($cname,'password2')||!strcasecmp($cname,'password3'))
		continue;
	if(!strcasecmp($cname,'is_enable'))
		echo "\t\tarray('name'=>'{$column->name}','type'=>'enable'),\n";
	else if(!strcasecmp($cname,'is_preset'))
		echo "\t\tarray('name'=>'{$column->name}','type'=>'preset'),\n";
	else if(!strcasecmp($cname,'sex'))
		echo "\t\tarray('name'=>'{$column->name}','type'=>'sex'),\n";
	else if(!strcasecmp($cname,'is_verify'))
		echo "\t\tarray('name'=>'{$column->name}','type'=>'verify'),\n";
	else if($column->dbType=='timestamp'||$column->dbType=='datetime')
		echo "\t\tarray('name'=>'{$column->name}','type'=>'datetime'),\n";
	else if($column->dbType=='date')
		echo "\t\tarray('name'=>'{$column->name}','type'=>'date'),\n";	
	else
		echo "\t\t'" . $column->name."',\n";
}
?>
	),
)); ?>
</div>
