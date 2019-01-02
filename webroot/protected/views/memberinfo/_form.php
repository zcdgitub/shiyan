<?php
/* @var $this MemberinfoController */
/* @var $model Memberinfo */
/* @var $form CActiveForm */
?>

<div class="form" >
	<div class="note"><?php echo t('epmms','带{ss}的字段是必填项。',['{ss}'=>'<span class="required">*</span>']);?></div>
<?php echo $form->render();?>
</div><!-- form -->