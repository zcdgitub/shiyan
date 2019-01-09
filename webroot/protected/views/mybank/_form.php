<?php
/* @var $this MybankController */
/* @var $model Mybank */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'mybank-form',
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
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'mybank_bank_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'mybank_bank_id',Bank::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('mybank_bank_id' ))); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'mybank_bank_id',array(),true); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'mybank_name'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'mybank_name',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'mybank_name',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'mybank_account'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'mybank_account',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'mybank_account',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'mybank_memberinfo_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'mybank_memberinfo_id'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'mybank_memberinfo_id',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'mybank_is_default'); ?>
		</td>
		<td class="value">
			<?php echo $form->truefalse($model,'mybank_is_default'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'mybank_is_default',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'mybank_address'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'mybank_address',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'mybank_address',array(),false); ?>
		</td>
	</tr>
    <tr class="row">
        <td class="title">
            <?php echo $form->labelEx($model,'mybank_memo'); ?>
        </td>
        <td class="value">
            <?php echo $form->textField($model,'mybank_memo',array('size'=>20,'maxlength'=>50)); ?>
        </td>
        <td class="hint"></td>
        <td class="error">
            <?php echo $form->error($model,'mybank_memo',array(),false); ?>
        </td>
    </tr>
</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->