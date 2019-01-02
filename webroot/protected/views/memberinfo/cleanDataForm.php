<?php
$this->breadcrumbs=array(
	t('epmms','清空数据'),
);
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'memberinfo-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,  // 这个是设置是否把提交按钮也做成客户端验证。
	),
	'enableAjaxValidation'=>true,
	)); 
?>

	<p class="note"><?php echo t('epmms','带');?> <span class="required">*</span> <?php echo t('epmms','的字段是必填项。');?></p>
	<?php $this->widget('ext.Flashes.Dialog',array('keys'=>array('success'),'target'=>'#memberinfo-form')); ?>
	<?php echo $form->errorSummary($model); ?>
<table class="form">
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'includeMember'); ?>
		</td>
		<td class="value">
			<?php echo $form->hiddenField($model,'isClean');?>
			<?php echo $form->checkBox($model,'includeMember'); ?>
		</td>
		<td class="hint">选中些项同时删除第一个以外的会员，不选保留会员并设置为未审核状态</td>
		<td class="error">
			<?php echo $form->error($model,'includeMember',array(),true); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'isCharge'); ?>
		</td>
		<td class="value">
			<?php echo $form->checkBox($model,'isCharge'); ?>
		</td>
		<td class="hint">选中此项自动给第一个会员充100000000电子币</td>
		<td class="error">
			<?php echo $form->error($model,'isCharge',array(),true); ?>
		</td>
	</tr>
</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton(themeBaseUrl() . '/images/button/submit.gif',['onclick'=>new CJavaScriptExpression("return confirm('真的要清空数据码？');")]); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->