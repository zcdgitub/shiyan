<?php
/* @var $this GroupMapController */
/* @var $model GroupMap */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	$model->showName,
);
?>

<h1><?php echo t('epmms','查看') . t('epmms',$model->modelName) . ' #' . $model->showName; ?></h1>
<div class="epview">
<?php
if($model->groupmap_order>=$my_group)
{
	$this->widget('zii.widgets.CDetailView', array(
		'data' => $model,
		'nullDisplay' => '',
		'attributes' => array(
			array('name' => 'groupmapmemberinfo.memberinfo_account', 'label' => $model->getAttributeLabel('groupmap_member_id')),
			'groupmap_order',
			'groupmap_verify_date',
			//'groupmap_name',
			//['name'=>'groupmap_is_award','value'=>$model->groupmap_is_award==1?'已出局':'未出局']
		),
	));
}
?>
</div>
