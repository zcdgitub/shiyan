<?php
/* @var $this MembermapController */
/* @var $model Membermap */
/* @var $form CActiveForm */
?>

<table class="wide form">
<?php
$form=$this->beginWidget('ActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
	'stateful'=>true
)); ?>
	<tr class="row">
		<td class="title"  style="border:none;text-align: right">
			<?php echo $form->label($model,'memberinfo.memberinfo_account'); ?>
		</td>
		<td class="value"  style="border:none;width:120px;">
			<?php echo $form->textField($model,'memberinfo[memberinfo_account]',array('size'=>20,'maxlength'=>100,'style'=>'width:100px;')); ?>
		</td>
		<td class="buttons">
				<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
		</td>
		<td class="hint"  style="border:none"></td>
	</tr>

</table>

<?php $this->endWidget(); ?>
