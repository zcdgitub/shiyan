<?php
/* @var $this AgentController */
/* @var $model Agent */
/* @var $form CActiveForm */
?>

<table class="wide form">
<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'agent-form',
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'agent_memberinfo_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'agentMemberinfo[memberinfo_account]'); ?>
		</td>
		<td class="hint"></td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'agent_account'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'agent_account',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="hint"></td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'agent_memo'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'agent_memo',array('size'=>20,'maxlength'=>200)); ?>
		</td>
		<td class="hint"></td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'agent_is_verify'); ?>
		</td>
		<td class="value">
			<?php echo $form->verify($model,'agent_is_verify'); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
