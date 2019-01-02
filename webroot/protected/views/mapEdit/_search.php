<?php
/* @var $this MapEditController */
/* @var $model MapEdit */
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
			<?php echo $form->label($model,'map_edit_src_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'map_edit_src_member_id',Membermap::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('map_edit_src_member_id' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'map_edit_dst_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'map_edit_dst_member_id',Membermap::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('map_edit_dst_member_id' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'map_edit_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'map_edit_type'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'map_edit_verify_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'map_edit_verify_date'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'map_edit_operate'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'map_edit_operate'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'map_edit_info'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'map_edit_info',array('rows'=>6, 'cols'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'map_edit_is_verify'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'map_edit_is_verify'); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
