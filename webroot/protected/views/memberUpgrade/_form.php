<?php
/* @var $this MemberUpgradeController */
/* @var $model MemberUpgrade */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'member-upgrade-form',
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

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'member_upgrade_type'); ?>
		</td>
		<td class="value">
			<?php echo $form->dropDownList($model,'member_upgrade_type',MemberType::model()->getListData(['condition'=>'membertype_level>:level','order'=>'membertype_level asc','params'=>[':level'=>user()->map->membermap_membertype_level]]),array('prompt'=>t('epmms','请选择') . $model->getAttributeLabel('member_upgrade_type' ))); ?>
		</td>
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'member_upgrade_type',array(),false); ?>
		</td>
	</tr>

</table>
	<div class="row buttons">
		<?php
		$a_str='';
		foreach(MemberType::model()->findAll() as $mtype)
		{
			$mlevel=$mtype->membertype_level;
			$mmoney=$mtype->membertype_money;
			$a_str.="\t\tmember_money[$mlevel]=$mmoney;\n";
		}
		$msg1=t('epmms','升级需要');
		$msg2=t('epmms','元,你确定要升级吗？');
		echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif',['onclick'=>new CJavaScriptExpression("
		var member_money= new Array();
$a_str
		if(\$('#MemberUpgrade_member_upgrade_type').val()!='')
		{
			member_index=\$('#MemberUpgrade_member_upgrade_type').val();
			return confirm('$msg1' + (member_money[member_index]-$money) + '$msg2');
		}
		return true;
		")]);
		?>
	</div>
<?php $this->endWidget(); ?>

</div><!-- form -->