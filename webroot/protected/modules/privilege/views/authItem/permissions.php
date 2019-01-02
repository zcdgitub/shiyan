<?php $this->breadcrumbs = array(
	'权限管理',
	t('epmms', '权限配置'),
);
$this->menu=array(
	array('label'=>t('epmms','添加权限角色') , 'url'=>array('authItem/create', 'type'=>CAuthItem::TYPE_ROLE)),
);
?>

<div id="permissions">

	<h1><?php echo t('epmms', '权限配置'); ?></h1>

	<p>
		<?php echo t('epmms', '查看和管理每个角色的权限'); ?><br />
	</p>

	<?php $this->widget('GridView', array(
		'dataProvider'=>$dataProvider,
		'emptyText'=>t('epmms', '没有权限项'),
		'htmlOptions'=>array('class'=>'grid-view permission-table'),
		'columns'=>$columns,
	)); ?>

	<p class="info">*) <?php echo t('epmms', 'Hover to see from where the permission is inherited.'); ?></p>

	<script type="text/javascript">

		/**
		* Attach the tooltip to the inherited items.
		*/
		jQuery('.inherited-item').rightsTooltip({
			title:'<?php echo t('epmms', '来源'); ?>: '
		});

		/**
		* Hover functionality for rights' tables.
		*/
		$('#rights tbody tr').hover(function() {
			$(this).addClass('hover'); // On mouse over
		}, function() {
			$(this).removeClass('hover'); // On mouse out
		});

	</script>

</div>
