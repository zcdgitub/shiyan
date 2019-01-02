<?php
/**
 * CHelpCommand class file.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @link http://www.yiiframework.com/
 * @copyright 2008-2013 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

/**
 *
 * 发送奖金通知短信
 * @property string $help The command description.
 *
 * @author Qiang Xue <qiang.xue@gmail.com>
 * @package system.console
 * @since 1.0
 */
class RemapCommand extends CConsoleCommand
{
	/**
	 * Execute the action.
	 * @param array $args command line parameters specific for this command
	 * @return integer non zero application exit code after printing help
	 */
	public function run($args)
	{
		ini_set("max_execution_time", 0);
		set_time_limit(0);
		echo "begin start.....\r\n";
		$sdate=date('Y-m-d H:i:s');
		echo $sdate;
		echo "\r\n";

		if(($status=MapEdit::model()->reMapAll())!=EError::SUCCESS)
		{
			echo 'status:' . $status . "\r\n";
			throw new Error('重计算网络图失败');
		}
		echo "start date:$sdate finish date:" . date('Y-m-d H:i:s');
		return 0;
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