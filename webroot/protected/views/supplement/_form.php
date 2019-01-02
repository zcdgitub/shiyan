<?php
/* @var $this SupplementController */
/* @var $model Supplement */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'supplement-form',
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
			<?php echo $form->labelEx($model,'supplement_member_level'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'supplement_member_level',MemberLevel::model()->getUpgradeLevel(),[':prompt'=>'请选择' . $model->getAttributeLabel('supplement_member_level')]); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'supplement_member_level',array(),false); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'supplement_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textPlain($model,'supplement_currency',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'supplement_currency',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->