<?php
/* @var $this MapEditController */
/* @var $model MapEdit */
/* @var $form CActiveForm */
?>
<h1><?php echo t('epmms','添加点位')?></h1>
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
$model->map_edit_dst_recommend_id=Memberinfo::id2name($model->map_edit_dst_recommend_id);
?>

	<p class="note">
		<?php echo t('epmms','带{ss}的字段是必填项。',['{ss}'=>'<span class="required">*</span>']);?>
		审核后生效。
	</p>

	<?php echo $form->errorSummary($model); ?>
<table class="form">

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'map_edit_src_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'map_edit_src_member_id'); ?>
		</td>
		<td class="hint">要添加的会员，需要是已从图谱中删除的会员，填写登录账号</td>
		<td class="error">
			<?php echo $form->error($model,'map_edit_src_member_id',array(),true); ?>
		</td>
	</tr>

	<?if(MemberinfoItem::model()->itemVisible('membermap_parent_id')==true):?>
	<tr class="row">
		<td class="title">
			<?php echo CHtml::label(t('epmms','接点人'),'MapEdit_map_edit_dst_member_id',['required'=>true]); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'map_edit_dst_member_id'); ?>
		</td>
		<td class="hint">添加会员的接点人，填写登录账号</td>
		<td class="error">
			<?php echo $form->error($model,'map_edit_dst_member_id',array(),true); ?>
		</td>
	</tr>

	<tr class="row" id="edit_order">
		<td class="title">
			<?php echo $form->labelEx($model,'map_edit_dst_order'); ?>
		</td>
		<td class="value">
			<?php echo $form->mapOrder($model,'map_edit_dst_order'); ?>
		</td>
		<td class="hint">添加会员的位置</td>
		<td class="error">
			<?php echo $form->error($model,'map_edit_dst_order',array(),true); ?>
		</td>
	</tr>
	<?endif;?>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'map_edit_dst_recommend_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'map_edit_dst_recommend_id'); ?>
		</td>
		<td class="hint">添加会员的推荐人，填写登录账号</td>
		<td class="error">
			<?php echo $form->error($model,'map_edit_dst_recommend_id',array(),true); ?>
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