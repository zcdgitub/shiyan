<?php
/* @var $this ProductController */
/* @var $model Product */
/* @var $form CActiveForm */
?>

<div class="form">

<?php
$this->widget('ext.kindeditor.KindEditor',array(
		'id'=>'Product_product_info',   //Textarea id
		// Additional Parameters (Check http://www.kindsoft.net/docs/option.html)
		'items' => array(
				'width'=>'450px',
				'height'=>'200px',
				'langType'=>webapp()->language,
				'themeType'=>'simple',
				'allowImageUpload'=>true,
				'allowFileManager'=>true,
				'uploadJson'=>$this->createUrl('fileUpload'),
				'fileManagerJson'=>$this->createUrl('fileManager'),
				'urlType'=>'domain',
				'items'=>array(
						'fontname', 'fontsize', '|', 'forecolor', 'hilitecolor', 'bold', 'italic',
						'underline', 'removeformat', '|', 'justifyleft', 'justifycenter',
						'justifyright', 'insertorderedlist','insertunorderedlist', '|',
						'emoticons', 'image', 'link')
		),
));
$img_path=path2url(params('product.image'));

$this->widget('ext.cropzoom.JCropZoom', array(
		'id' => 'cropZoom',
		'start'=>false,//是否自动加载
		'cropTriggerId'=>'cropTrigger',
		'restoreTriggerId'=>'restoreTrigger',
		'containerId'=>'crop_container',
		'callbackUrl'=>$this->createUrl('CropZoom'),
		'onServerHandled' => "js:function(response){
		$('#generated').attr('src','{$img_path}' + response );
		$('#Product_product_image_url').val(response);
		console.log(response);
		}",
		'width'=>250,
		'height'=>280,
		'selector'=>array('w'=>160,'h'=>160,'maxWidth'=>160,'maxHeight'=>160,'aspectRatio'=>true,'Centered'=>true),
			'image'=>array('minZoom'=>0,'maxZoom'=>150)
));
$this->widget('ext.EAjaxUpload.EAjaxUpload',
	array(
		'id'=>'uploadFile',
		'config'=>array(
		//'uploaderType'=>'basic',
		'debug'=>false,
		'request'=>array('endpoint'=>$this->createUrl('upload')),
		'validation'=>array(
			'allowedExtensions'=>array("jpg","jpeg","gif","png"),
			'sizeLimit'=>8*1024*1024,// maximum file size in bytes
			'minSizeLimit'=>1024,// minimum file size in bytes
		),
		'text'=>array('uploadButton'=>'选择图片'),
		'callbacks'=>array('onComplete'=>new CJavaScriptExpression('function(id,filename,response){	create_crop(response.filename,response.width,response.height);}'))
	)
));
CHtml::$beforeRequiredLabel='<span class="required">*</span>';
CHtml::$afterRequiredLabel='';
$form=$this->beginWidget('ActiveForm', array(
	'id'=>'product-form',
	'enableClientValidation'=>true,
	'clientOptions'=>array(
		'validateOnSubmit'=>true,  // 这个是设置是否把提交按钮也做成客户端验证。
	),
	'enableAjaxValidation'=>false,
	'htmlOptions'=>array('enctype'=>'multipart/form-data')
)); ?>
	<p class="note"><?php echo t('epmms','带{ss}的字段是必填项。',['{ss}'=>'<span class="required">*</span>']);?></p>

	<?php echo $form->errorSummary($model); ?>
