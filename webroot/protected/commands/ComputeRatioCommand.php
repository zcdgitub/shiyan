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
class ComputeRatioCommand extends CConsoleCommand
{
	/**
	 * Execute the action.
	 * @param array $args command line parameters specific for this command
	 * @return integer non zero application exit code after printing help
	 */
	public function run($args)
	{
		$start_member=1;
		$member_cnt=10;
		if(isset($args[0]))
		{
			$start_member=Memberinfo::name2id($args[0]);
		}
		if(isset($args[1]))
		{
			$member_cnt=intval($args[1]);
		}
		$gen=new GenMember();
		$gen->root=Memberinfo::id2name($start_member);
		$gen->count=$member_cnt;
		echo "start account:" . $gen->root . "\r\n";
		echo "generate count:" . $gen->count . "\r\n";
		if($gen->validate())
		{
			ini_set("max_execution_time", 0);
			set_time_limit(0);
			$model=Memberinfo::model()->findByPk($start_member);
			$cnt=0;
			echo "begin start.....\r\n";
			echo date('Y-m-d H:i:s');
			echo "\r\n";
			if($cnt=$model->genMember($member_cnt))
			{
				//$Proc=new DbCall('gen_finance_log');
				//$Proc->run();
				echo "gen {$cnt} members success\r\n";
				echo date('Y-m-d H:i:s');
				return 0;
			}
			else
			{
				echo "gen member failed\r\n";
			}
		}
		else
		{
			echo "input error\r\n";
		}
		return 1;
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