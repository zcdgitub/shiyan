<?php
/* @var $this AwardTotalSumController */
/* @var $model awardTotalSum */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'award-total-sum-form',
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
			<?php echo $form->labelEx($model,'award_total_sum_memberinfo_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'award_total_sum_memberinfo_id',Memberinfo::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('award_total_sum_memberinfo_id' ))); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'award_total_sum_memberinfo_id',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'award_total_sum_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'award_total_sum_currency'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'award_total_sum_currency',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'award_total_sum_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'award_total_sum_type',SumType::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('award_total_sum_type' ))); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'award_total_sum_type',array(),true); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->