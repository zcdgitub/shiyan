<?php
/* @var $this ConfigMapController */
/* @var $model ConfigMap */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'config-map-form',
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
			<?php echo $form->labelEx($model,'config_map_levels'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_map_levels'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_map_levels',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_map_branch'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_map_branch'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_map_branch',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_map_orientation'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'config_map_orientation',['top'=>'上','bottom'=>'下','left'=>'左','right'=>'右']); ?>
		</td>
		<td class="hint">根节点所在的方向</td>
		<td class="error">
			<?php echo $form->error($model,'config_map_orientation',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_map_tree_levels'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_map_tree_levels'); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_map_tree_levels',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->