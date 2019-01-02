<?php
/**
 * CHelpCommand class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */


class FxCommand extends CConsoleCommand
{
	public $defaultAction = 'runa';
	/**
	 * Execute the action.
	 * @param array $args command line parameters specific for this command
	 * @return integer non zero application exit code after printing help
	 */
	public function actionruna()
	{
		 
		$a=fopen("C:\phpStudy1\PHPTutorial\WWW\\181227\\test.txt","a+");
		echo fwrite($a, "hello morning".date('Y-m-d H:i:s',time())."\n\r");
		fclose($a);
	}

	/**
	 * Provides the command description.
	 * @return string the command description.
	 */
	public function getHelp()
	{
		return parent::getHelp().' [command-name]';
	}
}