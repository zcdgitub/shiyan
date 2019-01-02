<?php
/* @var $this AgentController */
/* @var $model Agent */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	t('epmms','申请'),
);
?>

<h1><?php echo t('epmms','申请') . t('epmms',$model->modelName)?></h1>
<?php if(user()->isAdmin()):?>
<?=t('epmms','您是')?>“<?php echo user()->roleName?>”<?=t('epmms','身份，不能申请成为代理中心')?>
<?php elseif($agent=Model::model('Agent')->find('agent_memberinfo_id=:id',[':id'=>user()->id])):?>
	<?php if($agent->agent_is_verify==1):?>
	<?=t('epmms','您已经是代理中心，请不要重复申请')?>
	<?php else:?>
	<?=t('epmms','你已经申请代理中心，请等待申核')?>
	<?php endif;?>
<?php else:?>
<?php echo $this->renderPartial('_form', array('model'=>$model)); ?>
<?php endif;?>