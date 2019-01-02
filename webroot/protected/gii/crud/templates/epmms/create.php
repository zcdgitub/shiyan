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
$label='$model->modelName';
echo "\$this->breadcrumbs=array(
	t('epmms',$label)=>array('index'),
	t('epmms','添加'),
);\n";
?>

$this->menu=array(
	array('label'=>t('epmms','浏览') . t('epmms',$model->modelName), 'url'=>array('list')),
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo "<?php echo t('epmms','添加') . t('epmms',\$model->modelName)";?>?></h1>

<?php echo "<?php echo \$this->renderPartial('_form', array('model'=>\$model)); ?>"; ?>
