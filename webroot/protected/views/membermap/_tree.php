<?php
$this->widget('ext.Flashes.Dialog',array('keys'=>array('error'),'target'=>'.search-form'));
?>
<div class="search-form" >
	<?php $this->renderPartial('_search',array(
		'model'=>$model,
	)); ?>
</div><!-- search-form -->
<?php
/* @var $this MembermapController */
/* @var $model Membermap */

if(!is_null($json_tree))
{
	$json_tree=CJSON::encode($json_tree);

	$this->widget('ext.ztree.zTree', array(
		'id'=>'tree',
		'isSimpleData'=>false,
		'options'=>array(
			'data'=>['key'=>['title'=>'tip']],
			'view'=>array(	'expandSpeed'=>"fast",'showLine'=>true,'showTitle'=>true,'nameIsHTML'=>true),
			'callback'=>['onExpand'=>new CJavaScriptExpression("function(event, treeId, treeNode){\$('#' + treeNode.tId + '_ul a.level'+ (treeNode.level+1) +'[title]').qtip();}")],
			'async'=>['enable'=>true,'url'=>'?r=Membermap/orgMapJson','type'=>'get','dataType'=>'json','autoParam'=>['id'],'otherParam'=>['levels'=>2,'type'=>'ztree','dataType'=>$dataType]]
		),
		'data'=>$json_tree
	));
	$this->widget('ext.qtip2.QTip',['selector'=>'#tree a[title]']);
}
?>