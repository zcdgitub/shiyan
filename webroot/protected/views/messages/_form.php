<?php
/* @var $this MessagesController */
/* @var $model Messages */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'messages-form',
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
			<?php echo $form->labelEx($model,'messages_title'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'messages_title'); ?>
		</td>

		<td class="error">
			<?php echo $form->error($model,'messages_title',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'messages_content'); ?>
		</td>
		<td class="value">
			<?php echo $form->editor($model,'messages_content',array('rows'=>25, 'cols'=>60)); ?>
		</td>
	
		<td class="error">
			<?php echo $form->error($model,'messages_content',array(),false); ?>
		</td>
	</tr>

<?php if ( $messagesType==0 ):?>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'messages_receiver_member_id'); ?>
		</td>
		<td class="value">
			<?php if(!is_null($reply_model)):?>
				<?=$reply_model->messagesSenderMember->memberinfo_account?>
				<?php
					echo $form->hiddenField($model,'messages_session');
					echo $form->hiddenField($model,'messages_receiver_member_id');
				?>
			<?php else:?>
				<?php echo $form->textField($model,'messages_receiver_member_id'); ?>
			<?php endif;?>
		</td>

		<td class="error">
			<?php echo $form->error($model,'messages_receiver_member_id',array(),true); ?>
		</td>
	</tr>
<?php endif;?>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->