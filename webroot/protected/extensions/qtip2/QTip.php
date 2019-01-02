<?php

/**
 * Tooltip behavior. Use static method :
 *
 *
 * $this->widget('ext.ztree.zTree', array(
 *'id'=>'tree',
 *'treeNodeNameKey'=>'name',
 *'treeNodeKey'=>'id',
 *'treeNodeParentKey'=>'pId',
 *'isSimpleData'=>false,
 *'options'=>array(
 *'data'=>['key'=>['title'=>'tip']],
 *'view'=>array(	'expandSpeed'=>"",'showLine'=>true,'showTitle'=>true),
 *'callback'=>['onExpand'=>new CJavaScriptExpression("function(event, treeId, treeNode){console.log(treeNode);\$('a.level'+ (treeNode.level+1) +'[title]').qtip();}")]
 *),
 *'data'=>array(
 *$json_tree
 *)
 *));
 * </code>
 * @author parcouss
 */

class QTip extends CWidget
{
	public $selector;
	public $options = array(); // array general qtip js options
	/**
	 * @brief retrieve the script file name
	 * @param minify bool true to get the minified version
	 */
	protected static function scriptName() {
		$ext = YII_DEBUG ? '' : '.min';
		return $ext;
	}
	
	/**
	 * @brief register core and qtip js needed files
	 * @param scriptName string the qtip file name
	 */
	protected static function registerScript() {
		$cs = Yii::app()->clientScript;
		$cs->registerCoreScript('jquery');
		$assets = dirname(__FILE__).DIRECTORY_SEPARATOR.'assets';
		$aUrl = Yii::app()->getAssetManager()->publish($assets);
		$cs->registerScriptFile($aUrl.'/jquery.qtip'.self::scriptName() . '.js');
		$cs->registerCssFile($aUrl.'/jquery.qtip'. self::scriptName() . '.css');
	}
	/**
	 * Initializes the widget.
	 */
	public function init()
	{
		parent::init();
		self::registerScript();
	}

	public function run()
	{
		Yii::app()->clientScript->registerScript(__CLASS__.$this->selector, '$("'.$this->selector.'").qtip('.CJavaScript::encode($this->options).');');
	}

}
