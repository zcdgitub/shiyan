<?php
/* @var $this MembermapController */
/* @var $model Membermap */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'membermap-form',
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
		<td class="title" >
			<?php echo $form->labelEx($model,'membermap_parent_id'); ?>
		</td>
		<td class="value">
			<?php 
			$listModel=Model::model('Membermap')->findAll();
			$listData=CHtml::listdata($listModel,'membermap_id','membermap_id');
			echo $form->dropDownList($model,'membermap_parent_id',$listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('membermap_parent_id' )))
			; ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_parent_id',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'membermap_recommend_id'); ?>
		</td>
		<td class="value">
			<?php 
			$listModel=Model::model('Membermap')->findAll();
			$listData=CHtml::listdata($listModel,'membermap_id','membermap_id');
			echo $form->dropDownList($model,'membermap_recommend_id',$listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('membermap_recommend_id' )))
			; ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_recommend_id',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'membermap_membertype_level'); ?>
		</td>
		<td class="value">
			<?php 
			$listModel=Model::model('Membertype')->findAll();
			$listData=CHtml::listdata($listModel,'membertype_id','membertype_name');
			echo $form->dropDownList($model,'membermap_membertype_level',$listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('membermap_membertype_level' )))
			; ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_membertype_level',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'membermap_layer'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membermap_layer'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_layer',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'membermap_path'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membermap_path',array('size'=>20,'maxlength'=>10000)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_path',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'membermap_recommend_path'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membermap_recommend_path',array('size'=>20,'maxlength'=>10000)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_recommend_path',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'membermap_recommend_number'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membermap_recommend_number'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_recommend_number',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'membermap_recommend_under_number'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membermap_recommend_under_number'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_recommend_under_number',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'membermap_child_number'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membermap_child_number'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_child_number',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'membermap_sub_number'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membermap_sub_number'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_sub_number',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'membermap_sub_product_count'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membermap_sub_product_count'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_sub_product_count',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'membermap_recommend_under_product_count'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membermap_recommend_under_product_count'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_recommend_under_product_count',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'membermap_product_id'); ?>
		</td>
		<td class="value">
			<?php 
			$listModel=Model::model('Product')->findAll();
			$listData=CHtml::listdata($listModel,'product_id','product_name');
			echo $form->dropDownList($model,'membermap_product_id',$listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('membermap_product_id' )))
			; ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_product_id',array(),true); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'membermap_product_money'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membermap_product_money',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_product_money',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'membermap_product_count'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membermap_product_count'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_product_count',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'membermap_agent_id'); ?>
		</td>
		<td class="value">
			<?php 
			$listModel=Model::model('Membermap')->findAll();
			$listData=CHtml::listdata($listModel,'membermap_id','membermap_id');
			echo $form->dropDownList($model,'membermap_agent_id',$listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('membermap_agent_id' )))
			; ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_agent_id',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'membermap_is_verify'); ?>
		</td>
		<td class="value">
			<?php echo $form->verify($model,'membermap_is_verify'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_is_verify',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'membermap_is_agent'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membermap_is_agent'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_is_agent',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'membermap_verify_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'membermap_verify_date'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_verify_date',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'membermap_verify_member_id'); ?>
		</td>
		<td class="value">
			<?php 
			$listModel=Model::model('Memberinfo')->findAll();
			$listData=CHtml::listdata($listModel,'memberinfo_id','memberinfo_account');
			echo $form->dropDownList($model,'membermap_verify_member_id',$listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('membermap_verify_member_id' )))
			; ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'membermap_verify_member_id',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->