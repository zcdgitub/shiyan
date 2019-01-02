<?php
/* @var $this GameChargeController */
/* @var $model GameCharge */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'game-charge-form',
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
?>

	<p class="note"><?php echo t('epmms','带');?> <span class="required">*</span> <?php echo t('epmms','的字段是必填项。');?></p>

	<?php echo $form->errorSummary($model); ?>
<table class="form">
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'charge_recommend'); ?>
		</td>
		<td class="value">
			<?php echo $form->hiddenField($model,'charge_recommend'); ?>
			<?php echo $form->textPlain($model->chargeRecommend,'memberinfo_account'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'charge_recommend',array(),true); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'charge_name'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'charge_name',array('size'=>20,'maxlength'=>20)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'charge_name',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'charge_phone'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'charge_phone',array('size'=>20,'maxlength'=>20)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'charge_phone',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'charge_age'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'charge_age'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'charge_age',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'charge_account'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'charge_account',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'charge_account',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'charge_money'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'charge_money',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'charge_money',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->