<?php
/* @var $this ConfigJackpotController */
/* @var $model ConfigJackpot */
/* @var $form CActiveForm */
?>
<style>
    .hint{
        display: none;
    }
</style>
<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'config-jackpot-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
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
            <?php echo $form->labelEx($model,'config_jackpot_addmember_money'); ?>
        </td>
        <td class="value">
            <?php echo $form->textField($model,'config_jackpot_addmember_money',array('size'=>20,'maxlength'=>16)); ?>
        </td>
        <td class="hint"></td>
        <td class="error">
            <?php echo $form->error($model,'config_jackpot_addmember_money',array(),false); ?>
        </td>
    </tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_jackpot_fund'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_jackpot_fund',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_jackpot_fund',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_jackpot_start_order_ratio'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_jackpot_start_order_ratio'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_jackpot_start_order_ratio',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_jackpot_lucky_order_ratio'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_jackpot_lucky_order_ratio'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_jackpot_lucky_order_ratio',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_jackpot_end_order_ratio'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_jackpot_end_order_ratio'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_jackpot_end_order_ratio',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_jackpot_start_time'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_jackpot_start_time'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_jackpot_start_time',array(),false); ?>
		</td>
	</tr>


</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->