<?php
/* @var $this MybankController */
/* @var $model Mybank */
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
			<?php echo $form->label($model,'mybank_bank_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'mybank_bank_id',Bank::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('mybank_bank_id' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'mybank_name'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'mybank_name',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'mybank_account'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'mybank_account',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'mybank_memberinfo_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'mybank_memberinfo_id',Memberinfo::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('mybank_memberinfo_id' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'mybank_is_default'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'mybank_is_default'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'mybank_address'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'mybank_address',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