<table class="form">
	<tr class="row">
		<td class="title" ><?php echo $form->labelEx($model,'product_name'); ?>：</td>
		<td class="value" ><?php echo $form->textField($model,'product_name',array('size'=>20,'maxlength'=>50)); ?></td>
		<td class="error" ><?php echo $form->error($model,'product_name'); ?></td>
	</tr>

	<tr class="row">
		<td class="title" ><?php echo $form->labelEx($model,'product_title'); ?>：</td>
		<td class="value" ><?php echo $form->textField($model,'product_title',array('size'=>20,'maxlength'=>255)); ?></td>
		<td class="error" ><?php echo $form->error($model,'product_title'); ?></td>
	</tr>

	<tr class="row">
		<td class="title">
			<?php echo $form->labelEx($model,'product_class_id'); ?>
		</td>
		<td class="value">
      
	
	    <?php echo $form->dropDownList($model,'product_class_id',CHtml::listData($data, 'product_class_id', 'product_name'),
				array('prompt'=>t('epmms','请选择') ));?>
		</td>	

		
	
		<td class="hint"></td>
		<td class="error">
			<?php echo $form->error($model,'product_class_id',array(),true); ?>
		</td>
	</tr>
	<tr class="row">
		<td class="title" ><?php echo $form->labelEx($model,'product_price'); ?>：</td>
		<td class="value" ><?php echo $form->textField($model,'product_price',array('size'=>20,'maxlength'=>16)); ?>￥</td>
		<td class="error" ><?php echo $form->error($model,'product_price'); ?></td>
	</tr>

	<tr class="row">
		<td class="title" ><?php echo $form->labelEx($model,'product_stock'); ?>：</td>
		<td class="value" ><?php echo $form->textField($model,'product_stock'); ?></td>
		<td class="error" ><?php echo $form->error($model,'product_stock'); ?></td>
	</tr>

	<tr class="row">
		<td class="title" ><?php echo $form->labelEx($model,'product_sale_status'); ?>：</td>
		<td class="value" ><?php echo $form->yesno($model,'product_sale_status'); ?></td>
		<td class="error" ><?php echo $form->error($model,'product_sale_status'); ?></td>
	</tr>
<tr class="row">
		<td class="title" ><?php echo $form->labelEx($model,'product_cost'); ?>：</td>
		<td class="value" ><?php echo $form->textField($model,'product_cost'); ?></td>
		<td class="error" ><?php echo $form->error($model,'product_cost'); ?></td>
	</tr>
	<tr class="row">
		<td class="title" ><?php echo $form->labelEx($model,'product_credit'); ?>：</td>
		<td class="value" ><?php echo $form->textField($model,'product_credit'); ?></td>
		<td class="error" ><?php echo $form->error($model,'product_credit'); ?></td>
	</tr>
	<tr class="row">
		<td class="title" ><?php echo $form->labelEx($model,'product_info'); ?>：</td>
		<td class="value" ><?php echo $form->textArea($model,'product_info',array('rows'=>5, 'cols'=>28,'visibility'=>'hidden')); ?></td>
		<td class="error" ><?php echo $form->error($model,'product_info'); ?></td>
	</tr>

	<tr class="row">
		<td class="title" ><?php echo $form->labelEx($model,'product_image_url'); ?>：</td>
		<td class="value" >
			<div id="crop_container" style="float:left;margin-right:5px;" ></div>
			<div style="float:left;text-align:center;">
			<div><img id="generated" style="width:160px;height:160px;" src="<?php echo $model->isNewRecord?(themeBaseUrl() . '/images/no_product.png'):($img_path . $model->product_image_url)?>" alt="预览"/></div>
			<br/>
			<input type="button" id="cropTrigger" value="裁剪图片" /><br/>
			<input type="button" id="restoreTrigger" onclick="cropZoom.restore();" value="还原图片" />
			</div>
			<div class="clear"></div>
			<?php 
			if($model->isNewRecord)
			{
				$model->product_image_url=(themeBaseUrl() . '/images/no_product.png');
			}
			?>
			<?php echo $form->hiddenField($model,'product_image_url',array('size'=>20,'maxlength'=>1024)); ?>
			<div id="uploadFile"></div> 
		</td>
		<td class="error" ><?php echo $form->error($model,'product_image_url'); ?></td>
	</tr>
</table>
	<div class="row buttons">
		<?php echo CHtml::imageButton($model->isNewRecord ? themeBaseUrl() . '/images/button/add.gif' : themeBaseUrl() . '/images/button/save.gif'); ?>
	</div>

<?php $this->endWidget(); ?>

</div><!-- form -->