<?php
/* @var $this PayLogController */
/* @var $model PayLog */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'pay-form',
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
			<?php echo $form->labelEx($model,'orders_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textPlain($order,'orders_sn'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textPlain($model,'currency'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'type'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'type',$model->getPayTypeList(),['prompt'=>t('epmms','请选择支付方式')]); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'type',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton(themeBaseUrl() . '/images/button/add.gif') ; ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->