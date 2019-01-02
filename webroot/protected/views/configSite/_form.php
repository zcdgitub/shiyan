<?php
/* @var $this ConfigSiteController */
/* @var $model ConfigSite */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'config-site-form',
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
			<?php echo $form->labelEx($model,'config_site_title'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_site_title',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_site_title',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_site_domain'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_site_domain',array('size'=>20,'maxlength'=>50)); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_site_domain',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_site_keyword'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_site_keyword',array('size'=>20,'maxlength'=>200)); ?>
		</td>
		<td class="hint">不同的关键词用“，”分隔</td>
		<td class="error">
			<?php echo $form->error($model,'config_site_keyword',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_site_desc'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'config_site_desc',array('size'=>20,'maxlength'=>200)); ?>
		</td>
		<td class="hint"></td>		
		<td class="error">
			<?php echo $form->error($model,'config_site_desc',array(),false); ?>
		</td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_site_deny_robots'); ?>
		</td>
		<td class="value">
			<?php echo $form->robots($model,'config_site_deny_robots'); ?>
			&nbsp;&nbsp;
			<?php if($model->config_site_deny_robots==1):?>
				<?php echo CHtml::link('提交百度搜索','http://www.baidu.com/search/url_submit.html')?>
				&nbsp;
				<?php echo CHtml::link('提交谷歌搜索','https://www.google.com/webmasters/tools/submit-url')?>
			<?php endif;?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'config_site_deny_robots',array(),false); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'config_site_is_enable'); ?>
		</td>
		<td class="value">
			<?php echo $form->enable($model,'config_site_is_enable'); ?>
		</td>
		<td class="hint">选择禁用将关闭系统</td>
		<td class="error">
			<?php echo $form->error($model,'config_site_is_enable',array(),false); ?>
		</td>
	</tr>
</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->