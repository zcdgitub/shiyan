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

<div class="form">

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
$model=Model::model($this->modelClass);
$rules=$model->rules();
$validators=array();
foreach($rules as $rule)
	$validators[]=$rule[1];
if(in_array('unique',$validators) || in_array('exist',$validators) || in_array('ext.validators.Exist',$validators))
{
	$ajax='true';
}
else
{
	$ajax='false';
}
echo "\$form=\$this->beginWidget('ActiveForm', array(
	'id'=>'".$this->class2id($this->modelClass)."-form',
	// Please note: When you enable ajax validation, make sure the corresponding
	// controller action is handling ajax validation correctly.
	// There is a call to performAjaxValidation() commented in generated controller code.
	// See class documentation of CActiveForm for details on this.
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,  // 这个是设置是否把提交按钮也做成客户端验证。
	),
	'enableAjaxValidation'=>$ajax,
	)); 
?>\n"; 
?>

	<p class="note"><?php echo "<?php echo t('epmms','带');?>";?> <span class="required">*</span> <?php echo "<?php echo t('epmms','的字段是必填项。');?>";?></p>

	<?php echo "<?php echo \$form->errorSummary(\$model); ?>\n"; ?>
<table class="form">
<?php
foreach($this->tableSchema->columns as $column)
{
	if($column->autoIncrement)
		continue;
	if($column->isPrimaryKey)
		continue;
	if(strpos($column->name,'add_date')!==false)
		continue;
	if(strpos($column->name,'mod_date')!==false)
		continue;
	if(strpos($column->name,'last_date')!==false)
		continue;
	if(strpos($column->name,'is_preset')!==false)
		continue;
	if(strpos($column->name,'_ip')!==false)
		continue;
	$model=Model::model($this->modelClass);
	$rules=$model->rules();
	$fieldAjax='false';
	foreach($rules as $rule)
	{
		$ruleColumn=explode(',',$rule[0]);
		if(in_array($column->name,$ruleColumn))
		{
			if(in_array($rule[1],array('unique','exist')))
			{
				$fieldAjax='true';
			}
		}
	}
?>
	<tr class="row">
		<td class="title">
			<?php echo "<?php echo ".$this->generateActiveLabel($this->modelClass,$column)."; ?>\n"; ?>
		</td>
		<td class="value">
			<?php echo "<?php ".$this->generateActiveField($this->modelClass,$column)."; ?>\n"; ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo "<?php echo \$form->error(\$model,'{$column->name}',array(),$fieldAjax); ?>\n"; ?>
		</td>
	</tr>

<?php
}
?>
</table>
	<div class="row buttons">
		<?php echo "<?php echo CHtml::imageButton(\$model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>\n"; ?>
	</div>

<?php echo "<?php \$this->endWidget(); ?>\n"; ?>

</div><!-- form -->