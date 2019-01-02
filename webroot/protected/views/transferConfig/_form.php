<style type="text/css">
	td.title
	{
		padding-right: 10px;
	}
</style>
<?php
/* @var $this TransferConfigController */
/* @var $model TransferConfig */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'transfer-config-form',
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
<table class="form config">
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'transfer_config_src_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->checkBoxList($model,'transfer_config_src_type',FinanceType::model()->getListData(),['separator'=>' ','checkAll'=>'全选','checkAllLast'=>true]); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'transfer_config_src_type',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'transfer_config_dst_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->checkBoxList($model,'transfer_config_dst_type',FinanceType::model()->getListData(),['separator'=>' ','checkAll'=>'全选','checkAllLast'=>true]); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'transfer_config_dst_type',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'transfer_config_src_role'); ?>
		</td>
		<td class="value">
			<?php echo $form->checkBoxList($model,'transfer_config_src_role',['agent'=>'代理中心','member'=>'会员'],['separator'=>' ','checkAll'=>'全选','checkAllLast'=>true]); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'transfer_config_src_role',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'transfer_config_dst_role'); ?>
		</td>
		<td class="value">
			<?php echo $form->checkBoxList($model,'transfer_config_dst_role',['agent'=>'代理中心','member'=>'会员'],['separator'=>' ','checkAll'=>'全选','checkAllLast'=>true]); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'transfer_config_dst_role',array(),false); ?>
		</td>
	</tr>
	<?php if(MemberinfoItem::model()->itemVisible('membermap_agent_id')):?>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'transfer_config_member_able'); ?>
		</td>
		<td class="value">
			<?php echo $form->checkBox($model,'transfer_config_member_able'); ?>
		</td>
		<td class="hint">选择此项可允许会员与会员之间转账，否者只能代理中心与代理中心，代理中心与会员之间转账</td>
		<td class="error">
			<?php echo $form->error($model,'transfer_config_member_able',array(),false); ?>
		</td>
	</tr>
	<?php endif;?>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'transfer_config_relation'); ?>
		</td>
		<td class="value">
			<?php echo $form->checkBox($model,'transfer_config_relation'); ?>
		</td>
		<td class="hint">选中此项，没有上下级接点关系的会员也可转账</td>
		<td class="error">
			<?php echo $form->error($model,'transfer_config_relation',array(),false); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'transfer_config_tax'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'transfer_config_tax'); ?>
		</td>
		<td class="hint">转账手续费</td>
		<td class="error">
			<?php echo $form->error($model,'transfer_config_tax',array(),false); ?>
		</td>
	</tr>
</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->