<?php
/* @var $this AgentController */
/* @var $model Agent */
/* @var $form CActiveForm */
?>
<style type="text/css">
	.pac
	{
		display: none;
	}
	<?php if($model->agent_type==1):?>
	.pac.province
	{
		display: table-row;
	}
	<?endif;?>
	<?php if($model->agent_type==2):?>
	.pac.province,.pac.area
	{
		display: table-row;
	}
	<?endif;?>
	<?php if($model->agent_type==3):?>
	.pac.province,.pac.area,.pac.county
	{
		display: table-row;
	}
	<?endif;?>
</style>
<div class="form">

<?php
$this->widget('ext.pacSelector.SelectorWidget', array(
	'model' => $model,
	'attributeProvince'=>'agent_province',
	'attributeCity'=>'agent_area',
	'attributeArea'=>'agent_county'
));
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'agent-form',
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
			<?php echo $form->labelEx($model,'agent_memberinfo_id'); ?>
		</td>
		<td class="value">
			<?php	echo $form->textField($model,'agent_memberinfo_id');?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'agent_memberinfo_id',array(),true); ?>
		</td>
	</tr>

<?php else:?>
	<?php	echo $form->hiddenField($model,'agent_memberinfo_id');?>
<?php endif;?>
<!--	<tr class="row">
		<td class="title">
			<?php /*echo $form->labelEx($model,'agent_account'); */?>
		</td>
		<td class="value">
			<?php /*echo $form->textField($model,'agent_account',array('size'=>20,'maxlength'=>50)); */?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php /*echo $form->error($model,'agent_account',array(),false); */?>
		</td>
	</tr>-->
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'agent_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'agent_type',AgentType::model()->getListData(),
			array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('agent_type' ),'onchange'=>new CJavaScriptExpression("
			if(this.value==1)
			{
				\$('.row.province').show();
				\$('.row.area').hide();
				\$('.row.county').hide();
			}
			else if(this.value==2)
			{
				\$('.row.province').show();
				\$('.row.area').show();
				\$('.row.county').hide();
			}
			else if(this.value==3)
			{
				\$('.row.province').show();
				\$('.row.area').show();
				\$('.row.county').show();
			}
			else
			{
				\$('.row.province').hide();
				\$('.row.area').hide();
				\$('.row.county').hide();
			}
			")));?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'agent_type',array(),true); ?>
		</td>
	</tr>

	<tr class="row pac province">
		<td class="title">
			<?php echo $form->labelEx($model,'agent_province'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'agent_province',array()); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'agent_province',array(),false); ?>
		</td>
	</tr>

	<tr class="row pac area">
		<td class="title">
			<?php echo $form->labelEx($model,'agent_area'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'agent_area',array()); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'agent_area',array(),false); ?>
		</td>
	</tr>

	<tr class="row pac county">
		<td class="title">
			<?php echo $form->labelEx($model,'agent_county'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'agent_county',array()); ?>
		</td>
		<td class="error">
			<?php echo $form->error($model,'agent_county',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'agent_memo'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'agent_memo',array('size'=>20,'maxlength'=>200)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'agent_memo',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . ($this->action->id=='register'?'/images/button/submit.gif':'/images/button/add.gif') : themeBaseUrl() . '/images/button/save.gif'); ?>	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->