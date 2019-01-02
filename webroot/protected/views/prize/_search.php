<?php
/* @var $this PrizeController */
/* @var $model Prize */
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
			<?php echo $form->label($model,'prize_info'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'prize_info',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'prize_is_verify'); ?>
		</td>
		<td class="value">
			<?php echo $form->verify($model,'prize_is_verify'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'prize_verify_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'prize_verify_date',array('size'=>20,'maxlength'=>0)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'prize_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'prize_date',array('size'=>20,'maxlength'=>0)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'prize_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'prize_member_id',Memberinfo::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('prize_member_id' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
