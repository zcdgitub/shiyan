<?php
/* @var $this SupplementController */
/* @var $model Supplement */
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
			<?php echo $form->label($model,'supplement_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'supplement_currency',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'supplement_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'supplement_member_id',Memberinfo::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('supplement_member_id' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'supplement_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'supplement_date',array('size'=>20,'maxlength'=>0)); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
