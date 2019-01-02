<div class="form">
<?php $form=$this->beginWidget('ActiveForm'); ?>
	<table class="form">
		<tr class="row">
			<td class="title"><?php echo $form->labelEx($model, 'name'); ?></td>
			<td class="value">
				<?php
				if(isset($update) && $update==true)
				{
					echo $form->textPlain($model, 'name');
					echo $form->hiddenField($model, 'name');
				}
				else
					echo $form->textField($model, 'name', array('maxlength'=>255, 'class'=>'text-field'));
				?>
			</td>
			<td class="hint"><?php echo t('epmms', '角色名称，不可修改'); ?></td>
			<td class="error"><?php echo $form->error($model, 'name'); ?></td>
		</tr>

		<tr class="row">
			<td class="title"><?php echo $form->labelEx($model, 'description'); ?></td>
			<td class="value"><?php echo $form->textField($model, 'description', array('maxlength'=>255, 'class'=>'text-field')); ?></td>
			<td class="hint"></td>
			<td class="error"><?php echo $form->error($model, 'description'); ?></td>
		</tr>
		<?php if( Rights::module()->enableBizRule===true ): ?>
		<tr class="row">
			<td class="title"><?php echo $form->labelEx($model, 'bizRule'); ?></td>
			<td class="value">
				<?php
				echo $form->textPlain($model, 'bizRule', array('maxlength'=>255, 'class'=>'text-field'));
				echo $form->hiddenField($model, 'bizRule');
				?>
			</td>
			<td class="hint"><?php echo t('epmms', '检查权限时执行的代码'); ?></td>
			<td class="error"><?php echo $form->error($model, 'bizRule'); ?></td>
		</tr>
		<?php endif; ?>
		</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton(themeBaseUrl() . (isset($update) && $update==true?'/images/button/save.gif':'/images/button/add.gif')); ?>
	</div>
<?php $this->endWidget(); ?>
</div>