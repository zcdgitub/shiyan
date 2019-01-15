<?php
/* @var $this BuyController */
/* @var $model Buy */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'buy-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
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
    <?php if($this->action->id=='create'):?>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'buy_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'buy_member_id',['size'=>20]); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'buy_member_id',array(),true); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'buy_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'buy_type',[0=>'本息钱包',1=>'管理钱包']); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'buy_type',array(),true); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'buy_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'buy_currency',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'buy_currency',array(),false); ?>
		</td>
	</tr>
    <?php endif;?>
    <tr class="row">
        <td class="title">
            <?php echo $form->labelEx($model,'buy_date'); ?>
        </td>
        <td class="value">
            <?php echo $form->datetime($model,'buy_date',array('size'=>20,'maxlength'=>16)); ?>
        </td>
        <td class="hint"></td>
        <td class="error">
            <?php echo $form->error($model,'buy_date',array(),false); ?>
        </td>
    </tr>
</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->