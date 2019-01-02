<?php
$class=get_class($model);
Yii::app()->clientScript->registerScript('gii.crud',"
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
<h1>易软configure生成器</h1>

<p>这个生成器跟据指定的数据模型生成一个实现CURD操作的控制器和视图。</p>

<?php $form=$this->beginWidget('CCodeForm', array('model'=>$model)); ?>

	<div class="row">
		<?php echo $form->labelEx($model,'model'); ?>
		<?php echo $form->textField($model,'model',array('size'=>65)); ?>
		<div class="tooltip">
			模型类是大小写敏感的. 它可以包括类名 (e.g. <code>Post</code>)
		    和类文件所在的路径别名 (e.g. <code>application.models.Post</code>).
		    如果只有前者，必须是自动加载的.
		</div>
		<?php echo $form->error($model,'model'); ?>
	</div>

	<div class="row">
		<?php echo $form->labelEx($model,'controller'); ?>
		<?php echo $form->textField($model,'controller',array('size'=>65)); ?>
		<div class="tooltip">
			控制器 ID 是大小写区分的. CRUD 控制器通常在指定的模型类后面加上Controller来命名. 例如下面:
			<ul>
				<li><code>post</code> 生成 <code>PostController.php</code></li>
				<li><code>postTag</code> 生成 <code>PostTagController.php</code></li>
				<li><code>admin/user</code> 生成 <code>admin/UserController.php</code>.
					如果应用 <code>admin</code> 为模块的话,
					将生成 <code>UserController</code> (还有其它的CRUD代码)
					在模块下面.
				</li>
			</ul>
		</div>
		<?php echo $form->error($model,'controller'); ?>
	</div>

	<div class="row sticky">
		<?php echo $form->labelEx($model,'baseControllerClass'); ?>
		<?php echo $form->textField($model,'baseControllerClass',array('size'=>65)); ?>
		<div class="tooltip">
			这是CRUD 控制器的基类.
			请确定它是存在的并且自动加载.
		</div>
		<?php echo $form->error($model,'baseControllerClass'); ?>
	</div>

<?php $this->endWidget(); ?>
