<?php
/* @var $this JackpotRecordController */
/* @var $model JackpotRecord */
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
			<?php echo $form->label($model,'jackpot_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'jackpot_member_id',Memberinfo::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('jackpot_member_id' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'jackpot_money'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'jackpot_money',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'jackpot_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'jackpot_type'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'jackpot_start_time'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'jackpot_start_time'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'jackpot_end_time'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'jackpot_end_time'); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
