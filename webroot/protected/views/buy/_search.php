<?php
/* @var $this BuyController */
/* @var $model Buy */
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
			<?php echo $form->label($model,'buy_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'buy_member_id',Memberinfo::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('buy_member_id' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'buy_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'buy_currency',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'buy_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'buy_date',array('size'=>20,'maxlength'=>0)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'buy_money'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'buy_money',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'buy_status'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'buy_status'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'buy_tax'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'buy_tax',array('size'=>20,'maxlength'=>1)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'buy_real_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'buy_real_currency',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
