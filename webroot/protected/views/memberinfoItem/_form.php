<?php
/* @var $this MemberinfoItemController */
/* @var $model MemberinfoItem */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'memberinfo-item-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,  // 这个是设置是否把提交按钮也做成客户端验证。
	),
	'enableAjaxValidation'=>true,
	)); 
?>

	<p class="note"><?php echo t('epmms','带');?> <span class="required">*</span> <?php echo t('epmms','的字段是必填项。');?></p>

	<?php echo $form->errorSummary($model); ?>
<table class="form">

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'memberinfo_item_title'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'memberinfo_item_title'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'memberinfo_item_title',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'memberinfo_item_visible'); ?>
		</td>
		<td class="value">
			<?php echo $form->checkBox($model,'memberinfo_item_visible'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'memberinfo_item_visible',array(),false); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'memberinfo_item_required'); ?>
		</td>
		<td class="value">
			<?php echo $form->checkBox($model,'memberinfo_item_required'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'memberinfo_item_required',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'memberinfo_item_update'); ?>
		</td>
		<td class="value">
			<?php echo $form->checkBox($model,'memberinfo_item_update'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'memberinfo_item_update',array(),false); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'memberinfo_item_view'); ?>
		</td>
		<td class="value">
			<?php echo $form->checkBox($model,'memberinfo_item_view'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'memberinfo_item_view',array(),false); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'memberinfo_item_admin'); ?>
		</td>
		<td class="value">
			<?php echo $form->checkBox($model,'memberinfo_item_admin'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'memberinfo_item_admin',array(),false); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'memberinfo_item_order'); ?>
		</td>
		<td class="value">
			<?php echo $form->spinner($model,'memberinfo_item_order',['size'=>5]); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'memberinfo_item_order',array(),false); ?>
		</td>
	</tr>
	
</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->