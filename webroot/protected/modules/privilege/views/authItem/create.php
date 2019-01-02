<?php $this->breadcrumbs = array(
	'权限管理',
	t('epmms', '添加权限角色'),
); ?>

	<h1><?php echo t('epmms', '添加权限角色'); ?></h1>

	<?php $this->renderPartial('_form', array('model'=>$formModel)); ?>
