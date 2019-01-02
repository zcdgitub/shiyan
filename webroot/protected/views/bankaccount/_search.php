<?php
/* @var $this BankaccountController */
/* @var $model Bankaccount */
/* @var $form CActiveForm */
?>

<table class="wide form">
<?php
$this->widget('ext.pacSelector.SelectorWidget', array(
	'model' => $model,
	'attributeProvince'=>'bankaccount_provience',
'attributeCity'=>'bankaccount_area',

	));
$form=$this->beginWidget('ActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'bankaccount_bank_id'); ?>
		</td>
		<td class="value">
			<?php 
			$listModel=Model::model('Bank')->findAll();
			$listData=CHtml::listdata($listModel,'bank_id','bank_name');
			echo $form->dropDownList($model,'bankaccount_bank_id',$listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('bankaccount_bank_id' )))
			; ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'bankaccount_name'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'bankaccount_name',array('size'=>20,'maxlength'=>50)); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'bankaccount_account'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'bankaccount_account',array('size'=>20,'maxlength'=>20)); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'bankaccount_provience'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'bankaccount_provience',array()); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'bankaccount_area'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'bankaccount_area',array()); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'bankaccount_branch'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'bankaccount_branch',array('size'=>20,'maxlength'=>50)); ?>
		</td>
	</tr>

<!--	<tr class="row">
		<td class="title">
			<?php /*echo $form->label($model,'bankaccount_mobi'); */?>
		</td>
		<td class="value">
			<?php /*echo $form->textField($model,'bankaccount_mobi',array('size'=>20,'maxlength'=>20)); */?>
		</td>
	</tr>-->

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'bankaccount_is_enable'); ?>
		</td>
		<td class="value">
			<?php echo $form->enable($model,'bankaccount_is_enable'); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
