<?php
/* @var $this AnnouncementController */
/* @var $model Announcement */
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
			<?php echo $form->label($model,'announcement_title'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'announcement_title',array('rows'=>6, 'cols'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'announcement_content'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'announcement_content',array('rows'=>6, 'cols'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'announcement_mod_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'announcement_mod_date',array('size'=>20,'maxlength'=>0)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'announcement_userinfo_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'announcement_userinfo_id',Userinfo::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('announcement_userinfo_id' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'announcement_class'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'announcement_class',AnnouncementClass::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('announcement_class' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>
</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
