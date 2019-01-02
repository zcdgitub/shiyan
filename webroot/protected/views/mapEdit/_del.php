<?php
/* @var $this MapEditController */
/* @var $model MapEdit */
/* @var $form CActiveForm */
?>
<h1><?php echo t('epmms','删除点位')?></h1>
<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'map-edit-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,  // 这个是设置是否把提交按钮也做成客户端验证。
	),
	'enableAjaxValidation'=>true,
	));
$model->map_edit_src_member_id=Memberinfo::id2name($model->map_edit_src_member_id);
?>

	<p class="note"><?php echo t('epmms','带');?> <span class="required">*</span> <?php echo t('epmms','的字段是必填项。');?></p>

	<?php echo $form->errorSummary($model); ?>
<table class="form">

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'map_edit_src_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'map_edit_src_member_id'); ?>
		</td>
		<td class="hint">要删除的会员，接点位置下面不能有人，推荐的人会上移一级，这里只是从图谱中删除，会员仍然可以登录，填写登录账号</td>
		<td class="error">
			<?php echo $form->error($model,'map_edit_src_member_id',array(),true); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'map_edit_info'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'map_edit_info'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'map_edit_info',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->