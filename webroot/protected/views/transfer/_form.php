<?php
/* @var $this TransferController */
/* @var $model Transfer */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'transfer-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,  // 这个是设置是否把提交按钮也做成客户端验证。
	),
	'enableAjaxValidation'=>true,
	)); 
?>

	<p class="note"><?php echo t('epmms','带{ss}的字段是必填项。',['{ss}'=>'<span class="required">*</span>']);?></p>

	<?php echo $form->errorSummary($model); ?>
<table class="form">

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'transfer_src_finance_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'transfer_src_finance_type',FinanceType::model()->getTransferSrcListData(),array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('transfer_src_finance_type' ))); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'transfer_src_finance_type',array(),false); ?>
		</td>
	</tr>
<?php if(!$self):?>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'transfer_dst_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'transfer_dst_member_id'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'transfer_dst_member_id',array(),true); ?>
		</td>
	</tr>
<?php endif;?>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'transfer_dst_finance_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'transfer_dst_finance_type',FinanceType::model()->getTransferDstListData(),array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('transfer_dst_finance_type' ))); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'transfer_dst_finance_type',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'transfer_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'transfer_currency'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'transfer_currency',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'transfer_remark'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'transfer_remark',array('rows'=>4, 'cols'=>26)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'transfer_remark',array(),false); ?>
		</td>
	</tr>


</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif',['confirm'=>t('epmms',"你确定要执行吗？")]); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->