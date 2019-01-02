<?php
/* @var $this StockTrendController */
/* @var $model StockTrend */
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
			<?php echo $form->label($model,'stock_trend_value'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'stock_trend_value',array('size'=>20,'maxlength'=>16)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'stock_trend_date'); ?>
		</td>
		<td class="value">
			<?php echo $form->textField($model,'stock_trend_date',array('size'=>20,'maxlength'=>0)); ?>
		</td>
		<td class="hint"></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->label($model,'stock_trend_memberinfo_id'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'stock_trend_memberinfo_id',Memberinfo::model()->listData,array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('stock_trend_memberinfo_id' ))); ?>
		</td>
		<td class="hint"></td>
	</tr>

</table>
	<div class="row buttons">
		<?php echo CHtml::submitButton(t('epmms','搜索')); ?>
	</div>

<?php $this->endWidget(); ?>
