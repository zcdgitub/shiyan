<?php
/* @var $this DealController */
/* @var $model Deal */
/* @var $form CActiveForm */
?>

<table class="wide form">
<?php
$form=$this->beginWidget('ActiveForm', array(
	'action'=>Yii::app()->createUrl($this->route),
	'method'=>'get',
)); ?>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'deal_sale_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'deal_sale_id',Sale::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('deal_sale_id' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'deal_buy_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'deal_buy_id',Buy::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('deal_buy_id' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'deal_currency'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'deal_currency',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'deal_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'deal_date',array('size'=>20,'maxlength'=>0)); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
