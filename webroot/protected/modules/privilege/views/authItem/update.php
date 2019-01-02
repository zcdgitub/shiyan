<?php $this->breadcrumbs = array(
	t('epmms','权限'),
	t('epmms','角色修改')
); ?>

<?php $this->renderPartial('_form', array('model'=>$formModel,'update'=>true)); ?>
