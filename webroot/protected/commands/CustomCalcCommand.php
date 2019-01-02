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
class CustomCalcCommand extends CConsoleCommand
{
	/**
	 * Execute the action.
	 * @param array $args command line parameters specific for this command
	 * @return integer non zero application exit code after printing help
	 */
	public function run($args)
	{
		$period=null;
		$period2=null;
		$bak=new Backup();
		if(!$bak->autoBackup('自动结算','结算时间：'.webapp()->format->formatdatetime(time())))
		{
			throw new Error('备份失败');
		}
		$transaction=webapp()->db->beginTransaction();
		try
		{
			/*
			$period=new DbEvaluate("nextval('award_period')");
			$period=$period->run();*/
			$sumProc=new DbCall('day_calc');
			$sumProc->run();
			/*
			$Proc=new DbCall('gen_finance_log');
			$Proc->run();
			$period2=new DbEvaluate("curval('award_period')");
			$period2=$period2->run();
			//发送短信
			for($i=$period+1;$i<=$period2;$i++)
				Sms::model()->sendAward($i);*/
		}
		catch(Error $e)
		{
			$transaction->rollback();
			throw $e;
			return EError::ERROR;
		}
		catch(CDbException $e)
		{
			$transaction->rollback();
			throw $e;
			return EError::ERROR;
		}
		catch(Exception $e)
		{
			$transaction->rollback();
			throw $e;
			return EError::ERROR;
		}
		$transaction->commit();

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