<?php
/* @var $this WithdrawalsController */
/* @var $model Withdrawals */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'withdrawals-form',
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

	<?if(user()->isAdmin()):?>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'withdrawals_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textPlain($model,'withdrawalsMember[memberinfo_account');?>
			<?php echo $form->hiddenfield($model,'withdrawals_member_id');?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'withdrawals_member_id',array(),false); ?>
		</td>
	</tr>
	<?endif;?>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'withdrawals_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'withdrawals_currency',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'withdrawals_currency',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'withdrawals_remark'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'withdrawals_remark',array('rows'=>6, 'cols'=>50)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'withdrawals_remark',array(),false); ?>
		</td>
	</tr>

	<?if(user()->isAdmin()):?>
		<tr class="row">
			<td class="title">
				<?php echo $form->labelEx($model,'withdrawals_finance_type_id'); ?>
			</td>
			<td class="value">
				<?php echo $form->textPlain($model,'withdrawalsFinanceType[finance_type_name]');?>
				<?php echo $form->hiddenField($model,'withdrawals_finance_type_id');?>
			</td>
			<td class="hint"></td>
			<td class="error">
				<?php echo $form->error($model,'withdrawals_finance_type_id',array(),true);?>
			</td>
		</tr>
	<?else:?>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'withdrawals_finance_type_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'withdrawals_finance_type_id',FinanceType::model()->getListData('finance_type_withdrawals=1'),array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('withdrawals_finance_type_id' )));?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'withdrawals_finance_type_id',array(),true);?>
		</td>
	</tr>
	<?endif;?>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->