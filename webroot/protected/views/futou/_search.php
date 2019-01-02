<?php
/* @var $this FutouController */
/* @var $model Futou */
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
			<?php echo $form->label($model,'futou_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'futou_member_id',Memberinfo::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('futou_member_id' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'futou_deduct1'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'futou_deduct1',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'futou_deduct2'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'futou_deduct2',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'futou_deduct3'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'futou_deduct3',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'futou_deduct4'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'futou_deduct4',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'futou_deduct'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'futou_deduct',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
