<?php
/**
 * Created by PhpStorm.
 * User: hetao
 * Date: 13-11-5
 * Time: 上午11:53
 */

class ColorPicker extends CWidget
{
	public $id;
	public $options = array(); // array general qtip js options
	/**
	 * @brief retrieve the script file name
	 * @param minify bool true to get the minified version
	 */
	protected static function scriptName() {
		$ext = '';
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
		$cs->registerScriptFile($aUrl.'/js/colorpicker'.self::scriptName() . '.js');
		$cs->registerCssFile($aUrl.'/css/colorpicker'. self::scriptName() . '.css');
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
		Yii::app()->clientScript->registerScript(__CLASS__.$this->id, '$("#'.$this->id.'").ColorPicker('.CJavaScript::encode($this->options).');');
	}

} 