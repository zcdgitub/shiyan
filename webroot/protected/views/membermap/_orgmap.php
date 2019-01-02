<?php
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/Jit/Extras/excanvas.js', CClientScript::POS_HEAD,array('media'=>'lt IE 9'));
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/Jit/jit-yc.js', CClientScript::POS_HEAD);
Yii::app()->clientScript->registerScriptFile(Yii::app()->baseUrl . '/js/treemap.js', CClientScript::POS_HEAD);
registerCssFile(themeBaseUrl() . '/css/Spacetree.css');
?>
<?php
/* @var $this MembermapController */
/* @var $model Membermap */
$js_jit="
\$(
    function()
    {
        treemap('$orientation',$levels,'$dataType');
    }
);";
if(!is_null($json_tree))
{
	$json_tree=CJSON::encode($json_tree);
	registerScript(uniqid('epmms_'),new CJavaScriptExpression("var json=$json_tree;$js_jit"),CClientScript::POS_HEAD);
}
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'.search-form'));
?>
<div class="search-form" >
	<?php $this->renderPartial('_search',array(
		'model'=>$model,
	)); ?>
</div><!-- search-form -->
<div id="infovis">
	<div id="log"></div>
</div>
