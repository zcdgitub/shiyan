<?php
/* @var $this MenuNavController */
/* @var $model MenuNav */
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
			<?php echo $form->label($model,'menu_name'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'menu_name',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'menu_url'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'menu_url',array('size'=>20,'maxlength'=>200)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'menu_pid'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'menu_pid',MenuNav::model()->getListData('menu_pid is null'),array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('menu_pid' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'menu_order'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'menu_order'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'menu_mod_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'menu_mod_date'); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
