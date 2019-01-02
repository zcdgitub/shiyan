<?php
/* @var $this MessagesController */
/* @var $model Messages */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	$model->showName,
);

$this->menu=array(
);
if($model->messages_is_read!=1)
{
	$model->messages_is_read=1;
	$model->saveAttributes(['messages_is_read']);
}
?>

<div class="epview">
<h1><?=$model->messages_title?></h1>
<p style="text-align: right"><?=t('epmms','发件人')?>:<?=is_null($model->messagesSenderMember)?t('epmms',"系统"):$model->messagesSenderMember->memberinfo_account?></p>
<p>
	<?=$model->messages_content?>
</p>
</div>
