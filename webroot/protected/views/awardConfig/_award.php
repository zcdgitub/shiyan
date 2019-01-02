<?php
/* @var $this AwardConfigController */
/* @var $model AwardConfig */
$grid=$this->widget('ext.yii-easyui.widgets.EuiEDataGrid', array(
	'id' => 'award_config',
	'url'=> $this->createUrl('search',['selIndex'=>$selTab]),
	'saveUrl'=> $this->createUrl('save',['selIndex'=>$selTab]),
	'updateUrl'=> $this->createUrl('save',['selIndex'=>$selTab]),
	'destroyUrl'=> $this->createUrl('delete',['selIndex'=>$selTab]),
	'idField'=>'award_config_id',
	'sortName'=>'award_config_id',
	'sortOrder'=>'asc',
	'pagination'=>true,
	'rownumbers'=>true,
	'pageSize'=>20,
	'singleSelect'=>true,
	'fitColumns'=>true,
	'toolbar' => array(
		array('text' => t('epmms','添加'), 'iconCls' => 'icon-add', 'handler' => new CJavaScriptExpression("function(){\$('#award_config').edatagrid('addRow');}")),
		array('text' => t('epmms','编缉'), 'iconCls' => 'icon-edit', 'handler' => new CJavaScriptExpression("function()
		{
			var row = \$('#award_config').edatagrid('getSelected');
			if (row) {
				var rowIndex = \$('#award_config').edatagrid('getRowIndex', row);
				\$('#award_config').edatagrid('editRow', rowIndex);
			}
		}") ),
		array('text' => t('epmms','删除'), 'iconCls' => 'icon-cancel', 'handler' => new CJavaScriptExpression("function()
		{
			var row = \$('#award_config').edatagrid('getSelected');
			if (row) {
				var rowIndex = \$('#award_config').edatagrid('getRowIndex', row);
				\$('#award_config').edatagrid('destroyRow', rowIndex);
			}
		}") ),
		array('text' => t('epmms','保存'), 'iconCls' => 'icon-save', 'handler' => new CJavaScriptExpression("function(){\$('#award_config').edatagrid('saveRow');}")),
		array('text' => t('epmms','撤消'), 'iconCls' => 'icon-undo', 'handler' => new CJavaScriptExpression("function(){\$('#award_config').edatagrid('cancelRow');}")),
	),
	'columns'=>$config[$selTab]['columns']
));
?>
