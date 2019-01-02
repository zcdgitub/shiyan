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
class CalcCommand extends CConsoleCommand
{
	/**
	 * Execute the action.
	 * @param array $args command line parameters specific for this command
	 * @return integer non zero application exit code after printing help
	 */
	public function run($args)
	{
		$group=null;
		$calc=null;
		$calc_type=null;
		if(isset($args[0]))
		{
			$group=intval($args[0]);
		}
		else
		{
			echo 'Insufficient parameters';
			return 10;
		}
		if(isset($args[1]))
		{
			$calc=intval($args[1]);
		}
		else
		{
			echo 'Insufficient parameters';
			return 10;
		}
		if(isset($args[2]))
		{
			$calc_type=intval($args[2]);
		}
		else
		{
			echo 'Insufficient parameters';
			return 10;
		}
		$bak=new Backup();
		if(!$bak->autoBackup('自动结算','结算时间：'.webapp()->format->formatdatetime(time())))
		{
			throw new Error('备份失败');
		}
		$transaction=webapp()->db->beginTransaction();
		try
		{
			Yii::import('ext.award.MySystem_calc');
			$mysystem=new MySystem_calc();
			//$mysystem->run($group,$calc,$calc_type);
			$mysystem->run(1,1,0); //日返利 推荐奖日结 存入现金钱包10  10
			$mysystem->run(3,1,0);//日返利 推荐奖日结 存入游戏币  30  10
			$mysystem->run(2,1,0);//抵扣日结
            $Proc = new DbCall('gen_finance_log');
            $Proc->run();



			//发送短信
			Sms::model()->sendAward($mysystem->getPeriod());
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