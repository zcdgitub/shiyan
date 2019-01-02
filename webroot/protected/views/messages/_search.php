<?php
/* @var $this MessagesController */
/* @var $model Messages */
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
			<?php echo $form->label($model,'messages_title'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'messages_title',array('rows'=>6, 'cols'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'messages_content'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'messages_content',array('rows'=>6, 'cols'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'messages_sender_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'messagesSenderMember[memberinfo_account]'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'messages_receiver_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'messagesReceiverMember[memberinfo_account]'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'messages_session'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'messages_session'); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
