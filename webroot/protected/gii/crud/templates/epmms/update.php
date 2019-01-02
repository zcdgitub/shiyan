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
	\$model->showName=>array('view','id'=>\$model->{$this->tableSchema->primaryKey}),
	t('epmms','修改'),
);\n";
?>

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','添加') . t('epmms',$model->modelName), 'url'=>array('create')),
	array('label'=>t('epmms','查看') . t('epmms',$model->modelName), 'url'=>array('view', 'id'=>$model-><?php echo $this->tableSchema->primaryKey; ?>)),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo "<?php echo t('epmms','修改') . t('epmms',\$model->modelName) . ' #' . \$model->showName; ?>"; ?></h1>

<?php echo "<?php echo \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>