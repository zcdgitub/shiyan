<?php
$this->breadcrumbs=array(
	t('epmms','生成会员'),
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
			<?php echo $form->labelEx($model,'root'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'root'); ?>
		</td>
		<td class="hint">从该会员下面开始生成会员</td>
		<td class="error">
			<?php echo $form->error($model,'root',array(),true); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'count'); ?>
		</td>
		<td class="value">
			<?php echo $form->spinner($model,'count'); ?>
		</td>
		<td class="hint">生成的会员越多，时间越长</td>
		<td class="error">
			<?php echo $form->error($model,'count',array(),true); ?>
		</td>
	</tr>
</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton(themeBaseUrl() . '/images/button/submit.gif',['onclick'=>new CJavaScriptExpression("return confirm('真的要生成会员吗？');")]); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->