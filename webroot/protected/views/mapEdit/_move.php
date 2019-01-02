<?php
/* @var $this MapEditController */
/* @var $model MapEdit */
/* @var $form CActiveForm */
?>
<h1><?php echo t('epmms','移动点位')?></h1>
<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'map-edit-form',
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
$model->map_edit_src_member_id=Memberinfo::id2name($model->map_edit_src_member_id);
$model->map_edit_dst_member_id=Memberinfo::id2name($model->map_edit_dst_member_id);
?>

	<p class="note">
		<?php echo t('epmms','带{ss}的字段是必填项。',['{ss}'=>'<span class="required">*</span>']);?>
		点位移动是整体移动的，移动一个点位时下级会员会跟随移动。
		审核后生效。
	</p>

	<?php echo $form->errorSummary($model); ?>
<table class="form">

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'map_edit_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'map_edit_type',MapEdit::getEditType(),['prompt'=>t('epmms','请选择图谱类型'),'onclick'=>new CDbExpression("
			if(this.value==1)$('#edit_order').show();else $('#edit_order').hide();
			")]); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'map_edit_type',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'map_edit_src_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'map_edit_src_member_id'); ?>
		</td>
		<td class="hint">要移动的会员，填写登录账号</td>
		<td class="error">
			<?php echo $form->error($model,'map_edit_src_member_id',array(),true); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'map_edit_dst_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'map_edit_dst_member_id'); ?>
		</td>
		<td class="hint">移动到这个会员的下面，填写登录账号</td>
		<td class="error">
			<?php echo $form->error($model,'map_edit_dst_member_id',array(),true); ?>
		</td>
	</tr>

	<tr class="row" style="<?=$model->map_edit_type==1?'':'display:none';?>" id="edit_order">
		<td class="title">
			<?php echo $form->labelEx($model,'map_edit_dst_order'); ?>
		</td>
		<td class="value">
			<?php echo $form->mapOrder($model,'map_edit_dst_order'); ?>
		</td>
		<td class="hint">目的会员下的位置</td>
		<td class="error">
			<?php echo $form->error($model,'map_edit_dst_order',array(),true); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'map_edit_info'); ?>
		</td>
		<td class="value">
			<?php echo $form->textArea($model,'map_edit_info'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'map_edit_info',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->