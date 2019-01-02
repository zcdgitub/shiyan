<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $dataProvider CActiveDataProvider */

<?php
$label='$dataProvider->model->modelName';
echo "\$this->breadcrumbs=array(
	t('epmms',$label),
	t('epmms','浏览'),
);\n";
?>

$this->menu=array(
	array('label'=>t('epmms','添加') . t('epmms',$dataProvider->model->modelName), 'url'=>array('create')),
	array('label'=>t('epmms','管理') . t('epmms',$dataProvider->model->modelName), 'url'=>array('index')),
);
?>

<h1><?php echo "<?php echo "; ?>t('epmms',$dataProvider->model->modelName)?></h1>

<?php echo "<?php"; ?> $this->widget('zii.widgets.CListView', array(
	'dataProvider'=>$dataProvider,
	'itemView'=>'_view',
)); ?>
