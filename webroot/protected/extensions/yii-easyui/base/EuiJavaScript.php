<?php 

class EuiJavaScript
{
	
	/**
	 * The default theme
	 */
	const DEFAULT_THEME='default';
	
	/**
	 * The default locale
	 */
	const DEFAULT_LOCALE='easyui-lang-zh_cn';
	
	/**
	 * Registers the core script files.
	 * This method registers jquery and EasyUI JavaScript files and the theme CSS file.
	 */
	public static function registerCoreScripts($theme=self::DEFAULT_THEME,$locale=self::DEFAULT_LOCALE)
	{
		$locale='easyui-lang-' . Yii::app()->language;
		$cs = Yii::app()->getClientScript();

		//$scriptUrl = Yii::app()->getAssetManager()->publish(Yii::app()->baseUrl.'/js/jquery-easyui');
		$scriptUrl = Yii::app()->baseUrl.'/js/jquery-easyui';
		$themeUrl = $scriptUrl.'/themes';					
		$localeUrl = $scriptUrl.'/locale';
																							
		$cs->registerCoreScript('jquery');		
		$cs->registerScriptFile($scriptUrl.'/jquery.easyui.min.js');
				
		$cs->registerCssFile($themeUrl.'/'.$theme.'/easyui.css');
		$cs->registerCssFile($themeUrl.'/'.$theme.'/combo.css');
		$cs->registerCssFile($themeUrl.'/icon.css');
		
		$cs->registerScriptFile($localeUrl.'/'.$locale.'.js');
	}
		
	
	/**	 
	 * Encodes a PHP variable into javascript JQuery id representation
	 * @param string $id
	 */
	public static function encodeId($id)
	{
		return "$('#{$id}')";
	}
	
	/**	 
	 * Encodes a PHP variable into javascript JQuery referenced id representation
	 * @param string $id
	 */
	public static function encodeRefId($id)
	{
		return "#{$id}";
	}
	
	/**	 
	 * Render JavaScript code the messege
	 * @param string $category
	 * @param string $msg	 
	 */
	private static function message($category, $msg, $type)
	{
		$msg = Yii::t($category, $msg);
		return "$.messager.alert('".Yii::app()->name."','{$msg}','{$type}');";				
	}
	
	/**	 
	 * Render JavaScript code the info messege
	 * @param string $category
	 * @param string $msg	 
	 */
	public static function info($category, $msg)
	{
		return self::message($category, $msg, 'info');						
	}
	
	/**	 
	 * Render JavaScript code the alert messege
	 * @param string $category
	 * @param string $msg	 
	 */
	public static function alert($category, $msg)
	{
		return self::message($category, $msg, 'warning');						
	}
	
	/**	 
	 * Render JavaScript code the error messege
	 * @param string $category
	 * @param string $msg	 
	 */
	public static function error($category, $msg)
	{
		return self::message($category, $msg, 'error');						
	}
	
	/**	 
	 * Render JavaScript code the alert messege
	 * @param string $category
	 * @param string $msg	 
	 * @param string $function
	 */
	public static function question($category, $msg, $function=null)
	{
		$js = "$.messager.confirm('".Yii::app()->name."','{$msg}'";
		
		if ($function)
			$js .=", function(r){ if(r){ {$function} }});";
		else 
			$js .= ");";

		return $js;
	}
}

?>