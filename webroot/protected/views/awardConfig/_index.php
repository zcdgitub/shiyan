<?php
/* @var $this AwardConfigController */
/* @var $model AwardConfig */


$grid=$this->widget('ext.yii-easyui.widgets.EuiEDataGrid', array(
	'id' => 'award_config',
	'url'=> $this->createUrl('search'),
	'saveUrl'=> $this->createUrl('save'),
	'updateUrl'=> $this->createUrl('save'),
	'destroyUrl'=> $this->createUrl('delete'),
	'idField'=>'award_config_id',
	'sortName'=>'award_config_order',
	'sortOrder'=>'asc',
	'pagination'=>true,
	'rownumbers'=>true,
	'pageSize'=>20,
	'singleSelect'=>true,
	'fitColumns'=>true,
	'toolbar' => array(
		array('text' => t('epmms','编缉'), 'iconCls' => 'icon-edit', 'handler' => new CJavaScriptExpression("function()
		{
			var row = \$('#award_config').edatagrid('getSelected');
			if (row) {
				var rowIndex = \$('#award_config').edatagrid('getRowIndex', row);
				\$('#award_config').edatagrid('editRow', rowIndex);
			}
		}") ),
		array('text' => t('epmms','保存'), 'iconCls' => 'icon-save', 'handler' => new CJavaScriptExpression("function(){\$('#award_config').edatagrid('saveRow');}")),
		array('text' => t('epmms','撤消'), 'iconCls' => 'icon-undo', 'handler' => new CJavaScriptExpression("function(){\$('#award_config').edatagrid('cancelRow');}"))
	),
	'columns'=>array(['field'=>'awardtype_award_type_name','title'=>$model->getAttributeLabel('award_config_type_id')],
		['field'=>'award_config_currency','title'=>$model->getAttributeLabel('award_config_currency'),'editor'=>['type'=>'numberbox','options'=>['precision'=>2]]],
		['field'=>'award_config_is_percent','title'=>$model->getAttributeLabel('award_config_is_percent'),
			'formatter'=>new CJavaScriptExpression("function(value,row,index){
				if(value==0)
					return '￥';
				else if(value==1)
					return '%';
				return '';
		}"),
			'editor'=>['type'=>'combobox','options'=>
			['editable'=>false,
			'valueField'=> 'value','textField'=> 'label',
			'data'=> [[
				'label'=> '￥',
				'value'=>'0'
			],[
				'label'=> '%',
				'value'=> '1'
			]]]]
		]),
));
?>

