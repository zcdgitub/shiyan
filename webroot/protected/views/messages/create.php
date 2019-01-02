<?php
/* @var $this MessagesController */
/* @var $model Messages */

$this->breadcrumbs=array(
	t('epmms',$model->modelName)=>array('index'),
	t('epmms','添加'),
);
$this->menu=array(
	array('label'=>t('epmms','管理') . t('epmms',$model->modelName), 'url'=>array('index')),
);
?>
<style type="text/css">
.form_Messages .row .title{
	width:100px;
	
	}
.form_Messages .row .value{
	width:800px;
	
	}
</style>


<h1><?php echo t('epmms',is_null($reply_model)?'添加':'回复') . t('epmms',$model->modelName)?></h1>

<?php echo $this->renderPartial('_form', array('model'=>$model,'messagesType'=>$messagesType,'reply_model'=>$reply_model)); ?>