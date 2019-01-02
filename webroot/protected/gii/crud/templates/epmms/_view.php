<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $data <?php echo $this->getModelClass(); ?> */
?>

<div class="view">
<table class="viewtable">
<?php
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$count=0;
$class=$count%2==0?'odd':'even';
echo "\t<tr class=\"$class\">\n";
echo "\t<td class=\"title\"><b><?php echo CHtml::encode(\$data->getAttributeLabel('{$nameColumn}')); ?></b></td>\n";
echo "\t<td class=\"value\"><?php echo CHtml::link(CHtml::encode(\$data->{$nameColumn}), array('view', 'id'=>\$data->{$this->tableSchema->primaryKey})); ?></td>\n";
echo "\t</tr>\n\n";
$count++;
foreach($this->tableSchema->columns as $column)
{
	$cname=$this->getShortColumnName($column->name);
	if(strpos($column->name,'password')!==false)
		continue;
	if(strpos($column->name,'question')!==false)
		continue;
	if(strpos($column->name,'answer')!==false)
		continue;
	if($column->isForeignKey)
	{
		$model=Model::model($this->modelClass);
		$relations=$model->relations();
		foreach($relations as $relationName=>$relation)
		{
			if($relation[2]==$column->name)
			{
				break;
			}
		}
		if(!empty($relation) && $relation[0]==Model::BELONGS_TO)
		{
			$foreignModel=Model::model($relation[1]);
			$class=$count%2==0?'odd':'even';
			echo "\t<tr class=\"$class\">\n";
			echo "\t<td class=\"title\"><b><?php echo CHtml::encode(\$data->getAttributeLabel('{$column->name}')); ?></b></td>\n";
			echo "\t<td class=\"value\"><?php echo CHtml::encode(@\$data->{$relationName}->{$foreignModel->nameColumn}); ?></td>\n";
			echo "\t</tr>\n\n";
			$count++;
		}
		continue;
	}
	if($column->isPrimaryKey)
		continue;
	if(!strcasecmp($cname,'is_enable'))
	{
		$class=$count%2==0?'odd':'even';
		echo "\t<tr class=\"$class\">\n";
		echo "\t<td class=\"title\"><b><?php echo CHtml::encode(\$data->getAttributeLabel('{$column->name}')); ?></b></td>\n";
		echo "\t<td class=\"value\"><?php echo CHtml::encode(webapp()->format->format(\$data->{$column->name},'enable')); ?></td>\n";
		echo "\t</tr>\n\n";
		$count++;
	}
	else if(!strcasecmp($cname,'is_preset'))
	{
		$class=$count%2==0?'odd':'even';
		echo "\t<tr class=\"$class\">\n";
		echo "\t<td class=\"title\"><b><?php echo CHtml::encode(\$data->getAttributeLabel('{$column->name}')); ?></b></td>\n";
		echo "\t<td class=\"value\"><?php echo CHtml::encode(webapp()->format->format(\$data->{$column->name},'preset')); ?></td>\n";
		echo "\t</tr>\n\n";
		$count++;
	}
	else if(!strcasecmp($cname,'sex'))
	{
		$class=$count%2==0?'odd':'even';
		echo "\t<tr class=\"$class\">\n";
		echo "\t<td class=\"title\"><b><?php echo CHtml::encode(\$data->getAttributeLabel('{$column->name}')); ?></b></td>\n";
		echo "\t<td class=\"value\"><?php echo CHtml::encode(webapp()->format->format(\$data->{$column->name},'sex')); ?></td>\n";
		echo "\t</tr>\n\n";
		$count++;
	}
	else if(!strcasecmp($cname,'is_verify'))
	{
		$class=$count%2==0?'odd':'even';
		echo "\t<tr class=\"$class\">\n";
		echo "\t<td class=\"title\"><b><?php echo CHtml::encode(\$data->getAttributeLabel('{$column->name}')); ?></b></td>\n";
		echo "\t<td class=\"value\"><?php echo CHtml::encode(webapp()->format->format(\$data->{$column->name},'verify')); ?></td>\n";
		echo "\t</tr>\n\n";
		$count++;
	}
	else if($column->dbType=='timestamp'||$column->dbType=='datetime')
	{
		$class=$count%2==0?'odd':'even';
		echo "\t<tr class=\"$class\">\n";
		echo "\t<td class=\"title\"><b><?php echo CHtml::encode(\$data->getAttributeLabel('{$column->name}')); ?></b></td>\n";
		echo "\t<td class=\"value\"><?php echo CHtml::encode(webapp()->format->format(\$data->{$column->name},'datetime')); ?></td>\n";
		echo "\t</tr>\n\n";
		$count++;
	}	
	else if($column->dbType=='date')
	{
		$class=$count%2==0?'odd':'even';
		echo "\t<tr class=\"$class\">\n";
		echo "\t<td class=\"title\"><b><?php echo CHtml::encode(\$data->getAttributeLabel('{$column->name}')); ?></b></td>\n";
		echo "\t<td class=\"value\"><?php echo CHtml::encode(webapp()->format->format(\$data->{$column->name},'date')); ?></td>\n";
		echo "\t</tr>\n\n";
				$count++;
	}
	else
	{
		$class=$count%2==0?'odd':'even';
		echo "\t<tr class=\"$class\">\n";
		echo "\t<td class=\"title\"><b><?php echo CHtml::encode(\$data->getAttributeLabel('{$column->name}')); ?></b></td>\n";
		echo "\t<td class=\"value\"><?php echo CHtml::encode(\$data->{$column->name}); ?></td>\n";
		echo "\t</tr>\n\n";
		$count++;
	}
}

?>
</table>
</div>