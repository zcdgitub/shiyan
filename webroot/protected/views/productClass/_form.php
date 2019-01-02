<?php
/* @var $this ProductClassController */
/* @var $model ProductClass */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'product-class-form',
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
			<?php echo $form->labelEx($model,'product_name'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'product_name',array('size'=>20,'maxlength'=>20)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'product_name',array(),false); ?>
		</td>
	</tr>
<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'product_parent_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'product_parent_id',ProductClass::model()->getListData($condition='product_parent_id=0'),
				array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('product_parent_id' )));?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'product_parent_id',array(),true); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'product_info'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'product_info',array('rows'=>6, 'cols'=>50)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'product_info',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->