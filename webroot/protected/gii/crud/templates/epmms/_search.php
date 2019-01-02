<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */
/* @var $form CActiveForm */
?>

<table class="wide form">
<?php 
echo "<?php\n";
foreach($this->areaColumn as $areaAttr)
{
	$areaParam='';
	foreach($areaAttr as $attr=>$value)
	{
		$areaParam.="'{$attr}'=>'{$value}',\n";
	}
	echo "\$this->widget('ext.pacSelector.SelectorWidget', array(
	'model' => \$model,
	$areaParam
	));\n";
}
echo "\$form=\$this->beginWidget('ActiveForm', array(
	'action'=>Yii::app()->createUrl(\$this->route),
	'method'=>'get',
)); ?>\n"; ?>

<?php foreach($this->tableSchema->columns as $column): ?>
<?php
	$field=$this->generateInputField($this->modelClass,$column);
	if(strpos($field,'password')!==false)
		continue;
	if($column->isPrimaryKey)
		continue;
	if(strpos($column->name,'add_date')!==false)
		continue;
	if(strpos($column->name,'is_preset')!==false)
		continue;
	if(strpos($column->name,'question')!==false)
		continue;
	if(strpos($column->name,'answer')!==false)
		continue;	
	if(strpos($column->name,'_ip')!==false)
		continue;	
?>
	<tr class="row">
		<td class="title">
			<?php echo "<?php echo \$form->label(\$model,'{$column->name}'); ?>\n"; ?>
		</td>
		<td class="value">
			<?php echo "<?php ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; ?>
		</td>
		<td class="hint"></td>
	</tr>

<?php endforeach; ?>
</table>
	<div class="row buttons">
		<?php echo "<?php echo CHtml::submitButton(t('epmms','搜索')); ?>\n"; ?>
	</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>
