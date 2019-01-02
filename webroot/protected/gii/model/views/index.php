<?php
$class=get_class($model);
Yii::app()->clientScript->registerScript('gii.model',"
$('#{$class}_connectionId').change(function(){
	var tableName=$('#{$class}_tableName');
	tableName.autocomplete('option', 'source', []);
	$.ajax({
		url: '".Yii::app()->getUrlManager()->createUrl('gii/model/getTableNames')."',
		data: {db: this.value},
		dataType: 'json'
	}).done(function(data){
		tableName.autocomplete('option', 'source', data);
	});
});
$('#{$class}_modelClass').change(function(){
	$(this).data('changed',$(this).val()!='');
});
$('#{$class}_tableName').bind('keyup change', function(){
	var model=$('#{$class}_modelClass');
	var tableName=$(this).val();
	if(tableName.substring(tableName.length-1)!='*') {
		$('.form .row.model-class').show();
	}
	else {
		$('#{$class}_modelClass').val('');
		$('.form .row.model-class').hide();
	}
	if(!model.data('changed')) {
		var i=tableName.lastIndexOf('.');
		if(i>=0)
			tableName=tableName.substring(i+1);
		var tablePrefix=$('#{$class}_tablePrefix').val();
		if(tablePrefix!='' && tableName.indexOf(tablePrefix)==0)
			tableName=tableName.substring(tablePrefix.length);
		var modelClass='';
		$.each(tableName.split('_'), function() {
			if(this.length>0)
				modelClass+=this.substring(0,1).toUpperCase()+this.substring(1);
		});
		model.val(modelClass);
	}
});
$('.form .row.model-class').toggle($('#{$class}_tableName').val().substring($('#{$class}_tableName').val().length-1)!='*');
");
?>
<h1>友拓Model 生成器</h1>

<p>这个生成器根据指定的数据库表生成Model类</p>

<?php $form=$this->beginWidget('CCodeForm', array('model'=>$model)); ?>

	<div class="row sticky">
		<?php echo $form->labelEx($model, 'connectionId')?>
		<?php echo $form->textField($model, 'connectionId', array('size'=>65))?>
		<div class="tooltip">
		数据库组件。
		</div>
		<?php echo $form->error($model,'connectionId'); ?>
	</div>
	<div class="row sticky">
		<?php echo $form->labelEx($model,'tablePrefix'); ?>
		<?php echo $form->textField($model,'tablePrefix', array('size'=>65)); ?>
		<div class="tooltip">
		这个表示所有数据库表名共用的前缀。
		设置这个属性主要影响使用表名生成的Model类名，例如， 表名 <code>tbl_post</code>的表前缀为 <code>tbl_</code>
		生成的类名将为 <code>Post</code>.
		<br/>
		如果你的数据库没有使用通用的表前缀，这个字段留空。
		</div>
		<?php echo $form->error($model,'tablePrefix'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'tableName'); ?>
		<?php $this->widget('zii.widgets.jui.CJuiAutoComplete',array(
			'model'=>$model,
			'attribute'=>'tableName',
			'name'=>'tableName',
			'source'=>Yii::app()->hasComponent($model->connectionId) ? array_keys(Yii::app()->{$model->connectionId}->schema->getTables()) : array(),
			'options'=>array(
				'minLength'=>'0',
				'focus'=>new CJavaScriptExpression('function(event,ui) {
					$("#'.CHtml::activeId($model,'tableName').'").val(ui.item.label).change();
					return false;
				}')
			),
			'htmlOptions'=>array(
				'id'=>CHtml::activeId($model,'tableName'),
				'size'=>'65',
				'data-tooltip'=>'#tableName-tooltip'
			),
		)); ?>
		<div class="tooltip" id="tableName-tooltip">
		这个指新Model类使用的表名
		(如 <code>tbl_user</code>)。 它也可以包含schema名(如 <code>public.tbl_post</code>。
		你也可以输入 <code>*</code> (或 <code>schemaName.*</code> 用于指定DB schema)
		为每个表生成模型类。
		</div>
		<?php echo $form->error($model,'tableName'); ?>
	</div>
	<div class="row model-class">
		<?php echo $form->label($model,'modelClass',array('required'=>true)); ?>
		<?php echo $form->textField($model,'modelClass', array('size'=>65)); ?>
		<div class="tooltip">
		要生成的Model类的名字 (如 <code>Post</code>， <code>Comment</code>。
		它是大小写敏感的。
		</div>
		<?php echo $form->error($model,'modelClass'); ?>
	</div>
	<div class="row">
		<?php echo $form->label($model,'modelName',array('required'=>true)); ?>
		<?php echo $form->textField($model,'modelName', array('size'=>65)); ?>
		<div class="tooltip">
			所生成模型类的标题 (e.g. <code>产品</code>, <code>会员</code>).
		</div>
		<?php echo $form->error($model,'modelName'); ?>
	</div>
	<div class="row sticky">
		<?php echo $form->labelEx($model,'baseClass'); ?>
		<?php echo $form->textField($model,'baseClass',array('size'=>65)); ?>
		<div class="tooltip">
			从这个类进行扩展
			请确认这个类存在而且可以autoloaded。
		</div>
		<?php echo $form->error($model,'baseClass'); ?>
	</div>
	<div class="row sticky">
		<?php echo $form->labelEx($model,'modelPath'); ?>
		<?php echo $form->textField($model,'modelPath', array('size'=>65)); ?>
		<div class="tooltip">
			这个指新的Model类将要生成在哪个目录下。
			它可以用path alias指定，例如， <code>application.models</code>.
		</div>
		<?php echo $form->error($model,'modelPath'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'buildRelations'); ?>
		<?php echo $form->checkBox($model,'buildRelations'); ?>
		<div class="tooltip">
			是否为model类生成relations。
			为了生成relations，需要完全扫描整个数据库。
			你可以禁用这个选项，如果你的数据库有太多的表。
		</div>
		<?php echo $form->error($model,'buildRelations'); ?>
	</div>
	<div class="row">
		<?php echo $form->labelEx($model,'commentsAsLabels'); ?>
		<?php echo $form->checkBox($model,'commentsAsLabels'); ?>
		<div class="tooltip">
			表列注释是否用于生成模型类的 attribute labels.
			如果你的RDBMS不支持列注释或没有设置列注释
			attribute name将基于列名。
		</div>
		<?php echo $form->error($model,'commentsAsLabels'); ?>
	</div>

<?php $this->endWidget(); ?>
