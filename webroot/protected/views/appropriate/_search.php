<?php
/* @var $this AppropriateController */
/* @var $model Appropriate */
/* @var $form CActiveForm */
?>

<table class="wide form">
<?php
$form=$this->beginWidget('ActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'appropriate_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'appropriate_currency',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'appropriate_finance_type_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'appropriate_finance_type_id',FinanceType::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('appropriate_finance_type_id' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'appropriate_memberinfo_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'appropriateMemberinfo[memberinfo_account]'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'appropriateMemberinfo[memberinfo_account]',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'appropriate_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'appropriate_type',[0=>'拨款',1=>'扣款']); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
