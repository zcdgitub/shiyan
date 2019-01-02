<?php $this->breadcrumbs = array(
	'权限',
	t('epmms', '权限角色'),
);
$this->menu=array(
	array('label'=>t('epmms','添加权限角色') , 'url'=>array('authItem/create', 'type'=>CAuthItem::TYPE_ROLE)),
);
?>

<div id="roles">

	<h1><?php echo Rights::t('epmms', '管理权限角色'); ?></h1>

	<?php $this->widget('GridView', array(
	    'dataProvider'=>$dataProvider,
	    'emptyText'=>Rights::t('core', '没有角色'),
	    'columns'=>array(
    		array(
    			'name'=>'name',
    			'header'=>t('epmms', '名称'),
    			'htmlOptions'=>array('class'=>'name-column'),
    		),
    		array(
    			'name'=>'description',
    			'header'=>t('epmms', '描述'),
    			'type'=>'raw',
    			'htmlOptions'=>array('class'=>'description-column'),
    		),
			array(
				'header'=>'&nbsp;',
				'type'=>'raw',
				'htmlOptions'=>array('class'=>'actions-column'),
				'value'=>'$data->getUpdateRoleLink() ."&nbsp;&nbsp;&nbsp;&nbsp;" . $data->getDeleteRoleLink()',
			),
	    )
	)); ?>

	<p class="info"><?php echo Rights::t('core', 'Values within square brackets tell how many children each item has.'); ?></p>

</div>