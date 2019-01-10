<?php
/* @var $this SaleController */
/* @var $model Sale */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'sale-form',
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
			<?php echo $form->labelEx($model,'sale_member_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'sale_member_id',array()); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'sale_member_id',array(),true); ?>
		</td>
	</tr>
	<?php
	$al=MemberType::model()->getListData(['condition'=>'membertype_level>1','order'=>'membertype_level asc']);
	$al2=[];
	foreach($al as $a=>$l)
	{
		$al2[$l]=$l;
	}
	?>
	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'sale_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'sale_currency',$al2); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'sale_currency',array(),false); ?>
		</td>
	</tr>
    <?php else:?>
    <tr class="row">
        <td class="title">
            <?php echo $form->labelEx($model,'sale_date'); ?>
        </td>
        <td class="value">
            <?php echo $form->datetime($model,'sale_date'); ?>
        </td>
        <td class="hint"></td>
        <td class="error">
            <?php echo $form->error($model,'sale_date',array(),false); ?>
        </td>
    </tr>
    <?php endif;?>
</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->