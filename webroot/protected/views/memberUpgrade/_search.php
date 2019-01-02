<?php
/* @var $this MemberUpgradeController */
/* @var $model MemberUpgrade */
/* @var $form CActiveForm */
?>

<table class="wide form">
<?php
$form=$this->beginWidget('ActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

<!--	<tr class="row">
		<td class="title">
			<?php /*echo $form->label($model,'member_upgrade_member_id'); */?>
		</td>
		<td class="value">
			<?php /*echo $form->dropDownList($model,'member_upgrade_member_id',Membermap::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('member_upgrade_member_id' ))); */?>
		</td>
		<td class="hint"></td>
	</tr>-->

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'member_upgrade_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'member_upgrade_type',Membertype::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('member_upgrade_type' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'member_upgrade_is_verify'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'member_upgrade_is_verify'); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'member_upgrade_verify_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'member_upgrade_verify_date',array('size'=>20,'maxlength'=>0)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'member_upgrade_period'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'member_upgrade_period'); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
