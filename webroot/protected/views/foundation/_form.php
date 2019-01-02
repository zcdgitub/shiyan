<?php
/* @var $this FoundationController */
/* @var $model Foundation */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'foundation-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,  // 这个是设置是否把提交按钮也做成客户端验证。
	),
	'enableAjaxValidation'=>false,
	)); 
?>

	<p class="note"><?php echo t('epmms','带');?> <span class="required">*</span> <?php echo t('epmms','的字段是必填项。');?></p>

	<?php echo $form->errorSummary($model); ?>
<table class="form">

	<?if($this->action->id=='create'):?>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'foundation_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'foundation_currency',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'foundation_currency',array(),false); ?>
		</td>
	</tr>
	<?endif;?>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'foundation_reamrk'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'foundation_reamrk',array('rows'=>6, 'cols'=>50)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'foundation_reamrk',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->