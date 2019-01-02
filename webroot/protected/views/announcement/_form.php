<?php
/* @var $this AnnouncementController */
/* @var $model Announcement */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'announcement-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,  // 这个是设置是否把提交按钮也做成客户端验证。
	),
	'enableAjaxValidation'=>false,
	)); 
?>

	<p class="note"><?php echo t('epmms','带');?> <span class="required">*</span> <?php echo t('epmms','的字段是必填项。');?></p>

	<?php echo $form->errorSummary($model); ?>
<table class="form">
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'announcement_title'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'announcement_title'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'announcement_title',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'announcement_content'); ?>
		</td>
		<td class="value">
			<?php echo $form->editor($model,'announcement_content',array('rows'=>25, 'cols'=>60)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'announcement_content',array(),false); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'announcement_class'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'announcement_class',AnnouncementClass::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('announcement_class' ))); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'announcement_class',array(),false); ?>
		</td>
	</tr>
</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->