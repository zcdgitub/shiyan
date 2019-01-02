<?php
/* @var $this ChargeController */
/* @var $model Charge */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'charge-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,  // 这个是设置是否把提交按钮也做成客户端验证。
	),
	'enableAjaxValidation'=>true,
	//'htmlOptions'=>['target'=>'_blank']
	)); 
?>

	<p class="note"><?php echo t('epmms','带');?> <span class="required">*</span> <?php echo t('epmms','的字段是必填项。');?></p>

	<?php echo $form->errorSummary($model); ?>
<table class="form">

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'charge_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'charge_currency',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'charge_currency',array(),false); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'charge_finance_type_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'charge_finance_type_id',FinanceType::model()->getListData('finance_type_charge=1'),array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('charge_finance_type_id' )));?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'charge_finance_type_id',array(),true);?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'charge_remark'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'charge_remark',array('rows'=>4, 'cols'=>20)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'charge_remark',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->