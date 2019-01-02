<?php
/**
 * The following variables are available in this template:
 * - $this: the CrudCode object
 */
?>
<?php echo "<?php\n"; ?>
/* @var $this <?php echo $this->getControllerClass(); ?> */
/* @var $model <?php echo $this->getModelClass(); ?> */

<?php
$nameColumn=$this->guessNameColumn($this->tableSchema->columns);
$label='$model->modelName';
echo "\$this->breadcrumbs=array(
	t('epmms',$label)=>array('index'),
	t('epmms','修改'),
);\n";
?>
	$tabType=[0=>'<?php echo t('epmms',Model::model($this->modelClass)->modelName)?>'];
	$tabParameters=[];
	foreach($tabType as $tab=>$title)
	{
		if($selTab==$tab)
		{
		$tabParameters[$tab]=['title'=>$title,'view'=>'_form','data'=>['model'=>$model,'selTab'=>$tab]];
		}
		else
		{
			$tabParameters[$tab]=['title'=>$title,'url'=>$this->createUrl('index',['selTab'=>$tab])];
		}
	}
	$this->widget('system.web.widgets.CTabView', array('tabs'=>$tabParameters,'activeTab'=>$selTab));
?>
