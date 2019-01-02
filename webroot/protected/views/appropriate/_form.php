
<?php
/* @var $this AppropriateController */
/* @var $model Appropriate */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'appropriate-form',
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
			<?php echo $form->labelEx($model,'appropriate_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'appropriate_currency',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'appropriate_currency',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'appropriate_finance_type_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'appropriate_finance_type_id',FinanceType::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('appropriate_finance_type_id' ))); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'appropriate_finance_type_id',array(),true); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'appropriate_memberinfo_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'appropriate_memberinfo_id'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'appropriate_memberinfo_id',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->