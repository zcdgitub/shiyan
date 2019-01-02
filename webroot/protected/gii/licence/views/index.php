<?php
$class=get_class($model);
Yii::app()->clientScript->registerScript('gii.licence',"
$('#{$class}_controller').change(function(){
	$(this).data('changed',$(this).val()!='');
});
$('#{$class}_model').bind('keyup change', function(){
	var controller=$('#{$class}_controller');
	if(!controller.data('changed')) {
		var id=new String($(this).val().match(/\\w*$/));
		if(id.length>0)
			id=id.substring(0,1).toLowerCase()+id.substring(1);
		controller.val(id);
	}
});
");
?>
<h1>设置授权时间</h1>

<?php $form=$this->beginWidget('CCodeForm', array('model'=>$model)); ?>

	<div class="row sticky">
		<?php echo $form->labelEx($model,'domainExpiry'); ?>
		<?php echo $form->textField($model,'domainExpiry', array('size'=>65)); ?>
		<div class="tooltip">
		正式域名到期时间
		</div>
		<?php echo $form->error($model,'domainExpiry'); ?>
	</div>
	<div class="row sticky">
		<?php echo $form->labelEx($model,'spaceExpiry'); ?>
		<?php echo $form->textField($model,'spaceExpiry', array('size'=>65)); ?>
		<div class="tooltip">
			正式空间到期时间
		</div>
		<?php echo $form->error($model,'spaceExpiry'); ?>
	</div>
<div class="row sticky">
	<?php echo $form->labelEx($model,'tryExpiry'); ?>
	<?php echo $form->textField($model,'tryExpiry', array('size'=>65)); ?>
	<div class="tooltip">
		试用到期时间
	</div>
	<?php echo $form->error($model,'tryExpiry'); ?>
</div>
<?php $this->endWidget(); ?>
